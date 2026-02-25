<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Fixtures amb productes realistes de segona mà
 * - 5 usuaris amb dades reals
 * - 20 productes amb descripció, preu, imatge real d'Unsplash i data
 */
class AppFixtures extends Fixture
{
    // Injectem el servei per hashjar contrasenyes
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher
    ) {}

    public function load(ObjectManager $manager): void
    {
        // --- DADES DELS 5 USUARIS ---
        $usersData = [
            ['name' => 'Marc Puig',   'email' => 'marc.puig@gmail.com'],
            ['name' => 'Laura Vidal', 'email' => 'laura.vidal@gmail.com'],
            ['name' => 'Jordi Mas',   'email' => 'jordi.mas@gmail.com'],
            ['name' => 'Anna Soler',  'email' => 'anna.soler@gmail.com'],
            ['name' => 'Pere Ferrer', 'email' => 'pere.ferrer@gmail.com'],
        ];

        $users = [];
        foreach ($usersData as $userData) {
            $user = new User();
            $user->setName($userData['name']);
            $user->setEmail($userData['email']);
            // Totes les contrasenyes de prova seran "password123"
            $user->setPassword($this->passwordHasher->hashPassword($user, 'password123'));
            $user->setRoles(['ROLE_USER']);
            $manager->persist($user);
            $users[] = $user;
        }

        // --- DADES DELS 20 PRODUCTES REALISTES ---
        // Imatges obtingudes d'Unsplash: canvia les URLs per les que vulguis
        $productsData = [
            [
                'title'       => 'iPhone 13 128GB Negre',
                'description' => 'iPhone 13 en perfecte estat, bateria al 89%, inclou caixa original, carregador i fundes. Sense cap ratllade ni cop. Motiu de venda: canvi a iPhone 15.',
                'price'       => 520.00,
                'image'       => 'https://images.unsplash.com/photo-1635425730507-26c324aadbc5?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8NHx8aXBob25lJTIwMTMlMjBuZWdyb3xlbnwwfHwwfHx8MA%3D%3D',
                'days_ago'    => 5,
            ],
            [
                'title'       => 'Bicicleta de Muntanya Trek Marlin 5',
                'description' => 'Bicicleta Trek Marlin 5 talla M, any 2021. Canvis Shimano 21 velocitats, forquilla suspensió delantera. Revisada recentment, frens nous. Ideal per camins i carretera.',
                'price'       => 380.00,
                'image'       => 'https://images.unsplash.com/photo-1485965120184-e220f721d03e?w=800',
                'days_ago'    => 12,
            ],
            [
                'title'       => 'Sofà de 3 Places Verd Antracita',
                'description' => 'Sofà de 3 places en teixit gris antracita, molt còmode i en bon estat. Dimensions: 210x90x85cm. Desmuntable per facilitar el transport. Recollida a Barcelona.',
                'price'       => 250.00,
                'image'       => 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=800',
                'days_ago'    => 3,
            ],
            [
                'title'       => 'MacBook Pro 14" M1 Pro 2021',
                'description' => 'MacBook Pro 14 polzades, chip M1 Pro, 16GB RAM, 512GB SSD. En perfecte estat, sempre amb funda protectora. Inclou carregador MagSafe original. Factura disponible.',
                'price'       => 1450.00,
                'image'       => 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=800',
                'days_ago'    => 8,
            ],
            [
                'title'       => 'Càmera Mirrorless Sony A6400',
                'description' => 'Sony A6400 amb objectiu kit 16-50mm. Molt poc ús, menys de 3000 disparaments. Inclou dues bateries, carregador, targeta SD 64GB i bossa de transport.',
                'price'       => 680.00,
                'image'       => 'https://images.unsplash.com/photo-1516035069371-29a1b244cc32?w=800',
                'days_ago'    => 20,
            ],
            [
                'title'       => 'Taula de Menjador + 6 Cadires',
                'description' => 'Conjunt de taula rectangular 160x80cm en fusta massissa i 6 cadires tapissades en teixit beige. Estil nòrdic, en molt bon estat. Venda conjunta o per separat.',
                'price'       => 320.00,
                'image'       => 'https://images.unsplash.com/photo-1602872030490-4a484a7b3ba6?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTF8fG1lbmphZG9yfGVufDB8fDB8fHww',
                'days_ago'    => 15,
            ],
            [
                'title'       => 'PlayStation 5 + 2 Mandos + 3 Jocs',
                'description' => 'PS5 edició disc en perfecte estat. Inclou 2 mandos DualSense (un blanc i un negre) i 3 jocs: Spider-Man 2, God of War Ragnarok i FIFA 24. Tot en perfecte estat.',
                'price'       => 480.00,
                'image'       => 'https://images.unsplash.com/photo-1606813907291-d86efa9b94db?w=800',
                'days_ago'    => 7,
            ],
            [
                'title'       => 'Roba de Muntanya The North Face (Lot)',
                'description' => 'Lot de roba de muntanya marca The North Face talla L: 2 flaçades, 1 pantaló convertible i 1 samarreta tèrmica. Tot en excel·lent estat, poc usat.',
                'price'       => 95.00,
                'image'       => 'https://images.unsplash.com/photo-1674350285486-a62fcdc2fa61?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8NHx8dGhlJTIwbm9ydGglMjBmYWNlfGVufDB8fDB8fHww',
                'days_ago'    => 25,
            ],
            [
                'title'       => 'Guitarra Acústica Yamaha F310',
                'description' => 'Guitarra acústica Yamaha F310 en molt bon estat. Inclou funda semirígida, cordes noves i afinador clip. Ideal per a principiants o intermedis. So excel·lent.',
                'price'       => 110.00,
                'image'       => 'https://images.unsplash.com/photo-1510915361894-db8b60106cb1?w=800',
                'days_ago'    => 18,
            ],
            [
                'title'       => 'Nevera Samsung 400L NoFrost',
                'description' => 'Nevera Samsung de dues portes 400L amb sistema NoFrost. Color inox, classe energètica A+. Funciona perfectament, venda per canvi de cuina. Dimensions: 185x70x65cm.',
                'price'       => 290.00,
                'image'       => 'https://images.unsplash.com/photo-1571175443880-49e1d25b2bc5?w=800',
                'days_ago'    => 10,
            ],
            [
                'title'       => 'Patinete Elèctric Xiaomi Mi 3',
                'description' => 'Patinete elèctric Xiaomi Mi Scooter 3, autonomia 30km, velocitat màxima 25km/h. En perfecte estat, poc ús. Inclou llum frontal i posterior. Plegable i fàcil de transportar.',
                'price'       => 280.00,
                'image'       => 'https://images.unsplash.com/photo-1654748646458-056253a82853?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8M3x8cGF0aW5ldGUlMjBlbGVjdHJpY298ZW58MHx8MHx8fDA%3D',
                'days_ago'    => 6,
            ],
            [
                'title'       => 'Llibres de Text Batxillerat (Lot)',
                'description' => 'Lot de 8 llibres de text de 2n de Batxillerat: Matemàtiques, Física, Química, Història, Anglès, Català, Castellà i Filosofia. Editorial Santillana i Vicens Vives. Bon estat.',
                'price'       => 45.00,
                'image'       => 'https://images.unsplash.com/photo-1497633762265-9d179a990aa6?w=800',
                'days_ago'    => 30,
            ],
            [
                'title'       => 'Cafetera Nespresso Vertuo Plus',
                'description' => 'Cafetera Nespresso Vertuo Plus en color negre, molt poc ús. Inclou aeroccino per fer escuma de llet i 20 càpsules variades. Perfecte per als amants del cafè.',
                'price'       => 75.00,
                'image'       => 'https://images.unsplash.com/photo-1545936055-22b27770efca?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTJ8fGNhZmV0ZXJhfGVufDB8fDB8fHww',
                'days_ago'    => 14,
            ],
            [
                'title'       => 'Monitor Gaming LG 27" 144Hz',
                'description' => 'Monitor LG 27 polzades, resolució 1440p, 144Hz, temps de resposta 1ms, panel IPS. En perfecte estat, inclou cables HDMI i DisplayPort. Ideal per a gaming i disseny.',
                'price'       => 220.00,
                'image'       => 'https://images.unsplash.com/photo-1615750260764-643b0f01a0af?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8NXx8bW9uaXRvciUyMGxnfGVufDB8fDB8fHww',
                'days_ago'    => 9,
            ],
            [
                'title'       => 'Raquetes de Pàdel Head (Parell)',
                'description' => 'Parell de raquetes de pàdel Head Delta Pro, nivell intermedi. Inclou funda protectora per a cada raqueta. Poc ús, en perfecte estat. Venda conjunta.',
                'price'       => 130.00,
                'image'       => 'https://images.unsplash.com/photo-1657704358775-ed705c7388d2?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8cmFxdWV0YSUyMHBhZGVsfGVufDB8fDB8fHww',
                'days_ago'    => 22,
            ],
            [
                'title'       => 'Armari de 3 Portes Blanc IKEA PAX',
                'description' => 'Armari IKEA PAX de 3 portes corredisses amb mirall, color blanc. Dimensions: 200x236x58cm. Inclou tots els accessoris interiors: calaixos, prestatges i barra penjadors.',
                'price'       => 180.00,
                'image'       => 'https://images.unsplash.com/photo-1558997519-83ea9252edf8?w=800',
                'days_ago'    => 16,
            ],
            [
                'title'       => 'Samsung Galaxy Watch 5 Pro',
                'description' => 'Samsung Galaxy Watch 5 Pro 45mm color gris titani. GPS integrat, monitor de freqüència cardíaca i son. Inclou carregador original i dues corretges addicionals.',
                'price'       => 195.00,
                'image'       => 'https://images.unsplash.com/photo-1579586337278-3befd40fd17a?w=800',
                'days_ago'    => 11,
            ],
            [
                'title'       => 'Taula de Surf 7\'2" NSP',
                'description' => 'Taula de surf NSP 7\'2" Funboard, ideal per a principiants i nivell intermedi. Inclou quilles, leash i funda protectora. Molt bon estat, utilitzada dues temporades.',
                'price'       => 340.00,
                'image'       => 'https://plus.unsplash.com/premium_photo-1676645882020-8387c2c77ef8?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MXx8dGF1bGElMjBzdXJmfGVufDB8fDB8fHww',
                'days_ago'    => 28,
            ],
            [
                'title'       => 'Teclat Mecànic Keychron K2 Pro',
                'description' => 'Teclat mecànic Keychron K2 Pro, switches Brown, retroil·luminació RGB. Compatible amb Mac i Windows, connexió Bluetooth i USB-C. En perfecte estat, inclou cable original.',
                'price'       => 85.00,
                'image'       => 'https://images.unsplash.com/photo-1587829741301-dc798b83add3?w=800',
                'days_ago'    => 4,
            ],
            [
                'title'       => 'Cotxet de Nadó Bugaboo Fox 3',
                'description' => 'Cotxet Bugaboo Fox 3 en color gris melange, molt poc ús. Inclou capazo, seient, bossa i adaptadors per a cadira de cotxe. En perfecte estat, com nou.',
                'price'       => 650.00,
                'image'       => 'https://images.unsplash.com/photo-1714392512700-4cab9e51710b?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8Y29jaGUlMjBkZSUyMGJlYmV8ZW58MHx8MHx8fDA%3D',
                'days_ago'    => 13,
            ],
        ];

        // Creem els 20 productes i els assignem aleatòriament als usuaris
        foreach ($productsData as $productData) {
            $product = new Product();
            $product->setTitle($productData['title']);
            $product->setDescription($productData['description']);
            $product->setPrice((string) $productData['price']);
            // URL d'imatge real d'Unsplash per cada producte
            $product->setImage($productData['image']);
            // Data de creació basada en els dies enrere definits
            $product->setCreatedAt(new \DateTime('-' . $productData['days_ago'] . ' days'));
            // Assignem un usuari aleatori com a propietari
            $product->setOwner($users[array_rand($users)]);
            $manager->persist($product);
        }

        // Guardem tots els canvis a la base de dades
        $manager->flush();
    }
}