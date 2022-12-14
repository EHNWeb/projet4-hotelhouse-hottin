<?php
namespace App\Service;

use App\Entity\Commande;
use App\Entity\CommandeChambre;
use App\Repository\ChambreRepository;
use App\Repository\CommandeChambreRepository;
use App\Repository\CommandeRepository;
use App\Repository\CommandeRestaurantRepository;
use App\Repository\CommandeSpaRepository;
use App\Repository\MembreRepository;
use App\Repository\OptionRepository;
use App\Repository\RestaurantRepository;
use App\Repository\SpaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\HttpFoundation\RequestStack;

class PanierService {
    private $rs;
    private $repoChambre;
    private $repoRestaurant;
    private $repoOption;
    private $repoSpa;
    private $repoMembre;
    private $repoCdeChambre;
    private $repoCdeRestaurant;
    private $repoCdeSpa;

    public function __construct(RequestStack $rs, 
                                ChambreRepository $repoChambre, 
                                RestaurantRepository $repoRestaurant,
                                OptionRepository $repoOption,
                                SpaRepository $repoSpa,
                                MembreRepository $repoMembre,
                                CommandeChambreRepository $repoCdeChambre,
                                CommandeRestaurantRepository $repoCdeRestaurant,
                                CommandeSpaRepository $repoCdeSpa,
                                EntityManagerInterface $manager) {
        // Hors d'un controller, nous devons faire les injections de dépendances dans un constructeur
        $this->rs = $rs;
        $this->repoChambre = $repoChambre;
        $this->repoRestaurant = $repoRestaurant;
        $this->repoOption = $repoOption;
        $this->repoMembre = $repoMembre;
        $this->repoSpa = $repoSpa;
        $this->repoCdeChambre = $repoCdeChambre;
        $this->repoCdeRestaurant = $repoCdeRestaurant;
        $this->repoCdeSpa = $repoCdeSpa;
        $this->manager = $manager;
    }

    public function addChambre($id){
        // On va créer une SESSION grâce à la classe RequestStack
        $session = $this->rs->getSession();
        $stock = $this->repoChambre->find($id)->getQuantite();

        // On récupère l'attribut de SESSION cart s'il existe, sinon , on récupère un tableau vide
        $chambre = $session->get('chambre', []);
        // Le tableau cart contient les quantités commandées des produits

        // Si le produit existe déjà dans le panier, on incrémente la quantité
        if (!empty($chambre[$id])) {
            if ($chambre[$id] < $stock) {
                $chambre[$id]++;
            }
        } else {
            // l'index du tableau cart est l'id du produit
            $chambre[$id] = 1;
        }
        // Je sauvegarde l'état de monm panier à l'attribut de session 'cart'
        $session->set('chambre', $chambre);
    }

    public function addSpa($id){
        // On va créer une SESSION grâce à la classe RequestStack
        $session = $this->rs->getSession();
        $stock = $this->repoSpa->find($id)->getQuantite();

        // On récupère l'attribut de SESSION cart s'il existe, sinon , on récupère un tableau vide
        $spa = $session->get('spa', []);
        // Le tableau cart contient les quantités commandées des produits

        // Si le produit existe déjà dans le panier, on incrémente la quantité
        if (!empty($spa[$id])) {
            if ($spa[$id] < $stock) {
                $spa[$id]++;
            }
        } else {
            // l'index du tableau cart est l'id du produit
            $spa[$id] = 1;
        }
        // Je sauvegarde l'état de monm panier à l'attribut de session 'cart'
        $session->set('spa', $spa);
    }

    public function addRestaurant($id){
        // On va créer une SESSION grâce à la classe RequestStack
        $session = $this->rs->getSession();
        $stock = $this->repoRestaurant->find($id)->getQuantite();

        // On récupère l'attribut de SESSION cart s'il existe, sinon , on récupère un tableau vide
        $restaurant = $session->get('restaurant', []);
        // Le tableau cart contient les quantités commandées des produits

        // Si le produit existe déjà dans le panier, on incrémente la quantité
        if (!empty($restaurant[$id])) {
            if ($restaurant[$id] < $stock) {
                $restaurant[$id]++;
            }
        } else {
            // l'index du tableau cart est l'id du produit
            $restaurant[$id] = 1;
        }
        // Je sauvegarde l'état de monm panier à l'attribut de session 'cart'
        $session->set('restaurant', $restaurant);
    }

    public function removeChambre($id) {
        $session = $this->rs->getSession();
        $chambre = $session->get('chambre', []);

        // Si le produit existe dans le panier, on le supprime du tableau $cart via unset()
        if (!empty($chambre[$id])) {
            unset($chambre[$id]);
        }

        // On sauvegarde l'état du panier
        $session->set('chambre', $chambre);
    }

    public function removeSpa($id) {
        $session = $this->rs->getSession();
        $spa = $session->get('spa', []);

        // Si le produit existe dans le panier, on le supprime du tableau $cart via unset()
        if (!empty($spa[$id])) {
            unset($spa[$id]);
        }

        // On sauvegarde l'état du panier
        $session->set('spa', $spa);
    }
    
    public function removeRestaurant($id) {
        $session = $this->rs->getSession();
        $restaurant = $session->get('restaurant', []);

        // Si le produit existe dans le panier, on le supprime du tableau $cart via unset()
        if (!empty($restaurant[$id])) {
            unset($restaurant[$id]);
        }

        // On sauvegarde l'état du panier
        $session->set('restaurant', $restaurant);
    }

    public function decrementChambre($id) {
        // On va créer une SESSION grâce à la classe RequestStack
        $session = $this->rs->getSession();

        // On récupère l'attribut de SESSION cart s'il existe, sinon , on récupère un tableau vide
        $chambre = $session->get('chambre', []);
        // Le tableau cart contient les quantités commandées des produits

        // Si le produit existe déjà dans le panier, on incrémente la quantité
        if (!empty($chambre[$id])) {
            if ($chambre[$id] > 1){
                $chambre[$id]--;
            } else {
                unset($chambre[$id]);
            }
        }

        // Je sauvegarde l'état de monm panier à l'attribut de session 'cart'
        $session->set('chambre', $chambre);
    }

    public function decrementSpa($id) {
        // On va créer une SESSION grâce à la classe RequestStack
        $session = $this->rs->getSession();

        // On récupère l'attribut de SESSION cart s'il existe, sinon , on récupère un tableau vide
        $spa = $session->get('spa', []);
        // Le tableau cart contient les quantités commandées des produits

        // Si le produit existe déjà dans le panier, on incrémente la quantité
        if (!empty($spa[$id])) {
            if ($spa[$id] > 1){
                $spa[$id]--;
            } else {
                unset($spa[$id]);
            }
        }

        // Je sauvegarde l'état de monm panier à l'attribut de session 'cart'
        $session->set('spa', $spa);
    }

    public function decrementRestaurant($id) {
        // On va créer une SESSION grâce à la classe RequestStack
        $session = $this->rs->getSession();

        // On récupère l'attribut de SESSION cart s'il existe, sinon , on récupère un tableau vide
        $restaurant = $session->get('restaurant', []);
        // Le tableau cart contient les quantités commandées des produits

        // Si le produit existe déjà dans le panier, on incrémente la quantité
        if (!empty($restaurant[$id])) {
            if ($restaurant[$id] > 1){
                $restaurant[$id]--;
            } else {
                unset($restaurant[$id]);
            }
        }

        // Je sauvegarde l'état de monm panier à l'attribut de session 'cart'
        $session->set('restaurant', $restaurant);
    }

    public function emptyChambre()
    {
        $session = $this->rs->getSession();
        $session->remove('chambre');
    }

    public function emptySpa()
    {
        $session = $this->rs->getSession();
        $session->remove('spa');
    }

    public function emptyRestaurant()
    {
        $session = $this->rs->getSession();
        $session->remove('restaurant');
    }

    public function getChambreWithData()
    {
        // On recupère la SESSION
        $session = $this->rs->getSession();
        $chambre = $session->get('chambre', []);

        // Quantité totale du panier
        $quantityPanier = 0;

        // on crée un nouveau tableau qui contiendra des objets Product et les quantités de chauque OBJET
        $chambreWithData = [];

        // $cartWithData est un tableau multidimensionnel:
        // Pour chaque ID qui se trouve dans le panier, nous allons créer un nouveau tableau dans $cartWithData qui contiendra 2 cases:
        // Product, Quantity
        foreach ($chambre as $id => $quantity) {
            // On créer une nouvelle case dans le tableau $cartWithData
            $chambreWithData[] = [
                'chambre' => $this->repoChambre->find($id),
                'quantite' => $quantity
            ];
            $quantityPanier += $quantity;
        }

        $session->set('totalChambreQuantity', $quantityPanier);

        return $chambreWithData;
    }

    public function getSpaWithData()
    {
        // On recupère la SESSION
        $session = $this->rs->getSession();
        $spa = $session->get('spa', []);

        // Quantité totale du panier
        $quantityPanier = 0;

        // on crée un nouveau tableau qui contiendra des objets Product et les quantités de chauque OBJET
        $spaWithData = [];

        // $cartWithData est un tableau multidimensionnel:
        // Pour chaque ID qui se trouve dans le panier, nous allons créer un nouveau tableau dans $cartWithData qui contiendra 2 cases:
        // Product, Quantity
        foreach ($spa as $id => $quantity) {
            // On créer une nouvelle case dans le tableau $cartWithData
            $spaWithData[] = [
                'spa' => $this->repoSpa->find($id),
                'quantite' => $quantity
            ];
            $quantityPanier += $quantity;
        }

        $session->set('totalSpaQuantity', $quantityPanier);

        return $spaWithData;
    }

    public function getRestaurantWithData()
    {
        // On recupère la SESSION
        $session = $this->rs->getSession();
        $restaurant = $session->get('restaurant', []);

        // Quantité totale du panier
        $quantityPanier = 0;

        // on crée un nouveau tableau qui contiendra des objets Product et les quantités de chauque OBJET
        $restaurantWithData = [];

        // $cartWithData est un tableau multidimensionnel:
        // Pour chaque ID qui se trouve dans le panier, nous allons créer un nouveau tableau dans $cartWithData qui contiendra 2 cases:
        // Product, Quantity
        foreach ($restaurant as $id => $quantity) {
            // On créer une nouvelle case dans le tableau $cartWithData
            $restaurantWithData[] = [
                'restaurant' => $this->repoRestaurant->find($id),
                'quantite' => $quantity
            ];
            $quantityPanier += $quantity;
        }

        $session->set('totalrestaurantQuantity', $quantityPanier);

        return $restaurantWithData;
    }

    public function getTotalPanier()
    {
        $session = $this->rs->getSession();
        $chambreWithData = $this->getChambreWithData();
        $restaurantWithData = $this->getRestaurantWithData();
        $spaWithData = $this->getSpaWithData();

        // Pour chaque produit dans mon panier, j erécupère le sous total
        $totalPanier = 0;
        $quantitePanier = 0;
        foreach ($chambreWithData as $item) {
            $totalItem = $item['chambre']->getPrix() * $item['quantite'];
            $totalPanier += $totalItem;
            $quantitePanier += $item['quantite'];
        }

        foreach ($spaWithData as $item) {
            $totalItem = $item['spa']->getMontant() * $item['quantite'];
            $totalPanier += $totalItem;
            $quantitePanier += $item['quantite'];
        }

        foreach ($restaurantWithData as $item) {
            $quantitePanier += $item['quantite'];
        }

        $session->set('totalPanier', $totalPanier);
        $session->set('quantitePanier', $quantitePanier);

        return $totalPanier;
    }

    public function passOrder($id)
    {
        $session = $this->rs->getSession();
        $chambreWithData = $this->getChambreWithData();

        $messageBDD = "La commande a bien été enregistrée.";
        $cdeDateEnregistrement = new \DateTime();
        $cdeEtat = 0;
        $cdeMembreID = $this->repoMembre->find($id);

        // Pour chaque produit dans mon panier, j erécupère le sous total
        foreach ($chambreWithData as $item) {

            $cdeProduitID = $item['produit'];
            $cdeProduitQTY = $item['quantite'];
            $cdeMontant = $item['produit']->getPrix() * $item['quantite'];

            $produitToUpdate = $this->repo->find($cdeProduitID);
            $produitToUpdate->setStock($produitToUpdate->getStock() - $item['quantite']);
            $this->manager->persist($produitToUpdate);

            $commande = new CommandeChambre();
            
            $commande->setIdMembre($cdeMembreID)
                     ->setDateEnregistrement($cdeDateEnregistrement)
                     ->setMontant($cdeMontant);

            $this->manager->persist($commande);
            $this->manager->flush();
        }

    }
}