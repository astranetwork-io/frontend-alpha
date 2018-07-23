<?php
namespace Astra\SharedBundle\Services;
use Astra\SharedBundle\Entity\MessageContainer;
use Astra\SharedBundle\Entity\Project;
use Astra\SharedBundle\Entity\ProjectUsers;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ProjectService
{
    private $entityManager;
    private $tokenStorage;
    private $fileService;
    private $projectUserService;

    public function __construct
    (
        EntityManager $entityManager,
        TokenStorageInterface $tokenStorage,
        ProjectFileService $fileService,
        ProjectUserService $projectUserService
    )
    {
        $this->entityManager = $entityManager;
        $this->tokenStorage = $tokenStorage;
        $this->fileService = $fileService;
        $this->projectUserService = $projectUserService;
    }

    public function saveProject(Project $project)
    {
        $currentUser = null;
        if(($this->tokenStorage->getToken())&&($this->tokenStorage->getToken()->getUser()))
        {
            $currentUser = $this->tokenStorage->getToken()->getUser();
        }
        if((!$project->getId())&&($currentUser))
        {
            $project->setAuthor($currentUser);
        }

        $this->projectUserService->addUserToProject($project,$currentUser);

        $this->entityManager->persist($project);
        $this->entityManager->flush();

        $this->fileService->getRootDirectory($project);

        if ($project->getNewLogotype())
        {
            $file = $this->fileService->loadFileToProject($project->getNewLogotype(),$project,false,'/logotypes');
            if (!empty($file)) $project->setLogotype($file);
            $this->entityManager->persist($project);
            $this->entityManager->flush();
        }
        return $project;
    }

    /**
     * @param Project $project
     * @return MessageContainer
     */
    public function getProjectMessageContainer(Project $project)
    {
        $messageContainer = $this->entityManager->getRepository('AstraSharedBundle:MessageContainer')->findOneBy(['project'=>$project]);
        if(!$messageContainer)
        {
            $messageContainer = new MessageContainer();
            $messageContainer->setType(MessageContainer::TYPE_PROJECT);
            $messageContainer->setProject($project);
            $this->entityManager->persist($messageContainer);
        }

        $this->synhroUsersToMessageContainer($messageContainer, $project);
        $this->entityManager->persist($messageContainer);
        $this->entityManager->flush();

        return $messageContainer;
    }

    private function synhroUsersToMessageContainer(MessageContainer $messageContainer, Project $project)
    {
        $projectUsers = $project->getUsers();
        $users = [];
        foreach ($projectUsers as $projectUser)
        {
            $users[$projectUser->getUser()->getId()] =  $projectUser->getUser();
        }

        foreach ($messageContainer->getUsers() as $user)
        {
            if(!in_array($user,$users,true))
            {
                $messageContainer->removeUser($user);
                continue;
            }

            unset($users[$user->getId()]);
        }

        foreach ($users as $user)
        {
            $messageContainer->addUser($user);
        }
    }
}