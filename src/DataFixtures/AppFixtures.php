<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Idea;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function load(ObjectManager $manager)
    {
        $categories = array();
        for($i = 1; $i <= 5; $i++) {
            $categories[$i] = new Category();
            switch ($i) {
                case 1:
                    $categories[$i]->setName('Travel & Adventure');
                    break;
                case 2:
                    $categories[$i]->setName('Sport');
                    break;
                case 3:
                    $categories[$i]->setName('Entertainment');
                    break;
                case 4:
                    $categories[$i]->setName('Human Relations');
                    break;
                case 5:
                    $categories[$i]->setName('Others');
                    break;
            }
            $manager->persist($categories[$i]);
        }

        $faker = \Faker\Factory::create('fr_FR');

        for ($i = 1; $i <= 100; $i++) {
            $idea = new Idea();
            $idea->setAuthor($faker->userName);
            $idea->setTitle($faker->sentence(5));
            $description = '<p>' . join($faker->paragraphs(3), '</p><p>') . '</p>';
            $idea->setDescription($description);
            $idea->setIsPublished(true);
            $idea->setDateCreated($faker->dateTimeBetween('-6 months', 'now'));
            $idea->setCategory($categories[$faker->numberBetween(1, 5)]);
            $manager->persist($idea);
        }

        $user = new User();
        $user->setUsername('Lolo');
        $user->setEmail('lo.grondin@gmail.com');
        $user->setDateCreated($faker->dateTimeBetween('-6months', 'now'));

        $password = $this->encoder->encodePassword($user, 'azerty');
        $user->setPassword($password);
        $manager->persist($user);

        $admin = new User();
        $admin->setUsername('Juju');
        $admin->setEmail('justine.houve@hotmail.fr');
        $admin->setDateCreated($faker->dateTimeBetween('-6months', 'now'));

        $passwordAdmin = $this->encoder->encodePassword($admin, 'azerty');
        $admin->setPassword($passwordAdmin);
        $admin->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);

        $manager->flush();
    }

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
}
