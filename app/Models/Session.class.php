<?php


namespace kivweb\Models;

/**
 * Trida pro praci se session
 * @package kivweb\Models
 * @author Viktor Kotlan
 */
class Session
{
    private static $session;

    /** @var string $userSessionKey Klicem pro data uzivatele, ktera jsou ulozena v session. */
    private $userSessionKey = "current_user_id";

    /**
     *  Pri vytvoreni objektu je zahajena session.
     */
    public function __construct()
    {
        session_start();
    }

    /**
     * Tovarni metoda pro poskytnuti singletonu session modelu.
     * @return Session    Session model.
     */
    public static function getSessionModel()
    {
        if (empty(self::$session)) {
            self::$session = new Session();
        }
        return self::$session;
    }


    //////////////////////////////////////////////////////////
    ///////////////////  Obecné funkce  //////////////////////
    //////////////////////////////////////////////////////////


    /**
     *  Funkce pro ulozeni hodnoty do session.
     * @param string $name Jmeno atributu.
     * @param mixed $value Hodnota
     */
    private function addSession($name, $value)
    {
        $_SESSION[$name] = $value;
    }

    /**
     *  Vrati hodnotu dane session nebo null, pokud session neni nastavena.
     * @param string $name Jmeno atributu.
     * @return mixed
     */
    private function readSession($name)
    {
        if ($this->isSessionSet($name)) {
            return $_SESSION[$name];
        } else {
            return null;
        }
    }

    /**
     *  Je session nastavena?
     * @param string $name Jmeno atributu.
     * @return boolean
     */
    private function isSessionSet($name)
    {
        return isset($_SESSION[$name]);
    }

    /**
     *  Odstrani danou session.
     * @param string $name Jmeno atributu.
     */
    private function removeSession($name)
    {
        unset($_SESSION[$name]);
    }


    //////////////////////////////////////////////////////////
    ///////////////////  Specifické funkce  //////////////////
    //////////////////////////////////////////////////////////


    /**
     * Prihlasi uzivatele s danym ID
     * @param string $userID
     */
    public function userLogin(string $userID)
    {
        $this->addSession($this->userSessionKey, $userID); // beru prvniho nalezeneho a ukladam jen jeho ID
    }

    /**
     * Odhlasi soucasneho uzivatele.
     */
    public function userLogout()
    {
        $this->removeSession($this->userSessionKey);
    }

    /**
     * Test, zda je nyni uzivatel prihlasen.
     * @return bool     Je prihlasen?
     */
    public function isUserLogged(): bool
    {
        return $this->isSessionSet($this->userSessionKey);
    }

    /**
     * Pokud je uzivatel prihlasen, tak vrati jeho data,
     * ale pokud nebyla v session nalezena, tak vypisu chybu.
     * @return array|null   Data uzivatele nebo null.
     */
    public function getLoggedUserID()
    {
        if ($this->isUserLogged()) {
            // ziskam data uzivatele ze session
            $userId = $this->readSession($this->userSessionKey);
            // pokud nemam data uzivatele, tak vypisu chybu a vynutim odhlaseni uzivatele
            if ($userId == null) {
                // nemam data uzivatele ze session - vypisu jen chybu, uzivatele odhlasim a vratim null
                echo "SEVER ERROR: Data přihlášeného uživatele nebyla nalezena, a proto byl uživatel odhlášen.";
                $this->userLogout();
                return null;
            } else {
                // nactu data uzivatele z databaze
                return $userId;
            }
        } else {
            // uzivatel neni prihlasen - vracim null
            return null;
        }
    }

}
