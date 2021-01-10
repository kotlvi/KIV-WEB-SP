<?php
//////////////////////////////////////////////////////////////////
/////////////////  Globalni nastaveni aplikace ///////////////////
//////////////////////////////////////////////////////////////////

//// Pripojeni k databazi ////

/** Adresa serveru. */
define("DB_SERVER","localhost");
/** Nazev databaze. */
define("DB_NAME","kivweb");
/** Uzivatel databaze. */
define("DB_USER","root");
/** Heslo uzivatele databaze */
define("DB_PASS","");

//// Nazvy tabulek v DB ////

/** Tabulka s uzivateli. */
define("TABLE_USER", "herniweb_uzivatele");
/** Tabulka s prispevky. */
define("TABLE_ARTICLE", "herniweb_prispevky");
/** Tabulka s recenzemi. */
define("TABLE_REVIEW", "herniweb_recenze");
/** Tabulka s pravy. */
define("TABLE_RANK", "herniweb_prava_uzivatelu");

//// Nazev namespace, ktery bude pri registraci nahrazen za vychozi adresar aplikace ////

/** @var string BASE_NAMESPACE_NAME  Zakladni namespace. */
const BASE_NAMESPACE_NAME = "kivweb";
/** @var string BASE_APP_DIR_NAME  Vychozi adresar aplikace. */
const BASE_APP_DIR_NAME = "app";
/** @var array FILE_EXTENSIONS  Dostupne pripony souboru, ktere budou testovany pri nacitani souboru pozadovanych trid. */
const FILE_EXTENSIONS = array(".class.php", ".interface.php");

/** Klic defaultni webove stranky. */
const DEFAULT_WEB_PAGE_KEY = "uvod";

/** Dostupne webove stranky. */
const WEB_PAGES = array(
    //// Uvodni stranka ////
    "uvod" => array(
        "title" => "Úvodní stránka",

        //// kontroler
        "controller_class_name" => \kivweb\Controllers\IntroductionController::class,

        // sablona
        "view_class_name" => \kivweb\Views\IntroductionTemplate::class,

    ),
    //// KONEC: Uvodni stranka ////

    //// Sprava uzivatelu ////
    "sprava" => array(
        "title" => "Správa uživatelů",

        //// kontroler
        "controller_class_name" => \kivweb\Controllers\UserManagementController::class,

        // sablona
        "view_class_name" => \kivweb\Views\UserManagementTemplate::class,
    ),
    //// KONEC: Sprava uzivatelu ////

    //// Login ////
    "login" => array(
        "title" => "Login",

        //// kontroler
        "controller_class_name" => \kivweb\Controllers\LoginController::class,

        // sablona
        "view_class_name" => \kivweb\Views\LoginTemplate::class,
    ),
    //// KONEC: Login ////

    //// Registrace ////
    "signup" => array(
        "title" => "Registrace",

        //// kontroler
        "controller_class_name" => \kivweb\Controllers\SingUpController::class,

        // sablona
        "view_class_name" => \kivweb\Views\SingUpTemplate::class,
    ),
    //// KONEC: Registrace ////

    //// Pridani prispevku ////
    "newarticle" => array(
        "title" => "Nový příspevek",

        //// kontroler
        "controller_class_name" => \kivweb\Controllers\NewArticleController::class,

        // sablona
        "view_class_name" => \kivweb\Views\NewArticleTemplate::class,
    ),
    //// KONEC: Pridani prispevku ////

    //// Pridani recenze ////
    "newreview" => array(
        "title" => "Nová recenze",

        //// kontroler
        "controller_class_name" => \kivweb\Controllers\NewReviewController::class,

        // sablona
        "view_class_name" => \kivweb\Views\NewReviewTemplate::class,
    ),
    //// KONEC: Pridani prispevku ////

    //// Zobrazeni clanku ////
    "articles" => array(
        "title" => "Články",

        //// kontroler
        "controller_class_name" => \kivweb\Controllers\ArticlesController::class,

        // sablona
        "view_class_name" => \kivweb\Views\ArticlesTemplate::class,
    ),
    //// KONEC: Zobrazeni clanku ////

    //// Zobrazeni recenzi ////
    "reviews" => array(
        "title" => "Recenze",

        //// kontroler
        "controller_class_name" => \kivweb\Controllers\ReviewsController::class,

        // sablona
        "view_class_name" => \kivweb\Views\ReviewsTemplate::class,
    ),
    //// KONEC: Zobrazeni recenzi ////
);

const USER_WEB_PAGES = array(
    //// Zobrazeni clanku ////
    "articles" => array(
        "title" => "Články",

        //// kontroler
        "controller_class_name" => \kivweb\Controllers\ArticlesController::class,

        // sablona
        "view_class_name" => \kivweb\Views\ArticlesTemplate::class,
    ),
    //// KONEC: Zobrazeni clanku ////
);

const Reviewer_WEB_PAGES = array(
    //// Zobrazeni recenzi ////
    "reviews" => array(
        "title" => "Recenze",

        //// kontroler
        "controller_class_name" => \kivweb\Controllers\ReviewsController::class,

        // sablona
        "view_class_name" => \kivweb\Views\ReviewsTemplate::class,
    ),
    //// KONEC: Zobrazeni recenzi ////
);

const Admin_WEB_PAGES = array(
    //// Zobrazeni clanku ////
    "articles" => array(
        "title" => "Články",

        //// kontroler
        "controller_class_name" => \kivweb\Controllers\ArticlesController::class,

        // sablona
        "view_class_name" => \kivweb\Views\ArticlesTemplate::class,
    ),
    //// KONEC: Zobrazeni clanku ////

    //// Zobrazeni recenzi ////
    "reviews" => array(
        "title" => "Recenze",

        //// kontroler
        "controller_class_name" => \kivweb\Controllers\ReviewsController::class,

        // sablona
        "view_class_name" => \kivweb\Views\ReviewsTemplate::class,
    ),
    //// KONEC: Zobrazeni recenzi ////

    //// Sprava uzivatelu ////
    "sprava" => array(
        "title" => "Správa uživatelů",

        //// kontroler
        "controller_class_name" => \kivweb\Controllers\UserManagementController::class,

        // sablona
        "view_class_name" => \kivweb\Views\UserManagementTemplate::class,
    ),
    //// KONEC: Sprava uzivatelu ////
);


?>
