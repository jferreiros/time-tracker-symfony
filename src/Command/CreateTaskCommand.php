<?php

namespace App\Command;

use App\Service\TaskService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateTaskCommand extends Command {
    protected static $defaultName = 'app:create-task';
    private $taskService;

    public function __construct(TaskService $taskService) {
        parent::__construct();
        $this->taskService = $taskService;
    }

    protected function configure() {
        $this
            ->setName('app:create-task')
            ->setDescription('Creates a new task.')
            ->addArgument('name', InputArgument::REQUIRED, 'The name of the task')
            ->addArgument('start', InputArgument::REQUIRED, 'Start time of the task')
            ->addArgument('end', InputArgument::REQUIRED, 'End time of the task');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int {
        $taskName = $input->getArgument('name');
        $start = new \DateTime($input->getArgument('start'));
        $end = new \DateTime($input->getArgument('end'));

        try {
            $this->taskService->createTask($taskName, $start, $end);
            $output->writeln('Task created successfully.');
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $output->writeln('Error: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }

}

