<?php
namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/product')]
class ProductController extends AbstractController
{
    // Llistat públic de tots els productes
    #[Route('', name: 'app_product_index', methods: ['GET'])]
    public function index(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findAllOrderedByDate();
        return $this->render('product/index.html.twig', [
            'products'        => $products,
            'title'           => 'Tots els productes',
            'show_actions'    => false,
            'show_new_button' => false,
            'empty_message'   => 'No hi ha productes disponibles encara.',
        ]);
    }

    // Llistat dels productes de l'usuari autenticat. Reutilitza la mateixa vista
    #[Route('/my/products', name: 'app_my_products', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function myProducts(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findByOwner($this->getUser());
        return $this->render('product/index.html.twig', [
            'products'        => $products,
            'title'           => 'Els meus productes',
            'show_actions'    => true,   // Mostra botons editar/esborrar
            'show_new_button' => true,   // Mostra botó nou producte
            'empty_message'   => 'Encara no tens cap producte publicat.',
        ]);
    }

    // Crear nou producte (protegit per autenticació)
    #[Route('/new', name: 'app_product_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product); // Formulari reutilitzable
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product->setOwner($this->getUser()); // Assignem l'usuari autenticat
            if (!$product->getImage()) {
                $product->setImage('https://picsum.photos/seed/' . uniqid() . '/300/200'); // Imatge automàtica
            }
            $product->setCreatedAt(new \DateTime());
            $em->persist($product);
            $em->flush();
            $this->addFlash('success', 'Producte publicat correctament!');
            return $this->redirectToRoute('app_product_show', ['id' => $product->getId()]);
        }

        return $this->render('product/new.html.twig', ['form' => $form]);
    }

    // Detall d'un producte (ParamConverter injecta automàticament el producte per id)
    #[Route('/{id}', name: 'app_product_show', methods: ['GET'])]
    public function show(Product $product): Response
    {
        return $this->render('product/show.html.twig', ['product' => $product]);
    }

    // Editar producte (reutilitza el mateix ProductType que new)
    #[Route('/{id}/edit', name: 'app_product_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function edit(Request $request, Product $product, EntityManagerInterface $em): Response
    {
        // Validem que l'usuari sigui el propietari
        if ($product->getOwner() !== $this->getUser()) {
            throw $this->createAccessDeniedException('No tens permís per editar aquest producte.');
        }

        $form = $this->createForm(ProductType::class, $product); // Mateix formulari que new
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush(); // No cal persist() perquè l'entitat ja existeix
            $this->addFlash('success', 'Producte actualitzat correctament!');
            return $this->redirectToRoute('app_product_show', ['id' => $product->getId()]);
        }

        return $this->render('product/edit.html.twig', ['form' => $form, 'product' => $product]);
    }

    // Esborrar producte (validació CSRF + propietari)
    #[Route('/{id}', name: 'app_product_delete', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function delete(Request $request, Product $product, EntityManagerInterface $em): Response
    {
        if ($product->getOwner() !== $this->getUser()) {
            throw $this->createAccessDeniedException('No tens permís per esborrar aquest producte.');
        }

        // Validem el token CSRF per evitar atacs
        if ($this->isCsrfTokenValid('delete' . $product->getId(), $request->request->get('_token'))) {
            $em->remove($product);
            $em->flush();
            $this->addFlash('success', 'Producte esborrat correctament.');
        } else {
            $this->addFlash('error', 'Token de seguretat invàlid.');
        }

        return $this->redirectToRoute('app_product_index');
    }
}