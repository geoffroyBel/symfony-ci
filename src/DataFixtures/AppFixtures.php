<?php

namespace App\DataFixtures;

use App\Entity\Horaire;
use App\Entity\Prestation;
use App\Entity\User;
use App\Security\TokenGenerator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    /**
     * @var UserPasswordHasherInterface
     */
    private $passwordHasher;

    /**
     * @var \Faker\Factory
     */
    private $faker;

    private const USERS = [
        [
            'username' => 'admin',
            'email' => 'admin@blog.com',
            'name' => 'Piotr Jura',
            'password' => 'Secret123#',
            'roles' => [User::ROLE_SUPERADMIN],
            'enabled' => true
        ],
        [
            'username' => 'john_doe',
            'email' => 'john@blog.com',
            'name' => 'John Doe',
            'password' => 'Secret123#',
            'roles' => [User::ROLE_ADMIN],
            'enabled' => true
        ],
        [
            'username' => 'rob_smith',
            'email' => 'rob@blog.com',
            'name' => 'Rob Smith',
            'password' => 'Secret123#',
            'roles' => [User::ROLE_COMPANY],
            'enabled' => true
        ],
        [
            'username' => 'jenny_rowling',
            'email' => 'jenny@blog.com',
            'name' => 'Jenny Rowling',
            'password' => 'Secret123#',
            'roles' => [User::ROLE_USER],
            'enabled' => true
        ],
        [
            'username' => 'han_solo',
            'email' => 'han@blog.com',
            'name' => 'Han Solo',
            'password' => 'secret123#',
            'roles' => [User::ROLE_COMPANY],
            'enabled' => false
        ],
        [
            'username' => 'jedi_knight',
            'email' => 'jedi@blog.com',
            'name' => 'Jedi Knight',
            'password' => 'secret123#',
            'roles' => [User::ROLE_USER],
            'enabled' => true
        ],
    ];
    /**
     * @var TokenGenerator
     */
    private $tokenGenerator;

    public function __construct(
        UserPasswordHasherInterface $passwordHasher, TokenGenerator $tokenGenerator
    )
    {
        $this->passwordHasher = $passwordHasher;
        $this->tokenGenerator = $tokenGenerator;
        $this->faker = \Faker\Factory::create();
    }

    /**
     * Load data fixtures with the passed EntityManager
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $this->loadUsers($manager);
        $this->loadPrestations($manager);
        $this->loadHoraires($manager);
    }

    public function loadPrestations(ObjectManager $manager)
    {
        for ($i = 0; $i < 100; $i++) {
            /** @var Prestation */
            $prestation = new Prestation();
            $prestation->setName($this->faker->name);
            // $prestation->setCreated($this->faker->dateTimeThisYear);
            // $ownerReference = $this->getRandomUserReference($prestation);
            // $prestation->setOwner($ownerReference);
            $this->setReference("prestation_$i", $prestation);

            $manager->persist($prestation);
        }

        $manager->flush();
    }

    public function loadHoraires(ObjectManager $manager)
    {
        for ($i = 0; $i < 100; $i++) {
            for ($j = 0; $j < rand(1, 10); $j++) {
                /** @var Horaire */
                $horaire = new Horaire();
                $horaire->setStartDate($this->faker->dateTimeThisYear);
                $horaire->setEndDate($this->faker->dateTimeThisYear);

                $prestationReference = $this->getReference(
                    'prestation_'. rand(0, 99)
                );
                $horaire->setPrestation(($prestationReference));

                $manager->persist($horaire);
            }
        }

        $manager->flush();
    }



    public function loadUsers(ObjectManager $manager)
    {
        foreach (self::USERS as $userFixture) {
            $user = new User();
            $user->setUsername($userFixture['username']);
            $user->setEmail($userFixture['email']);
            $user->setName($userFixture['name']);

            $user->setPassword(
                $this->passwordHasher->hashPassword($user, $userFixture['password'])
            );
            $user->setRoles($userFixture['roles']);
            $user->setEnabled($userFixture['enabled']);

            if (!$userFixture['enabled']) {
                $user->setConfirmationToken(
                    $this->tokenGenerator->getRandomSecureToken()
                );
            }

            $this->addReference('user_'.$userFixture['username'], $user);

            $manager->persist($user);
        }

        $manager->flush();
    }

    protected function getRandomUserReference($entity): User
    {
        $randomUser = self::USERS[rand(0, 5)];

        if ($entity instanceof Prestation && !count(
                array_intersect(
                    $randomUser['roles'],
                    [User::ROLE_SUPERADMIN, User::ROLE_ADMIN, User::ROLE_COMPANY]
                )
            )) {
            return $this->getRandomUserReference($entity);
        }

        // if ($entity instanceof Comment && !count(
        //         array_intersect(
        //             $randomUser['roles'],
        //             [
        //                 User::ROLE_SUPERADMIN,
        //                 User::ROLE_ADMIN,
        //                 User::ROLE_WRITER,
        //                 User::ROLE_COMMENTATOR,
        //             ]
        //         )
        //     )) {
        //     return $this->getRandomUserReference($entity);
        // }


        return $this->getReference(
            'user_'.$randomUser['username']
        );
    }
}