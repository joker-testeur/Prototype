<?php

namespace App\Controller;

use App\Entity\Basic;
use App\Form\BasicType;
use App\Repository\BasicRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/basic")
 */
class BasicController extends AbstractController
{
    /**
     * @Route("/", name="basic_index", methods={"GET"})
     */
    public function index(BasicRepository $basicRepository): Response
    {
        return $this->render('basic/index.html.twig', [
            'basics' => $basicRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="basic_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $basic = new Basic();
        $form = $this->createForm(BasicType::class, $basic);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($basic);
            $entityManager->flush();

            return $this->redirectToRoute('basic_index');
        }

        return $this->render('basic/new.html.twig', [
            'basic' => $basic,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="basic_show", methods={"GET"})
     */
    public function show(Basic $basic): Response
    {
        return $this->render('basic/show.html.twig', [
            'basic' => $basic,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="basic_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Basic $basic): Response
    {
        $form = $this->createForm(BasicType::class, $basic);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('basic_index');
        }

        return $this->render('basic/edit.html.twig', [
            'basic' => $basic,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="basic_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Basic $basic): Response
    {
        if ($this->isCsrfTokenValid('delete'.$basic->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($basic);
            $entityManager->flush();
        }

        return $this->redirectToRoute('basic_index');
    }
}
