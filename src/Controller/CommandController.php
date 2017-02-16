<?php
namespace App\Controller;

use App\Model\CommandModel;
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
//        var_dump($produits);
        return $app["twig"]->render('backOff/Commande/show.html.twig',['data'=>$commandeModel]);
    }

    public function showByUser(Application $app) {
        $user_id = $app['session']->get('user_id');
        $this->commandeModel = new CommandModel($app);
        $commandeModel = $this->commandeModel->getCommandByUser($user_id);
//var_dump($commandeModel);die();
        return $app["twig"]->render('backOff/Commande/show.html.twig',['data'=>$commandeModel]);
    }

    public function add(Application $app) {

        $user_id = $app['session']->get('user_id');
        $this->panierModel = new PanierModel($app);
        $sum = $this->panierModel->GetSumPrix($user_id);
//        $var= floatval(preg_replace("/[^-0-9\\.]/","",$sum));
//        $var = Convert.ToInt32[$sum];
//        $var = '122.34343The';
//       var_dump($sum);die();

        $this->commandeModel = new CommandModel($app);
        $this->commandeModel->insertCommand($user_id,$sum);
        $LastinsertID =$app['db']->lastInsertId();

        $this->panierModel->updateCommandePanier($LastinsertID,$user_id);


        return $app->redirect($app["url_generator"]->generate("command.show"));
//
    }

    public function connect(Application $app)
    {  //http://silex.sensiolabs.org/doc/providers.html#controller-providers
        $controllers = $app['controllers_factory'];

        $controllers->get('/', 'App\Controller\commandController::index')->bind('command.index');
        $controllers->get('/show', 'App\Controller\commandController::showByUser')->bind('command.show');

        $controllers->get('/add', 'App\Controller\commandController::add')->bind('commande.add');
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