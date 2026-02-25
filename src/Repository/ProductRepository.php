<?php

namespace App\Repository;

use App\Entity\Product;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Repositori per a l'entitat Product
 * Conté mètodes per consultar productes a la base de dades
 */
class ProductRepository extends ServiceEntityRepository
{
    // Constructor que inicialitza el repositori amb l'entitat Product
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * Obté tots els productes ordenats per data de creació (més recents primer)
     */
    public function findAllOrderedByDate(): array
    {
        return $this->findBy([], ['createdAt' => 'DESC']);
    }

    /**
     * Obté tots els productes d'un usuari específic
     */
    public function findByOwner(User $user): array
    {
        return $this->findBy(['owner' => $user], ['createdAt' => 'DESC']);
    }
}