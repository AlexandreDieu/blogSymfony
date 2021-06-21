<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\ArticleRepository;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

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
    public function show(Article $article, Request $request, UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {
        $comment = new Comment;
        $form = $this->createForm(CommentType::class, $comment);
        // $user = $userRepository->find(13);
        $user = $this->getUser();

        $form->handleRequest($request);
        if($form->isSubmitted() AND $form->isValid()){
            $comment = $form->getData();
            $comment->setCreatedAt(new DateTime());
            $comment->setArticle($article);
            $comment->setUser($user);
            $entityManager->persist($comment);
            $entityManager->flush();
            return $this->redirectToRoute('show', ['id'  => $article->getId()]);
        }
        
        return $this->render('main/_show.html.twig', [
            'article' => $article,
            'form' => $form->createView()
        ]);
    }
    /**
     * @Route("/admin", name="admin")
     */
    public function admin()
    {
        return $this->render("admin.html.twig");
    }
}
