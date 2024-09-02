<?php

namespace App\Service\File;

use App\Exception\ValidationException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

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
     *
     * @throws ValidationException
     * @throws \Exception
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
     * @return array<string, string>
     *
     * @throws \Exception
     */
    public function uploadFile(UploadedFile $file, string $destination): array
    {
        if (null === $file->guessExtension()) {
            throw new \Exception('Cannot determine file extension.');
        }
        $this->fileValidator->validate($file);
        $uniqueFileName = $this->fileNameGenerator->createFileName($file->getClientOriginalName(), $file->guessExtension());

        $file->move($destination, $uniqueFileName);

        return [
            'name' => $uniqueFileName,
            'path' => $destination.'/'.$uniqueFileName,
        ];
    }
}
