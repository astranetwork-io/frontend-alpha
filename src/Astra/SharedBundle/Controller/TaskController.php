<?php

namespace Astra\SharedBundle\Controller;

use Astra\SharedBundle\Entity\Task;
use Astra\SharedBundle\Services\SharedVariableService;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TaskController extends BaseController
{
    public function reorderAgileListAction(Request $request)
    {
        $taskListId = $request->get('taskList',0);
        $data = $request->get('data',[]);

        $taskList = $this->getEm()->getRepository('AstraSharedBundle:TaskList')->find($taskListId);

        if(empty($taskList))
        {
            $result =
                [
                    'success'=>false,
                    'message'=>$this->trans('task.agile.reorder.notFoundList'),
                ];
            return $this->json($result);
        }

        if(!is_array($data))
        {
            $result =
                [
                    'success'=>false,
                    'message'=>$this->trans('task.agile.reorder.wrongDataFormat'),
                ];
            return $this->json($result);
        }

        try
        {
            $this->get('astra.task.service')->reorderTaskList($taskList,$data);
        }
        catch (\Exception $e)
        {
            $result =
                [
                    'success'=>false,
                    'message'=>$this->trans($e->getMessage()),
                ];
            return $this->json($result);
        }

        $result =
            [
                'success'=>true,
                'message'=>'OK',
            ];

        return $this->json($result);
    }

    public function UploadFileForTaskAction($id, Request $request)
    {
        $public_files_directory = $this->getParameter('public_files_directory');
        $fileService = $this->get('astra.file.service');
        $taskFileService = $this->get('astra.task_file.service');
        $task = $this->LoadTask($id);
        $isPublic = !empty($request->get('public',0));
        $directory = $request->get('directory','task');

        $result =
            [
                'success'=>false,
                'message'=>$this->trans('dropzone.defaultError'),
                'filename'=>'',
                'url'=>'',
                'type'=>'',
            ];

        if(!$task)
        {
            $result['message']=$this->trans('task.notFound');
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
        $file = $taskFileService->loadFileToTask($fileUpload,$task,$isPublic,$directory);

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

    public function loadProjectCalendarAction(Request $request)
    {
        $project = $this->getEm()->getRepository('AstraSharedBundle:Project')
            ->find($request->get('projectId',0));
        if(!$project) throw new NotFoundHttpException();

        $end = null;
        if($endInput = $request->get('end',null))
        {
            $end = new \DateTime($endInput);
        }

        $start = null;
        if($startInput = $request->get('start',null))
        {
            $start = new \DateTime($startInput);
        }

        $taskService = $this->get('astra.task.service');
        $result = $taskService->getTaskListForProjectCalendar($project,$start,$end);
        return $this->json($result);
    }

    public function loadUserCalendarAction(Request $request)
    {
        $end = null;
        if($endInput = $request->get('end',null))
        {
            $end = new \DateTime($endInput);
        }

        $start = null;
        if($startInput = $request->get('start',null))
        {
            $start = new \DateTime($startInput);
        }

        $taskService = $this->get('astra.task.service');
        $result = $taskService->getTaskListForUserCalendar($this->getUser(),$start,$end);
        return $this->json($result);
    }

    public function updateCalendarAction(Request $request)
    {
        $result =
            [
                'success'=>false,
                'message'=>$this->trans('task.calendar.updateError'),
            ];

        $id = $request->get('id',null);
        $start = $request->get('start',null);
        $end = $request->get('end',null);

        if((empty($id))||(empty($start)))
        {
            $result['message']=$this->trans('task.calendar.wrongData');
            return $this->json($result);
        }

        $startObj = new \DateTime($start);
        if(!empty($end))
        {
            $endObj = new \DateTime($end);
        }
        else
        {
            $endObj = clone $startObj;
            $endObj->modify('+2 hour');
        }

        $taskService = $this->get('astra.task.service');
        $taskListItem = $this->getEm()->getRepository('AstraSharedBundle:TaskListItem')->find($id);

        if(!$taskListItem)
        {
            $result['message']=$this->trans('task.calendar.wrongTask');
            return $this->json($result);
        }

        /** @var Task $task */
        $task = $taskListItem->getTask();
        $task->setStartWork($startObj);
        $task->setEndWork($endObj);

        $taskService->saveTask($task);

        $result =
            [
                'success'=>true,
                'message'=>'',
            ];

        return $this->json($result);
    }

    protected function LoadTask($id)
    {
        if (empty($id)) throw new NotFoundHttpException();
        $task = $this->getEm()->getRepository('AstraSharedBundle:Task')->find($id);
        if(!$task) throw new NotFoundHttpException();
        $this->get('astra.shared_variable.service')->set(SharedVariableService::NAME_CURRENT_TASK,$task);
        return $task;
    }
}
