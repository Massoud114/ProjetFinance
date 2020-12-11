<?php

namespace App\Form;

use App\Entity\OperationSearch;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OperationSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstDate', DateType::class, [
            	'required' => false,
				'label' => 'Date de début',
				'widget' => 'single_text',
			])
            ->add('secondDate', DateType::class, [
            	'required' => false,
				'label' => 'Date de fin',
				'widget' => 'single_text',
			])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => OperationSearch::class,
			'method' => 'get',
			'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix()
	{
		return "";
	}
}
