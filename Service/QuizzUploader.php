<?php

namespace Aldaflux\GameQuizzBundle\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;



class QuizzUploader
{
    private $soundDirectory;
    private $videoDirectory;
    private $publicDirectory;
    private $parameters;

    
//    public function __construct(ContainerInterface $container)
    public function __construct(ParameterBagInterface $parameters)
    {
        $this->soundDirectory=$parameters->get("aldaflux_game_quizz.folder_audio");
        $this->videoDirectory=$parameters->get("aldaflux_game_quizz.folder_video");
        $this->publicDirectory=$parameters->get("aldaflux_game_quizz.folder_public");
     
    }

    public function uploadSound(UploadedFile $file, $relativePath,$fileName)
    {
         
        $extension = strtolower(pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION));
        $fileName=$fileName.".".$extension;
        $folder=$this->getSoundDirectory()."/".$relativePath;
        try 
        {
            $file->move($this->getSoundDirectoryFullPath().$relativePath, $fileName);
        } 
        catch (FileException $e) 
        {
          
        }
        return $this->getSoundDirectory()."/".$relativePath.$fileName;
    }
    
    public function uploadVideo(UploadedFile $file, $relativePath,$fileName)
    {
         
        $extension = strtolower(pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION));
        $fileName=$fileName.".".$extension;
        $folder=$this->getVideoDirectory()."/".$relativePath;
        try 
        {
            $file->move($this->getVideoDirectoryFullPath().$relativePath, $fileName);
        } 
        catch (FileException $e) 
        {
          
        }
        return $this->getVideoDirectory()."/".$relativePath.$fileName;
    }
    
    

    
    public function getPublicDirectory()
    {
        return $this->publicDirectory;
    }
    
    public function getSoundDirectoryFullPath()
    {
        return $this->getPublicDirectory()."/".$this->getSoundDirectory()."/";
    }
    
    public function getSoundDirectory()
    {
        return $this->soundDirectory;
    }

    public function getVideoDirectoryFullPath()
    {
        return $this->getPublicDirectory()."/".$this->getVideoDirectory()."/";
    }

    public function getVideoDirectory()
    {
        return $this->videoDirectory;
    }
}