<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskCreateType;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{

    public function __construct(private EntityManagerInterface $entityManager)
    {

    }

    #[Route('/tasks', name: 'task_list')]
    public function listTask(TaskRepository $taskRepository): Response
    {
        return $this->render('task/list.html.twig', ['tasks' => $taskRepository->findAll()]);
    }

    #[Route('/tasks_create', name: 'task_create')]
    public function createTask(Request $request)
    {
        $task = new Task();
        $form = $this->createForm(TaskCreateType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $task->setUser($this->getUser());
            $task->setCreatedAt(new \DateTime('now'));
            $task->setIsDone(false);

            $this->entityManager->persist($task);
            $this->entityManager->flush();
            $this->addFlash('success', 'La tâche a été bien été ajoutée..');
            return $this->redirectToRoute('task_list');

        }
        return $this->render('task/create.html.twig', ['form' => $form->createView()]);
    }


    #[Route('/tasks/{id}/edit', name: 'task_edit')]
    public function editAction(Task $task, Request $request)
    {
        $form = $this->createForm(TaskCreateType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'La tâche a bien été modifiée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/edit.html.twig', [
            'form' => $form->createView(),
            'task' => $task,
        ]);
    }


    #[Route('/tasks/{id}/toggle', name: 'task_toggle')]
    public function toggleTaskAction(Task $task)
    {
        $task->setIsDone(!$task->getIsDone());
        $this->entityManager->flush($task);
        if($task->getIsDone() == true) {
            $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle()));
        }else{
            $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme non faite.', $task->getTitle()));

        }
        return $this->redirectToRoute('task_list');
    }


    #[Route("/tasks/{id}/delete", name: 'task_delete')]
    public function deleteTaskAction(Task $task)
    {

        if ($this->getUser() === $task->getUser() || $this->getUser()->getRoles()[0] == 'ROLE_ADMIN') {
            $this->entityManager->remove($task);
            $this->entityManager->flush();

            $this->addFlash('success', 'La tâche a bien été supprimée.');
        }else{
            $this->addFlash('error', 'Vous ne pouver pas supprimée la tâche !');

        }

        return $this->redirectToRoute('task_list');
    }
}
