<?php

namespace Astra\SharedBundle\Controller;

use Astra\SharedBundle\Entity\Task;
use Astra\SharedBundle\Form\TaskType;
use Astra\SharedBundle\Form\UserAboutType;
use Astra\SharedBundle\Form\UserType;
use Astra\SharedBundle\Services\SharedVariableService;
use Astra\SharedBundle\Utils\Values;
use Doctrine\Common\Collections\Expr\Value;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MyProfileController extends BaseController
{
    public function ViewAction()
    {
        return new RedirectResponse($this->generateUrl('astra_profile_my_view'));
    }

    public function EditAction(Request $request)
    {

        $fileService = $this->get('astra.file.service');
        $form = $this->createForm(UserType::class, $this->user, []);
        $form->handleRequest($request);
        if(($form->isSubmitted()) && ($form->isValid()))
        {
            if ($this->user->getNewPhoto())
            {
                $file = $fileService->saveUploadetFile($this->user->getNewPhoto(),$this->user,'avatars');
                if (!empty($file)) $this->user->setPhoto($file);
            }

            /** @var $userManager UserManagerInterface */
            $userManager = $this->get('fos_user.user_manager');
            $userManager->updateUser($this->user);

            return new RedirectResponse($this->generateUrl('astra_myprofile_edit'));
        }

        return $this->render('AstraSharedBundle:MyProfile:edit.html.twig', ['user'=>$this->user, 'form'=>$form->createView()]);
    }

    public function EditAboutAction(Request $request)
    {
        $form = $this->createForm(UserAboutType::class, $this->user, []);
        $form->handleRequest($request);
        if(($form->isSubmitted()) && ($form->isValid()))
        {
            /** @var $userManager UserManagerInterface */
            $userManager = $this->get('fos_user.user_manager');
            $userManager->updateUser($this->user);

            return new RedirectResponse($this->generateUrl('astra_myprofile_about_me'));
        }

        return $this->render('AstraSharedBundle:MyProfile:edit.html.twig', ['user'=>$this->user, 'form'=>$form->createView()]);
    }

    public function fileManagerAction(Request $request)
    {
        $projectFileService = $this->get('astra.user_file.service');

        $currentDirectory = $projectFileService->getCurrentDirectory($this->getUser(),$request->get('directory'));

        $directoryLine = $projectFileService->getDirectoryLine($this->getUser(), $currentDirectory);

        $directoryList = $projectFileService->getSubDirectories($this->getUser(), $currentDirectory);

        $fileList = $this->getEm()->getRepository('AstraSharedBundle:File')->getFileForDirectoryAndUser($this->getUser(),$currentDirectory);


        return $this->render('AstraSharedBundle:MyProfile:files.html.twig', ['directoryList'=>$directoryList,'currentDirectory'=>$currentDirectory,'directoryLine'=>$directoryLine,'fileList'=>$fileList]);
    }

    public function CreateDirectoryForUserAction(Request $request)
    {
        $userFileService = $this->get('astra.user_file.service');
        $fileService = $this->get('astra.file.service');
        $directoryName = Values::getString($request->get('directoryName',''));
        $parentDirectoryId = $request->get('parentDirectoryId',0);
        if($parentDirectoryId)
        {
            $parentDirectory = $this->getEm()->getRepository('AstraSharedBundle:Directory')->find($parentDirectoryId);
        }
        else
        {
            $parentDirectory = $userFileService->genRootDir($this->user);
        }


        $result =
            [
                'success'=>false,
                'message'=>$this->trans('dropzone.defaultError'),
                'name'=>'',
                'url'=>'',
            ];

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

        if($parentDirectory->getUser()!==$this->getUser())
        {
            $result['message']=$this->trans('fileManager.isDirectoryNotAllowForUser');
            return $this->json($result);
        }


        $newDirectory = $fileService->getDirectoryByPath($directoryName,$parentDirectory,$this->getUser(),true);

        $this->getEm()->flush();
        $result =
            [
                'success'=>true,
                'message'=>'',
                'name'=>$newDirectory->getName(),
                'url'=>$this->generateUrl('astra_myprofile_files',['directory'=>$newDirectory->getId()]),
            ];

        return $this->json($result);
    }

    public function TaskListAgileAction(Request $request)
    {
        $tastService = $this->get('astra.task.service.user');
        $taskListItemRepository = $this->getEm()->getRepository('AstraSharedBundle:TaskListItem');
        $taskList = $tastService->getActiveTaskList($this->getUser());
        $agileList = $taskListItemRepository->getAgileList($taskList);

        $statusList = Task::$fullStatusList;

        return $this->render('AstraSharedBundle:MyProfile:agile.html.twig',
            ['agileList'=>$agileList, 'statusList'=>$statusList, 'taskList'=>$taskList]);
    }

    public function TaskEditAction(Request $request)
    {
        $taskService = $this->get('astra.task.service');
        $idTask = $request->get('task',null);

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
            $this->get('astra.shared_variable.service')->set(SharedVariableService::NAME_CURRENT_TASK,$task);
        }

        $form = $this->createForm(TaskType::class, $task, ['worker'=>['isRequire'=>true,'users'=>[$this->getUser()]]]);
        $form->handleRequest($request);
        if(($form->isValid()) && ($form->isSubmitted()))
        {
            $taskService->saveTask($task,null,$task->getWorker());
            return new RedirectResponse($this->generateUrl('astra_myprofile_task_view',['task'=>$task->getId()]));
        }


        return $this->render('AstraSharedBundle:MyProfile:task_add.html.twig',['form'=>$form->createView()]);
    }

    public function TaskViewAction(Request $request)
    {
        $task = $this->getEm()->getRepository('AstraSharedBundle:Task')->find($request->get('task'));
        if((!$task) || (($task->getWorker() !== $this->getUser()) && ($task->getAuthor() !== $this->getUser())))  throw new NotFoundHttpException();
        $this->get('astra.shared_variable.service')->set(SharedVariableService::NAME_CURRENT_TASK,$task);
        return $this->render('AstraSharedBundle:MyProfile:task_view.html.twig',['task'=>$task]);
    }

    public function TaskListCalendarAction(Request $request)
    {
        $tastService = $this->get('astra.task.service.user');
        $taskListItemRepository = $this->getEm()->getRepository('AstraSharedBundle:TaskListItem');
        $taskList = $tastService->getActiveTaskList($this->getUser());
        $agileList = $taskListItemRepository->getAgileList($taskList);

        $statusList = Task::$fullStatusList;

        return $this->render('AstraSharedBundle:MyProfile:calendar.html.twig',
            ['agileList'=>$agileList, 'statusList'=>$statusList, 'taskList'=>$taskList]);
    }
}
