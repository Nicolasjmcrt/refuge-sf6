<?php

namespace App\Form;

use App\Entity\Sex;
use App\Entity\Races;
use App\Entity\Status;
use App\Entity\Animals;
use App\Entity\Refuges;
use App\Repository\RacesRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnimalsFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', options: [
                'label' => 'Nom de l\'animal'
            ])
            ->add('description')
            ->add('races', EntityType::class, [
                'class' => Races::class,
                'choice_label' => 'name',
                'label' => 'Race',
                'group_by' => 'parent.name',
                'query_builder' => function (RacesRepository $racesRepository) {
                    return $racesRepository->createQueryBuilder('r')
                        ->where('r.parent IS NOT NULL')
                        ->orderBy('r.parent', 'ASC');
                }
            ])
            ->add('sex', EntityType::class, [
                'class' => Sex::class,
                'choice_label' => 'name',
                'label' => 'Sexe'
                ])
            ->add('status', EntityType::class, [
                'class' => Status::class,
                'choice_label' => 'name',
                'label' => 'Statut'
            ])
            ->add('refuge', EntityType::class, [
                'class' => Refuges::class,
                'choice_label' => 'name',
                'label' => 'Refuge',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Animals::class,
        ]);
    }
}
