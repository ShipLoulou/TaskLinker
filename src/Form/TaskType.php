<?php

namespace App\Form;

use App\Entity\Employee;
use App\Entity\Project;
use App\Entity\Status;
use App\Entity\Tag;
use App\Entity\Task;
use App\Repository\StatusRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaskType extends AbstractType
{
    protected $statusRepository;
    protected $requestStack;

    public function __construct(StatusRepository $statusRepository, RequestStack $requestStack)
    {
        $this->statusRepository = $statusRepository;
        $this->requestStack = $requestStack;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $request = $this->requestStack->getCurrentRequest();
        $projectId = $request->get('id_project');

        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre de la tâche',
                'attr' => [
                    'maxlength' => '255'
                ],
                'required' => false
            ])
            ->add('description', TextType::class, [
                'label' => 'Description',
                'attr' => [
                    'maxlength' => '255'
                ],
                'required' => false
            ])
            ->add('deadline', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date',
                'attr' => [
                    'maxlength' => '255'
                ],
                'required' => false
            ])
            ->add('employee', EntityType::class, [
                'class' => Employee::class,
                'choice_label' => 'firstName',
                'placeholder' => '-- associer un employer --',
                'required' => false
            ])
            ->add('status', EntityType::class, [
                'class' => Status::class,
                'choice_label' => 'libelle',
                'query_builder' => function (StatusRepository $sr) use ($projectId) {
                    return $sr->createQueryBuilder('s')
                        ->where('s.project = :project')
                        ->setParameter('project', $projectId);
                },
                'placeholder' => '-- ajouter un status --',
                'required' => false
            ])
            // ->add('project', EntityType::class, [
            //     'class' => Project::class,
            //     'choice_label' => 'id',
            // ])
            // ->add('tag', EntityType::class, [
            //     'class' => Tag::class,
            //     'choice_label' => 'id',
            //     'multiple' => true,
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);
    }
}