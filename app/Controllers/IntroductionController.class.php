<?php

namespace kivweb\Controllers;

use kivweb\Models\DatabaseModel;

/**
 * Ovladac zajistujici vypsani uvodni stranky.
 * @package kivweb\Controllers
 * @author Viktor Kotlan
 */
class IntroductionController implements IController {

    /** @var DatabaseModel $db  Sprava databaze. */
    private $db;

    /**
     * Inicializace pripojeni k databazi.
     */
    public function __construct() {
        // inicializace prace s DB
        $this->db = DatabaseModel::getDatabaseModel();
    }

    /**
     * Vrati obsah uvodni stranky.
     * @param string $pageTitle     Nazev stranky.
     * @return array                Vytvorena data pro sablonu.
     */
    public function show(string $pageTitle):array {
        $tplData = [];
        $tplData['title'] = $pageTitle;

        //// Nascteni schvalenych prispevku ////

        $tplData['articles'] = $this->db->getAllArticlesUsersApproved();

        // vratim sablonu naplnenou daty
        return $tplData;
    }
}
?>
