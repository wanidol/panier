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

        return $app["twig"]->render('backOff/Commande/show.html.twig',['data'=>$commandeModel]);
    }

    public function showByUser(Application $app) {
        $user_id = $app['session']->get('user_id');
        $this->commandeModel = new CommandModel($app);
        $commandeModel = $this->commandeModel->getCommandByUser($user_id);

        return $app["twig"]->render('backOff/Commande/show.html.twig',['data'=>$commandeModel]);
    }

    public function add(Application $app) {

        $user_id = $app['session']->get('user_id');
        $this->panierModel = new PanierModel($app);
        $sum = $this->panierModel->GetSumPrix($user_id);

        $this->commandeModel = new CommandModel($app);
        $this->commandeModel->insertCommand($user_id,$sum['total']);

        $LastinsertID =$app['db']->lastInsertId();
        $this->panierModel->updateCommandePanier($LastinsertID,$user_id);

        return $app->redirect($app["url_generator"]->generate("command.show"));
    }

    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];
        $controllers->get('/', 'App\Controller\commandController::index')->bind('command.index');
        $controllers->get('/show', 'App\Controller\commandController::showByUser')->bind('command.show');
        $controllers->get('/add', 'App\Controller\commandController::add')->bind('commande.add');

        return $controllers;
    }



}