<?php


namespace kivweb\Controllers;

use kivweb\Models\DatabaseModel;
use kivweb\Models\Session;

/**
 * Class MenuContentController  Controller pro správné načtení menu
 * @package kivweb\Controllers
 * @author Viktor Kotlan
 */
class MenuContentController
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
     * Vrati polozky menu pro daneho uzivatele
     */
    public function getMenuContent(){

        //Je prihlasen?
        if($this->session->isUserLogged()) {
            $userId = $this->session->getLoggedUserID();
            $userData = $this->db->getUserDataByID($userId);
            //Autor, recenzent nebo admin?
            if(!empty($userData)){
                if ($userData[0]['id_prava'] == 1) {
                    return USER_WEB_PAGES;
                }
                if ($userData[0]['id_prava'] == 2) {
                    return Reviewer_WEB_PAGES;
                }
                if ($userData[0]['id_prava'] == 3) {
                    return Admin_WEB_PAGES;
                }
            }
        }

        return null;
    }
}