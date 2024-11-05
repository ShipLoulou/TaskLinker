<?php

namespace App\Controller;

use App\Repository\EmployeeRepository;
use Twig\Environment;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TeamController extends AbstractController
{
    protected $twig;
    protected $employeeRepository;

    public function __construct(Environment $twig, EmployeeRepository $employeeRepository)
    {
        $this->twig = $twig;
        $this->employeeRepository = $employeeRepository;
    }

    #[Route('/equipe', name: 'equipe')]
    public function showTeam()
    {
        $employees = $this->employeeRepository->findAll();

        return $this->render('team.html.twig', [
            'employees' => $employees
        ]);
    }
}
