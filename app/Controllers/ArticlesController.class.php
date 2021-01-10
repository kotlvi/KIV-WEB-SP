<?php

namespace kivweb\Controllers;

use kivweb\Models\DatabaseModel;
use kivweb\Models\Session;

/**
 * Class ArticlesController         Controller pro spravu clanku
 * @package kivweb\Controllers
 * @author Viktor Kotlan
 */
class ArticlesController implements IController
{
    /** @var DatabaseModel $db  Sprava databaze. */
    private $db;

    /** @var Session $session  Vlastni objekt pro spravu session. */
    private $session;

    /**
     * Inicializace pripojeni k databazi.
     */
    public function __construct() {
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

        //// Ativovana akce? ////

        if(isset($_POST['action'])){

            //// Smazani prispevku ////

            if( $_POST['action'] == "delete" and isset($_POST['id_prispevku'])) {
                $tplData['deletedArticle'] = $_POST['id_prispevku'];
                $ok = $this->db->deleteArticle(intval($_POST['id_prispevku']));
                if($ok){
                    $tplData['delete'] = 1;
                } else {
                    $tplData['delete'] = 0;
                }

            //// Schvaleni prispevku ////

            } elseif ($_POST['action'] == "submit" and isset($_POST['id_prispevku'])) {
                $tplData['approvedArticle'] = $_POST['id_prispevku'];
                $ok = $this->db->approveArticle(intval($_POST['id_prispevku']));
                if($ok){
                    $tplData['approve'] = 1;
                } else {
                    $tplData['approve'] = 0;
                }
            }
        }

        //// Overeni prav uzivatele a aktualizace dat ////

        if($this->session->isUserLogged()){
            $tplData['logged'] = 1;
            // ziskam data prihlasenoho uzivatele
            $userId = $this->session->getLoggedUserID();
            $userData = $this->db->getUserDataByID($userId);

            if(!empty($userData)){
                if($userData[0]['id_prava'] == 1){
                    $tplData['articles'] = $this->db->getAllArticlesOfUser($userId);
                    $tplData['rights'] = 1;
                } elseif ($userData[0]['id_prava'] == 2){
                    $tplData['rights'] = 2;
                } elseif ($userData[0]['id_prava'] == 3){
                    $tplData['articles'] = $this->db->getAllArticlesUsers();
                    $tplData['reviewers'] = $this->db->getOnlyReviewers();
                    $tplData['rights'] = 3;
                }
            } else {
                $tplData['logged'] = 0;
            }
        } else {
            $tplData['logged'] = 0;
        }

        // vratim sablonu naplnenou daty
        return $tplData;
    }
}