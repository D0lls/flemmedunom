<?php
use Doctrine\DBAL\Connection;

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
}