<?php

namespace App\Form;

use App\Entity\Employee;
use App\Entity\Project;
use App\Repository\EmployeeRepository;
use Doctrine\Common\Collections\Collection;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Titre du projet',
                'required' => false
            ])
            // ->add('start_date', null, [
            //     'widget' => 'single_text',
            // ])
            // ->add('deadline', null, [
            //     'widget' => 'single_text',
            // ])
            // ->add('archive')
            ->add('employee', EntityType::class, [
                'label' => 'Inviter des membres',
                'class' => Employee::class,
                'multiple' => true,
                'choice_label' => fn(Employee $employee) => $employee->getFirstName() . ' ' . $employee->getLastName(),
                'by_reference' => false,
                'attr' => [
                    'class' => 'select-employee'
                ],
                'required' => false
                // 'query_builder' => function (EmployeeRepository $er) use ($idTest) {
                //     return $er->createQueryBuilder('e')
                //         ->where('e.project = :project')
                //         ->setParameter('project', $idTest);
                // },
                // 'choice_attr' => function ($choice) use ($options) {
                //     if (
                //         $options['data']->getEmployee() instanceof Collection
                //         && $choice instanceof Employee
                //     ) {
                //         if ($options['data']->getEmployee()->contains($choice)) {
                //             return ['selected' => 'selected'];
                //         }
                //     }
                //     return [];
                // }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}
