<?php


namespace kivweb\Controllers;

use kivweb\Models\DatabaseModel;
use kivweb\Models\Session;

/**
 * Class NewArticleController   Controller pro vytvoreni noveho prispevku
 * @package kivweb\Controllers
 * @author Viktor Kotlan
 */
class NewArticleController implements IController
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
     * Vrati obsah stranky pro vytvoreni noveho prispevku
     * @param string $pageTitle     Nazev stranky.
     * @return array                Vytvorena data pro sablonu.
     */
    public function show(string $pageTitle):array {
        $tplData = [];
        $tplData['title'] = $pageTitle;

        //Uzivatel prihlasen?
        if($this->session->isUserLogged()) {
            // ziskam data prihlasenoho uzivatele
            $tplData['logged'] = 1;
            $userId = $this->session->getLoggedUserID();
            $userData = $this->db->getUserDataByID($userId);

            //// Je to autor, aby mohl vytvorit prispevek? ////

            if(!empty($userData) && $userData[0]['id_prava'] == 1){
                $tplData['allowed'] = 1;
            } else {
                $tplData['allowed'] = 0;
            }

            //// Byl odeslan novy prispevek? ////

            if (isset($_POST['newarticle_submit'])) {
                $targetDir = "uploads/";
                $fileName = basename($_FILES["pdf"]["name"]);
                $targetFilePath = $targetDir . $fileName;
                $fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);
                //Jsou zadany vsechny potrebne parametry?
                if (isset($_POST['nazev']) && isset($_POST['abstrakt']) && !empty($_FILES['pdf']['name'])
                    && $_POST['nazev'] != "" && $_POST['abstrakt'] != "") {
                    $userID = $this->session->getLoggedUserID();
                    $allowedType = 'pdf';
                    //Je soubor PDF?
                    if($fileType == $allowedType){
                        if(move_uploaded_file($_FILES["pdf"]["tmp_name"], $targetFilePath)){
                            //Upload souboru na server
                            $res = $this->db->addNewArticle($_POST['nazev'], $userID, $_POST['abstrakt'], $fileName);
                            if ($res) {
                                $tplData['newarticle'] = "1";
                            } else {
                                $tplData['newarticle'] = "2";
                            }
                        }
                    } else {
                        $tplData['newarticle'] = "3";
                    }

                } else {
                    // nemam vsechny atributy
                    $tplData['newarticle'] = "4";
                }
            }
        } else {
            $tplData['logged'] = 0;
        }

        return $tplData;
    }
}