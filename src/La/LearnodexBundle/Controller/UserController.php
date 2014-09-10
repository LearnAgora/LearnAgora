<?php
/**
 * Created by PhpStorm.
 * User: bart
 * Date: 8/22/14
 * Time: 4:16 PM
 */

namespace La\LearnodexBundle\Controller;

use La\CoreBundle\Entity\User;
use La\LearnodexBundle\Forms\RegistrationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContextInterface;


class UserController extends Controller{

    public function loginAction(Request $request)
    {
        $session = $request->getSession();

        // get the login error if there is one
        if ($request->attributes->has(SecurityContextInterface::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(
                SecurityContextInterface::AUTHENTICATION_ERROR
            );
        } elseif (null !== $session && $session->has(SecurityContextInterface::AUTHENTICATION_ERROR)) {
            $error = $session->get(SecurityContextInterface::AUTHENTICATION_ERROR);
            $session->remove(SecurityContextInterface::AUTHENTICATION_ERROR);
        } else {
            $error = '';
        }

        // last username entered by the user
        $lastUsername = (null === $session) ? '' : $session->get(SecurityContextInterface::LAST_USERNAME);

        return $this->render(
            'LaLearnodexBundle:User:login.html.twig',
            array(
                // last username entered by the user
                'last_username' => $lastUsername,
                'error'         => $error,
            )
        );
    }

    public function registerAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(new RegistrationType(), $user);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $user->setIsActive(1);
            $factory = $this->get('security.encoder_factory');
            $encoder = $factory->getEncoder($user);
            $password = $encoder->encodePassword($user->getPassword(), $user->getSalt());
            $user->setPassword($password);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirect($this->generateUrl('login'));
        }

        return $this->render('LaLearnodexBundle:User:register.html.twig',array('form'=>$form->createView()));
    }
}