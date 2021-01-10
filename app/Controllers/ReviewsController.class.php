<?php


namespace kivweb\Controllers;

use kivweb\Models\DatabaseModel;
use kivweb\Models\Session;

/**
 * Class ReviewsController          Controller pro spravu recenzi
 * @package kivweb\Controllers
 * @author Viktor Kotlan
 */
class ReviewsController implements IController
{
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
     * Vrati obsah stranky se spravou uzivatelu
     * @param string $pageTitle     Nazev stranky.
     * @return array                Vytvorena data pro sablonu.
     */
    public function show(string $pageTitle):array {
        $tplData = [];
        $tplData['title'] = $pageTitle;

        if(isset($_POST['action'])){

            //// Neprisel pozadavek na smazani recenze? ////

            if( $_POST['action'] == "deleteReview" and isset($_POST['id_recenze'])) {
                $tplData['deletedReview'] = $_POST['id_recenze'];
                $ok = $this->db->deleteReview(intval($_POST['id_recenze']));
                if($ok){
                    $tplData['delete'] = 1;
                } else {
                    $tplData['delete'] = 0;
                }

            //// Neprisel pozadavek na ulozeni recenze? ////

            } elseif ($_POST['action'] == "update_submit" and isset($_POST['id_recenze'])){
                $tplData['updatedReview'] = $_POST['id_recenze'];

                if(isset($_POST['originalita']) && isset($_POST['tema']) && isset($_POST['technika']) && isset($_POST['jazyk']) && isset($_POST['celkem']) && isset($_POST['poznamka']))
                {
                    $res = $this->db->updateReview(intval($_POST['id_recenze']), intval($_POST['originalita']), intval($_POST['tema']), intval($_POST['technika']), intval($_POST['jazyk']), intval($_POST['celkem']), $_POST['poznamka']);
                    if($res){
                        $tplData['update'] = 1;
                    } else {
                        $tplData['update'] = 0;
                    }
                } else {
                    $tplData['update'] = 0;
                }

            //// Neprisel pozadavek na potvrzeni recenze? ////

            } elseif ($_POST['action'] == "submit" and isset($_POST['id_recenze']) and isset($_POST['id_prispevku'])){
                $tplData['submitedReview'] = $_POST['id_recenze'];
                $res = $this->db->finishReview(intval($_POST['id_recenze']), $_POST['id_prispevku']);
                if($res){
                    $tplData['submit'] = 1;
                } else {
                    $tplData['submit'] = 0;
                }
            }
        }

        //Aktualizace dat pro povolene uzivatele
        if($this->session->isUserLogged()){
            // ziskam data prihlasenoho uzivatele
            $tplData['logged'] = 1;

            $userId = $this->session->getLoggedUserID();
            $userData = $this->db->getUserDataByID($userId);

            if(!empty($userData)){
                if ($userData[0]['id_prava'] == 2){
                    $tplData['review_work'] = $this->db->getAllReviewsArticlesUsersOfUser((int)$userId, 0);
                    $tplData['review_done'] = $this->db->getAllReviewsArticlesUsersOfUser((int)$userId, 1);
                    $tplData['rights'] = 2;
                } elseif ($userData[0]['id_prava'] == 3){
                    $tplData['review_work'] = $this->db->getAllReviewsArticlesUsers(0);
                    $tplData['review_done'] = $this->db->getAllReviewsArticlesUsers(1);
                    $tplData['rights'] = 3;
                } else{
                    $tplData['rights'] = 1;
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