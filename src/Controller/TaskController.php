<?php

namespace App\Controller;

use App\Service\TaskService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class TaskController extends AbstractController
{
    private $taskService;

    public function __construct(TaskService $taskService) {
        $this->taskService = $taskService;
    }

    /**
     * @Route("/", name="homepage")
     */
    public function index(): Response {
        $tasks = $this->taskService->getAllTasksGrouped();
        $totalDuration = $this->taskService->getTotalDuration();
        $formattedTotalDuration = $this->formatDuration($totalDuration);

        return $this->render('task/task_manager.html.twig', [
            'tasks' => $tasks,
            'totalDuration' => $formattedTotalDuration,
        ]);
    }

    /**
     * @Route("/task/start", name="task_start", methods={"POST"})
     */
    public function startTask(Request $request): JsonResponse {
        $data = json_decode($request->getContent(), true);
        $taskName = $data['name'] ?? '';

        if (empty($taskName)) {
            return $this->json(['error' => 'Task name is required'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $task = $this->taskService->startTask($taskName);

        return $this->json(['id' => $task->getId()]);
    }

    /**
     * @Route("/task/stop/{id}", name="task_stop", methods={"POST"})
     */
    public function stopTask($id): JsonResponse {
        try {
            $updatedTask = $this->taskService->stopTask($id);

            return $this->json([
                'task' => [
                    'name' => $updatedTask->getName(),
                    'duration' => $updatedTask->getDuration()
                ]
            ]);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], JsonResponse::HTTP_NOT_FOUND);
        }
    }

    private function formatDuration($seconds) {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $seconds = $seconds % 60;

        return sprintf("%dh %dm %ds", $hours, $minutes, $seconds);
    }
}
