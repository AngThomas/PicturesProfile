<?php

namespace App\Service\File;

class FileNameGenerator
{
    public function createFileName(string $fileName, string $extension): string
    {
        $originalFilename = pathinfo($fileName, PATHINFO_FILENAME);
        $safeFilename = $this->generateUniqueFileName($originalFilename);

        return $safeFilename.'.'.$extension;
    }

    private function generateUniqueFileName(string $originalFileName): string
    {
        return $originalFileName.uniqid();
    }
}
