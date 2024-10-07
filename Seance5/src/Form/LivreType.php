<?php

namespace App\Form;

use App\Entity\Livre;
use App\Entity\Author; // Import Author entity
use Symfony\Bridge\Doctrine\Form\Type\EntityType; // Import EntityType for relational fields
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LivreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('ref', IntegerType::class, [
                'label' => 'Ref',
            ])

            ->add('title', TextType::class, [
                'label' => 'Title',
            ])
            ->add('nbrPage', IntegerType::class, [
                'label' => 'Number of Pages',
            ])
            ->add('picture', TextType::class, [
                'label' => 'Picture URL',
            ])
            // Adjusting the 'author' field to use EntityType for author selection
            ->add('author', EntityType::class, [
                'class' => Author::class, // Reference the Author entity
                'choice_label' => 'username', // Specify which field to display in the dropdown
                'label' => 'Author',
            ])

            ->add('save', SubmitType::class, [
                'label' => 'Add Book',
                'attr' => ['class' => 'btn btn-success btn-lg'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Livre::class,
        ]);
    }
}
