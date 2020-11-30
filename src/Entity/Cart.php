<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;


class Cart extends AbstractController
{

    private $session;

    public function __construct(Request $request)
    {
        $this->session = $request->getSession();
    }


    public function addToCart($productId, $product)
    {
        if (empty($this->session->get($productId)))
        {
            $this->session->set($productId, ['product' => $product, 'quantity' => 1]);

        }
        else if ($this->session->get($productId)['product']->id == $productId)
        {
            $this->addQuantity($productId);
        }
    }

    public function addQuantity($productId)
    {
        $product = $this->session->get($productId);
        if($product != null)
        {
            //update quantity
            $product['quantity']++;
            $this->session->remove($productId);
            $this->session->set($productId, $product);
        }
    }

    public function removeQuantity($productId)
    {
        $product = $this->session->get($productId);
        if($product != null)
        {
            //update quantity
            $product['quantity']--;
            $this->session->remove($productId);
            $this->session->set($productId, $product);
        }
        if ($product['quantity'] <= 0)
        {
            $this->session->remove($productId);
        }
    }

    public function getCartProducts()
    {
        $inCart = [];
        foreach ($this->session->all() as $item) {
            if (is_array($item))
            {
                array_push($inCart, $item);
            }
        }
      return $inCart;
    }

    public function subtotal($products)
    {
        $subtotal = 0;
        foreach ($products as $product)
        {
            $subtotal += $product['product']->price * $product['quantity'];
        }
     return $subtotal;
    }

    public function XinCart()
    {
        $inCart = 0;
        $products = $this->getCartProducts();
        foreach ($products as $product)
        {
            $inCart += $product['quantity'];
        }
        return $inCart;
    }

    /**
     * @Route("/cart/item/remove", name="removeFromCart", methods={"GET"})
     * @param Request $request
     * @param ProductRepository $productRepository
     * @return RedirectResponse
     */
    public function removeFromCart(Request $request, ProductRepository $productRepository)
    {
        $productId = $request->query->get('product_id');
        $product = $productRepository->findOneBy(array('id' => $productId));
        $this->session->remove($product->getName());

        return $this->redirectToRoute('cart_index');
    }
}
