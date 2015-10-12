<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symsite\Bundle\BlogBundle\Entity\Article;

class BlogController extends Controller
{
    /**
     * @Route("/blog", name="blog")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $page = (int)$request->query->get('page', 1);

        $qb = $this->getDoctrine()->getRepository(Article::class)
          ->qbFindWithCategoryBy()
          ->orderBy('a.publishedAt', 'desc')
          ->addOrderBy('a.title', 'asc');

        $entities = $this->get('knp_paginator')->paginate($qb, $page, 20);

        return $this->render('blog/index.html.twig', compact('entities'));
    }

    /**
     * @Route("/blog/{id}", name="blog_show")
     * @Method("GET")
     */
    public function showAction(Request $request, Article $article)
    {
        return $this->render('blog/show.html.twig', compact('article'));
    }

}
