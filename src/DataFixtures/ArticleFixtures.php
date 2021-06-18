<?php

namespace App\DataFixtures;

use Faker;
use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        //créer 3 catégories fakées
        for($i =1; $i <= 3; $i++) {
            $category = new Category;
            $category->setName($faker->sentence());

            $manager->persist($category);
            
            //créer entre 4 et 6 articles
            for($j = 1; $j <= mt_rand(4, 6); $j++){
                $article = new Article();

                $content = join($faker->paragraphs(5));

                $article->setTitle($faker->sentence())
                        ->setContent($content)
                        ->setStatus(true)
                        ->setImage($faker->imageUrl())
                        ->setCreatedAt($faker->dateTimeBetween('-6 months'))
                        ->setCategory($category);
                $manager->persist($article);

                //on donne des commentaires à l'article
                for($k =1; $k <= mt_rand (4, 10); $k++) {
                    $comment = new Comment();

                    $content = join($faker->paragraphs(2));

                    $days = (new \DateTime())->diff($article->getCreatedAt())->days;

                    $comment->setAuthor($faker->name)
                            ->setContent($content)
                            ->setCreatedAt($faker->dateTimeBetween('-' . $days . ' days'))
                            ->setArticle($article);

                            $manager->persist($comment);
                }
            }
        }
        

        $manager->flush();
    }
}