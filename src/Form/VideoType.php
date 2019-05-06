<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\TagVideo;
use App\Entity\TypeVideo;
use App\Entity\Video;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VideoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('type', EntityType::class, [
                'required'   => true,
                'class' => TypeVideo::class,
                'choice_label' => function(TypeVideo $typeVideo){
                    return sprintf('(%d) %s', $typeVideo->getId(), $typeVideo->getName());
                },
                'placeholder' => 'Choose a type'
            ])
            ->add('tags', EntityType::class, [
                'required'   => true,
                'multiple' => true,
                'class' => TagVideo::class,
                'choice_label' => function(TagVideo $tagVideo){
                    return sprintf('(%d) %s', $tagVideo->getId(), $tagVideo->getName());
                },
                'placeholder' => 'Choose tags'
            ])
            ->add('imageFile', FileType::class)
            ->add('description')
            ->add('notation', ChoiceType::class,[
                'choices'  => [
                    0,
                    1,
                    2,
                    3,
                    4,
                    5,
                    6,
                    7,
                    8,
                    9,
                    10,
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Video::class,
        ]);
    }
}
