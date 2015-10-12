<?php

namespace Symsite\Bundle\BlogBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Httpfoundation\Request;
use Symsite\Bundle\BlogBundle\Entity\Article;
use Symsite\Bundle\BlogBundle\Form\ArticleType;

class ArticlesController extends Controller
{
    /**
     * @Route("/articles", name="articles")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $page = (int)$request->query->get('page', 1);
        $sortKey = $request->query->get('sort', 'a.publishedAt');
        $direction = $request->query->get('direction', 'desc');

        $qb = $this->getDoctrine()->getRepository(Article::class)
          ->qbFindWithCategoryBy()
          ->orderBy($sortKey, $direction)
          ->addOrderBy('a.title', 'asc');

        $entities = $this->get('knp_paginator')->paginate($qb, $page, 20);

        return $this->render('@SymsiteBlog/Articles/index.html.twig', compact('entities'));
    }

    /**
     * @Route("/articles/new", name="articles_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $article = new Article();
        $article->setPublishedAt(new \DateTime());

        $form = $this->createForm(new ArticleType(), $article, [
            'action' => $this->generateUrl('articles_new'),
            'method' => 'POST'
        ]);

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($article);
                $em->flush();

                $t = $this->get('translator');
                $message = $t->trans('The %entity% has been saved.', ['%entity%' => $t->trans('Article')]);
                $this->addFlash('success', $message);

                return $this->redirect($this->generateUrl('articles'));
            }
        }

        return $this->render('@SymsiteBlog/Articles/new.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/articles/{id}/edit", name="articles_edit")
     * @Method({"GET", "PUT"})
     */
    public function editAction(Request $request, Article $article)
    {
        $form = $this->createForm(new ArticleType(), $article, [
          'action' => $this->generateUrl('articles_edit', ['id' => $article->getId()]),
          'method' => 'PUT'
        ]);

        if ($request->getMethod() == 'PUT') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $this->getDoctrine()->getManager()->flush();

                $t = $this->get('translator');
                $message = $t->trans('The %entity% has been saved.', ['%entity%' => $t->trans('Article')]);
                $this->addFlash('success', $message);

                return $this->redirect($this->generateUrl('articles'));
            }
        }

        return $this->render('@SymsiteBlog/Articles/edit.html.twig', [
          'article' => $article,
          'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/articles/delete", name="articles_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request)
    {
        $delete_items = $request->request->get('delete_items');

        if (count($delete_items) > 0) {
            $result = $this->getDoctrine()->getRepository(Article::class)
              ->qbDeleteByIds($delete_items)
              ->getQuery()
              ->execute();

            $t = $this->get('translator');
            if ($result > 0) {
                $message = $t->trans('%count% %entity% has been deleted.', [
                  '%count%' => $result,
                  '%entity%' => 'Article',
                ]);
                $this->addFlash('success', $message);
            } else {
                $message = $t->trans('The %entity% could not be deleted.', ['%entity%' => 'Article']);
                $this->addFlash('danger', $message);
            }
        }

        return $this->redirect($this->generateUrl('articles'));
    }

}
