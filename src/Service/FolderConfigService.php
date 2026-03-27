<?php

namespace App\Service;

use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Yaml\Yaml;

class FolderConfigService
{
    private array $folders;

    public function __construct(
        private readonly KernelInterface $kernel,
    ) {
        $configPath = $this->kernel->getProjectDir() . '/config/daily_folders.yaml';
        $this->folders = Yaml::parseFile($configPath);
    }

    public function getFolderNames(): array
    {
        return array_keys($this->folders);
    }

    public function getConfig(string $folderName): array
    {
        if (!isset($this->folders[$folderName])) {
            throw new \InvalidArgumentException(sprintf('Folder "%s" is not configured in daily_folders.yaml', $folderName));
        }

        $config = $this->folders[$folderName];

        return [
            'prefix' => $config['prefix'] ?? '',
            'fileType' => $config['filetype'] ?? 'jpg',
            'digits' => $config['digits'] ?? 4,
            'startZero' => $config['start_zero'] ?? false,
        ];
    }
}
