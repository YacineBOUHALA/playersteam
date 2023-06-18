<?php

namespace App\Form;

use App\Entity\NationalTeam;
use App\Entity\Player;
use App\Entity\Team;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;

class PlayerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                'constraints'=>[
                    new NotBlank([
                        'message'=>'First name is required',
                    ]),
                    new Length([
                       'min'=>2,
                       'minMessage'=> 'First name is too short'
                    ]),
                ],
            ])
            ->add('lastname', TextType::class, [
                'constraints'=>[
                    new NotBlank([
                        'message'=>'Last name is required',
                    ]),
                    new Length([
                        'min'=>2,
                        'minMessage'=> 'First name is too short'
                    ]),
                ],
            ])
            ->add('birthday', BirthdayType::class,[
                'widget'=> 'single_text',
                'empty_data'=>null,
                'constraints'=>[
                    new NotBlank([
                        'message' => 'Portrait is required'
                    ]),
                ],
            ])
            ->add('portrait', FileType::class,[
                'data_class' => null,
                'constraints'=> $options['data']->getId()? []: [
                    new NotBlank([
                        'message' => 'Portrait is required'
                    ])
                ],
            ])
            ->add('number', IntegerType::class, [
                'constraints'=>[
                    new NotBlank([
                        'message' => 'Number is required'
                    ]),
                    new Positive([
                        'message' => 'Number must be positive'
                    ])
                ],
            ])
            ->add('team', EntityType::class, [
                'class'=> Team::class,
                'choice_label' => 'name',
                'placeholder' =>'',
                'constraints'=>[
                    new NotBlank([
                        'message' => 'Team is required'
                    ]),
                ],
            ])
            ->add('nationalTeam', EntityType::class, [
                'class'=> NationalTeam::class,
                'choice_label' => 'name',
                'placeholder' =>'',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Player::class,
        ]);
    }
}
