<?php
namespace Astra\SharedBundle\Services;
use Astra\SharedBundle\Entity\Directory;
use Astra\SharedBundle\Entity\File;
use Astra\SharedBundle\Entity\Project;
use Astra\SharedBundle\Entity\ProjectDirectory;
use Astra\SharedBundle\Entity\ProjectFile;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ProjectFileService
{
    private $entityManager;
    private $fileService;

    public function __construct(EntityManager $entityManager, FileService $fileService)
    {
        $this->entityManager = $entityManager;
        $this->fileService = $fileService;
    }

    public function addFileToProject(Project $project, File $file, bool $public)
    {

        foreach ($project->getFiles() as $projectFile)
        {
            if($projectFile->getFile()===$file) return $file;
        }

        $projectFile = new ProjectFile();
        $projectFile->setProject($project);
        $projectFile->setFile($file);
        $projectFile->setIsPublic($public);
        $this->entityManager->persist($projectFile);

        return $file;
    }

    public function loadFileToProject(UploadedFile $uploadetFile, Project $project, bool $public = false, string $virtualDirectory = '')
    {
        $base_directory = $this->getRootDirectory($project);
        $file = $this->fileService->saveUploadetFile($uploadetFile,null,$virtualDirectory,$base_directory);
        if(!$file) return null;

        $this->addFileToProject($project,$file,$public);
        $this->addDirectoryToProjectByPath($base_directory,$virtualDirectory,$project);
        $this->entityManager->flush();
        return $file;
    }

    public function loadFileToProjectDirectory(UploadedFile $uploadetFile, Project $project, bool $public = false, Directory $directory)
    {
        $file = $this->fileService->saveUploadetFile($uploadetFile,null,null,$directory);
        if(!$file) return null;
        $this->addFileToProject($project,$file,$public);
        $this->entityManager->flush();
        return $file;
    }

    public function addDirectoryToProjectByPath(Directory $baseDirectory, string $virtualDirectory, Project $project)
    {
        $virtualDirectoryArray = explode('/',$this->fileService->normalizePathName($virtualDirectory));
        foreach ($virtualDirectoryArray as $dirName)
        {
            $baseDirectory = $this->fileService->getDirectoryByPath($dirName,$baseDirectory,null,false);
            $this->addDirectoryToProject($baseDirectory,$project, false);
        }
    }

    public function addDirectoryToProject(Directory $directory, Project $project, bool $isRoot)
    {
        $findetProjectDirectory = null;
        foreach ($project->getProjectDirectories() as $projectDirectory)
        {
            if($projectDirectory->getDirectory()===$directory)$findetProjectDirectory = $projectDirectory;
            if (!$isRoot) continue;

            $projectDirectory->setIsRoot(false);
            $this->entityManager->persist($projectDirectory);
        }

        if(!$findetProjectDirectory)
        {
            $findetProjectDirectory = new ProjectDirectory();
        }
        $findetProjectDirectory->setProject($project);
        $findetProjectDirectory->setDirectory($directory);
        $findetProjectDirectory->setIsRoot($isRoot);
        $this->entityManager->persist($findetProjectDirectory);
    }

    public function getCurrentDirectory(Project $project, $directoryId)
    {
        $index = $this->getIndexDirectoryes($project);
        $directory = $this->entityManager->getRepository('AstraSharedBundle:Directory')->find($directoryId);
        if ((!$directory)||(empty($index[$directory->getId()]))) $directory = $this->getRootDirectory($project);
        return $directory;
    }

    /**
     * @param Project $project
     * @param Directory $parentDirectory
     * @return Directory[]
     */
    public function getSubDirectories(Project $project, Directory $parentDirectory)
    {
        $result = [];
        $index = $this->getIndexDirectoryes($project);
        foreach ($parentDirectory->getChildDirectories() as $directory)
        {
            if (empty($index[$directory->getId()])) continue;
            $result[] = $directory;
        }

        return $result;
    }

    /**
     * @param Project $project
     * @param Directory $directory
     * @return Directory[]
     */
    public function getDirectoryLine(Project $project, Directory $directory)
    {
        $result = [];
        $index = $this->getIndexDirectoryes($project);
        $rootDirectory = $this->getRootDirectory($project);
        if ((!$rootDirectory) || (empty($index[$directory->getId()]))) return $result;

        $result[] = $directory;
        while ($directory = $directory->getParent())
        {
            if (empty($index[$directory->getId()])) break;
            $result[] = $directory;
            if ($directory===$rootDirectory) break;
        }

        return array_reverse($result);
    }

    public function getProjectDirectoryTree(Project $project)
    {
        $tree = [];
        $index = $this->getIndexDirectoryes($project);
        $rootDirectory = $this->getRootDirectory($project);
        if((!$rootDirectory) || (empty($index[$rootDirectory->getId()]))) return $tree;

        $tree[$rootDirectory->getId()] = $this->loadTreeDirectory($rootDirectory,$index);

        return $tree;
    }

    private function loadTreeDirectory(Directory $directory, $indexDirectory)
    {
        $result = ['item'=>$directory, 'childs' => []];
        foreach ($directory->getChildDirectories() as $child)
        {
            if(empty($indexDirectory[$child->getId()])) continue;
            $result['childs'][$child->getId()] = $this->loadTreeDirectory($child,$indexDirectory);
        }
        return $result;
    }

    /**
     * @param Project $project
     * @return Directory
     */
    public function getRootDirectory(Project $project)
    {
        static $cache = [];
        if (isset($cache[$project->getId()])) return $cache[$project->getId()];
        $root = $this->entityManager->getRepository('AstraSharedBundle:ProjectDirectory')->findOneBy(['isRoot'=>true,'project'=>$project]);
        if($root)
        {
            $cache[$project->getId()] = $root->getDirectory();
            return $cache[$project->getId()];
        }

        $projectName = preg_replace ("/[^a-zA-ZА-Яа-я0-9\s]/u","",mb_substr($project->getName(),0,32));
        if(mb_strlen($project->getName())>32)$projectName .= '...';

        $parentDirectory = $this->fileService->getDirectoryByPath('projects');
        $directory_for_loading = $this->fileService->normalizePathName($project->getId().': '.$projectName);

        $dirArray = explode('/',$directory_for_loading);
        $isRoot = true;
        $lastDirectory = null;
        foreach ($dirArray as $dirName)
        {
            $item = $this->fileService->getDirectoryByPath($dirName,$parentDirectory,null,true);
            $this->addDirectoryToProject($item,$project,$isRoot);
            if($isRoot)$lastDirectory = $item;
            $isRoot = false;
            $parentDirectory = $item;
        }

        $this->entityManager->flush();
        $cache[$project->getId()] = $lastDirectory;
        return $cache[$project->getId()];
    }

    public function isDirectoryInProject(Directory $directory, Project $project)
    {
        $index = $this->getIndexDirectoryes($project);
        return isset($index[$directory->getId()]);
    }

    /**
     * @param Project $project
     * @return Directory[]
     */
    private function getIndexDirectoryes(Project $project)
    {
        $index = [];
        foreach ($project->getProjectDirectories() as $projectDirectory)
        {
            $index[$projectDirectory->getDirectory()->getId()] = $projectDirectory->getDirectory();
        }

        return $index;
    }

}