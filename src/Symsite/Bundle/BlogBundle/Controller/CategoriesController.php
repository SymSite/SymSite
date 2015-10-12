<?php

namespace Symsite\Bundle\BlogBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symsite\Bundle\BlogBundle\Entity\Category;
use Symsite\Bundle\BlogBundle\Form\CategoryType;

class CategoriesController extends Controller
{
    /**
     * @Route("/categories", name="categories")
     * @Method({"GET", "POST"})
     */
    public function indexAction(Request $request)
    {
        $category = new Category();
        $form = $this->createForm(new CategoryType(), $category, [
          'action' => $this->generateUrl('categories'),
          'method' => 'POST'
        ]);

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($category);
                $em->flush();

                $t = $this->get('translator');
                $message = $t->trans('The %entity% has been saved.', ['%entity%' => $t->trans('Category')]);
                $this->addFlash('success', $message);

                return $this->redirect($this->generateUrl('categories'));
            }
        }

        $page = (int)$request->query->get('page', 1);
        $sortKey = $request->query->get('sort', 'c.name');
        $direction = $request->query->get('direction', 'asc');

        $qb = $this->getDoctrine()->getRepository(Category::class)
          ->qbFindWithCountArticle()
          ->orderBy($sortKey, $direction);

        $entities = $this->get('knp_paginator')->paginate($qb, $page, 20);

        return $this->render(
          '@SymsiteBlog/Categories/index.html.twig',
          ['entities' => $entities, 'form' => $form->createView()]
        );
    }

    /**
     * @Route("/categories/delete", name="categories_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request)
    {
        $delete_items = $request->request->get('delete_items');

        if (count($delete_items) > 0) {
            $result = $this->getDoctrine()->getRepository(Category::class)
              ->qbDeleteByIds($delete_items)
              ->getQuery()
              ->execute();

            $t = $this->get('translator');
            if ($result > 0) {
                $message = $t->trans('%count% %entity% has been deleted.', [
                  '%count%' => $result,
                  '%entity%' => $t->trans('Category')
                ]);
                $this->addFlash('success', $message);
            } else {
                $message = $t->trans('The %entity% could not be deleted.', ['%entity%' => 'Category']);
                $this->addFlash('danger', $message);
            }
        }

        return $this->redirect($this->generateUrl('categories'));
    }
}
