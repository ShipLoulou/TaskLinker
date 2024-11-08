<?php

namespace App\Controller;

use App\Repository\ProjectRepository;
use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class ProjectController extends AbstractController
{
    protected $twig;
    protected $projectRepository;
    protected $taskRepository;

    public function __construct(Environment $twig, ProjectRepository $projectRepository, TaskRepository $taskRepository)
    {
        $this->twig = $twig;
        $this->projectRepository = $projectRepository;
        $this->taskRepository = $taskRepository;
    }

    #[Route('/projets', name: 'projects')]
    public function showProjects()
    {
        $projects = $this->projectRepository->findAll();

        return $this->render('projects.html.twig', [
            'projects' => $projects
        ]);
    }

    #[Route('/projet/{id}', name: 'project')]
    public function showProject($id)
    {
        $project = $this->projectRepository->find($id);

        $tasks = $this->taskRepository->findBy([
            'project' => $project->getId()
        ], ['status' => 'DESC']);

        $taskPerStatus = [];

        foreach ($tasks as $task) {
            $taskPerStatus[$task->getStatus()->getLibelle()][] = $task;
        }

        // dd($taskPerStatus);

        return $this->render('project.html.twig', [
            'project' => $project,
            'taskPerStatus' => $taskPerStatus
        ]);
    }
}
