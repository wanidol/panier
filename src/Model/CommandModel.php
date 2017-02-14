<?php

namespace App\Model;

use Doctrine\DBAL\Query\QueryBuilder;
use Silex\Application;

class CommandModel {

    private $db;

    public function __construct(Application $app) {
        $this->db = $app['db'];
    }

    public function getAllCommand() {

        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->select('co.id','co.user_id','co.prix', 'co.date_achat', 'co.etat_id', 'co.panier_id')
            ->from('commandes', 'co')
            ->addOrderBy('co.id', 'ASC');
        return $queryBuilder->execute()->fetchAll();

    }

    public function deleteCommand($id) {
//        $queryBuilder = new QueryBuilder($this->db);
//        $queryBuilder
//            ->delete('paniers')
//            ->where('id = :id')
//            ->setParameter('id',(int)$id)
//        ;
//        return $queryBuilder->execute();
    }

    public function insertCommand($donnees) {
        $date = date('Y-m-d');
        $queryBuilder = new QueryBuilder($this->db);

        foreach ($donnees as $donnee){
        $queryBuilder
            ->insert('commandes')
            ->values([
                'user_id' => '?',
                'prix' => '?',
                'date_achat' => '?',
                'etat_id' => '?',
                'panier_id' => '?'
            ])
            ->setParameter(0, $donnee['user_id'])
            ->setParameter(1, $donnee['prix'])
            ->setParameter(2, $date)
            ->setParameter(3, 1)
            ->setParameter(4, $donnee['panier_id'])
        ;

        }
        return $queryBuilder->execute();
    }

    public function updateCommand($donnees) {
//        $queryBuilder = new QueryBuilder($this->db);
//        $queryBuilder
//            ->update('produits')
//            ->set('nom', '?')
//            ->set('typeProduit_id','?')
//            ->set('prix','?')
//            ->set('photo','?')
//            ->where('id= ?')
//            ->setParameter(0, $donnees['nom'])
//            ->setParameter(1, $donnees['typeProduit_id'])
//            ->setParameter(2, $donnees['prix'])
//            ->setParameter(3, $donnees['photo'])
//            ->setParameter(4, $donnees['id']);
//        return $queryBuilder->execute();
    }

}