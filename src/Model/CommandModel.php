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
            ->select('co.id','co.user_id','co.prix', 'co.date_achat', 'e.libelle','u.username')
            ->from('commandes', 'co')
            ->innerJoin('co', 'etats', 'e', 'co.etat_id=e.id')
            ->innerJoin('co', 'users', 'u', 'co.user_id=u.id')
            ->addOrderBy('co.id', 'ASC');
//        var_dump($queryBuilder->execute()->fetchAll());die();
        return $queryBuilder->execute()->fetchAll();

    }

    public function getCommandByUser($id) {

        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->select('co.id','co.prix', 'co.date_achat','e.libelle')
            ->from('commandes', 'co')
            ->innerJoin('co', 'etats', 'e', 'co.etat_id=e.id')
            ->where('co.user_id= :id')
            ->setParameter('id',$id)
            ->addOrderBy('co.id', 'ASC');
//        var_dump($queryBuilder->execute()->fetchAll());die();
        return $queryBuilder->execute()->fetchAll();

    }

    public function insertCommand($id,$total) {
        $date = date('Y-m-d');
        $etat_id=1;
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->insert('commandes')
            ->values([
                    'user_id' => '?',
                    'prix' => '?',
                    'date_achat' => '?',
                    'etat_id' => '?'
                ])
                 ->setParameter(0, $id)
                 ->setParameter(1, $total)
                 ->setParameter(2, $date)
                 ->setParameter(3, $etat_id);

        return $queryBuilder->execute();
    }

    function getDetail($id) {
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->select('co.id','co.user_id','co.prix', 'co.date_achat', 'e.libelle','u.username','co.etat_id')
            ->from('commandes', 'co')
            ->innerJoin('co', 'etats', 'e', 'co.etat_id=e.id')
            ->innerJoin('co', 'users', 'u', 'co.user_id=u.id')
            ->where('co.id= :id')
            ->setParameter('id', $id);
        return $queryBuilder->execute()->fetch();
    }

    public function update($donnees) {

        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->update('commandes')
            ->set('etat_id', '?')
            ->where('id= ?')
            ->setParameter(0, (int)$donnees['etat_id'])
            ->setParameter(1, (int)$donnees['commande_id']);

        return $queryBuilder->execute();
    }
}