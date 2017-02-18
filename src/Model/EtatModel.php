<?php

namespace App\Model;

use Doctrine\DBAL\Query\QueryBuilder;
use Silex\Application;

class EtatModel {

    private $db;

    public function __construct(Application $app) {
        $this->db = $app['db'];
    }


    public function getEtats() {

        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->select('e.id','e.libelle')
            ->from('etats', 'e')
            ->addOrderBy('e.id', 'ASC');
        return $queryBuilder->execute()->fetchAll();

    }


}