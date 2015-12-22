<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Message;
use AppBundle\Entity\User;
use AppBundle\Form\MessageType;
use AppBundle\Form\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\ClickableInterface;
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

        $list = $em->getRepository('AppBundle:Message')->findBy(array(), array('id' => 'DESC'));

        $message = new Message();
        $form = $this->createForm(new MessageType(), $message, array(
            'action' => $this->generateUrl('guestbook'),
            'method' => 'POST',
        ));
        $form->add('submit', 'submit', array('label' => 'Add message'));
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->persist($message);
            $em->flush();

            return $this->redirectToRoute('guestbook');
        }

        return $this->render('AppBundle::index.html.twig', array(
            'form' => $form->createView(),
            'list' => $list,
        ));
    }

    /**
     * User registration page
     *
     * @Route("/register", name="register")
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function registerAction(Request $request)
    {
        $user = new User();

        $form = $this->createForm(new UserType(), $user, array(
            'action' => $this->generateUrl('register'),
            'method' => 'POST',
        ));
        $form->add('submit', 'submit', array('label' => 'Register'));
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('guestbook');
        }

        return $this->render('AppBundle::register.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
