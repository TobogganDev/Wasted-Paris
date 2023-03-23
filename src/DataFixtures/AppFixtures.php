<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
	
	private UserPasswordHasherInterface $hasher;
	public function __construct(UserPasswordHasherInterface $hasher)
	{
		$this->hasher = $hasher;
	}
    public function load(ObjectManager $manager): void
    {
	    $user = new User();
	    $user->setEmail('admin@localhost');
	    $user->setRoles(['ROLE_ADMIN']);
	    $password = $this->hasher->hashPassword($user, 'admin');
	    $user->setPassword($password);
	    $user->setName('admin');
	    $manager->persist($user);
	
	    $categories = array(
		    'T-shirts',
		    'Hoodies / Sweats',
		    'Knits',
		    'Vestes / Doudounes',
		    'Pantalons',
		    'Shorts',
		    'Chemises',
		    'Headwear',
		    'Accessoires',
		    'Skate',
	    );
	    foreach ($categories as $style) {
		    $category = new Category();
		    $category->setName($style);
		    $manager->persist($category);
	    }
			
			$manager->flush();
	
	   
    }
}
