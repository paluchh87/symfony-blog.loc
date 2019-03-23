<?php

namespace App\Controller;

use App\Entity\Articles;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    protected $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * @Route("/login", name="login")
     */
    public function login(Request $request)
    {
        if (!empty($_POST)) {
            if ($request->get('login') == 'admin' && $request->get('password') == 'mysql') {
                $this->session->set('admin', true);

                return $this->redirectToRoute('admin');
            } else {
                $error = 'Error!!!';
            }
        }

        if ($this->session->get('admin') === true) {
            return $this->redirectToRoute('admin');
        }
        $this->session->set('admin', false);

//      dump(!empty($_POST));
//      die();

        return $this->render('admin/login.html.twig', ['error' => $error ?? null]);
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {
        $this->session->set('admin', false);

        return $this->redirectToRoute('login');
    }
}