<?php

namespace App\Controller;

use App\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class ProjectController extends AbstractController
{
    protected $twig;
    protected $projectRepository;

    public function __construct(Environment $twig, ProjectRepository $projectRepository)
    {
        $this->twig = $twig;
        $this->projectRepository = $projectRepository;
    }

    #[Route('/projet', name: 'projet')]
    public function project()
    {
        $projects = $this->projectRepository->findAll();

        return $this->render('project.html.twig', [
            'projects' => $projects
        ]);
    }
}
