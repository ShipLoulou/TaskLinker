<?php

namespace App\Controller;

use App\Form\EmployeeType;
use App\Repository\EmployeeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Twig\Environment;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TeamController extends AbstractController
{
    protected $twig;
    protected $employeeRepository;
    protected $em;

    public function __construct(Environment $twig, EmployeeRepository $employeeRepository, EntityManagerInterface $em)
    {
        $this->twig = $twig;
        $this->employeeRepository = $employeeRepository;
        $this->em = $em;
    }

    #[Route('/equipe', name: 'equipe')]
    public function showTeam()
    {
        $employees = $this->employeeRepository->findAll();

        return $this->render('team.html.twig', [
            'employees' => $employees
        ]);
    }

    #[Route('/equipe/edition/{id}', name: 'edit_employee')]
    public function createEmployee($id, Request $request)
    {
        $employee = $this->employeeRepository->find($id);

        if (!$employee) {
            throw $this->createNotFoundException("L'employé demandé n'existe pas.");
        }

        $form = $this->createForm(EmployeeType::class, $employee);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();

            return $this->redirectToRoute('equipe');
        }

        return $this->render('employee-edit.html.twig', [
            'employee' => $employee,
            'formView' => $form->createView()
        ]);
    }

    #[Route('/equipe/delete/{id}', name: 'delete_employee')]
    public function deleteEmployee($id)
    {
        $employee = $this->employeeRepository->find($id);

        if (!$employee) {
            throw $this->createNotFoundException("L'employé demandé n'existe pas.");
        }

        $this->em->remove($employee);
        $this->em->flush();

        return $this->redirectToRoute('equipe');
    }
}
