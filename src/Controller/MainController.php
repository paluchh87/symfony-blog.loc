<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Repository\ArticlesRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="blognew")
     */
    public function index(Request $request, PaginatorInterface $paginator)
    {
        //$em = $this->getDoctrine()->getManager();
        //$em = $this->get('doctrine.orm.entity_manager');
        //$dql = "SELECT t0.id, t0.title, t0.text, t0.description, t0.alias FROM App\Entity\Articles t0";
        //$query = $em->createQuery($dql);
        $query = $this->getDoctrine()->getRepository(Articles::class)->getQuery();
        //$paginator  = $this->get('knp_paginator');
        $articles = $paginator->paginate($query, $request->query->getInt('page', 1), 2);

//        dump($articles);
//        die();

        return $this->render('blog/index.html.twig', ['articles' => $articles]);
    }

    /**
     * @Route("/post/{id}", name="post")
     */
    public function post(Articles $article)
    {
        return $this->render('blog/post.html.twig', ['data' => $article]);
    }

    /**
     * @Route("/about", name="about")
     */
    public function about()
    {
        return $this->render('blog/about.html.twig');
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function actionContact()
    {
//        if (!empty($_POST)) {
//            if (!$this->model->messageValidate($_POST)) {
//                $this->status = false;
//            } else {
//                if (mail('p.kobziev@gmail.com', 'Сообщение из блога',
//                    $_POST['name'] . '|' . $_POST['email'] . '|' . $_POST['text'])) {
//                    $this->status = true;
//                    $this->model->error = 'Сообщение отправлено Администратору';
//                } else {
//                    $this->status = false;
//                    $this->model->error = 'Ошибка отправки сообщения';
//                }
//            }
//        }
//
//        $vars = [
//            'status' => $this->status,
//            'message' => $this->model->error
//        ];
//        $this->view->render('Контакты', $vars);

        return $this->render('blog/contact.html.twig');
    }
}