<?php

namespace App\DataFixtures;

use App\Entity\God;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;



class AppFixtures extends Fixture
{

    public function __construct(private UserPasswordHasherInterface $userPasswordHasher)
    {
    }

    public function load(ObjectManager $manager)
    {
        $base = [
            [
                "name" => "Zeus",
                "title" => "God of the sky and thunder",
                "picture" => "https://img.freepik.com/vecteurs-premium/zeus-dieu-du-tonnerre_2029-345.jpg"
            ],
            [
                "name" => "Poséidon",
                "title" => "God of the sea",
                "picture" => "https://i.pinimg.com/564x/8a/ff/03/8aff03449a4fe57a4934c0b4b26782f1.jpg"
            ],
            [
                "name" => "Hadés",
                "title" => "God of the underworld",
                "picture" => "https://i.pinimg.com/736x/5a/5b/5e/5a5b5e03d3164f6b245f57eb4cfdc97e.jpg"
            ],
            [
                "name" => "Athéna",
                "title" => "Goddess of wisdom and war",
                "picture" => "https://p7.hiclipart.com/preview/756/45/236/athena-pegasus-seiya-saint-seiya-knights-of-the-zodiac-drawing-chibi-temples.jpg"
            ],
            [
                "name" => "Hestia",
                "title" => "Goddess of home and hearth",
                "picture" => "https://static.vecteezy.com/ti/vecteur-libre/p3/12041070-creation-de-logo-de-mascotte-hestia-chibi-esport-vectoriel.jpg"
            ],
            [
                "name" => "Artémis",
                "title" => "Goddess of the hunt and wilderness",
                "picture" => "https://static.vecteezy.com/ti/vecteur-libre/p3/12041067-creation-de-logo-de-mascotte-artemis-chibi-vectoriel.jpg"
            ],
        ];

        foreach ($base as $god) {
            $new_god = new God();
            $new_god->setName($god['name']);
            $new_god->setTitle($god['title']);
            $new_god->setPicture($god['picture']);
            $new_god->setSlug($god['title'] . '-' . uniqid());
            $new_god->setPraiseCount(0);
            $manager->persist($new_god);
        }
        

        $manager->flush();

        $user = new User();
        $user->setUsername('Nyx');
        $user->setPassword($this->userPasswordHasher->hashPassword(
            $user,
            'azerty'
        ));
        $user->setRoles(['ROLE_ADMIN']);
        $manager->persist($user);

        $user = new User();
        $user->setUsername('Anna');
        $user->setPassword($this->userPasswordHasher->hashPassword(
            $user,
            'aqwzsx'
        ));
        $user->setRoles(['ROLE_USER']);
        $manager->persist($user);

        $manager->flush();

    }
}
