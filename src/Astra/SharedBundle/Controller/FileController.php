<?php

namespace Astra\SharedBundle\Controller;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

class FileController extends BaseController
{
    public function UploadFileAction(Request $request)
    {
        $public_files_directory = $this->getParameter('public_files_directory');
        $fileService = $this->get('astra.file.service');
        $isPublic = !empty($request->get('public',0));
        $directory = $request->get('directory','');
        $directoryId = $request->get('directoryId',null);

        $result =
            [
                'success'=>false,
                'message'=>$this->trans('dropzone.defaultError'),
                'filename'=>'',
                'url'=>'',
                'type'=>'',
                'id'=>0,
            ];

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
        $dir = null;
        if ($directoryId)
        {
            $dir = $this->getEm()->getRepository('AstraSharedBundle:Directory')->find($directoryId);
        }
        $file = $fileService->saveUploadetFile($fileUpload,$this->getUser(),$directory,$dir);


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
                'id'=>$file->getId(),
            ];

        return $this->json($result);
    }
}
