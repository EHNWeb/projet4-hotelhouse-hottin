<?php

namespace App\Entity;

use App\Repository\MembreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=MembreRepository::class)
 * @UniqueEntity(fields={"pseudo"}, message="There is already an account with this pseudo")
 */
class Membre implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $pseudo;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $civilite;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_enregistrement;

    /**
     * @ORM\OneToMany(targetEntity=Commentaire::class, mappedBy="id_membre")
     */
    private $commentaires;

    /**
     * @ORM\OneToMany(targetEntity=CommandeChambre::class, mappedBy="id_membre")
     */
    private $commandeChambres;

    /**
     * @ORM\OneToMany(targetEntity=CommandeRestaurant::class, mappedBy="id_membre")
     */
    private $commandeRestaurants;

    /**
     * @ORM\OneToMany(targetEntity=CommandeSpa::class, mappedBy="id_membre")
     */
    private $commandeSpas;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $telephone;

    public function __construct()
    {
        $this->commentaires = new ArrayCollection();
        $this->commandeChambres = new ArrayCollection();
        $this->commandeRestaurants = new ArrayCollection();
        $this->commandeSpas = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->pseudo;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->pseudo;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

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

    public function getCivilite(): ?string
    {
        return $this->civilite;
    }

    public function setCivilite(string $civilite): self
    {
        $this->civilite = $civilite;

        return $this;
    }

    public function getDateEnregistrement(): ?\DateTimeInterface
    {
        return $this->date_enregistrement;
    }

    public function setDateEnregistrement(\DateTimeInterface $date_enregistrement): self
    {
        $this->date_enregistrement = $date_enregistrement;

        return $this;
    }

    /**
     * @return Collection<int, Commentaire>
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaire $commentaire): self
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires[] = $commentaire;
            $commentaire->setIdMembre($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): self
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getIdMembre() === $this) {
                $commentaire->setIdMembre(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CommandeChambre>
     */
    public function getCommandeChambres(): Collection
    {
        return $this->commandeChambres;
    }

    public function addCommandeChambre(CommandeChambre $commandeChambre): self
    {
        if (!$this->commandeChambres->contains($commandeChambre)) {
            $this->commandeChambres[] = $commandeChambre;
            $commandeChambre->setIdMembre($this);
        }

        return $this;
    }

    public function removeCommandeChambre(CommandeChambre $commandeChambre): self
    {
        if ($this->commandeChambres->removeElement($commandeChambre)) {
            // set the owning side to null (unless already changed)
            if ($commandeChambre->getIdMembre() === $this) {
                $commandeChambre->setIdMembre(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CommandeRestaurant>
     */
    public function getCommandeRestaurants(): Collection
    {
        return $this->commandeRestaurants;
    }

    public function addCommandeRestaurant(CommandeRestaurant $commandeRestaurant): self
    {
        if (!$this->commandeRestaurants->contains($commandeRestaurant)) {
            $this->commandeRestaurants[] = $commandeRestaurant;
            $commandeRestaurant->setIdMembre($this);
        }

        return $this;
    }

    public function removeCommandeRestaurant(CommandeRestaurant $commandeRestaurant): self
    {
        if ($this->commandeRestaurants->removeElement($commandeRestaurant)) {
            // set the owning side to null (unless already changed)
            if ($commandeRestaurant->getIdMembre() === $this) {
                $commandeRestaurant->setIdMembre(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CommandeSpa>
     */
    public function getCommandeSpas(): Collection
    {
        return $this->commandeSpas;
    }

    public function addCommandeSpa(CommandeSpa $commandeSpa): self
    {
        if (!$this->commandeSpas->contains($commandeSpa)) {
            $this->commandeSpas[] = $commandeSpa;
            $commandeSpa->setIdMembre($this);
        }

        return $this;
    }

    public function removeCommandeSpa(CommandeSpa $commandeSpa): self
    {
        if ($this->commandeSpas->removeElement($commandeSpa)) {
            // set the owning side to null (unless already changed)
            if ($commandeSpa->getIdMembre() === $this) {
                $commandeSpa->setIdMembre(null);
            }
        }

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }
}
