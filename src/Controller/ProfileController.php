<?php
namespace App\Controller;

use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
class ProfileController extends AbstractController
{
    // Editar perfil: reutilitza el MATEIX RegistrationFormType que el registre
    #[Route('/profile/edit', name: 'app_profile_edit')]
    public function edit(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        $user = $this->getUser();

        // Reutilitzem el mateix formulari que el registre
        $form = $this->createForm(RegistrationFormType::class, $user, [
            'is_edit' => true, // Opció per indicar que estem editant
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Només actualitzem la contrasenya si s'ha introduït una de nova
            $plainPassword = $form->get('plainPassword')->getData();
            if ($plainPassword) {
                $user->setPassword($passwordHasher->hashPassword($user, $plainPassword));
            }

            $em->flush();
            $this->addFlash('success', 'Perfil actualitzat correctament!');
            return $this->redirectToRoute('app_product_index');
        }

        // Reutilitzem la mateixa vista de registre amb paràmetres diferents
        return $this->render('registration/register.html.twig', [
            'form'     => $form,
            'is_edit'  => true,
            'title'    => 'Editar perfil',
            'subtitle' => 'Modifica les teves dades personals',
            'button'   => 'Guardar canvis',
        ]);
    }
}