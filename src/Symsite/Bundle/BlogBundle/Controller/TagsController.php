<?php

namespace Symsite\Bundle\BlogBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symsite\Bundle\BlogBundle\Entity\Tag;
use Symsite\Bundle\BlogBundle\Form\TagType;

class TagsController extends Controller
{
    /**
     * @Route("/tags", name="tags")
     * @Method({"GET", "POST"})
     */
    public function indexAction(Request $request)
    {
        $tag = new Tag();
        $form = $this->createForm(new TagType(), $tag, [
          'action' => $this->generateUrl('tags'),
          'method' => 'POST'
        ]);

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($tag);
                $em->flush();

                $t = $this->get('translator');
                $message = $t->trans('The %entity% has been saved.', ['%entity%' => $t->trans('Tag')]);
                $this->addFlash('success', $message);

                return $this->redirect($this->generateUrl('tags'));
            }
        }

        $page = (int)$request->query->get('page', 1);
        $sortKey = $request->query->get('sort', 't.name');
        $direction = $request->query->get('direction', 'asc');

        $qb = $this->getDoctrine()->getRepository(Tag::class)
          ->qbFindWithCountArticle()
          ->orderBy($sortKey, $direction);

        $entities = $this->get('knp_paginator')->paginate($qb, $page, 20);

        return $this->render(
          '@SymsiteBlog/Tags/index.html.twig',
          ['entities' => $entities, 'form' => $form->createView()]
        );
    }

    /**
     * @Route("/tags/delete", name="tags_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request)
    {
        $delete_items = $request->request->get('delete_items');

        if (count($delete_items) > 0) {
            $result = $this->getDoctrine()->getRepository(Tag::class)
              ->qbDeleteByIds($delete_items)
              ->getQuery()
              ->execute();

            $t = $this->get('translator');
            if ($result > 0) {
                $message = $t->trans('%count% %entity% has been deleted.', [
                  '%count%' => $result,
                  '%entity%' => $t->trans('Tag')
                ]);
                $this->addFlash('success', $message);
            } else {
                $message = $t->trans('The %entity% could not be deleted.', ['%entity%' => 'Tag']);
                $this->addFlash('danger', $message);
            }
        }

        return $this->redirect($this->generateUrl('tags'));
    }

}
