<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Post;
use AppBundle\Form\Type\PostType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $post = new Post();
        $form = $this->createForm(new PostType(), $post);
        $em = $this->get('doctrine.orm.entity_manager');

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $em->persist($form->getData());
                $em->flush();

                return $this->redirect($this->generateUrl('homepage'));
            }
        }

        return $this->render('AppBundle::index.html.twig', array(
            'form' => $form->createView(),
            'posts' => $em->getRepository('AppBundle:Post')->findAll()
        ));
    }
}
