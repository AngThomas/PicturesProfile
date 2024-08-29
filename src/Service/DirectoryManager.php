<?php

namespace App\Service;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

class DirectoryManager
{
    private string $baseDirectory;
    private Filesystem $filesystem;

    public function __construct(
        Filesystem $filesystem
    )
    {
        $this->filesystem = $filesystem;
    }
    public function directoryExists(string $directoryPath): bool
    {
        return $this->filesystem->exists($directoryPath);
    }

    /**

     * @param string $directoryPath
     * @return void
     * @throws IOExceptionInterface
     */
    public function createDirectory(string $directoryPath): void
    {
        $this->filesystem->mkdir($directoryPath, 0700);
    }

     /**
     * @param string $directoryPath
     * @return string
     * @throws IOExceptionInterface
     */
    public function ensureDirectoryExists(string $directoryPath): string
    {
        if (!$this->directoryExists($directoryPath)) {
            $this->createDirectory($directoryPath);
        }

        return $directoryPath;
    }
}
