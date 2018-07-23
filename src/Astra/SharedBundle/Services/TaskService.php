<?php
namespace Astra\SharedBundle\Services;
use Astra\SharedBundle\Entity\Project;
use Astra\SharedBundle\Entity\Task;
use Astra\SharedBundle\Entity\TaskList;
use Astra\SharedBundle\Entity\TaskListItem;
use Astra\SharedBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\Router;
use Symfony\Component\Validator\Constraints\DateTime;

class TaskService
{
    private $entityManager;
    private $taskServiceProject;
    private $taskServiceUser;
    private $router;

    public function __construct(EntityManager $entityManager, TaskServiceProject $taskServiceProject, TaskServiceUser $taskServiceUser, Router $router)
    {
        $this->entityManager = $entityManager;
        $this->taskServiceProject = $taskServiceProject;
        $this->taskServiceUser = $taskServiceUser;
        $this->router = $router;
    }

    public function saveTask(Task $task, Project $project = null, User $user = null)
    {
        $this->entityManager->persist($task);
        $this->TaskHandle($task, $project, $user);
        $this->entityManager->flush();
    }

    private function TaskHandle(Task $task, Project $project = null, User $user = null)
    {
        if($project)
        {
            $taskList = $this->taskServiceProject->getActiveTaskList($project);
            $this->addTaskToTaskList($taskList,$task);
        }

        if($user)
        {
            $taskList = $this->taskServiceUser->getActiveTaskList($user);
            $this->addTaskToTaskList($taskList,$task);
        }
    }

    public function addTaskToTaskList(TaskList $taskList, Task $task)
    {
        /** @var TaskListItem[] $taskListItems */
        $taskListItems = $taskList->getTaskListItem();
        foreach ($taskListItems as $item)
        {
            if($item->getTask() === $task)return;
        }

        $item = new TaskListItem();
        $item->setTask($task);
        $item->setTaskList($taskList);
        $item->setPosition($this->getTaskListPosition($taskList,$task));
        $this->entityManager->persist($item);
    }

    private function getTaskListPosition(TaskList $taskList, Task $task)
    {
        $result = 0;
        foreach($taskList->getTaskListItem() as $item)
        {
            if($item->getTask()===$task)
            {
                return $item->getPosition();
            }

            if($item->getTask()->getStatus() !== $task->getStatus()) continue;

            if($item->getPosition() > $result)$result = $item->getPosition();
        }
        return $result+1;
    }

    public function reorderTaskList(TaskList $taskList, array $newOrder)
    {
        $compiledData = $this->compileReorderData($newOrder);
        foreach ($taskList->getTaskListItem() as $item)
        {
            if(!isset($compiledData[$item->getId()])) continue;
            $newStatus = $compiledData[$item->getId()]['status'];
            $newOrder = $compiledData[$item->getId()]['order'];
            $task = $item->getTask();

            if ($task->getStatus() !== $newStatus)
            {
                $task->setStatus($newStatus);
                $this->entityManager->persist($task);
            }

            if($newOrder!==$item->getPosition())
            {
                $item->setPosition($newOrder);
                $this->entityManager->persist($item);
            }
        }
        $this->entityManager->flush();
    }

    private function compileReorderData($newOrder)
    {
        $result = [];
        foreach ($newOrder as $status=>$items)
        {
            if(!is_array($items))throw new \Exception('task.agile.reorder.wrongDataFormat');
            if(!in_array($status,Task::$fullStatusList))throw new \Exception('task.agile.reorder.wrongDataFormat');

            foreach ($items as $order=>$task)
            {
                $result[$task]['status'] = $status;
                $result[$task]['order'] = $order;
            }
        }

        return $result;
    }

    public function getTaskListForUserCalendar(User $user, \DateTime $dateStart = null, \DateTime $dateEnd = null)
    {
        $taskList = $this->taskServiceUser->getActiveTaskList($user);
        $list = $this->entityManager->getRepository('AstraSharedBundle:TaskListItem')->getCalendarList($taskList,$dateStart,$dateEnd);
        return $this->prepareCalendarItemList($list,$taskList);
    }

    public function getTaskListForProjectCalendar(Project $project, \DateTime $dateStart = null, \DateTime $dateEnd = null)
    {
        $taskList = $this->taskServiceProject->getActiveTaskList($project);
        $list = $this->entityManager->getRepository('AstraSharedBundle:TaskListItem')->getCalendarList($taskList,$dateStart,$dateEnd);
        return $this->prepareCalendarItemList($list,$taskList);
    }

    /**
     * @param TaskListItem[] $list
     * @param TaskList $taskList
     * @return array
     */
    public function prepareCalendarItemList($list,$taskList)
    {
        $result = [];
        $urlPath = '';
        $urlParams = [];

        if($taskList->getProject())
        {
            $urlPath = 'astra_shared_project_task_view';
            $urlParams['id'] = $taskList->getProject()->getId();

        }elseif ($taskList->getUser())
        {
            $urlPath = 'astra_myprofile_task_view';
        }

        foreach ($list as $item)
        {
            $urlParams['task'] = $item->getId();
            $id = $item->getId();
            $task = $item->getTask();
            $itemResult =
                [
                    'id' => $id,
                    'title' => $task->getCaption(),
                    'start' => $task->getStartWork()->format('Y-m-d').'T'.$task->getStartWork()->format('H:i:s'),
                    'end' => $task->getEndWork()->format('Y-m-d').'T'.$task->getEndWork()->format('H:i:s'),
                    'url' => $this->url($urlPath,$urlParams),
                ];

            $result[] = $itemResult;
        }
        return $result;
    }

    private function url($urlPath,$urlParams)
    {
        if(empty($urlPath))return '';
        return $this->router->generate($urlPath,$urlParams);
    }
}