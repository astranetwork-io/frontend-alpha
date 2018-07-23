<?php
namespace Astra\SharedBundle\Services;
use Astra\SharedBundle\Entity\Directory;
use Astra\SharedBundle\Entity\File;
use Astra\SharedBundle\Entity\Project;
use Astra\SharedBundle\Entity\ProjectDirectory;
use Astra\SharedBundle\Entity\ProjectFile;
use Astra\SharedBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UserFileService
{
    private $entityManager;
    private $fileService;

    public function __construct(EntityManager $entityManager, FileService $fileService)
    {
        $this->entityManager = $entityManager;
        $this->fileService = $fileService;
    }

    public function getCurrentDirectory(User $user, $directoryId = null)
    {
        if(empty($directoryId))return $this->genRootDir($user);
        $directory = $this->entityManager->getRepository('AstraSharedBundle:Directory')->find($directoryId);
        if($directory->getUser()!==$user)return $this->genRootDir($user);
        return $directory;
    }

    public function getDirectoryLine(User $user, Directory $currentDirectory)
    {
        $result = [];
        if(empty($currentDirectory->getId()))
        {
            $result[] = $this->genRootDir($user);
            return $result;
        }

        $result[] = $currentDirectory;
        while ($currentDirectory = $currentDirectory->getParent())
        {
            if($currentDirectory->getUser()!==$user)break;
            $result[] = $currentDirectory;
        }
        $result[] = $this->genRootDir($user);

        return array_reverse($result);
    }

    public function getSubDirectories(User $user, Directory $currentDirectory = null)
    {
        $filter = [];
        $filter['user'] = $user;
        $filter['parent'] = null;
        if (($currentDirectory) && ($currentDirectory->getId()))
        {
            $filter['parent'] = $currentDirectory;
        }

        return $this->entityManager->getRepository('AstraSharedBundle:Directory')->findBy($filter,['name'=>'ASC']);
    }

    public function genRootDir(User $user)
    {
        $newDirectory = new Directory();
        $newDirectory->setUser($user);
        $newDirectory->setName('root');
        return $newDirectory;
    }
}