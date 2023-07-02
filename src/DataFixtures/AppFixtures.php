<?php

namespace App\DataFixtures;

use App\Entity\Consumer;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $user = new User();
        $user->setEmail('mathieulagnel@gmail.com');
        $user->setPassword('$2y$13$sEVgGnRII.iFEsLA0RZV1u3T41m5AUvTCxF4yFkRu.pZUUkBZzKEK');
        $manager->persist($user);

        $consumer1 = new Consumer();
        $consumer1->setName("Lagnel");
        $consumer1->setFirstname("Mathieu");
        $consumer1->setClient($user);
        $manager->persist($consumer1);

        $consumer2 = new Consumer();
        $consumer2->setName("Léo");
        $consumer2->setFirstname("Gabriel");
        $consumer2->setClient($user);
        $manager->persist($consumer2);

        $consumer3 = new Consumer();
        $consumer3->setName("Eden");
        $consumer3->setFirstname("Gabriel");
        $consumer3->setClient($user);
        $manager->persist($consumer3);

        $consumer4 = new Consumer();
        $consumer4->setName("Arthur");
        $consumer4->setFirstname("Louis");
        $consumer4->setClient($user);
        $manager->persist($consumer4);

        $consumer5 = new Consumer();
        $consumer5->setName("Jules");
        $consumer5->setFirstname("Adam");
        $consumer5->setClient($user);
        $manager->persist($consumer5);

        $consumer6 = new Consumer();
        $consumer6->setName("Lucas");
        $consumer6->setFirstname("Hugo");
        $consumer6->setClient($user);
        $manager->persist($consumer6);

        $product1 = new Product();
        $product1->setName("iphone 12");
        $product1->setDescription("L'iPhone 12 d'Apple est sorti le 13 octobre 2020. Il est muni d'un écran de 6,1 pouces OLED Super Retina XDR, d'un double capteur photo");
        $product1->setPrice("426");
        $manager->persist($product1);

        $product2 = new Product();
        $product2->setName("iphone 13");
        $product2->setDescription("iPhone 13. Le double appareil photo le plus avancé à ce jour sur iPhone. La puce A15 Bionic, d'une vitesse fulgurante. Une autonomie nettement améliorée");
        $product2->setPrice("651");
        $manager->persist($product2);

        $product2 = new Product();
        $product2->setName("iphone 14");
        $product2->setDescription("iPhone 14 et iPhone 14 Plus. 6,1″ et 6,7″. Autonomie d'une journée. SOS d'urgence par satellite. Détection des accidents. Photos sublimes. 6 couleurs.");
        $product2->setPrice("856");
        $manager->persist($product2);

        $product2 = new Product();
        $product2->setName("Samsung  galaxy s21");
        $product2->setDescription("Le Samsung Galaxy S21 est le porte-étendard de la marque, succédant à la gamme S20. Il est équipé d'un SoC Exynos 2100 (gravé en 5 nm),");
        $product2->setPrice("245");
        $manager->persist($product2);

        $product2 = new Product();
        $product2->setName("Samsung  galaxy s21");
        $product2->setDescription("Le Samsung Galaxy S22 est le porte-étendard de la marque, succédant à la gamme S20. Il est équipé d'un SoC Exynos 2100 (gravé en 5 nm),");
        $product2->setPrice("643");
        $manager->persist($product2);



        $manager->flush();
    }
}
