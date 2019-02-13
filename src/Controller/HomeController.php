<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home()
    {
        return $this->render('home/home.html.twig');
    }

    /**
     * @Route("/chat", name="chat")
     */
    public function chat()
    {
        return $this->render('home/chat.html.twig');
    }

    /**
     * @Route("/cv", name="cv")
     */
    public function cv()
    {
        return $this->render('home/cv.html.twig');
    }
}