<?php

namespace App\Controller;

use App\Entity\OrderList;
use App\Form\ProductType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    /**
     * @Route("/myorders", name="myorders", methods={"GET"})
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        return $this->render('orders/index.html.twig', [
        ]);
    }

    /**
     * @Route("/createOrder", name="createOrder", methods={"GET"})
     * @param Request $request
     * @return Response
     */
    public function createOrder(Request $request)
    {
        $order = new OrderList();
        $form = $this->createForm(ProductType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($order);
            $entityManager->flush();

            return $this->redirectToRoute('myorders');
        }

        return $this->render('orders/createOrder.html.twig', [
    ]);
    }
}
