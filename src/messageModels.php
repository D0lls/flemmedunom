<?php
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
class messageModels {
    public function getMessages($app){
        $sql = "SELECT informations.* FROM informations";
        return $app['db']->fetchAll($sql);
    }
    public function insertMessage($app,$contenu,$id_auteur){
        $sql = "SELECT nom FROM utilisateur where utilisateur.id_user = :id";
        $tmp = $app['db']->fetchAll($sql,array(':id' => $id_auteur));
        $app['db']->insert('informations', array(
            'contenu' => $contenu,
            'auteur' => $tmp[0]["nom"]
        ));
        $sql = "SELECT informations.* FROM informations order by id_info desc limit 1";
        return $app['db']->fetchAll($sql);
    }
    public function removeMessage($app,$id){
        $app['db']->delete('informations',array('id_info' => $id ));
    }
    public function updateMessage($app,$id,$contenu){
        $app['db']->update('informations',array('contenu' => $contenu ),array('id_info' => $id ));
    }
    public function checkIfExist($app,$name,$password){
        $sql = "SELECT * FROM utilisateur where utilisateur.nom = :nom and utilisateur.mdp = :mdp ";
        $tmp = $app['db']->fetchAll($sql,array(':nom' => $name,'mdp' =>$password));
        if(!empty($tmp) ){
            return true;
        }
            return false;
    }
    public function getId($app,$name,$password){
        $sql = "SELECT id_user FROM utilisateur where utilisateur.nom = :nom and utilisateur.mdp = :mdp";
        $tmp = $app['db']->fetchAll($sql,array(':nom' => $name,'mdp' =>$password));
        return $tmp[0]["id_user"];
    }
    public function getRole($app,$name,$password){
        $sql = "SELECT role FROM utilisateur where utilisateur.nom = :nom and utilisateur.mdp = :mdp";
        $tmp = $app['db']->fetchAll($sql,array(':nom' => $name,'mdp' =>$password));
        return $tmp[0]["role"];
    }
}