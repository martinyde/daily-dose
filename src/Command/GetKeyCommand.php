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
          ->addArgument('start_date', InputArgument::OPTIONAL, 'Date(Y-m-d) for the first image to be displayed')
          ->addArgument('folder_name', InputArgument::OPTIONAL, 'The folder within daily-files folder where files for this key are stored')
          ->addOption('prefix', null, InputOption::VALUE_OPTIONAL, 'A prefix before the incrementing number of the file to show (If the files are named ch_0001.jpg the prefix would be "ch_"', '')
          ->addOption('filetype', null, InputOption::VALUE_OPTIONAL, 'The file type without "." if none is given jpg is assumed', 'jpg')
          ->addOption('digits', null, InputOption::VALUE_OPTIONAL, 'The number of digits to use in the file names (The sequential numbering of the files after prefix, i.e 01, 0001 or 000001), defaults to 4', 4)
          ->addOption('ignore_weekends', null, InputOption::VALUE_OPTIONAL, 'Whether to count weekends when iterating over images.', FALSE)
          ->addOption('start_zero', null, InputOption::VALUE_OPTIONAL, 'Whether the first image is named 0, if omitted 1 is assumed as first image name.', FALSE)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $key = $this->keyService->encode(
            $input->getArgument('start_date'),
            $input->getArgument('folder_name'),
            $input->getOption('prefix'),
            $input->getOption('filetype'),
            (int) $input->getOption('digits'),
            (bool) $input->getOption('ignore_weekends'),
            (bool) $input->getOption('start_zero'),
        );

        $io->success('Created key:' . $key);
        $io->warning('Store this key somewhere to access your daily dose.');

        $io->success('Check it out here: ' . $this->kernel->getProjectDir() . '/display/daily/' . $key);

        return Command::SUCCESS;
    }
}
