<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Message;
use AppBundle\Form\MessageType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class DefaultController
 *
 * @package AppBundle\Controller
 */
class DefaultController extends Controller
{
    /**
     * Guestbook. List of messages. New message form.
     *
     * @Route("/", name="guestbook")
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $list = $em->getRepository("AppBundle:Message")->findBy(array(), array("id" => "DESC"));

        $message = new Message();
        $form = $this->createForm(new MessageType(), $message, array(
            "action" => $this->generateUrl("guestbook"),
            "method" => "POST",
        ));
        $form->add("submit", "submit", array("label" => "Add message"));
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->persist($message);
            $em->flush();

            return $this->redirectToRoute("guestbook");
        }

        return $this->render("AppBundle::index.html.twig", array(
            'form' => $form->createView(),
            'list' => $list,
        ));
    }

}
