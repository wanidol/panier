<?php
namespace App\Controller;

use App\Model\CommandModel;
use App\Model\EtatModel;
use App\Model\PanierModel;


use Silex\Application;
use Silex\Api\ControllerProviderInterface;   // modif version 2.0

use Symfony\Component\HttpFoundation\Request;   // pour utiliser request



use Symfony\Component\Validator\Constraints as Assert;   // pour utiliser la validation
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Security;

class CommandController implements ControllerProviderInterface
{
    private $commandeModel;

    public function index(Application $app) {
        return $this->show($app);
    }

    public function show(Application $app) {

        $this->commandeModel = new CommandModel($app);
        $commandeModel = $this->commandeModel->getAllCommand();

        return $app["twig"]->render('backOff/Commande/show.html.twig',['data'=>$commandeModel]);
    }


    public function showByUser(Application $app) {

        $user_id = $app['session']->get('user_id');
        $this->commandeModel = new CommandModel($app);
        $commandeModel = $this->commandeModel->getCommandByUser($user_id);

        return $app["twig"]->render('frontOff/Commande/show.html.twig',['data'=>$commandeModel]);
    }

    public function add(Application $app)
    {

        $user_id = $app['session']->get('user_id');
        $this->panierModel = new PanierModel($app);
        $sum = $this->panierModel->GetSumPrix($user_id);

        $this->commandeModel = new CommandModel($app);
        $this->commandeModel->insertCommand($user_id, $sum['total']);

        $LastinsertID = $app['db']->lastInsertId();
        $this->panierModel->updateCommandePanier($LastinsertID, $user_id);
        return $app->redirect($app["url_generator"]->generate("commande.showbyuser"));

    }

        public function detail(Application $app ,$id) {
            //get etats

            $this->etatsModel = new EtatModel($app);
            $etats=  $this->etatsModel->getEtats();
//            var_dump($etats);die();

            $this->panierModel = new PanierModel($app);
            $paniers =  $this->panierModel->getPanierById($id);

//            var_dump($paniers);die();
            $this->commandeModel = new CommandModel($app);

            $commandes = $this->commandeModel->getDetail($id);
//            var_dump($commades);die();

            return $app["twig"]->render('backOff/Commande/detail.html.twig',['etats'=>$etats,'paniers'=>$paniers,'commandes'=>$commandes]);
        }

    public function validFormUpdate(Application $app, Request $req) {

        $this->commandeModel = new CommandModel($app);

        $donnees = [
            'etat_id' => htmlspecialchars($req->get('etats_id')),
            'commande_id' =>$app->escape($req->get('commande_id'))
        ];

        $this->commandeModel->update($donnees);

        return $app->redirect($app["url_generator"]->generate("commande.adminshow"));
    }





    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];
        $controllers->get('/', 'App\Controller\commandController::index')->bind('commande.index');
        $controllers->get('/show', 'App\Controller\commandController::show')->bind('commande.adminshow');
        $controllers->get('/showbyuser', 'App\Controller\commandController::showByUser')->bind('commande.showbyuser');
        $controllers->get('/add', 'App\Controller\commandController::add')->bind('commande.add');


        $controllers->get('/detail/{id}', 'App\Controller\commandController::detail')->bind('commande.detail')->assert('id', '\d+');
//        $controllers->post('/add', 'App\Controller\panierController::validFormAdd')->bind('panier.validFormAdd');

        $controllers->post('/update', 'App\Controller\commandController::validFormUpdate')->bind('commande.update');
        return $controllers;
    }



}