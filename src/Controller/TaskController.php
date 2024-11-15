<?php

namespace App\Controller;

use App\Form\TaskType;
use App\Repository\ProjectRepository;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{
    protected $taskRepository;
    protected $projectRepository;
    protected $em;

    public function __construct(TaskRepository $taskRepository, ProjectRepository $projectRepository, EntityManagerInterface $em)
    {
        $this->taskRepository = $taskRepository;
        $this->projectRepository = $projectRepository;
        $this->em = $em;
    }

    #[Route('projet/{id_project}/tache/{id}', name: 'edit_task', priority: -1)]
    public function editTask($id, Request $request)
    {

        $task = $this->taskRepository->find($id);

        if (!$task) {
            throw $this->createNotFoundException("La tâche demandé n'existe pas.");
        }

        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();

            return $this->redirectToRoute('projects');
        }

        return $this->render('task-edit.html.twig', [
            'task' => $task,
            'formView' => $form->createView()
        ]);
    }

    #[Route('projet/{id_project}/tache/create', name: 'add_task')]
    public function addTask(Request $request, $id_project)
    {
        $project = $this->projectRepository->find($id_project);

        $form = $this->createForm(TaskType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();
            $task->setProject($project);
            $this->em->persist($task);
            $this->em->flush();
            return $this->redirectToRoute('projects');
        }

        return $this->render('task-add.html.twig', [
            'formView' => $form->createView()
        ]);
    }
}
