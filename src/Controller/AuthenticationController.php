<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AuthenticationController extends AbstractController
{
    /**
     * @Route("/login",name="user_login")
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {
        if($this->isGranted('ROLE_USER')){
            return $this->redirectToRoute('user_logout');
            
        } else{
        $error=$authenticationUtils->getLastAuthenticationError();
        $lastUsername=$authenticationUtils->getLastUsername();
        return $this->render('authentication/login.html.twig',[
            'error'=>$error,
            'lastUsername'=>$lastUsername,
        ]);
    }}

    /**
     * @Route("/logout",name="user_logout")
     */
    public function logout()
    { }

    /**
     * @Route("/register", name="user_register", methods={"GET","POST"})
     */
    public function register(Request $request,UserPasswordEncoderInterface $encoder): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user, ['validation_groups' => ['register'], ]);
        $form ->remove("description");
        $form ->remove("phone");
        $form ->remove("website");
        $form ->remove("facebook");
        $form ->remove("picture");

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $hash = $encoder->encodePassword($user,$user->getPassword());
            $user->setPassword($hash);

            // defaults values (user can edit them later)
            $user->setDescription("Apparently, this User prefers to keep an air of mystery about them.");
            $user->setPhone(null);
            $user->setPicture("default.png");
            $user->setWebsite(null);
            $user->setFacebook(null);
            $user->setRoles(["ROLE_USER"]);
            // end default values
        
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            

            return $this->redirectToRoute('user_login');
    }
        return $this->render('authentication/register.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }





}
