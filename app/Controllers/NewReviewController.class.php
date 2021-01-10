<?php


namespace kivweb\Controllers;

use kivweb\Models\DatabaseModel;
use kivweb\Models\Session;

/**
 * Class NewReviewController        Controller pro prideleni nove recenze
 * @package kivweb\Controllers
 * @author Viktor Kotlan
 */
class NewReviewController implements IController
{
    /** @var Session $session Vlastni objekt pro spravu session. */
    private $session;

    /** @var DatabaseModel $db Sprava databaze. */
    private $db;

    /**
     * Inicializace pripojeni k databazi.
     */
    public function __construct()
    {
        $this->db = DatabaseModel::getDatabaseModel();
        $this->session = Session::getSessionModel();
    }

    /**
     * Vrati obsah stranky pro prideleni recenze
     * @param string $pageTitle Nazev stranky.
     * @return array                Vytvorena data pro sablonu.
     */
    public function show(string $pageTitle): array{
        $tplData = [];
        $tplData['title'] = $pageTitle;

        //// Pozadavek na prideleni recenze? ////

        if(isset($_POST['action'])){
            if( $_POST['action'] == "newreview_submit") {
                if(isset($_POST['articleIn']) && isset($_POST['reviewerIn'])){

                    $ok = $this->db->addNewReview($_POST['reviewerIn'], $_POST['articleIn']);
                    if($ok){
                        $tplData['review'] = 1;
                    } else {
                        $tplData['review'] = 0;
                    }
                }
            }
        }

        //// Nactu aktualni data uzivatelu ////

        if($this->session->isUserLogged()){
            // ziskam data prihlasenoho uzivatele
            $tplData['logged'] = 1;

            $userId = $this->session->getLoggedUserID();
            $userData = $this->db->getUserDataByID($userId);

            if(!empty($userData) && $userData[0]['id_prava'] == 3){
                $tplData['allowed'] = 1;
                $tplData['articles'] = $this->db->getAllArticlesUsersNotApproved();
                $tplData['reviewers'] = $this->db->getOnlyReviewers();

            } else {
                $tplData['allowed'] = 0;
            }
        } else {
            $tplData['logged'] = 0;
        }

        return $tplData;
    }
}