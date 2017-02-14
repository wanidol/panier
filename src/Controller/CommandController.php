<?php
namespace App\Controller;

use App\Model\CommandModel;
use Silex\Application;
use Silex\Api\ControllerProviderInterface;   // modif version 2.0

use Symfony\Component\HttpFoundation\Request;   // pour utiliser request

use App\Model\PanierModel;

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

    public function add(Application $app,$id) {
        $this->panierModel = new PanierModel($app);
        $paniers= $this->panierModel->getPanierByUser($id);
        $this->commandeModel = new CommandModel($app);


        $commandeModel = $this->commandeModel->insertCommand($paniers);
//        return $app["twig"]->render('backOff/Produit/add.html.twig',['typeProduits'=>$typeProduits]);
    }

    public function connect(Application $app)
    {  //http://silex.sensiolabs.org/doc/providers.html#controller-providers
        $controllers = $app['controllers_factory'];

        $controllers->get('/', 'App\Controller\commandController::index')->bind('command.index');
//        $controllers->get('/show', 'App\Controller\produitController::show')->bind('produit.show');
//
//        $controllers->get('/add', 'App\Controller\produitController::add')->bind('produit.add');
//        $controllers->post('/add', 'App\Controller\produitController::validFormAdd')->bind('produit.validFormAdd');
//
//        $controllers->get('/delete/{id}', 'App\Controller\produitController::delete')->bind('produit.delete')->assert('id', '\d+');
//        $controllers->delete('/delete', 'App\Controller\produitController::validFormDelete')->bind('produit.validFormDelete');
//
//        $controllers->get('/edit/{id}', 'App\Controller\produitController::edit')->bind('produit.edit')->assert('id', '\d+');
//        $controllers->put('/edit', 'App\Controller\produitController::validFormEdit')->bind('produit.validFormEdit');

        return $controllers;
    }



}