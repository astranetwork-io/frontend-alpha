<?php
namespace Astra\SharedBundle\Services;
use Astra\SharedBundle\Entity\File;
use Astra\SharedBundle\Entity\Task;
use Astra\SharedBundle\Entity\TaskFile;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class TaskFileService
{
    private $entityManager;
    private $fileService;

    public function __construct(EntityManager $entityManager, FileService $fileService)
    {
        $this->entityManager = $entityManager;
        $this->fileService = $fileService;
    }

    public function addFileToTask(Task $task, File $file, bool $public)
    {

        foreach ($task->getFiles() as $taskFile)
        {
            if($taskFile->getFile()===$file) return $file;
        }

        $projectFile = new TaskFile();
        $projectFile->setTask($task);
        $projectFile->setFile($file);
        $projectFile->setIsPublic($public);
        $this->entityManager->persist($projectFile);

        return $file;
    }

    public function loadFileToTask(UploadedFile $uploadetFile, Task $task, bool $public = false, string $virtualDirectory = '')
    {
        $projectName = preg_replace ("/[^a-zA-ZА-Яа-я0-9\s]/u","",mb_substr($task->getCaption(),0,32));
        if(mb_strlen($task->getCaption())>32)$projectName .= '...';

        $virtualDirectory = $virtualDirectory.'/'.$projectName;
        $file = $this->fileService->saveUploadetFile($uploadetFile,null,$virtualDirectory);
        if(!$file) return null;

        $this->addFileToTask($task,$file,$public);
        $this->entityManager->flush();
        return $file;
    }
}