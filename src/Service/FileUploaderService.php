<?php

namespace App\Service;


use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploaderService
{
    public function __construct(
        private readonly string $targetDirectory,
        private readonly SluggerInterface $slugger,
        private LoggerInterface $logger
    ){}

    public function upload(UploadedFile $file): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $newFilename = $safeFilename.'-'.uniqid('img', false).'.'.$file->guessExtension();

        try {
            $file->move($this->getTargetDirectory(), $newFilename);
        }catch (FileException $e){
            $this->logger->error('Téléchargement non éffectuer', [
                'date' => new \DateTime('now', 'Europe/Paris'),
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
                ]);
            throw new HttpException(Response::HTTP_NOT_FOUND, $e->getMessage(), $e);
        }catch (\Exception $ex){
            $this->logger->error('Téléchargement non éffectuer', [
                'date' => new \DateTime('now', 'Europe/Paris'),
                'message' => $ex->getMessage(),
                'code' => $ex->getCode(),
                'file' => $ex->getFile(),
                'line' => $ex->getLine()
            ]);
            throw new HttpException(Response::HTTP_INTERNAL_SERVER_ERROR, 'Une erreur est survenu lors du téléchargement');
        }

        return $newFilename;

    }

    public function getTargetDirectory(): string
    {
        return $this->targetDirectory;
    }
}