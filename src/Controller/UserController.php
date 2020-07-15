<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/", name="user_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/new", name="user_new", methods={"GET","POST"})
     */
    public function new(Request $request,UserPasswordEncoderInterface $encoder): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user, ['validation_groups' => ['register'] ]);
        $form ->remove("phone");
        $form ->remove("website");
        $form ->remove("facebook");
        $form ->remove("description");

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            
            $file=$user->getPicture();
            if ($file!=null)
            {
                $fileName=md5(uniqid()).'.'.$file->guessExtension();
                $file->move($this->getParameter('upload_directory'),$fileName);
                $user->setPicture($fileName);
            } 
            else {
                $user->setPicture("default.png");
            }
            
            $user->setPhone(null);
            $user->setWebsite(null);
            $user->setFacebook(null);
            $user->setRoles(["ROLE_ADMIN"]);

            $hash = $encoder->encodePassword($user,$user->getPassword());
            $user->setPassword($hash);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user, 
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/{id}/edit", name="user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user,['validation_groups' => ['edit']]);
        $form ->remove("password2");
        $form ->remove("password");
        $original_pic=$user->getPicture();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $file=$user->getPicture();
            if (($file!=null) and ($file!=$original_pic))
            {
                $fileName=md5(uniqid()).'.'.$file->guessExtension();
                $file->move($this->getParameter('upload_directory'),$fileName);
                $user->setPicture($fileName);
            } 
            else {
                $user->setPicture($original_pic);
            }

            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('dashboard');
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_delete", methods={"DELETE"})
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_index');
    }
}
