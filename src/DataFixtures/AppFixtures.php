<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Faker\Factory as Faker;

class AppFixtures extends BaseFixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Faker::create();

        for ($i = 0; $i < 4; $i++) {
            $category = new Category();
            $category->setName($faker->word);
            $manager->persist($category);

            for ($a = 0; $a < 5; $a++) {
                $product = new Product();
                $product->setName($faker->firstName);
                $product->setPrice($faker->randomNumber(2));
                $product->setCategory($category);
                $product->setDescription($faker->sentence);
                $product->setImageLink('../assets/images/placeholder.png');
                $manager->persist($product);
            }
        }
        $testUser = new User();
        $testUser->setEmail('testmpa@hotmail.com');
        $password = $this->encoder->encodePassword($testUser, 'test');
        $testUser->setPassword($password);
        $manager->persist($testUser);
        $manager->flush();
    }
}
