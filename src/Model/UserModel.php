<?php
namespace App\Model;

use Silex\Application;
use Doctrine\DBAL\Query\QueryBuilder;;

class UserModel {

	private $db;

	public function __construct(Application $app) {
		$this->db = $app['db'];
	}

	public function verif_login_mdp_Utilisateur($login,$mdp){
		$sql = "SELECT id,username,motdepasse,roles FROM users WHERE username = ? AND motdepasse = ?";
		$res=$this->db->executeQuery($sql,[$login,$mdp]);   //md5($mdp);
		if($res->rowCount()==1)
			return $res->fetch();
		else
			return false;
	}
<<<<<<< HEAD


    public function addUser($donnees)
    {
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder->insert('users')
            ->values([
                'username' => '?',
                'motdepasse' => '?',
                'roles' => '?',
                'email' => '?',
                'isEnabled' => '?'
            ])
            ->setParameter(0, $donnees['username'])
            ->setParameter(1, $donnees['motdepasse'])
            ->setParameter(2, 'ROLE_CLIENT')
            ->setParameter(3, $donnees['email'])
            ->setParameter(4, 1);

        return $queryBuilder->execute();
    }
=======
	// public function verif_login_mdp_Utilisateur($login,$mdp){
	// 	$sql = "SELECT id,login,password,droit FROM users WHERE login = ? AND password = ?";
	// 	$res=$this->db->executeQuery($sql,[$login,$mdp]);   //md5($mdp);
	// 	if($res->rowCount()==1)
	// 		return $res->fetch();
	// 	else
	// 		return false;
	// }
>>>>>>> 8727a8ddef7406a90d935687fb3b4d0415826e16

	public function getUser($user_id) {
		$queryBuilder = new QueryBuilder($this->db);
		$queryBuilder
			->select('*')
			->from('users')
			->where('id = :idUser')
			->setParameter('idUser', $user_id);
		return $queryBuilder->execute()->fetch();

	}
}