<?php

namespace App\Form;

use App\Entity\NationalTeam;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class NationalTeamType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'constraints'=>[
                    new NotBlank([
                        'message'=>'Name is required',
                    ]),
                    new Length([
                        'min'=>2,
                        'minMessage'=> 'First name is too short'
                    ]),
                ],
            ])
            ->add('drapeau', FileType::class, [
                'data_class'=> null,
                'constraints'=>$options['data']->getId()?[] :[
                    new NotBlank([
                        'message'=>' Flag is required',
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => NationalTeam::class,
        ]);
    }
}
