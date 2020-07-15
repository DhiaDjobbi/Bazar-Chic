<?php

namespace App\Entity;


class Search
{

   /**
    * @var string|null   
    */  
    private $category;


    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }
    

   /**
    * @var int|null   
    */  
   private $minPrice;

   /**
    * @var int|null   
    */ 
   private $maxPrice;

   
   public function getMinPrice(): ?int
   {
       return $this->minPrice;
   }

   public function setMinPrice(int $minPrice): self
   {
       $this->minPrice = $minPrice;

       return $this;
   }

   public function getMaxPrice(): ?int
   {
       return $this->maxPrice;
   }

   public function setMaxPrice(int $maxPrice): self
   {
       $this->maxPrice = $maxPrice;

       return $this;
   }

   /**
    * @var string|null   
    */  
   
   private $name;

   
   public function getName(): ?string
   {
       return $this->name;
   }

   public function setName(string $name): self
   {
       $this->name = $name;

       return $this;
   }


}