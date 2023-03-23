<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use App\Services\Cart\CartServices;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{
	#[Route('/', name: 'app_index')]
	public function index(): Response
	{
		return $this->render('app/index.html.twig', [
			'controller_name' => 'AppController',
		]);
	}
	
	#[Route('/shop', name: 'app_shop')]
	public function shop(CategoryRepository $categoryRepository): Response
	{
		
		return $this->render('app/shop.html.twig', [
			'categories' => $categoryRepository->findAll(),
		]);
	}
	
	#[Route('/category/{id}', name: 'app_category')]
	public function category(CategoryRepository $categoryRepository, $id): Response
	{
		$category = $categoryRepository->find($id);
		
		if (!$category) {
			throw $this->createNotFoundException('Category not found');
		}
		
		return $this->render('app/category.html.twig', [
			'category' => $category,
		]);
	}
	
	#[Route('/product/{id}', name: 'app_product')]
	public function product(ProductRepository $productRepository, $id): Response
	{
		$product = $productRepository->find($id);
		
		if (!$product) {
			throw $this->createNotFoundException('Category not found');
		}
		
		return $this->render('app/product.html.twig', [
			'product' => $product,
		]);
	}
	
	#[Route('/cart', name: 'app_cart')]
	public function cart(SessionInterface $session, ProductRepository $productRepository): Response
	{
		
		$cart = $session->get('cart', []);
		$cartWithData = [];
		
		foreach ($cart as $id => $quantity) {
			$cartWithData[] = [
				'product' => $productRepository->find($id),
				'quantity' => $quantity
			];
		}
		
		return $this->render('app/cart.html.twig', [
			'items' => $cartWithData,
		]);
	}
	
	#[Route('/cart/add/{id}', name: 'app_cart_add')]
	public function cartAdd($id, SessionInterface $session, ProductRepository $productRepository, EntityManagerInterface $entityManager): Response
	{
		$cart = $session->get('cart', []);
		if (!empty($cart[$id])) {
			$cart[$id]++;
		} else {
			$cart[$id] = 1;
		}
		$session->set('cart', $cart);
		$product = $productRepository->find($id);
		$product->setStock($product->getStock() - 1);
		
		$entityManager->persist($product);
		$entityManager->flush();
		return $this->redirectToRoute('app_product', ['id' => $id]);
	}
	
	#[Route('/cart/remove/{id}', name: 'app_cart_remove')]
	public function cartRemove($id, SessionInterface $session, ProductRepository $productRepository): Response
	{
		$cart = $session->get('cart', []);
		if (!empty($cart[$id])) {
			unset($cart[$id]);
		}
		$session->set('cart', $cart);
		return $this->redirectToRoute('app_cart');
	}
}
