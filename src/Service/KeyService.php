<?php

namespace App\Service;

class KeyService
{
    public function __construct(
        private readonly FolderConfigService $folderConfigService,
    ) {
    }

    public function encode(
        string $startDate,
        string $folderName,
        bool $ignoreWeekends,
    ): string {
        $config = $this->folderConfigService->getConfig($folderName);

        foreach ([$startDate, $folderName, $config['prefix'], $config['fileType']] as $value) {
            if (str_contains($value, '|')) {
                throw new \InvalidArgumentException('Arguments and options cannot contain "|"');
            }
        }

        return base64_encode(
            $startDate . '|' . $folderName . '|' . $config['prefix'] . '|' . $config['fileType'] . '|' . $config['digits'] . '|' . ($ignoreWeekends ? '1' : '') . '|' . ($config['startZero'] ? '1' : '')
        );
    }

    public function decode(string $key): array
    {
        $decoded = base64_decode($key);
        $parts = explode('|', $decoded);

        return [
            'startDate' => $parts[0],
            'folderName' => $parts[1],
            'prefix' => $parts[2],
            'fileType' => $parts[3],
            'digits' => (int) $parts[4],
            'ignoreWeekends' => (bool) $parts[5],
            'startZero' => (bool) $parts[6],
        ];
    }
}
