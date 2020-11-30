<?php

namespace App\Controller;

use App\Entity\UserInfo;
use App\Form\UserInfoType;
use App\Repository\UserInfoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user/info")
 */
class UserInfoController extends AbstractController
{
    /**
     * @Route("/", name="user_info_index", methods={"GET"})
     * @param UserInfoRepository $userInfoRepository
     * @return Response
     */
    public function index(UserInfoRepository $userInfoRepository): Response
    {
        return $this->render('user_info/index.html.twig', [
            'user_infos' => $userInfoRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="user_info_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $userInfo = new UserInfo();
        $userInfo->setUser($this->getUser());
        $form = $this->createForm(UserInfoType::class, $userInfo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($userInfo);
            $entityManager->flush();

            return $this->redirectToRoute('user_info_index');
        }

        return $this->render('user_info/new.html.twig', [
            'user_info' => $userInfo,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_info_show", methods={"GET"})
     */
    public function show(UserInfo $userInfo): Response
    {
        return $this->render('user_info/show.html.twig', [
            'user_info' => $userInfo,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="user_info_edit", methods={"GET","POST"})
     * @param Request $request
     * @param UserInfo $userInfo
     * @return Response
     */
    public function edit(Request $request, UserInfo $userInfo): Response
    {
        $form = $this->createForm(UserInfoType::class, $userInfo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('homepage');
        }

        return $this->render('user_info/edit.html.twig', [
            'user_info' => $userInfo,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_info_delete", methods={"DELETE"})
     */
    public function delete(Request $request, UserInfo $userInfo): Response
    {
        if ($this->isCsrfTokenValid('delete'.$userInfo->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($userInfo);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_info_index');
    }
}
