<?php

namespace App\Command;

use App\Service\TaskService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ListTasksCommand extends Command {
    protected static $defaultName = 'app:list-tasks';
    private $taskService;

    public function __construct(TaskService $taskService) {
        parent::__construct();
        $this->taskService = $taskService;
    }

    protected function configure() {
        $this
            ->setName('app:list-tasks')
            ->setDescription('Lists all tasks.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int {
        try {
            $output->writeln("<info>Tasks done this day:</info>");

            $tasks = $this->taskService->getAllTasks();
            foreach ($tasks as $task) {
                $formattedDuration = $this->taskService->formatDuration($task->getDuration());
                $output->writeln("- {$task->getName()} ---> {$formattedDuration}");
            }

            $totalDuration = $this->taskService->getTotalDuration();
            $formattedTotalDuration = $this->taskService->formatDuration($totalDuration);

            $output->writeln("+---------------------------+");
            $output->writeln("| Total Duration: {$formattedTotalDuration} |");
            $output->writeln("+---------------------------+");

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $output->writeln("<error>Error: {$e->getMessage()}</error>");
            return Command::FAILURE;
        }
    }


}
