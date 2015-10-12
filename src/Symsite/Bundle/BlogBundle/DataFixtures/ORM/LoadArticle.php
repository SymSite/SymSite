<?php

namespace Symsite\Bundle\BlogBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symsite\Bundle\BlogBundle\Entity\Article;
use Carbon\Carbon;

class LoadArticle implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $statuses = Article::getStatuses();

        for($i = 1; $i < 100; $i++) {
            $article = new Article();
            $article->setTitle(sprintf("Title: %03d", $i));
            $article->setBody("Foo\nBar\n$i\n");
            $article->setStatus($statuses[array_rand($statuses)]);
            $article->setPublishedAt(Carbon::today());

            $manager->persist($article);
        }
        $manager->flush();
    }
}