<?php


namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;

class ProfileEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username')
                ->add('email')
                ->add('description')
                ->add('imageFile', FileType::class);
    }
//
//    public function getParent()
//    {
//        return 'FOS\UserBundle\Form\Type\RegistrationFormType';
//
//        // Or for Symfony < 2.8
//        // return 'fos_user_registration';
//    }
//
//    public function getBlockPrefix()
//    {
//        return 'app_user_registration';
//    }

}