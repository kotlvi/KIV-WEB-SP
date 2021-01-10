<?php


namespace kivweb\Controllers;

use kivweb\Models\DatabaseModel;
use kivweb\Models\Session;

/**
 * Class LoginController            Controller pro prihlaseni
 * @package kivweb\Controllers
 * @author Viktor Kotlan
 */
class LoginController implements IController
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
     * Vrati obsah stranky pro prihlaseni
     * @param string $pageTitle     Nazev stranky.
     * @return array                Vytvorena data pro sablonu.
     */
    public function show(string $pageTitle):array {
        $tplData = [];
        $tplData['title'] = $pageTitle;

        //// Ativovana akce? ////

        if(isset($_POST['action'])){

            //// Prihlaseni ////

            if($_POST['action'] == 'login' && isset($_POST['login']) && isset($_POST['heslo'])){
                // pokusim se prihlasit uzivatele
                $hash = $this->db->getUserHash($_POST['login']);
                if($hash != null)
                {
                    if(password_verify($_POST['heslo'], $hash[0][0])){
                        $user = $this->db->getUserDataByLogin($_POST['login']);
                        if($user != null){
                            $this->session->userLogin($user[0]['id_uzivatele']);
                            $tplData['login'] = 1;
                        } else{
                            $tplData['login'] = 0;
                        }
                    } else {
                        $tplData['login'] = 0;
                    }
                } else {
                    $tplData['login'] = 0;
                }
            }

            //// Odhlaseni ////

            else if($_POST['action'] == 'logout'){
                // odhlasim uzivatele
                $this->session->userLogout();
                $tplData['logout'] = 1;
            }

            //// Neznama akce ////

            else {
                $tplData['unknown'] = 1;
            }
        }

        //// Pokud je uzivatel prihlasen, tak ziskam jeho data ////
        if($this->session->isUserLogged()){
            // ziskam data prihlasenoho uzivatele
            $tplData['logged'] = 1;
            $tplData['login_user'] = $this->getLoggedUserData();
            $tplData['login_prava'] = $this->db->getUserRank($tplData['login_user']['id_prava']);
        } else {
            $tplData['logged'] = 0;
        }

        return $tplData;
    }

    /** Nacte data prihlaseneho uzivatele
     */
    private function getLoggedUserData(){
        $userId = $this->session->getLoggedUserID();
        $userData = $this->db->getUserDataByID($userId);
        // mam data uzivatele?
        if(empty($userData)){
            // nemam - vypisu jen chybu, uzivatele odhlasim a vratim null
            $this->session->userLogout();
            return null;
        } else {
            // protoze DB vraci pole uzivatelu, tak vyjmu jeho prvni polozku a vratim ziskana data uzivatele
            return $userData[0];
        }
    }
}
?>
