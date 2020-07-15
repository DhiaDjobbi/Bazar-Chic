<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Product;
use App\Entity\Category;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * @Route("/product")
 */
class ProductController extends AbstractController
{
    /**
     * @IsGranted("ROLE_USER")
     * @Route("/", name="product_index", methods={"GET"})
     */
    public function index(ProductRepository $productRepository): Response
    {

            if(sizeof($this->getUser()->getRoles())==2){
                $prods=$productRepository->findAll();
            }else{
                $prods=$productRepository->findBy(['user' => $this->getUser()]);
            }
        return $this->render('product/index.html.twig', [
            'products' => $prods,
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/new", name="product_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->add('category_id',EntityType::class,[
            'class'=>Category::class,
            'choice_label'=>'name',
            'mapped' => false]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $product->setUser($this->getUser());
            if ($product->getPdescription()==null) {
                $product->setPdescription("There is no description for this product.");
            }
            $product->setCategory($form['category_id']->getData()->getName());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($product);
            $entityManager->flush();

            //images uploading
            $up=$this->getParameter('upload_directory2');
            $files= $request->files->get('product')['pictures'];
            foreach ($files as $file)
            {
                $image = new Image();
                $fileName=md5(uniqid()).'.'.$file->guessExtension();
                $file->move($up,$fileName);
                $image->setImg($fileName);
                $image->setProduct($product);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($image);
                $entityManager->flush();
            }

            return $this->redirectToRoute('product_index');
        }

        return $this->render('product/new.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="product_show", methods={"GET"})
     */
    public function show(Product $product): Response
    {
        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/{id}/edit", name="product_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Product $product): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {



            $this->getDoctrine()->getManager()->flush();

            //images uploading
            $up=$this->getParameter('upload_directory2');
            $files= $request->files->get('product')['pictures'];
            foreach ($files as $file)
            {
                $image = new Image();
                $fileName=md5(uniqid()).'.'.$file->guessExtension();
                $file->move($up,$fileName);
                $image->setImg($fileName);
                $image->setProduct($product);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($image);
                $entityManager->flush();
            }

            return $this->redirectToRoute('product_index');
        }

        return $this->render('product/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="product_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Product $product): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($product);
            $entityManager->flush();
        }

        return $this->redirectToRoute('product_index');
    }
}
