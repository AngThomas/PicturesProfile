<?php

namespace App\Service;

use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;

class DirectoryManager
{
    private Filesystem $filesystem;

    public function __construct(
        Filesystem $filesystem,
    ) {
        $this->filesystem = $filesystem;
    }

    public function directoryExists(string $directoryPath): bool
    {
        return $this->filesystem->exists($directoryPath);
    }

    /**
     * @throws IOExceptionInterface
     */
    public function createDirectory(string $directoryPath): void
    {
        $this->filesystem->mkdir($directoryPath, 0700);
    }

    /**
     * @throws IOExceptionInterface
     */
    public function ensureDirectoryExists(string $directoryPath): void
    {
        if (!$this->directoryExists($directoryPath)) {
            $this->createDirectory($directoryPath);
        }
    }
}
