<?php
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
class messageModels {
    public function getMessages($app){
        $sql = "SELECT informations.* FROM informations";
        return $app['db']->fetchAll($sql);
    }
    public function insertMessage($app,$contenu,$auteur){
        $app['db']->insert('informations', array(
            'contenu' => $contenu,
            'auteur' => $auteur
        ));
    }
    public function checkIfExist($app,$name,$password){
        $sql = "SELECT * FROM utilisateur where utilisateur.nom = :nom and utilisateur.mdp = :mdp ";
        $tmp = $app['db']->fetchAll($sql,array(':nom' => $name,'mdp' =>$password));
        if(!empty($tmp) ){
            return true;
        }
            return false;
        }
    }