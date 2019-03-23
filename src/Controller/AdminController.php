<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Form\ArticlesType;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class AdminController extends AbstractController
{
    protected $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * @Route("/admin", name="admin")
     */
    public function index()
    {
        if ($this->session->get('admin') === false) {
            return $this->redirectToRoute('login');
        }

        return $this->render('admin/index.html.twig');
    }

    /**
     * @Route("/admin/add", name="admin_add")
     */
    public function add(Request $request)
    {
        if ($this->session->get('admin') === false) {
            return $this->redirectToRoute('login');
        }

        $form = $this->createForm(ArticlesType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($data);
            $em->flush();

            $this->addFlash('success', 'Saved');

            return $this->redirectToRoute('admin_add');
        }

        return $this->render('admin/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/edit/{id}", name="admin_edit")
     */
    public function edit(Request $request, Articles $article)
    {
        if ($this->session->get('admin') === false) {
            return $this->redirectToRoute('login');
        }

        $form = $this->createForm(ArticlesType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($data);
            $em->flush();

            $this->addFlash('success', ' Updated');
        }

        return $this->render('admin/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/delete/{id}", name="admin_delete")
     */
    public function delete(Articles $article)
    {
        if ($this->session->get('admin') === false) {
            return $this->redirectToRoute('login');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($article);
        $em->flush();

        $this->addFlash('success', ' Deleted');

        return $this->redirectToRoute('admin');
    }

    /**
     * @Route("/admin/posts", name="admin_posts")
     */
    public function posts(Request $request, PaginatorInterface $paginator)
    {
        if ($this->session->get('admin') === false) {
            return $this->redirectToRoute('login');
        }

        $em = $this->getDoctrine()->getManager();
        //$em = $this->get('doctrine.orm.entity_manager');
        $dql = "SELECT t0.id, t0.title, t0.text, t0.description, t0.alias FROM App\Entity\Articles t0";
        $query = $em->createQuery($dql);
        //$query = $em;
        //$paginator  = $this->get('knp_paginator');
        $articles = $paginator->paginate($query, $request->query->getInt('page', 1), 4);

//        dump($articles);
//        die();

        return $this->render('admin/posts.html.twig', ['articles' => $articles]);
    }
}