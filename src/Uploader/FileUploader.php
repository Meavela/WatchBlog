<?php


namespace App\Uploader;


use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    private $targetDirectory;
    private $logger;

    public function __construct($targetDirectory,LoggerInterface $logger)
    {
        $this->targetDirectory = $targetDirectory;
        $this->logger = $logger;
    }

    public function upload(UploadedFile $file)
    {
        $this->logger->debug("uploading file");
        $fileName = md5(uniqid()).'.'.$file->guessExtension();

        $file->move($this->targetDirectory, $fileName);
        $this->logger->debug("file uploaded");
        return $fileName;
    }
}