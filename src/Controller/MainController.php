<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Category;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    /**
     * @Route("/main", name="main")
     */
    public function index(ArticleRepository $articleRepository): Response
    {
        $articles = $articleRepository->findAll();
        dump($articles);
        return $this->render('main/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    /**
     * @Route("/blog/{id}", name="show")
     */
    public function show(Article $article)
    {
        return $this->render('main/_show.html.twig', [
            'article' => $article,
        ]);
    }
}
