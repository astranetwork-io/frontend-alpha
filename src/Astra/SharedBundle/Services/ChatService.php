<?php
namespace Astra\SharedBundle\Services;
use Astra\SharedBundle\Entity\Message;
use Astra\SharedBundle\Entity\MessageContainer;
use Astra\SharedBundle\Entity\User;
use Doctrine\ORM\EntityManager;

class ChatService
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getMessageContainer($id)
    {
       return $this->entityManager->getRepository('AstraSharedBundle:MessageContainer')->find($id);
    }

    public function getUsersForChat(MessageContainer $messageContainer)
    {
        $users = $messageContainer->getUsers();
        return $users;
    }

    public function getNewMessages($messageContainers, $fromId, User $user ,$limit = 50, $reverse=false)
    {
        if(!is_array($messageContainers))$messageContainers = [$messageContainers];

        $messageContainerIds = [];
        foreach ($messageContainers as $messageContainer)$messageContainerIds[] = $messageContainer->getId();
        $messageContainerIds = array_unique($messageContainerIds);

        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->select('message, user')
            ->from('AstraSharedBundle:Message','message')
            ->innerJoin('message.user','user')
        ->where
        ("(message.messageContainer IN (:messageContainer))")
            ->setParameter('messageContainer',$messageContainerIds);

        if(($fromId>0)&&(!$reverse))
        {
            $queryBuilder->orderBy('message.id','ASC')
                ->andWhere("message.id > :id")
                ->setParameter('id',$fromId);
        }
        else
        {
            $queryBuilder->orderBy('message.id','DESC');
            if($fromId>0)
            {
                $queryBuilder->andWhere("message.id < :id")
                ->setParameter('id',$fromId);
            }
        }

        $messages = $queryBuilder->getQuery()->setMaxResults($limit)->getResult();

        $this->prepareReaders($messages, $user, true);

        return $messages;

    }

    /**
     * @param Message[] $messages
     * @param User $user
     * @param bool $addReaders
     */
    public function prepareReaders($messages, User $user, $addReaders = false)
    {
        foreach ($messages as $message)
        {
            $is_read = $message->getReaders()->contains($user);
            $message->setIsRead($is_read);
            if(!$is_read)
            {
                $message->addReader($user);
                $this->entityManager->persist($message);
            }
        }

        $this->entityManager->flush();
    }

    public function createMessage(string $message, $files, MessageContainer $messageContainer, User $user)
    {
        if(!$messageContainer->getUsers()->contains($user))
        {
            $messageContainer->addUser($user);
            $this->entityManager->persist($messageContainer);
        }

        $newMessage = new Message();
        $newMessage->setUser($user);
        $newMessage->setText($message);
        $newMessage->setMessageContainer($messageContainer);

        $this->entityManager->persist($newMessage);

        $messageContainer->addMessage($newMessage);

        $filesObj = $this->entityManager->getRepository('AstraSharedBundle:File')->findBy(['id'=>$files]);
        foreach ($filesObj as $file)
        {
            $newMessage->addFile($file);
        }

        $this->entityManager->flush();
    }

    public function getAllMessagesUser(User $user, $fromId, $limit = 50, $reverse=false)
    {
        $messageContainers = $this->entityManager->getRepository('AstraSharedBundle:MessageContainer')->getAllForUser($user);
        return $this->getNewMessages($messageContainers,$fromId,$user,$limit,$reverse);
    }
}