<?php

namespace App\Controller;

use App\Entity\Search;
use App\Entity\Product;
use App\Form\SearchType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SearchController extends AbstractController
{

        public function SearchAction(Request $request)
        {
            $sform = $this->createForm(SearchType::class,null, ['action' => $this->generateUrl('search_product')]);

            return $this->render('search/index.html.twig', [
                'sform' => $sform->createView(),
            ]);
        }

        /**
         * @Route("/search", name="search_product")
         */
        public function handleSearch(Request $request){
            $form=$request->request->get('search');

            $prods= [];
                //search by price
                $Search_Min = $form['minPrice'];   
                $Search_Max = $form['maxPrice']; 
                if (($Search_Min!=null) and ($Search_Max!= null)) {
                    $prods= array_merge($prods+ $this->getDoctrine()->getRepository(Product::class)->findByPriceRange($Search_Min,$Search_Max));
                }
                //search by name using (LIKE)
                $Search_name = $form['name'];   
                if ($Search_name!= null ) {
                    $prods= array_merge($prods, $this->getDoctrine()->getRepository(Product::class)->findByName($Search_name));
                }
                //search by Category
                $Search_category = $form['category'];
                if ($Search_category!=null) {
                    $prods= array_merge($prods, $this->getDoctrine()->getRepository(Product::class)->findByExampleField($Search_category));
                }   
                return $this->render('search/results.html.twig', [
                    'prods' => $prods,
                    ]);        
        }

}
