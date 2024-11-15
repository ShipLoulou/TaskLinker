<?php

namespace App\Controller;

use App\Form\ProjectType;
use App\Repository\ProjectRepository;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class ProjectController extends AbstractController
{
    protected $twig;
    protected $projectRepository;
    protected $taskRepository;
    protected $em;

    public function __construct(Environment $twig, ProjectRepository $projectRepository, TaskRepository $taskRepository, EntityManagerInterface $em)
    {
        $this->twig = $twig;
        $this->projectRepository = $projectRepository;
        $this->taskRepository = $taskRepository;
        $this->em = $em;
    }

    #[Route('/projets', name: 'projects')]
    public function showProjects()
    {
        $projects = $this->projectRepository->findAll();

        return $this->render('projects.html.twig', [
            'projects' => $projects
        ]);
    }

    #[Route('/projet/{id}', name: 'project', priority: -1)]
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

        $listEmployee = [];

        foreach ($project->getEmployee() as $employee) {
            $listEmployee[] = $employee;
        }

        return $this->render('project.html.twig', [
            'project' => $project,
            'taskPerStatus' => $taskPerStatus,
            'listEmployee' => $listEmployee
        ]);
    }

    #[Route('/projet/{id}/edit', name: 'edit_project')]
    public function editProject($id, Request $request)
    {
        $project = $this->projectRepository->find($id);

        if (!$project) {
            throw $this->createNotFoundException("Le projet demandé n'existe pas.");
        }

        $form = $this->createForm(ProjectType::class, $project);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();

            return $this->redirectToRoute('projects');
        }

        return $this->render('project-edit.html.twig', [
            'project' => $project,
            'formView' => $form->createView()
        ]);
    }

    #[Route('/projet/add', name: 'add_project')]
    public function addProject(Request $request)
    {
        $form = $this->createForm(ProjectType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $project = $form->getData();
            $project->setStartDate(new \DateTime());
            $this->em->persist($project);
            $this->em->flush();
            return $this->redirectToRoute('projects');
        }

        return $this->render('project-add.html.twig', [
            'formView' => $form->createView()
        ]);
    }
}
