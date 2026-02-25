<?php
namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Formulari reutilitzable per a Product
 * Usat tant per crear (new) com per editar (edit)
 */
class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Camp títol: mínim 3, màxim 255 caràcters
            ->add('title', TextType::class, [
                'label' => 'Títol del producte',
                'constraints' => [
                    new Assert\NotBlank(['message' => 'El títol no pot estar buit']),
                    new Assert\Length(['min' => 3, 'max' => 255,
                        'minMessage' => 'El títol ha de tenir com a mínim {{ limit }} caràcters',
                        'maxMessage' => 'El títol no pot superar els {{ limit }} caràcters',
                    ]),
                ],
            ])
            // Camp descripció: mínim 10 caràcters
            ->add('description', TextareaType::class, [
                'label' => 'Descripció',
                'constraints' => [
                    new Assert\NotBlank(['message' => 'La descripció no pot estar buida']),
                    new Assert\Length(['min' => 10, 'minMessage' => 'La descripció ha de tenir com a mínim {{ limit }} caràcters']),
                ],
            ])
            // Camp preu: valor positiu amb 2 decimals
            ->add('price', NumberType::class, [
                'label' => 'Preu (€)',
                'scale' => 2,
                'constraints' => [
                    new Assert\NotBlank(['message' => 'El preu no pot estar buit']),
                    new Assert\Positive(['message' => 'El preu ha de ser un valor positiu']),
                ],
            ])
            // Camp imatge: URL opcional
            ->add('image', UrlType::class, [
                'label'    => 'URL de la imatge (opcional)',
                'required' => false,
                'constraints' => [
                    new Assert\Url(['message' => 'La URL de la imatge no és vàlida']),
                ],
            ]);
    }

    // Associem el formulari a l'entitat Product
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Product::class]);
    }
}