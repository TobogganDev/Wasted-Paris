<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
	    $categoryRepository = $manager->getRepository(Category::class);
	    $categories = $categoryRepository->findAll();
	    foreach ($categories as $category) {
		    for ($i = 0; $i < 20; $i++) {
			    $product = new Product();
			    $product->setName('Product ' . $i);
			    $product->setPrice(rand(100, 1000));
			    $product->setStock(rand(0, 100));
			    $product->setDescription('Lorem ipsum dolor sit amet, consectetur adipiscing elit.
				Sed euismod, nisl nec ultricies aliquam, nisl nisl aliquet nisl, nec aliquet nisl nisl nec nisl.
				Sed euismod, nisl nec ultricies aliquam, nisl nisl aliquet nisl, nec aliquet nisl nisl nec nisl.
				Sed euismod, nisl nec ultricies aliquam, nisl nisl aliquet nisl, nec aliquet nisl nisl nec nisl.');
			    $product->setCategory($category);
			    $product->setPictureUrl('https://cdn.shopify.com/s/files/1/0257/4952/0477/products/varsity-jacket-sick-329502_1296x.jpg?v=1674750119');
			    $manager->persist($product);
		    }
		
		    $manager->flush();
	    }
    }
}
