<?php
namespace App\Controller;

use App\Model\UserModel;

use Silex\Application;
use Silex\Api\ControllerProviderInterface;   // modif version 2.0

use Symfony\Component\HttpFoundation\Request;   // pour utiliser request

class UserController implements ControllerProviderInterface {

	private $userModel;

	public function index(Application $app) {
		return $this->connexionUser($app);
	}
//
//    public function register(Application $app) {
//
//        return $app["twig"]->render('register.html.twig');
//    }

	public function connexionUser(Application $app)
	{
		return $app["twig"]->render('login.html.twig');
	}

<<<<<<< HEAD
	public function registerUser(Application $app)
    {
        return $app["twig"]->render('register.html.twig');
    }

=======
>>>>>>> 8727a8ddef7406a90d935687fb3b4d0415826e16
//    public function validFormRegister(Application $app, Request $req) {
//        // var_dump($app['request']->attributes);
//        if (isset($_POST['nom']) && isset($_POST['typeProduit_id']) and isset($_POST['nom']) and isset($_POST['photo'])) {
//            $donnees = [
//                'username' => htmlspecialchars($_POST['nom']),                    // echapper les entrées
//                'password' => htmlspecialchars($req->get('typeProduit_id')),  //$app['request']-> ne focntionne plus
//                'prix' => htmlspecialchars($req->get('prix')),
//
//            ];
//            if ((! preg_match("/^[A-Za-z ]{2,}/",$donnees['nom']))) $erreurs['nom']='nom composé de 2 lettres minimum';
//            if(! is_numeric($donnees['typeProduit_id']))$erreurs['typeProduit_id']='veuillez saisir une valeur';
//            if(! is_numeric($donnees['prix']))$erreurs['prix']='saisir une valeur numérique';
//            if (! preg_match("/[A-Za-z0-9]{2,}.(jpeg|jpg|png)/",$donnees['photo'])) $erreurs['photo']='nom de fichier incorrect (extension jpeg , jpg ou png)';
//
//            if(! empty($erreurs))
//            {
//                $this->typeProduitModel = new TypeProduitModel($app);
//                $typeProduits = $this->typeProduitModel->getAllTypeProduits();
//                return $app["twig"]->render('backOff/Produit/add.html.twig',['donnees'=>$donnees,'erreurs'=>$erreurs,'typeProduits'=>$typeProduits]);
//            }
//            else
//            {
//                $this->ProduitModel = new ProduitModel($app);
//                $this->ProduitModel->insertProduit($donnees);
//                return $app->redirect($app["url_generator"]->generate("produit.index"));
//            }
//
//        }
//        else
//            return $app->abort(404, 'error Pb data form Add');
//    }

<<<<<<< HEAD

    public function validFormRegisterUser(Application $app, Request $req)
    {

        $app['session']->clear();
        $password = $req->get('motdepasse');
        $confiemPass = $req->get('confirmpassword');
        $donnees['username']=$req->get('username');
        $donnees['email']=$req->get('email');

        if ($password == $confiemPass)
        {
            $donnees['motdepasse']= $password;

        }else{

            $erreurs['motdepasse'] = 'Les mots de passe ne correspondent pas';
        }

        if ((! preg_match("/^[A-Za-z ]{2,}/",$donnees['username']))) $erreurs['username']='username composé de 2 lettres minimum';

        if(! empty($erreurs))
        {
            return $app["twig"]->render('register.html.twig',['donnees'=>$donnees,'erreurs'=>$erreurs]);
        }else{

            $this->userModel = new UserModel($app);
            $this->userModel->addUser($donnees);
            $data=$this->userModel->verif_login_mdp_Utilisateur($donnees['username'],$donnees['motdepasse']);
            if($data != NULL)
            {
                $app['session']->set('roles', $data['roles']);  //dans twig {{ app.session.get('roles') }}
                $app['session']->set('username', $data['username']);
                $app['session']->set('logged', 1);
                $app['session']->set('user_id', $data['id']);
                return $app->redirect($app["url_generator"]->generate("accueil"));
            }
            else
            {
                $app['session']->set('erreur','L\'enregistrement ne réussit pas');
                return $app["twig"]->render('register.html.twig');
            }

        }

    }

=======
>>>>>>> 8727a8ddef7406a90d935687fb3b4d0415826e16
	public function validFormConnexionUser(Application $app, Request $req)
	{

		$app['session']->clear();
		$donnees['login']=$req->get('login');
		$donnees['password']=$req->get('password');

		$this->userModel = new UserModel($app);
		$data=$this->userModel->verif_login_mdp_Utilisateur($donnees['login'],$donnees['password']);

		if($data != NULL)
		{
			$app['session']->set('roles', $data['roles']);  //dans twig {{ app.session.get('roles') }}
			$app['session']->set('username', $data['username']);
			$app['session']->set('logged', 1);
			$app['session']->set('user_id', $data['id']);
			return $app->redirect($app["url_generator"]->generate("accueil"));
		}
		else
		{
			$app['session']->set('erreur','mot de passe ou login incorrect');
			return $app["twig"]->render('login.html.twig');
		}
	}
	public function deconnexionSession(Application $app)
	{
		$app['session']->clear();
		$app['session']->getFlashBag()->add('msg', 'vous êtes déconnecté');
		return $app->redirect($app["url_generator"]->generate("accueil"));
	}

	public function connect(Application $app) {
		$controllers = $app['controllers_factory'];
		$controllers->match('/', 'App\Controller\UserController::index')->bind('user.index');
		$controllers->get('/login', 'App\Controller\UserController::connexionUser')->bind('user.login');
		$controllers->post('/login', 'App\Controller\UserController::validFormConnexionUser')->bind('user.validFormlogin');
		$controllers->get('/logout', 'App\Controller\UserController::deconnexionSession')->bind('user.logout');
<<<<<<< HEAD
        $controllers->post('/register', 'App\Controller\UserController::validFormRegisterUser')->bind('user.validFormRegister');
        $controllers->get('/register', 'App\Controller\UserController::registerUser')->bind('user.register');
=======
//        $controllers->get('/register', 'App\Controller\UserController::register')->bind('user.register');
>>>>>>> 8727a8ddef7406a90d935687fb3b4d0415826e16
		return $controllers;
	}
}