<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TaskController extends AbstractController
{
    /**
     * Display the list of tasks
     *
     * @param TaskRepository $taskRepository
     * @param TagAwareCacheInterface $cache
     * @return void
     */
    #[Route('/tasks', name: 'task_list')]
    public function listAction(TaskRepository $taskRepository, TagAwareCacheInterface $cache)
    {
        $tasks = $cache->get('tasks', function (ItemInterface $item) use ($taskRepository) {
            $item->tag("tasks");
            return $taskRepository->findAll();
        });


        return $this->render('task/list.html.twig', ['tasks' => $tasks]);
    }

    /**
     * Create a new task
     *
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param TagAwareCacheInterface $cache
     * @return void
     */
    #[Route('/tasks/create', name: 'task_create')]
    public function createAction(Request $request, EntityManagerInterface $em, TagAwareCacheInterface $cache)
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task->setUser($this->getUser());
            $em->persist($task);
            $em->flush();

            $this->addFlash('success', 'La tâche a été bien été ajoutée.');

            $cache->invalidateTags(["tasks"]);

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * Edit a task
     * 
     * @param Task $task
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param TagAwareCacheInterface $cache
     * @return void
     */
    #[Route('/tasks/{id}/edit', name: 'task_edit')]
    public function editAction(Task $task, Request $request, EntityManagerInterface $em, TagAwareCacheInterface $cache)
    {
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'La tâche a bien été modifiée.');

            $cache->invalidateTags(["tasks"]);

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/edit.html.twig', [
            'form' => $form->createView(),
            'task' => $task,
        ]);
    }

    /**
     * Toggle a task
     * 
     * @param Task $task
     * @param EntityManagerInterface $em
     * @param TagAwareCacheInterface $cache
     * @return void
     */
    #[Route('/tasks/{id}/toggle', name: 'task_toggle')]
    public function toggleTaskAction(Task $task, EntityManagerInterface $em, TagAwareCacheInterface $cache)
    {
        $task->toggle(!$task->isDone());
        $em->flush();

        $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle()));

        $cache->invalidateTags(["tasks"]);

        return $this->redirectToRoute('task_list');
    }

    /**
     * Delete a task
     * 
     * @param Task $task
     * @param EntityManagerInterface $em
     * @param TagAwareCacheInterface $cache
     * @return void
     */
    #[Route('/tasks/{id}/delete', name: 'task_delete')]
    public function deleteTaskAction(Task $task, EntityManagerInterface $em, TagAwareCacheInterface $cache)
    {
        if( $task->getUser() !== $this->getUser() && !$this->isGranted('ROLE_ADMIN') ) {
            $this->addFlash('error', 'Vous n\'avez pas le droit de supprimer cette tâche.');
            return $this->redirectToRoute('task_list');
        }

        $em->remove($task);
        $em->flush();

        $this->addFlash('success', 'La tâche a bien été supprimée.');

        $cache->invalidateTags(["tasks"]);

        return $this->redirectToRoute('task_list');
    }
}
