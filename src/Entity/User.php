<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;


/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(
 *     fields={"username"},
 *     message="The username is already used.",
 *     groups={"register", "edit"},
 * )
 * @UniqueEntity(
 *     fields={"email"},
 *     message="The Email is already used.",
 *     groups={"register", "edit"},
 * )
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(groups={"register", "edit"})
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255 , unique=true)
     * @Assert\NotBlank(groups={"register"})
     * @Assert\Length(
     *      min = 8, max = 30,
     *      minMessage = "Your password must be between 6 and 30 characters.", groups={"register"})
     * @Assert\EqualTo(propertyPath = "password2", message="Passwords does not match!", groups={"register"} )
     */
    private $password;
   
    /**
    * @Assert\NotBlank(groups={"register"})
    * @Assert\EqualTo(propertyPath = "password", message="Passwords does not match!" , groups={"register"} )
    */
    private $password2;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(groups={"register", "edit"})
     * @Assert\Email(message="This Email is not valid ", groups={"register", "edit"})
     */
    private $email;


    /**
     * @ORM\Column(type="string", length=255 , unique=true)
     * @Assert\NotBlank(groups={"register", "edit"})
     * @Assert\Length(min=5, max=100)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(
     *      min = 10,
     *      minMessage = "Your description must be Must be atleast 10 charachters", groups={"edit"})
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank(groups={"edit"}),
     * @Assert\Regex(
     * pattern="/[0-9]{8}/",
     * message="Invalid Phone number" , groups={"edit"}
     * )
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $picture;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $website;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $facebook;

    /**
     * @ORM\Column(type="json", nullable=true )
     */
    private $roles = [];

    /**
     * @ORM\OneToMany(targetEntity=Product::class, mappedBy="user", orphanRemoval=true)
     */
    private $products;

    /**
     * @ORM\OneToMany(targetEntity=Marketplace::class, mappedBy="user", orphanRemoval=true)
     */
    private $marketplaces;

    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->marketplaces = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }


    public function getPassword2(): ?string
    {
        return $this->password2;
    }

    public function setPassword2(string $password2): self
    {
        $this->password2 = $password2;

        return $this;
    }


    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getPicture()
    {
        return $this->picture;
    }

    public function setPicture($picture)
    {
        $this->picture = $picture;

        return $this;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(?string $website): self
    {
        $this->website = $website;

        return $this;
    }

    public function getFacebook(): ?string
    {
        return $this->facebook;
    }

    public function setFacebook(?string $facebook): self
    {
        $this->facebook = $facebook;

        return $this;
    }


    public function getRoles()
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER'; 
        return array_unique($roles);    
    }
    
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        return $this;
    }

    public function eraseCredentials() {}

    public function getSalt() {}

    /**
     * @return Collection|Product[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setUser($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->contains($product)) {
            $this->products->removeElement($product);
            // set the owning side to null (unless already changed)
            if ($product->getUser() === $this) {
                $product->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Marketplace[]
     */
    public function getMarketplaces(): Collection
    {
        return $this->marketplaces;
    }

    public function addMarketplace(Marketplace $marketplace): self
    {
        if (!$this->marketplaces->contains($marketplace)) {
            $this->marketplaces[] = $marketplace;
            $marketplace->setUser($this);
        }

        return $this;
    }

    public function removeMarketplace(Marketplace $marketplace): self
    {
        if ($this->marketplaces->contains($marketplace)) {
            $this->marketplaces->removeElement($marketplace);
            // set the owning side to null (unless already changed)
            if ($marketplace->getUser() === $this) {
                $marketplace->setUser(null);
            }
        }

        return $this;
    }


}
