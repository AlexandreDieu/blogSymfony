<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/main", name="main")
     */
    public function index(): Response
    {

        $article1 = [
            'title' => 'Un titre 1',
            'text' => 'Du contenu avec un peu de texte'
        ];
        $article2 = [
            'title' => 'Un titre 2',
            'text' => 'Du contenu avec un peu de texte'
        ];
        $articles = [$article1, $article2];
        return $this->render('main/index.html.twig', [
            'articles' => $articles,
        ]);
    }
}
