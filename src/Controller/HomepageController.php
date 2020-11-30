<?php

namespace App\Controller;


use App\Entity\Cart;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController
{
    /**
     * @Route("/homepage", name="homepage")
     * @param ProductRepository $productRepository
     * @return Response
     */
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('homepage/index.html.twig', [
            'controller_name' => 'HomepageController',
            'products' => $productRepository->findAll(),
        ]);
    }


    /**
     * @Route("/homepage/search/", name="homepageSearch")
     * @param ProductRepository $productRepository
     * @param CategoryRepository $categoryRepository
     * @return Response
     */
    public function indexSearch(ProductRepository $productRepository, CategoryRepository $categoryRepository): Response
    {
        $slug = $_POST['searchFor'];
        $products = $productRepository->findAll();
        $categories = $categoryRepository->findAll();
        foreach ($products as $product) {
            if ($product->getName() == $slug)
            {
                return $this->render('homepage/index.html.twig', [
                    'controller_name' => 'HomepageController',
                    'products' => $productRepository->findBy(array('name' => $slug)),
                ]);
            }
        }
        foreach ($categories as $category) {
            if ($category->getName() == $slug)
            $slug = $categoryRepository->findOneBy(array('name' => $slug));
            {
                return $this->render('homepage/index.html.twig', [
                    'controller_name' => 'HomepageController',
                    'products' => $productRepository->findBy(array('category' => $slug))
                ]);
            }
        }

        return $this->render('homepage/index.html.twig', [
            'controller_name' => 'HomepageController',
            'products' => $productRepository->findAll(),
        ]);
    }


    /**
     * @Route("/cart/", name="cart_index")
     * @param Request $request
     * @return Response
     */
    public function cart_index(Request $request)
    {
        $cart = new Cart($request);
        $products = $cart->getCartProducts();
        $subtotal = $cart->subtotal($products);

        return $this->render('cart/index.html.twig',[
            'products' => $products,
            'subtotal' => $subtotal,
        ]);
    }
}
