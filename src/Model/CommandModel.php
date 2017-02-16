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
            ->select('co.id','co.user_id','co.prix', 'co.date_achat', 'co.etat_id')
            ->from('commandes', 'co')
            ->addOrderBy('co.id', 'ASC');
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
}