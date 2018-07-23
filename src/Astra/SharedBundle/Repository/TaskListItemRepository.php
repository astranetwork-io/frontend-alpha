<?php

namespace Astra\SharedBundle\Repository;

use Astra\SharedBundle\Entity\TaskList;
use Astra\SharedBundle\Entity\TaskListItem;
use Astra\SharedBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

/**
 * TaskListItemRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TaskListItemRepository extends EntityRepository
{
    public function getAgileList(TaskList $taskList, User $worker = null, User $author = null, $isCalendar = false)
    {
        $query =
            $this->createQueryBuilder('task_list_item')->
                innerJoin('task_list_item.task','task')->
                where('task_list_item.taskList = :taskList')->
                orderBy('task_list_item.position','ASC')->
                addOrderBy('task_list_item.id','ASC')->
                setParameter('taskList',$taskList);

        if(!is_null($isCalendar))$query->andWhere("(task.isCalendar = :isCalendar)")->setParameter('isCalendar',$isCalendar);

        if($worker)
        {
            $query->where('(task.worker = :worker)');
            $query->setParameter('worker',$worker);
        }

        if($author)
        {
            $query->where('(task.author = :author)');
            $query->setParameter('author',$author);
        }

        /** @var TaskListItem[] $list */
        $list = $query->getQuery()->getResult();

        $result = [];

        foreach ($list as $item)
        {
            $result[$item->getTask()->getStatus()][$item->getId()] = $item;
        }

        return $result;
    }

    /**
     * @param TaskList $taskList
     * @param \DateTime|null $dateStart
     * @param \DateTime|null $dateEnd
     * @param boolean $isCalendar
     * @return TaskListItem[]
     */
    public function getCalendarList(TaskList $taskList, \DateTime $dateStart = null, \DateTime $dateEnd = null, $isCalendar = true)
    {
        $dbBuilder =
            $this->createQueryBuilder('task_list_item')
            ->innerJoin('task_list_item.task','task')
            ->where('task_list_item.taskList = :taskList')
            ->setParameter('taskList',$taskList);

        if(!is_null($isCalendar))
        $dbBuilder->andWhere("task.isCalendar = :isCalendar")->setParameter('isCalendar',$isCalendar);

        if($dateStart)
        {
            $dbBuilder->andWhere('task.startWork >= :dateStart')->
            setParameter('dateStart',$dateStart->format('Y-m-d').' 00:00:00');
        }

        if($dateEnd)
        {
            $dbBuilder->andWhere('task.endWork <= :dateEnd')->
            setParameter('dateEnd',$dateEnd->format('Y-m-d').' 23:59:59');
        }

        return $dbBuilder->getQuery()->getResult();
    }
}
