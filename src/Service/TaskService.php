<?php

namespace App\Service;

use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;

class TaskService {
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }

    public function createTask(string $name, \DateTime $start, \DateTime $end) {
        $task = new Task();
        $task->setName($name);
        $task->setStartTime($start);
        $task->setEndTime($end);
        $duration = $start->diff($end)->s;
        $task->setDuration($duration);

        $this->entityManager->persist($task);
        $this->entityManager->flush();

        return $task;
    }

    public function getAllTasks() {
        return $this->entityManager->getRepository(Task::class)->findAll();
    }

    public function getTotalDuration() {
        $tasks = $this->getAllTasks();
        $totalDuration = 0;
        foreach ($tasks as $task) {
            $totalDuration += $task->getDuration();
        }
        return $totalDuration;
    }

    public function getAllTasksGrouped() {
        $tasks = $this->entityManager->getRepository(Task::class)->findAll();
        $groupedTasks = [];
        foreach ($tasks as $task) {
            $key = strtolower($task->getName());
            if (!isset($groupedTasks[$key])) {
                $groupedTasks[$key] = [
                    'name' => $task->getName(),
                    'duration' => 0
                ];
            }
            $groupedTasks[$key]['duration'] += $task->getDuration();
        }


        foreach ($groupedTasks as $key => $groupedTask) {
            $groupedTasks[$key]['duration'] = $this->formatDuration($groupedTask['duration']);
        }

        return array_values($groupedTasks);
    }

    public function startTask(string $name) {
        $task = new Task();
        $task->setName($name);
        $task->setStartTime(new \DateTime());

        $this->entityManager->persist($task);
        $this->entityManager->flush();

        return $task;
    }

    public function stopTask($taskId) {
        $task = $this->entityManager->getRepository(Task::class)->find($taskId);

        if (!$task) {
            throw new \Exception("Task not found");
        }

        $task->setEndTime(new \DateTime());
        $duration = $task->getStartTime()->diff($task->getEndTime());
        $totalSeconds = $duration->h * 3600 + $duration->i * 60 + $duration->s;
        $task->setDuration($totalSeconds);

        $this->entityManager->flush();

        return $task;
    }


    public function formatDuration($seconds) {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $seconds = $seconds % 60;

        return sprintf("%dh %dm %ds", $hours, $minutes, $seconds);
    }

}

