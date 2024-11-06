<?php

namespace App\Form;

use App\Entity\Employee;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmployeeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lastName', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'class' => 'required',
                    'id' => 'employe_nom',
                    'maxlength' => '255'
                ],
                'required' => false
            ])
            ->add('firstName', TextType::class, [
                'label' => 'Prenom',
                'attr' => [
                    'class' => 'required',
                    'id' => 'employe_prenom',
                    'maxlength' => '255'
                ],
                'required' => false
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => [
                    'class' => 'required',
                    'id' => 'employe_email',
                    'maxlength' => '255'
                ],
                'required' => false
            ])
            ->add('arrival_date', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date d\'entrée',
                'attr' => [
                    'class' => 'required',
                    'id' => 'employe_dateArrivee',
                    'maxlength' => '255'
                ],
                'required' => false
            ])
            ->add('contract', TextType::class, [
                'label' => 'Statut',
                'attr' => [
                    'class' => 'required',
                    'id' => 'employe_statut',
                    'maxlength' => '255'
                ],
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Employee::class,
        ]);
    }
}
