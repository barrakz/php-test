<?php

namespace App\Service;

use App\Entity\Task;
use Doctrine\Persistence\ManagerRegistry;

/**
 * TaskService jest odpowiedzialny za zarządzanie logiką związaną z zadaniami.
 */
class TaskService
{
    private ManagerRegistry $doctrine;

    /**
     * Konstruktor, który wstrzykuje zależność do ManagerRegistry.
     *
     * @param ManagerRegistry $doctrine - Służy do interakcji z bazą danych.
     */
    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * Pobiera wszystkie zadania z bazy danych.
     *
     * @return Task[] - Lista zadań.
     */
    public function getAllTasks(): array
    {
        return $this->doctrine->getRepository(Task::class)->findAll();
    }

    /**
     * Zapisuje zadanie do bazy danych.
     *
     * @param Task $task - Obiekt zadania do zapisania.
     */
    public function saveTask(Task $task): void
    {
        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($task);
        $entityManager->flush();
    }
}
