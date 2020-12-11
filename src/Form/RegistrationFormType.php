<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\EqualTo;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username')
			->add('plainPassword', RepeatedType::class, [
				'type' => PasswordType::class,
				'invalid_message' => 'Les mots de passe doivent etre les memes.',
				'required' => true,
				'first_options'  => ['label' => 'Mot de passe'],
				'second_options' => ['label' => 'Confirmer le mot de passe'],
				'mapped' => false,
				'constraints' => [
					new NotBlank([
						'message' => 'Merci d\'entrer un mot de passe',
					]),
					new Length([
						'min' => 4,
						'minMessage' => 'Le mot de passe doit faire plus de {{ limit }} caractères',
						'max' => 4096,
					]),
				],
			]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
