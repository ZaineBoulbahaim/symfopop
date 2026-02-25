<?php
namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Formulari reutilitzable per a registre i edició de perfil
 * L'opció 'is_edit' permet adaptar el comportament
 */
class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // Saber si estem en mode edició
        $isEdit = $options['is_edit'];

        $builder
            // Camp nom
            ->add('name', TextType::class, [
                'label' => 'Nom complet',
                'constraints' => [
                    new Assert\NotBlank(['message' => 'El nom no pot estar buit']),
                    new Assert\Length(['min' => 2, 'max' => 255,
                        'minMessage' => 'El nom ha de tenir com a mínim {{ limit }} caràcters',
                    ]),
                ],
            ])
            // Camp email: únic a la base de dades
            ->add('email', EmailType::class, [
                'label' => 'Correu electrònic',
                'constraints' => [
                    new Assert\NotBlank(['message' => 'El correu no pot estar buit']),
                    new Assert\Email(['message' => 'El correu no és vàlid']),
                ],
            ])
            // Camp contrasenya: obligatori en registre, opcional en edició
            ->add('plainPassword', RepeatedType::class, [
                'type'           => PasswordType::class,
                'mapped'         => false, // No mapejat directament a l'entitat
                'required'       => !$isEdit, // Opcional si estem editant
                'first_options'  => ['label' => $isEdit ? 'Nova contrasenya (opcional)' : 'Contrasenya'],
                'second_options' => ['label' => 'Repeteix la contrasenya'],
                'invalid_message' => 'Les contrasenyes no coincideixen.',
                'constraints' => $isEdit ? [] : [ // Validació només en registre
                    new Assert\NotBlank(['message' => 'La contrasenya no pot estar buida']),
                    new Assert\Length(['min' => 6, 'minMessage' => 'La contrasenya ha de tenir com a mínim {{ limit }} caràcters']),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'is_edit'    => false, // Per defecte no estem en mode edició
            'constraints' => [
                // Validació d'email únic a nivell de formulari
                new UniqueEntity([
                    'entityClass' => User::class,
                    'fields'      => ['email'],
                    'message'     => 'Aquest correu electrònic ja està registrat.',
                ]),
            ],
        ]);
    }
}