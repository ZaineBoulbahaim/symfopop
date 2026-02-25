<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    // Ruta de login
    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Si ja està autenticat, redirigim al llistat
        if ($this->getUser()) {
            return $this->redirectToRoute('app_product_index');
        }

        // Obtenim l'error d'autenticació si n'hi ha
        $error = $authenticationUtils->getLastAuthenticationError();
        // Recuperem l'últim email introduït per no haver de tornar-lo a escriure
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }

    // El logout el gestiona Symfony automàticament via security.yaml
    #[Route('/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}