<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Service\TaskService; // Dodajemy nowy serwis
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * TaskController jest odpowiedzialny za zarządzanie żądaniami HTTP związanymi z zadaniami.
 */
class TaskController extends AbstractController
{
    private TaskService $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService; // Inicjalizujemy serwis w konstruktorze
    }

    /**
     * Wyświetla listę wszystkich zadań.
     *
     * @return Response - Renderowany widok listy zadań.
     */
    #[Route('/tasks', name: 'task_index')]
    public function index(): Response
    {
        // Pobieramy wszystkie zadania z serwisu
        $tasks = $this->taskService->getAllTasks();

        // Renderujemy widok listy zadań
        return $this->render('task/index.html.twig', [
            'tasks' => $tasks,
        ]);
    }

    /**
     * Obsługuje tworzenie nowego zadania.
     * 
     * @param Request $request - Obiekt żądania HTTP.
     * @return Response - Renderowany widok formularza lub przekierowanie do listy zadań.
     */
    #[Route('/tasks/new', name: 'task_new')]
    public function new(Request $request): Response
    {
        // Tworzymy nowy obiekt Task
        $task = new Task();
        // Tworzymy formularz dla zadania
        $form = $this->createForm(TaskType::class, $task);
    
        // Obsługujemy wysłanie formularza
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Zapisujemy zadanie do bazy danych przez serwis
            $this->taskService->saveTask($task);

            // Przekierowanie do listy zadań
            return $this->redirectToRoute('task_index');
        }
    
        // Renderujemy widok formularza
        return $this->render('task/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
