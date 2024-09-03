<?php

namespace App\Service\File;

use App\Exception\ValidationException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;

class FileUploader
{
    private FileValidator $fileValidator;
    private FileNameGenerator $fileNameGenerator;

    public function __construct(
        FileValidator $fileValidator,
        FileNameGenerator $fileNameGenerator,
    ) {
        $this->fileValidator = $fileValidator;
        $this->fileNameGenerator = $fileNameGenerator;
    }

    /**
     * @param UploadedFile[] $files
     *
     * @return File[]
     *
     * @throws \InvalidArgumentException
     */
    public function uploadFiles(array $files, string $targetDir): array
    {
        $filePaths = [];

        foreach ($files as $file) {
            $filePaths[] = $this->uploadFile($file, $targetDir);
        }

        return $filePaths;
    }

    /**
     * @return File[]
     *
     * @throws \InvalidArgumentException
     * @throws ValidationException
     *
     * @throwsValidationException
     */
    public function uploadFile(UploadedFile $file, string $destination): File
    {
        if (null === $file->guessExtension()) {
            throw new \InvalidArgumentException('Cannot determine file extension.', Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $this->fileValidator->validate($file);
        $uniqueFileName = $this->fileNameGenerator->createFileName($file->getClientOriginalName(), $file->guessExtension());

        return $file->move($destination, $uniqueFileName);
    }
}
