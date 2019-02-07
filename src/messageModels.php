<?php
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
class messageModels {
    public function getMessages($app){
        $sql = "SELECT informations.* FROM informations";
        return $app['db']->fetchAll($sql);
    }
    public function getMessagesAttente($app){
        $sql = "SELECT attente.* FROM attente";
        return $app['db']->fetchAll($sql);
    }
    public function validateMessages($app,$id){
        $sql = "SELECT attente.* FROM attente where attente.id_attente = :attente";
        $tmp = $app['db']->fetchAll($sql,array(':attente' => $id));
        $app['db']->delete('attente',array('id_attente' => $id ));
        $app['db']->insert('informations', array(
            'contenu' => $tmp[0]["contenu"],
            'auteur' => $tmp[0]["auteur"]
        ));
        $sql = "SELECT informations.* FROM informations order by id_info desc limit 1";
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
    public function insertMessageAttente($app,$contenu,$id_auteur){
        $sql = "SELECT nom FROM utilisateur where utilisateur.id_user = :id";
        $tmp = $app['db']->fetchAll($sql,array(':id' => $id_auteur));
        $app['db']->insert('attente', array(
            'contenu' => $contenu,
            'auteur' => $tmp[0]["nom"]
        ));
        $sql = "SELECT attente.* FROM attente order by id_attente desc limit 1";
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
    public function checkIfExistLdap($app,$name,$password){
        $ldapHost = "ldap://10.10.28.101";
        $ldapPort = 389;
        $ldapCon = ldap_connect($ldapHost, $ldapPort) or die();
        if(ldap_connect($ldapHost, $ldapPort)!=false){
        ldap_set_option($ldapCon, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($ldapCon, LDAP_OPT_REFERRALS, 0);
        $ldapUser = "uid=".$name.",ou=Users,dc=iutcb,dc=univ-littoral,dc=fr";
        $ldapPwd = $password;
        $ldapBind = ldap_bind($ldapCon, $ldapUser, $ldapPwd);
        if($ldapBind){
            return true;
        }
        else{
            return false;
        }
        }else{
            return false;
        }
    }
    public function getRoleLdap($app,$name,$password){
        $ldapHost = "ldap://10.10.28.101";
        $ldapPort = 389;
        $ldapCon = ldap_connect($ldapHost, $ldapPort) or die();
        if(ldap_connect($ldapHost, $ldapPort)!=false){
        ldap_set_option($ldapCon, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($ldapCon, LDAP_OPT_REFERRALS, 0);
        $ldapUser = "uid=".$name.",ou=Users,dc=iutcb,dc=univ-littoral,dc=fr";
        $ldapPwd = $password;
        $ldapBind = ldap_bind($ldapCon, $ldapUser, $ldapPwd);
        $dn = "ou=Groups,dc=iutcb,dc=univ-littoral,dc=fr";
        $filter = "(&(cn=enseignant)(memberuid=nom.prenom))";
        $elem = array("uid","gidNumber","ou");
        $ldapSearch = ldap_search($ldapCon, $dn, $filter, $elem);
        $info = ldap_get_entries($ldapCon, $ldapSearch);
        if($info["count"]==1){
            return "moderateur";
        }else{
            return "redacteur";
        }
        }
    }
    public function getIdLdap($app,$name,$password,$role){
        $sql = "SELECT * FROM utilisateur where utilisateur.nom = :nom";
        $tmp = $app['db']->fetchAll($sql,array(':nom' => $name));
        if(empty($tmp) ){
            $app['db']->insert('utilisateur', array(
                'nom' => $name,
                'prenom' => $name,
                'role' => $role,
                'mdp' => ""
            ));
            $sql = "SELECT id_user FROM utilisateur where utilisateur.nom = :nom";
        $tmp = $app['db']->fetchAll($sql,array(':nom' => $name));
        return $tmp[0]["id_user"];
        }
        $sql = "SELECT id_user FROM utilisateur where utilisateur.nom = :nom";
        $tmp = $app['db']->fetchAll($sql,array(':nom' => $name));
        return $tmp[0]["id_user"];
    }
    
}