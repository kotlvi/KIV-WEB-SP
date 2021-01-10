<?php

namespace kivweb\Controllers;

use kivweb\Models\DatabaseModel;
use kivweb\Models\Session;

/**
 * Class SingUpController       Controller pro registraci uzivatelu
 * @package kivweb\Controllers
 * @author Viktor Kotlan
 */
class SingUpController
{
    /** @var Session $session  Vlastni objekt pro spravu session. */
    private $session;

    /** @var DatabaseModel $db  Sprava databaze. */
    private $db;

    /**
     * Inicializace pripojeni k databazi.
     */
    public function __construct() {
        $this->db = DatabaseModel::getDatabaseModel();
        $this->session = Session::getSessionModel();
    }

    /**
     * Vrati obsah registracni stranky
     * @param string $pageTitle     Nazev stranky.
     * @return array                Vytvorena data pro sablonu.
     */
    public function show(string $pageTitle):array {
        $tplData = [];
        $tplData['title'] = $pageTitle;

        //// Pozadavek k registraci ////

        if(isset($_POST['signup_submit'])){
            // Vsechny pozadovane hodnoty?
            if(isset($_POST['login']) && isset($_POST['heslo']) && isset($_POST['heslo2'])
                && isset($_POST['jmeno']) && isset($_POST['prijmeni']) && isset($_POST['email'])
                && $_POST['heslo'] == $_POST['heslo2']
                && $_POST['login'] != "" && $_POST['heslo'] != "" && $_POST['jmeno'] != "" && $_POST['email'] != ""
                && $_POST['prijmeni'] != ""
            ){
                //Sifrovani hesla
                $heslo = password_hash($_POST['heslo'], PASSWORD_BCRYPT);

                // mam vsechny atributy - ulozim uzivatele do DB
                $res = $this->db->addNewUser($_POST['jmeno'], $_POST['prijmeni'], $_POST['login'], $heslo, $_POST['email']);
                // byl ulozen?
                if($res){
                    $tplData['signup'] = 1;
                } else {
                    $tplData['signup'] = 0;
                }
            } else {
                // nemam vsechny atributy
                $tplData['signup'] = 2;
            }
        }

        // pokud je uzivatel prihlasen, tak ziskam jeho data
        if($this->session->isUserLogged()){
            // ziskam data prihlasenoho uzivatele
            $tplData['logged'] = 1;
        } else {
            $tplData['logged'] = 0;
        }

        return $tplData;
    }
}