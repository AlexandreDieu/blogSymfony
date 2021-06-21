<?php

namespace App\DataFixtures;

use Faker;
use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\Category;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ArticleFixtures extends Fixture
{
    private UserPasswordEncoderInterface $encoder;

    public function __construct(UserPasswordEncoderInterface $userPasswordEncoderInterface)
    {
        $this->encoder = $userPasswordEncoderInterface;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        $user = new User();
        $user->setEmail("toto@toto.fr")
            ->setRoles(['ROLE_USER']);
        $hash = $this->encoder->encodePassword($user, "toto");
        $user->setPassword($hash);
        $manager->persist($user);

        $admin = new User();
        $admin->setEmail("admin@admin.fr");
        $admin->setRoles(['ROLE_ADMIN']);
        $hash = $this->encoder->encodePassword($admin, "admin");
        $admin->setPassword($hash);
        $manager->persist($admin);

        //créer 3 catégories fakées
        for ($i = 1; $i <= 3; $i++) {
            $category = new Category;
            $category->setName($faker->sentence());
            $manager->persist($category);




            //créer entre 4 et 6 articles
            for ($j = 1; $j <= mt_rand(4, 6); $j++) {
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
                for ($k = 1; $k <= mt_rand(4, 10); $k++) {
                    $comment = new Comment();

                    $content = join($faker->paragraphs(2));

                    $days = (new \DateTime())->diff($article->getCreatedAt())->days;

                    $comment->setUser($user)
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
