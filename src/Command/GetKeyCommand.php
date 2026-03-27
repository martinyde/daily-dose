<?php

namespace App\Command;

use App\Service\KeyService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpKernel\KernelInterface;

#[AsCommand(
    name: 'app:get-key',
    description: 'Create a unique key used to access the display of your daily files',
)]
class GetKeyCommand extends Command
{
    public function __construct(
        private readonly KernelInterface $kernel,
        private readonly KeyService $keyService,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
          ->addArgument('start_date', InputArgument::REQUIRED, 'Date(Y-m-d) for the first image to be displayed')
          ->addArgument('folder_name', InputArgument::REQUIRED, 'The folder name as configured in config/daily_folders.yaml')
          ->addOption('ignore_weekends', null, InputOption::VALUE_NONE, 'Whether to skip weekends when iterating over images')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $key = $this->keyService->encode(
            $input->getArgument('start_date'),
            $input->getArgument('folder_name'),
            $input->getOption('ignore_weekends'),
        );

        $io->success('Created key:' . $key);
        $io->warning('Store this key somewhere to access your daily dose.');

        $io->success('Check it out here: ' . $this->kernel->getProjectDir() . '/display/daily/' . $key);

        return Command::SUCCESS;
    }
}
