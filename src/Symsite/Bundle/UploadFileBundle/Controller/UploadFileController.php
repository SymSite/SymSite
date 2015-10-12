<?php

namespace Symsite\Bundle\UploadFileBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Form;
use Symsite\Bundle\UploadFileBundle\Entity\UploadFile;
use Symsite\Bundle\UploadFileBundle\Form\UploadFileType;

class UploadFileController extends Controller
{
    /**
     * @Route("/upload_files", name="upload_files")
     * @Method({"GET", "POST"})
     */
    public function indexAction(Request $request)
    {
        $upload_file = new UploadFile();
        $form = $this->createForm(new UploadFileType(), $upload_file, [
          'action' => $this->generateUrl('upload_files'),
          'method' => 'POST'
        ]);

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            $this->createUploadFile($form, $upload_file);
        }

        $page = (int)$request->query->get('page', 1);
        $sortKey = $request->query->get('sort', 'u.createdAt');
        $direction = $request->query->get('direction', 'desc');

        $qb = $this->getDoctrine()->getRepository(UploadFile::class)
            ->createQueryBuilder('u')
            ->orderBy($sortKey, $direction);

        $entities = $this->get('knp_paginator')->paginate($qb, $page, 12);

        return $this->render(
          '@SymsiteUploadFile/UploadFile/index.html.twig',
          ['entities' => $entities, 'form' => $form->createView()]
        );
    }

    private function createUploadFile(Form $form, UploadFile $upload_file)
    {
        $t = $this->get('translator');

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($upload_file);
            $em->flush();

            $message = $t->trans('The file has been uploaded.');
            $this->addFlash('success', $message);
        } else {
            $message = $t->trans('The file could not be uploaded.');
            $this->addFlash('danger', $message);
        }
    }

    /**
     * @Route("/upload_files/api/upload", name="upload_files_api_upload")
     * @Method("POST")
     */
    public function uploadAction(Request $request)
    {
        $t = $this->get('translator');

        $upload_file = new UploadFile();
        $form = $this->createForm(new UploadFileType(), $upload_file);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($upload_file);
            $em->flush();

            return new Response($t->trans('The file has been uploaded.'), 200);
        }

        $message =  $t->trans('The file could not be uploaded.');
        if (count($form->getErrors()) > 0) {
            $message = $form->getErrors()[0]->getMessage();
        }

        return new Response($message, 400);
    }

    /**
     * @Route("/upload_files", name="upload_files_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request)
    {
        $delete_items = $request->request->get('delete_items');

        if (count($delete_items) > 0) {
            $em = $this->getDoctrine()->getManager();
            $upload_files = $em->getRepository(UploadFile::class)
                ->qbFindByIds($delete_items)
                ->getQuery()
                ->execute();

            $cnt = 0;
            foreach ($upload_files as $upload_file) {
                $em->remove($upload_file);
                $cnt++;
            }
            $em->flush();

            $t = $this->get('translator');
            $message = $t->trans('%count% %entity% has been deleted.', [
              '%count%' => $cnt,
              '%entity%' => $t->trans('File')
            ]);
            $this->addFlash('success', $message);
        }

        return $this->redirect($this->generateUrl('upload_files'));
    }
}
