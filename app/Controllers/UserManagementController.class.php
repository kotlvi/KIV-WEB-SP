<?php

namespace kivweb\Controllers;

use kivweb\Models\DatabaseModel;
use kivweb\Models\Session;

/**
 * Ovladac zajistujici vypsani stranky se spravou uzivatelu.
 * @package kivweb\Controllers
 * @author Viktor Kotlan
 */
class UserManagementController implements IController {

    /** @var DatabaseModel $db  Sprava databaze. */
    private $db;

    /** @var Session $session  Vlastni objekt pro spravu session. */
    private $session;

    /**
     * Inicializace pripojeni k databazi.
     */
    public function __construct() {
        // inicializace prace s DB
        $this->db = DatabaseModel::getDatabaseModel();
        $this->session = Session::getSessionModel();
    }

    /**
     * Vrati obsah stranky se spravou uzivatelu.
     * @param string $pageTitle     Nazev stranky.
     * @return array                Vytvorena data pro sablonu.
     */
    public function show(string $pageTitle):array {
        $tplData = [];
        $tplData['title'] = $pageTitle;

        if(isset($_POST['action'])){

            //// Pozadavek na smazani uzivatele? ////

            if( $_POST['action'] == "delete" and isset($_POST['id_user'])) {
                echo $tplData['deletedUser'] = $_POST['id_user'];
                // provedu smazani uzivatele
                $ok = $this->db->deleteUser(intval($_POST['id_user']));
                if($ok){
                    $tplData['delete'] = 1;
                } else {
                    $tplData['delete'] = 0;
                }

            //// Pozadavek na upravu uzivatele? ////

            } elseif ($_POST['action'] == "update_submit"){
                if(isset($_POST['login']) && isset($_POST['jmeno']) && isset($_POST['prijmeni']) && isset($_POST['email']) && isset($_POST['id_prava'])
                    && $_POST['login'] != "" && $_POST['heslo'] != "" && $_POST['jmeno'] != "" && $_POST['email'] != "" && $_POST['id_prava'] != "" && $_POST['prijmeni'] != ""
                    && $_POST['id_prava'] > 0 && $_POST['id_prava'] < 4){

                    $res = $this->db->updateUser($_POST['id_uzivatele'], $_POST['jmeno'], $_POST['prijmeni'], $_POST['login'], $_POST['heslo'], $_POST['email'], $_POST['id_prava']);
                    if($res){
                        $tplData['update'] = 1;
                    } else {
                        $tplData['update'] = 0;
                    }
                } else {
                    $tplData['update'] = 0;
                }
            }
        }


        // Nacitani aktualnich dat uzivatelu
        $tplData['users'] = $this->db->getOnlyUsers();
        $tplData['reviewers'] = $this->db->getOnlyReviewers();

        if($this->session->isUserLogged()){
            $tplData['logged'] = 1;

            $userId = $this->session->getLoggedUserID();
            $userData = $this->db->getUserDataByID($userId);

            if(!empty($userData) && $userData[0]['id_prava'] == 3){
                $tplData['allowed'] = 1;
            } else {
                $tplData['allowed'] = 0;
            }
        } else {
            $tplData['logged'] = 0;
        }

        // vratim sablonu naplnenou daty
        return $tplData;
    }
}

?>
