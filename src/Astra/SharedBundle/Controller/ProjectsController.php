<?php

namespace Astra\SharedBundle\Controller;

use Astra\SharedBundle\Entity\Project;
use Astra\SharedBundle\Entity\Task;
use Astra\SharedBundle\Entity\User;
use Astra\SharedBundle\Form\ProjectType;
use Astra\SharedBundle\Form\TaskType;
use Astra\SharedBundle\Services\SharedVariableService;
use Astra\SharedBundle\Utils\Values;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProjectsController extends BaseController
{
    public function IndexAction(Request $request)
    {
        $template = $request->get('template','index');
        $projects = $this->getEm()->getRepository('AstraSharedBundle:Project')->getProjectsForUsers([$this->getUser()]);

        switch (mb_strtolower($template))
        {
            case 'mosaic' : {$template = 'mosaic'; break;}
            case 'index' : {$template = 'index'; break;}
            default: {$template = 'index'; break;}
        }

        return $this->render('AstraSharedBundle:Projects:'.$template.'.html.twig', ['projects' => $projects]);
    }

    public function EditAction($id = 0, Request $request)
    {
        $projectService = $this->get('astra.project.service');
        $project = null;
        if(!empty($id))$project = $this->LoadProject($id);
        if ((empty($project)) && (empty($id))) $project =  new Project();
        if(empty($project)) throw new NotFoundHttpException();
        $form = $this->createForm(ProjectType::class, $project, []);
        $form->handleRequest($request);
        if(($form->isValid()) && ($form->isSubmitted()))
        {
            $projectService->saveProject($project);
            return new RedirectResponse($this->generateUrl('astra_shared_project_view',['id'=>$project->getId()]));
        }

        return $this->render('AstraSharedBundle:Projects:edit.html.twig', ['project'=>$project,'form'=>$form->createView()]);
    }

    public function ViewAction($id)
    {
        $project = $this->LoadProject($id);

        $projectService = $this->get('astra.project.service');
        $messageContainer = $projectService->getProjectMessageContainer($project);

        $fileList = $this->getEm()->getRepository('AstraSharedBundle:ProjectFile')->findBy(['isPublic'=>true,'project'=>$project]);

        return $this->render('AstraSharedBundle:Projects:view.html.twig', ['messageContainer'=>$messageContainer,'project'=>$project,'fileList'=>$fileList]);
    }

    public function ViewFilesAction($id,$directory)
    {
        $project = $this->LoadProject($id);

        $projectFileService = $this->get('astra.project_file.service');

        $currentDirectory = $projectFileService->getCurrentDirectory($project,$directory);

        $directoryLine = $projectFileService->getDirectoryLine($project, $currentDirectory);

        $directoryList = $projectFileService->getSubDirectories($project, $currentDirectory);

        $fileList = $this->getEm()->getRepository('AstraSharedBundle:ProjectFile')->getFileForDirectoryAndProject($currentDirectory,$project);


        return $this->render('AstraSharedBundle:Projects:files.html.twig',
            ['project'=>$project,'directoryList'=>$directoryList,'currentDirectory'=>$currentDirectory,'directoryLine'=>$directoryLine,'fileList'=>$fileList]);
    }

    public function UploadFileForProjectAction($id, Request $request)
    {
        $public_files_directory = $this->getParameter('public_files_directory');
        $fileService = $this->get('astra.file.service');
        $projectFileService = $this->get('astra.project_file.service');
        $project = $this->LoadProject($id);
        $isPublic = !empty($request->get('public',0));
        $directory = $request->get('directory',null);
        $directoryId = $request->get('directoryId',null);

        $result =
            [
                'success'=>false,
                'message'=>$this->trans('dropzone.defaultError'),
                'filename'=>'',
                'url'=>'',
                'type'=>'',
            ];

        if(!$project)
        {
            $result['message']=$this->trans('projects.projectNotFound');
            return $this->json($result);
        }

        /**
         * @var UploadedFile $fileUpload
         */
        $fileUpload = $request->files->get('file');

        if(!$fileUpload)
        {
            $result['message']=$this->trans('dropzone.fileNotLoadet');
            return $this->json($result);
        }

        if(!$fileService->checkExtensionUploadedFile($fileUpload))
        {
            $result['message']=$this->trans('dropzone.wrongTypeFile');
            return $this->json($result);
        }

        $file = null;
        if($directory)
        {
            $file = $projectFileService->loadFileToProject($fileUpload,$project,$isPublic,$directory);
        }elseif ($directoryId)
        {
            $dir = $this->getEm()->getRepository('AstraSharedBundle:Directory')->find($directoryId);
            $file = $projectFileService->loadFileToProjectDirectory($fileUpload,$project,$isPublic,$dir);
        }

        if(empty($file))
        {
            $result['message']=$this->trans('dropzone.fileNotLoadet');
            return $this->json($result);
        }


        $this->getEm()->flush();
        $result =
            [
                'success'=>true,
                'message'=>'',
                'filename'=>$file->getName(),
                'url'=> $this->get('twig.extension.assets')->getAssetUrl($public_files_directory.'/'.$file->getAsset()),
                'type'=>$file->getType(),
            ];

        return $this->json($result);
    }

    public function CreateDirectoryForProjectAction(Request $request)
    {
        $projectFileService = $this->get('astra.project_file.service');
        $fileService = $this->get('astra.file.service');

        $project = $this->LoadProject($request->get('projectId',null));
        $directoryName = Values::getString($request->get('directoryName',''));
        $parentDirectory = $this->getEm()->getRepository('AstraSharedBundle:Directory')->find($request->get('parentDirectoryId',0));

        $result =
            [
                'success'=>false,
                'message'=>$this->trans('dropzone.defaultError'),
                'name'=>'',
                'url'=>'',
            ];

        if(!$project)
        {
            $result['message']=$this->trans('projects.projectNotFound');
            return $this->json($result);
        }

        if(!$parentDirectory)
        {
            $result['message']=$this->trans('fileManager.uploadingDirectoryNotFound');
            return $this->json($result);
        }

        if(empty($directoryName))
        {
            $result['message']=$this->trans('fileManager.directoryNameIsEmpty');
            return $this->json($result);
        }

        if(!$projectFileService->isDirectoryInProject($parentDirectory,$project))
        {
            $result['message']=$this->trans('projects.isDirectoryNotInProject');
            return $this->json($result);
        }


        $newDirectory = $fileService->getDirectoryByPath($directoryName,$parentDirectory,null,true);
        $projectFileService->addDirectoryToProject($newDirectory,$project,false);

        $this->getEm()->flush();
        $result =
            [
                'success'=>true,
                'message'=>'',
                'name'=>$newDirectory->getName(),
                'url'=>$this->generateUrl('astra_shared_project_view_files',['id'=>$project->getId(),'directory'=>$newDirectory->getId()]),
            ];

        return $this->json($result);
    }

    public function TaskListAgileAction($id, Request $request)
    {

        $project = $this->LoadProject($id);
        $tastServiceProject = $this->get('astra.task.service.project');
        $taskListItemRepository = $this->getEm()->getRepository('AstraSharedBundle:TaskListItem');
        $taskList = $tastServiceProject->getActiveTaskList($project);
        $agileList = $taskListItemRepository->getAgileList($taskList);

        $statusList = Task::$fullStatusList;

        return $this->render('AstraSharedBundle:Projects:agile.html.twig',
            ['project'=>$project, 'agileList'=>$agileList, 'statusList'=>$statusList, 'taskList'=>$taskList]);
    }

    public function TaskListViewAction(Request $request)
    {
        $project = $this->LoadProject($request->get('id',0));
        $tastServiceProject = $this->get('astra.task.service.project');
        $taskListItemRepository = $this->getEm()->getRepository('AstraSharedBundle:TaskListItem');
        $taskList = $tastServiceProject->getActiveTaskList($project);
        $agileList = $taskListItemRepository->getAgileList($taskList);

        $statusList = Task::$fullStatusList;
        unset($statusList[Task::STATUS_COMPLEET]);

        return $this->render('AstraSharedBundle:Projects:task_list.html.twig',
            ['project'=>$project, 'agileList'=>$agileList, 'statusList'=>$statusList, 'taskList'=>$taskList]);
    }

    public function TaskListCalendarAction($id, Request $request)
    {

        $project = $this->LoadProject($id);
        $tastServiceProject = $this->get('astra.task.service.project');
        $taskListItemRepository = $this->getEm()->getRepository('AstraSharedBundle:TaskListItem');
        $taskList = $tastServiceProject->getActiveTaskList($project);
        $agileList = $taskListItemRepository->getAgileList($taskList);

        $statusList = Task::$fullStatusList;

        return $this->render('AstraSharedBundle:Projects:calendar.html.twig',
            ['project'=>$project, 'agileList'=>$agileList, 'statusList'=>$statusList, 'taskList'=>$taskList]);
    }

    public function TaskEditAction($id, Request $request)
    {
        $taskService = $this->get('astra.task.service');
        $project = $this->LoadProject($id);
        $idTask = $request->get('taskId',null);

        if(empty($idTask))
        {
            $task = new Task();
            $task->setCaption($request->get('new_name',''));
            $task->setIsCalendar($request->get('calendar',0)>0);
            $task->setAuthor($this->getUser());
        }
        else
        {
            $task = $this->getEm()->getRepository('AstraSharedBundle:Task')->find($idTask);
            $this->get('astra.shared_variable.service')->set(SharedVariableService::NAME_CURRENT_PROJECT_TASK,$task);
        }

        $form = $this->createForm(TaskType::class, $task, ['worker'=>['isRequire'=>true,'users'=>$project->getUsersAsEntity()]]);
        $form->handleRequest($request);
        if(($form->isValid()) && ($form->isSubmitted()))
        {
            $taskService->saveTask($task,$project);
            return new RedirectResponse($this->generateUrl('astra_shared_project_task_view',['id'=>$project->getId(),'task'=>$task->getId()]));
        }


        return $this->render('AstraSharedBundle:Projects:task_add.html.twig',['project'=>$project,'form'=>$form->createView()]);
    }

    public function TaskViewAction(Request $request)
    {
        $project = $this->LoadProject($request->get('id'));
        $task = $this->getEm()->getRepository('AstraSharedBundle:Task')->find($request->get('task'));
        if((!$task) || (!$project->isUserInProject($this->getUser())))  throw new NotFoundHttpException();
        $this->get('astra.shared_variable.service')->set(SharedVariableService::NAME_CURRENT_PROJECT_TASK,$task);
        return $this->render('AstraSharedBundle:Projects:task_view.html.twig',['project'=>$project,'task'=>$task]);
    }

    public function ChatAction(Request $request)
    {
        $projectService = $this->get('astra.project.service');
        $project = $this->LoadProject($request->get('id'));
        $projectMessageContainer = $projectService->getProjectMessageContainer($project);
        return $this->render('AstraSharedBundle:Projects:chat.html.twig',['project'=>$project,'messageContainer'=>$projectMessageContainer]);
    }

    public function UsersAction(Request $request)
    {
        $project = $this->LoadProject($request->get('id'));
        return $this->render('AstraSharedBundle:Projects:users.html.twig',['project'=>$project,'projetUsers'=>$project->getUsers()]);
    }

    public function UsersModPostAction(Request $request)
    {
        /** @var Project $project */
        $project = $this->LoadProject($request->get('projectId'));
        $projectUser =$this->getEm()->getRepository('AstraSharedBundle:ProjectUsers')->find($request->get('projectUserId'));
        $projectService = $this->get('astra.project_user.service');

        $result =
            [
                'success'=>false,
                'message'=>'',
                'html'=>'',
            ];

        if(!$projectUser)
        {
            $result['message'] = $this->trans('projects.isUserNotFound');
            return $this->json($result);
        }

        if(!$project->getUsers()->contains($projectUser))
        {
            $result['message'] = $this->trans('projects.isUserNotInProject');
            return $this->json($result);
        }

        $projectService->setPostUser($projectUser,Values::getString($request->get('post','')));

        $result['success']=true;
        $result['message']='';
        $result['html']=$this->render('AstraSharedBundle:Projects:_users_item.html.twig',['projetUser'=>$projectUser])->getContent();

        return $this->json($result);
    }

    public function UsersRemovePostAction(Request $request)
    {
        /** @var Project $project */
        $project = $this->LoadProject($request->get('projectId'));
        $projectUser =$this->getEm()->getRepository('AstraSharedBundle:ProjectUsers')->find($request->get('projectUserId'));
        $projectService = $this->get('astra.project_user.service');

        $result =
            [
                'success'=>false,
                'message'=>'',
            ];

        if(!$projectUser)
        {
            $result['message'] = $this->trans('projects.isUserNotFound');
            return $this->json($result);
        }

        if(!$project->getUsers()->contains($projectUser))
        {
            $result['message'] = $this->trans('projects.isUserNotInProject');
            return $this->json($result);
        }

        if($project->getAuthor() === $projectUser->getUser())
        {
            $result['message'] = $this->trans('projects.dinedAuthorForRemove');
            return $this->json($result);
        }

        $projectService->removeProjectUser($project,$projectUser);

        $result['success']=true;
        $result['message']='';

        return $this->json($result);
    }

    public function UsersAddPostAction(Request $request)
    {
        /** @var Project $project */
        $project = $this->LoadProject($request->get('projectId'));
        $user =$this->getEm()->getRepository('AstraSharedBundle:User')->find($request->get('userId'));
        $projectService = $this->get('astra.project_user.service');

        $result =
            [
                'success'=>false,
                'message'=>'',
                'html'=>'',
            ];

        if(!$user)
        {
            $result['message'] = $this->trans('user.notFound');
            return $this->json($result);
        }



        if($project->isUserInProject($user))
        {
            $result['message'] = $this->trans('projects.isUserAlreadyInProject');
            return $this->json($result);
        }

        $projectUser = $projectService->addUserToProject($project,$user);
        $this->getEm()->flush();

        $result['success']=true;
        $result['message']='';
        $result['html']=$this->render('AstraSharedBundle:Projects:_users_item.html.twig',['projetUser'=>$projectUser])->getContent();

        return $this->json($result);
    }

    public function SearchAddUsersAjaxAction(Request $request){
        $project = $this->LoadProject($request->get('projectId'));
        $searchRequest = Values::getString($request->get('q',''));
        $result = [];
        if(!mb_strlen($searchRequest))
        {
            return $this->json($result);
        }

        $searchService = $this->get('astra.search.service');

        $allReadyUsers = [];
        foreach ($project->getUsers() as $user)
        {
            $allReadyUsers[] = $user->getUser()->getId();
        }


        /** @var User[] $users */
        $users = $searchService->SearchUsers($searchRequest,50,$allReadyUsers);

        foreach ($users as $user)
        {
            $result[] = ['id'=>$user->getId(), 'text'=>trim($this->render(':pieces:_user_name.html.twig',['user'=>$user])->getContent())];
        }

        return $this->json($result);
    }

    protected function LoadProject($id)
    {
        if (empty($id)) throw new NotFoundHttpException();
        $project = $this->getEm()->getRepository('AstraSharedBundle:Project')->find($id);
        if(!$project) throw new NotFoundHttpException();
        $this->get('astra.shared_variable.service')->set(SharedVariableService::NAME_CURRENT_PROJECT,$project);
        return $project;
    }
}
