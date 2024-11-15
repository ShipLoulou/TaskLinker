<?php

namespace App\Controller;

use Twig\Environment;
use App\Form\EmployeeType;
use App\Repository\SlotRepository;
use App\Repository\TaskRepository;
use App\Repository\EmployeeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TeamController extends AbstractController
{
    protected $twig;
    protected $employeeRepository;
    protected $slotRepository;
    protected $taskRepository;
    protected $em;

    public function __construct(Environment $twig, EmployeeRepository $employeeRepository, EntityManagerInterface $em, TaskRepository $taskRepository, SlotRepository $slotRepository)
    {
        $this->twig = $twig;
        $this->employeeRepository = $employeeRepository;
        $this->taskRepository = $taskRepository;
        $this->slotRepository = $slotRepository;
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

        $tasks = $this->taskRepository->findBy(['employee' => $id]);
        $slots = $this->slotRepository->findBy(['employee' => $id]);

        if (!$employee) {
            throw $this->createNotFoundException("L'employé demandé n'existe pas.");
        }

        $this->em->remove($employee);

        if ($tasks) {
            foreach ($tasks as $task) {
                $task->setEmployee(null);
                // $this->em->remove($task->getEmployee());
            }
        }

        if ($slots) {
            foreach ($slots as $slot) {
                $slot->setEmployee(null);
            }
        }

        $this->em->flush();

        return $this->redirectToRoute('equipe');
    }
}
