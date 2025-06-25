<?php

namespace App\Form;

use App\Entity\RecurringTask;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecurringTaskForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $existingFiles = $options['existing_files'] ?? [];

        $builder
            ->add('task', null, [
                'label' => false,
                'attr' => [
                    'placeHolder' => 'Libellé de la tâche',
                ],
            ])
            ->add('oneTime', CheckboxType::class, [
                'required' => false,
                'mapped' => false,
                'label' => 'Une seule fois',
                'data' => $options['data'] && $options['data']->getFrequency() === null,
            ])
            ->add('frequency', null, [
                'label' => false,
            ])
            ->add('frequencyUnit', ChoiceType::class, [
                'label' => false,
                'choices' => [
                    '--' => null,
                    'jour(s)' => 'day',
                    'semaine(s)' => 'week',
                    'mois' => 'month',
                ],
            ])
            ->add('nextTime', null, [
                'label' => false,
                'required' => true,
            ])
            ->add('nbDaysBeforeToDisplay', null, [
                'label' => false,
            ])
            ->add('end', null, [
                'label' => false,
            ])
            ->add('comments', null, [
                'label' => false,
                'attr' => [
                    'placeHolder' => 'Consignes pour la réalisation de cette tâche.',
                    'rows' => 4,
                ],
            ])
            ->add('uploads', FileType::class, [
                'label' => 'Ajouter des fichiers',
                'mapped' => false,
                'required' => false,
                'multiple' => true,
            ])
            ->add('remove_files', CollectionType::class, [
                'label' => false,
                'entry_type' => CheckboxType::class,
                'entry_options' => ['label' => false],
                'mapped' => false,
                'data' => array_fill_keys(array_map(fn($f) => $f->getId(), $existingFiles), false),
                'required' => false,
            ])
            ->add('latestOperationsVisible', ChoiceType::class, [
                'label' => false,
                'choices' => [
                    'NON' => false,
                    'OUI' => true,
                ],
                'expanded' => true,
                'multiple' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RecurringTask::class,
            'existing_files' => [],
        ]);
    }
}
