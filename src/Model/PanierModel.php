<?php

namespace App\Model;

use Doctrine\DBAL\Query\QueryBuilder;
use Silex\Application;

class PanierModel {

    private $db;

    public function __construct(Application $app) {
        $this->db = $app['db'];
    }

    //temp
//    public function getAllCommand() {
//
//        $queryBuilder = new QueryBuilder($this->db);
//        $queryBuilder
//            ->select('co.id','co.user_id','co.prix', 'co.date_achat', 'co.etat_id')
//            ->from('commandes', 'co')
//            ->addOrderBy('co.id', 'ASC');
//        var_dump($queryBuilder->execute()->fetchAll());die();
//        return $queryBuilder->execute()->fetchAll();
//
//    }


    public function getAllPanier() {

        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->select('pa.id','pa.quantite','pa.prix', 'pa.dateAjoutPanier', 'pa.user_id', 'p.nom','p.photo')
            ->from('paniers', 'pa')
            ->innerJoin('pa','produits','p','pa.produit_id = p.id')
            ->addOrderBy('pa.id', 'ASC');
        return $queryBuilder->execute()->fetchAll();

    }


    public function getPanierByUser($id){
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->select('pa.id','pa.quantite','pa.prix', 'pa.dateAjoutPanier', 'pa.user_id', 'p.nom','p.photo','pa.produit_id')
            ->from('paniers','pa')
            ->innerJoin('pa','produits','p','pa.produit_id = p.id')
            ->where('pa.commande_id IS NULL ')
            ->andWhere('pa.user_id= :id')
            ->setParameter('id', $id)
            ->addOrderBy('pa.id', 'ASC');

        $result = $queryBuilder->execute()->fetchAll();

        return $result;
    }

    public function GetTotal($id){

        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->select('SUM(pa.prix) as total','Count(pa.id) as qualtity')
            ->from('paniers','pa')
            ->where('pa.user_id= :id')
            ->andWhere('pa.commande_id IS NULL')
            ->setParameter('id', $id)
            ->addOrderBy('pa.id', 'ASC');
//        var_dump($queryBuilder);
        return $queryBuilder->execute()->fetchAll();

    }
    public function GetSumPrix($id){

        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->select('SUM(pa.prix)')
            ->from('paniers','pa')
            ->where('pa.user_id= :id')
            ->andWhere('pa.commande_id IS NULL')
            ->setParameter('id', $id);

//        var_dump($queryBuilder);
        return $queryBuilder->execute()->fetch();

    }

    public function updateCommandePanier($id,$user_id) {

        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->update('paniers')
            ->set('commande_id', '?')
//            ->innerJoin('pa')
            ->where('user_id= ?')
//            ->andWhere('user_id=?')
            ->setParameter(0, (int)$id)
            ->setParameter(1, (int)$user_id);

        return $queryBuilder->execute();
    }

    public function updatePanier($checkPanier) {
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->update('paniers')
            ->set('quantite', '?')
            ->set('prix','?')
            ->where('id= ?')
            ->setParameter(0, $checkPanier['quantite']+1)
            ->setParameter(1, $checkPanier['prix']+ $checkPanier['prix'])
            ->setParameter(2, $checkPanier['id']);

        return $queryBuilder->execute();
    }

    public function insertPanier($donnees) {
        $queryBuilder = new QueryBuilder($this->db);
        $date = date('Y-m-d');

        $queryBuilder->insert('paniers')
            ->values([
                'quantite' => '?',
                'prix' => '?',
                'dateAjoutPanier' => '?',
                'user_id' => '?',
                'produit_id' => '?',
                'commande_id' => '?'

            ])
            ->setParameter(0, 1)
            ->setParameter(1, $donnees['prix'])
            ->setParameter(2, $date)
            ->setParameter(3, $donnees['user_id'])
            ->setParameter(4, $donnees['produit_id'])
            ->setParameter(5,null)
        ;
//        var_dump($queryBuilder);
        return $queryBuilder->execute();
    }

    function CheckDup($id,$user_id){
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->select('*')
            ->from('paniers', 'p')
            ->where('p.produit_id= :id')
            ->andWhere('p.user_id= :user')
            ->andWhere('p.commande_id IS NULL')
            ->setParameter('id', $id)
            ->setParameter('user',$user_id);

        $result = $queryBuilder->execute()->fetch();
        print_r($result);
        return $result;

    }
    function getPanier($id) {
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->select('pa.id','pa.quantite','pa.prix', 'pa.dateAjoutPanier', 'pa.user_id', 'pa.produit_id',  'pa.commande_id')
            ->from('paniers', 'pa')
            ->where('pa.id= :id')
            ->setParameter('id', $id);
        return $queryBuilder->execute()->fetch();
    }


    public function deletePanier($id) {
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->delete('paniers')
            ->where('id = :id')
            ->setParameter('id',(int)$id)
        ;
        return $queryBuilder->execute();
    }



}