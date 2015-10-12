<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symsite\Bundle\BlogBundle\Entity\Article;

class PageController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        return $this->render('home.html.twig');
    }

    /**
     * @Route("/{page}", name="pages", requirements={ "page": "^[^_].+" })
     * @Method("GET")
     */
    public function pageAction(Request $request, $page)
    {
        if (! $this->get('templating')->exists("{$page}.html.twig")) {
            throw $this->createNotFoundException("Page Not Found");
        }
        return $this->render("{$page}.html.twig");
    }
}
