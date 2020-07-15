<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomepageController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index(CategoryRepository $categoryRepository,ProductRepository $productRepository)
    {
        $category=$categoryRepository->findAll();
        shuffle($category);
        $prods=$productRepository->findAll();
        shuffle($prods);

        return $this->render('homepage/index.html.twig', [
            'categories' => $category,
            'prods' => $prods,
        ]);
    }
}
