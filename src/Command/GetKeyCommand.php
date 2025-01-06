<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

#[AsCommand(
    name: 'app:get-key',
    description: 'Add a short description for your command',
)]
class GetKeyCommand extends Command
{
    public function __construct(
      private ContainerBagInterface $params,
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
          ->addArgument('start_date', InputArgument::OPTIONAL, 'Day for the first image to be displayed')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $inputKey  = $this->params->get('display_key');
        $startDate = $input->getArgument('start_date');

        $key = base64_encode($inputKey.'|'.$startDate);

        $io->success('Created key:' . $key);

        return Command::SUCCESS;
    }
}
