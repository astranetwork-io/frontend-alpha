<?php
namespace Astra\SharedBundle\Services;
use Astra\SharedBundle\Entity\Project;
use Astra\SharedBundle\Entity\ProjectUsers;
use Astra\SharedBundle\Entity\User;
use Doctrine\ORM\EntityManager;

class ProjectUserService
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param Project $project
     * @param User $user
     * @param string $post
     * @return ProjectUsers
     */
    public function addUserToProject(Project $project, User $user,$post='')
    {
        foreach ($project->getUsers() as $projectUser)
        {
            if($projectUser->getUser()===$user) return $projectUser;
        }

        $projectUser = new ProjectUsers();
        $projectUser->setProject($project);
        $projectUser->setUser($user);
        $projectUser->setPost($post);
        $this->entityManager->persist($projectUser);

        return $projectUser;
    }

    public function setPostUser(ProjectUsers $projectUser, $post)
    {
        $projectUser->setPost($post);
        $this->entityManager->persist($projectUser);
        $this->entityManager->flush($projectUser);
    }

    public function removeProjectUser(Project $project, ProjectUsers $projectUser)
    {
        $project->removeUser($projectUser);
        $this->entityManager->persist($project);
        $this->entityManager->remove($projectUser);
        $this->entityManager->flush();
    }
}