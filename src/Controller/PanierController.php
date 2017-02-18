<?php
namespace App\Controller;

use App\Model\ProduitModel;
use App\Model\CommandModel;
use App\Model\TypeProduitModel;
use Silex\Application;
use Silex\Api\ControllerProviderInterface;   // modif version 2.0

use Symfony\Component\HttpFoundation\Request;   // pour utiliser request

use App\Model\PanierModel;

use Symfony\Component\Validator\Constraints as Assert;   // pour utiliser la validation
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Security;

class PanierController implements ControllerProviderInterface
{
    private $panierModel;


    public function initModel(Application $app){  //  ne fonctionne pas dans le const
        $this->panierModel = new PanierModel($app);

    }

    public function index(Application $app) {
        return $this->show($app);
    }

    public function panierIsEmpty(Application $app){

        return $app["twig"]->render('frontOff/Panier/empty.html.twig');
    }


    public function show(Application $app) {
        $this->panierModel = new PanierModel($app);
        $panier = $this->panierModel->getAllPanier();
        return $app["twig"]->render('frontOff/Panier/show.html.twig',['data'=>$panier]);
    }

    public function showVotrePanier(Application $app){

        $this->panierModel = new PanierModel($app);
        $client_id =$app['session']->get('user_id');
        $panier_total = $this->panierModel->GetTotal($client_id);
//        $sum = $this->panierModel->GetSumPrix($client_id);
//      var_dump(is_null($sum['total']));die();
        $panier = $this->panierModel->getPanierByUser($client_id);

//        if (empty($sum['total'])){

//            return $app->redirect($app["url_generator"]->generate("panier.panierIsEmpty"));
//        }
//        else{
            return $app["twig"]->render('frontOff/Panier/show.html.twig', ['data' => $panier, 'panier_total' => $panier_total]);

//        }
    }

    public function delete(Application $app, $id) {
        $this->panierModel = new PanierModel($app);
        $panierModel = $this->panierModel->deletePanier($id);
        return $app->redirect($app["url_generator"]->generate("panier.showVotrePanier"));

    }

    public function detail(Application $app ,$id) {
        $this->typeProduitModel = new TypeProduitModel($app);
        $typeProduits = $this->typeProduitModel->getAllTypeProduits();
        $this->produitModel = new ProduitModel($app);

        $donnees = $this->produitModel->getDetail($id);

        return $app["twig"]->render('frontOff/Produit/detail.html.twig',['typeProduits'=>$typeProduits,'donnees'=>$donnees]);
    }

    public function validFormAdd(Application $app, Request $req) {
        $this->panierModel = new PanierModel($app);
        $this->produitModel = new ProduitModel($app);

        $produit_id = $app->escape($req->get('produit_id'));
        $client_id =$app['session']->get('user_id');
        $checkPanier = $this->panierModel->CheckDup($produit_id,$client_id);
        print_r($checkPanier);

        $donnees = [
            'prix' => $app->escape($req->get('prix')),
            'user_id' =>  htmlspecialchars($client_id),
            'produit_id' =>  htmlspecialchars($produit_id),

            ];

        if(! empty($checkPanier))
        {
            $this->panierModel->UpdatePanier($checkPanier);

        }else{$this->panierModel->insertPanier($donnees);}


        return $app->redirect($app["url_generator"]->generate("panier.showVotrePanier"));
 }


    public function connect(Application $app) {

        $controllers = $app['controllers_factory'];

        $controllers->get('/', 'App\Controller\panierController::index')->bind('panier.index');
        $controllers->get('/show', 'App\Controller\panierController::show')->bind('panier.show');
        $controllers->get('/show', 'App\Controller\panierController::panierIsEmpty')->bind('panier.panierIsEmpty');

        $controllers->get('/delete/{id}', 'App\Controller\panierController::delete')->bind('panier.delete')->assert('id', '\d+');
        $controllers->delete('/delete', 'App\Controller\panierController::validFormDelete')->bind('panier.validFormDelete');

        $controllers->get('/detail/{id}', 'App\Controller\panierController::detail')->bind('panier.detail')->assert('id', '\d+');
        $controllers->post('/add', 'App\Controller\panierController::validFormAdd')->bind('panier.validFormAdd');

        $controllers->get('/detailByUser', 'App\Controller\panierController::showVotrePanier')->bind('panier.showVotrePanier');


        return $controllers;
    }
}

