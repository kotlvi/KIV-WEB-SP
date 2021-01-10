<?php

namespace kivweb\Models;

/**
 * Trida spravujici databazi.
 * @package kivweb\Models
 * @author Viktor Kotlan
 */
class DatabaseModel {

    /** @var DatabaseModel $database  Singleton databazoveho modelu. */
    private static $database;

    /** @var \PDO $pdo  Objekt pracujici s databazi prostrednictvim PDO. */
    private $pdo;

    /** @var string $dDate  Klic pro ulozeni datumu do session. */
    private $dDate = "datum";

    /**
     * Inicializace pripojeni k databazi.
     */
    private function __construct() {
        // inicializace DB
        $this->pdo = new \PDO("mysql:host=".DB_SERVER.";dbname=".DB_NAME, DB_USER, DB_PASS);
        // vynuceni kodovani UTF-8
        $this->pdo->exec("set names utf8");
    }

    /**
     * Tovarni metoda pro poskytnuti singletonu databazoveho modelu.
     * @return DatabaseModel    Databazovy model.
     */
    public static function getDatabaseModel(){
        if(empty(self::$database)){
            self::$database = new DatabaseModel();
        }
        return self::$database;
    }


    //////////////////////////////////////////////////////////
    ///////////  Prace s databazi  ///////////////////////////
    //////////////////////////////////////////////////////////


    //////////////////////////////////////////////////////////
    ///////////////////  Obecné funkce  //////////////////////
    //////////////////////////////////////////////////////////


    /**
     * Jednoduche cteni z prislusne DB tabulky.
     * @param string $tableName         Nazev tabulky.
     * @param string $whereStatement    Pripadne omezeni na ziskani radek tabulky. Default "".
     * @param string $orderByStatement  Pripadne razeni ziskanych radek tabulky. Default "".
     * @return array                    Vraci pole ziskanych radek tabulky.
     */
    public function selectFromTable(string $tableName, string $whereStatement = "", string $orderByStatement = ""):array {
        $q = "SELECT * FROM ".$tableName
            .(($whereStatement == "") ? "" : " WHERE $whereStatement")
            .(($orderByStatement == "") ? "" : " ORDER BY $orderByStatement");
        // provedu ho a vratim vysledek
        $obj = $this->pdo->query($q);
        // pokud je null, tak vratim prazdne pole
        if($obj == null){
            return [];
        }
        return $obj->fetchAll();
    }

    /**
     * Jednoduchy zapis do prislusne tabulky.
     * @param string $tableName         Nazev tabulky.
     * @param string $insertStatement   Text s nazvy sloupcu pro insert.
     * @param string $insertValues      Text s hodnotami pro prislusne sloupce.
     * @return bool                     Vlozeno v poradku?
     */
    public function insertIntoTable(string $tableName, string $insertStatement, string $insertValues):bool {
        // slozim dotaz
        $q = "INSERT INTO $tableName($insertStatement) VALUES ($insertValues)";
        // provedu ho a vratim uspesnost vlozeni
        $obj = $this->pdo->query($q);
        if($obj == null){
            return false;
        } else {
            return true;
        }
    }

    /**
     * Jednoducha uprava radku databazove tabulky.
     * @param string $tableName                     Nazev tabulky.
     * @param string $updateStatementWithValues     Cela cast updatu s hodnotami.
     * @param string $whereStatement                Cela cast pro WHERE.
     * @return bool                                 Upraveno v poradku?
     */
    public function updateInTable(string $tableName, string $updateStatementWithValues, string $whereStatement):bool {
        // slozim dotaz
        $q = "UPDATE $tableName SET $updateStatementWithValues WHERE $whereStatement";
        // provedu ho a vratim vysledek
        $obj = $this->pdo->query($q);
        if($obj == null){
            return false;
        } else {
            return true;
        }
    }

    /**
     * Dle zadane podminky maze radky v prislusne tabulce.
     * @param string $tableName         Nazev tabulky.
     * @param string $whereStatement    Podminka mazani.
     */
    public function deleteFromTable(string $tableName, string $whereStatement){
        // slozim dotaz
        $q = "DELETE FROM $tableName WHERE $whereStatement";
        // provedu ho a vratim vysledek
        $obj = $this->pdo->query($q);
        if($obj == null){
            return false;
        } else {
            return true;
        }
    }


    //////////////////////////////////////////////////////////
    ///////////////////  Specifické funkce  //////////////////
    //////////////////////////////////////////////////////////


    //////////////////////////////////////////////////////////
    //////////////////////////  Funkce s uzivateli  //////////
    //////////////////////////////////////////////////////////


    /**
     *  Vrati seznam vsech uzivatelu pro spravu uzivatelu.
     *  @return array Obsah spravy uzivatelu.
     */
    public function getAllUsers():array {
        return $this->selectFromTable(TABLE_USER, "", "");
    }

    /**
     *  Vrati seznam vsech autoru
     *  @return array
     */
    public function getOnlyUsers():array {
        return $this->selectFromTable(TABLE_USER, "id_prava = 1", "");
    }

    /**
     *  Vrati seznam vsech recenzentu
     *  @return array
     */
    public function getOnlyReviewers():array {
        return $this->selectFromTable(TABLE_USER, "id_prava = 2", "");
    }

    /**
     *  Vrati seznam vsech adminu
     *  @return array
     */
    public function getOnlyAdmins():array {
        return $this->selectFromTable(TABLE_USER, "id_prava = 3", "");
    }

    /**
     * Vrati data uzivatele podle jeho ID
     * @param $userID   ID Uzivatele
     * @return array
     */
    public function getUserDataByID($userID){
        $userID = intval($userID);

        $q = "SELECT * FROM ".TABLE_USER." WHERE id_uzivatele=:uID;";
        $vystup = $this->pdo->prepare($q);
        $vystup->bindValue(":uID", $userID);

        if($vystup->execute()){
            // dotaz probehl v poradku
            // vsechny radky do pole a to vratim
            return $vystup->fetchAll();
        } else {
            // dotaz skoncil chybou
            return null;
        }
    }

    /**
     * Vrati data uzivatele podle jeho loginu
     * @param string $login
     * @return array
     */
    public function getUserDataByLogin(string $login){
        $login = htmlspecialchars($login);

        $q = "SELECT * FROM ".TABLE_USER." WHERE login=:uLogin;";
        $vystup = $this->pdo->prepare($q);
        $vystup->bindValue(":uLogin", $login);

        if($vystup->execute()){
            // dotaz probehl v poradku
            // vsechny radky do pole a to vratim
            return $vystup->fetchAll();
        } else {
            // dotaz skoncil chybou
            return null;
        }
    }

    /**
     * Vrati ulozene zasifrovane heslo z databaze
     * @param string $login
     * @return array|null
     */
    public function getUserHash(string $login){
        $login = htmlspecialchars($login);

        $q = "SELECT heslo FROM ".TABLE_USER." WHERE login=:uLogin;";
        $vystup = $this->pdo->prepare($q);
        $vystup->bindValue(":uLogin", $login);

        if($vystup->execute()){
            // dotaz probehl v poradku
            // vsechny radky do pole a to vratim
            return $vystup->fetchAll();
        } else {
            // dotaz skoncil chybou
            return null;
        }
    }

    /**
     * Vrati opravneni uzivatele dle ID jeho prava
     * @param int $rank_id
     * @return mixed|null
     */
    public function getUserRank(int $rank_id){
        $rank_id = intval($rank_id);

        $q = "SELECT * FROM ".TABLE_RANK." WHERE id_prava=:uID;";
        $vystup = $this->pdo->prepare($q);
        $vystup->bindValue(":uID", $rank_id);

        if($vystup->execute()){
            // dotaz probehl v poradku
            // vsechny radky do pole a to vratim
            return $vystup->fetchAll()[0];
        } else {
            // dotaz skoncil chybou
            return null;
        }
    }


    /**
     * Vytvoreni noveho uzivatele v databazi.
     * @param string $jmeno     Jmeno.
     * @param string $prijmeni  Prijmeni
     * @param string $login     Login.
     * @param string $heslo     Heslo
     * @param string $email     E-mail.
     * @return bool             Vlozen v poradku?
     */
    public function addNewUser(string $jmeno, string $prijmeni, string $login, string $heslo, string $email){
        $jmeno = htmlspecialchars($jmeno);
        $prijmeni = htmlspecialchars($prijmeni);
        $login = htmlspecialchars($login);
        $heslo = htmlspecialchars($heslo);
        $email = htmlspecialchars($email);

        $q = "INSERT INTO ".TABLE_USER." (jmeno, prijmeni, login, heslo, email) VALUES (:jmeno, :prijmeni, :login, :heslo, :email);";
        $vystup = $this->pdo->prepare($q);
        $vystup->bindValue(":jmeno", $jmeno);
        $vystup->bindValue(":prijmeni", $prijmeni);
        $vystup->bindValue(":login", $login);
        $vystup->bindValue(":heslo", $heslo);
        $vystup->bindValue(":email", $email);

        if($vystup->execute()){
            // dotaz probehl v poradku
            // vsechny radky do pole a to vratim
            return 1;
        } else {
            // dotaz skoncil chybou
            return 0;
        }
    }

    /**
     * Uprava konkretniho uzivatele v databazi.
     * @param int $idUzivatele  ID upravovaneho uzivatele.
     * @param string $jmeno     Jmeno
     * @param string $prijmeni  Prijmeni
     * @param string $login     Login.
     * @param string $heslo     Heslo.
     * @param string $email     E-mail.
     * @param int $id_prava     ID prava.
     * @return bool             Bylo upraveno?
     */
    public function updateUser(int $idUzivatele, string $jmeno, string $prijmeni, string $login, string $heslo, string $email, int $id_prava){
        $idUzivatele = intval($idUzivatele);
        $jmeno = htmlspecialchars($jmeno);
        $prijmeni = htmlspecialchars($prijmeni);
        $login = htmlspecialchars($login);
        $heslo = htmlspecialchars($heslo);
        $email = htmlspecialchars($email);
        $id_prava = intval($id_prava);

        $q = "UPDATE ".TABLE_USER." SET jmeno=:jmeno, prijmeni=:prijmeni, login=:login, heslo=:heslo, email=:email, id_prava=:id_prava WHERE id_uzivatele=:id_uzivatele;";
        $vystup = $this->pdo->prepare($q);
        $vystup->bindValue(":jmeno", $jmeno);
        $vystup->bindValue(":prijmeni", $prijmeni);
        $vystup->bindValue(":login", $login);
        $vystup->bindValue(":heslo", $heslo);
        $vystup->bindValue(":email", $email);
        $vystup->bindValue(":id_prava", $id_prava);
        $vystup->bindValue(":id_uzivatele", $idUzivatele);

        if($vystup->execute()){
            // dotaz probehl v poradku
            // vsechny radky do pole a to vratim
            return 1;
        } else {
            // dotaz skoncil chybou
            return 0;
        }
    }

    /**
     *  Smaze daneho uzivatele z DB.
     *  @param int $userId  ID uzivatele.
     */
    public function deleteUser(int $userId):bool {
        $userId = intval($userId);
        return $this->deleteFromTable(TABLE_USER, "id_uzivatele = $userId");
    }


    //////////////////////////////////////////////////////////
    //////////////////////////  Funkce s prispevky  //////////
    //////////////////////////////////////////////////////////


    /**
     *  Vrati seznam vsech clanku spolecne s jejich autory
     *  @return array
     */
    public function getAllArticlesUsers():array {
        $q = "SELECT * FROM ".TABLE_ARTICLE." INNER JOIN ".TABLE_USER." ON ".TABLE_ARTICLE.".id_autora=".TABLE_USER.".id_uzivatele";
        $obj = $this->pdo->query($q);
        // pokud je null, tak vratim prazdne pole
        if($obj == null){
            return [];
        }
        return $obj->fetchAll();
    }

    /**
     *  Vrati seznam vsech clanku, ktere byly schvaleny spolecne s jejich autory
     *  @return array
     */
    public function getAllArticlesUsersApproved():array {
        $q = "SELECT * FROM ".TABLE_ARTICLE." INNER JOIN ".TABLE_USER." ON ".TABLE_ARTICLE.".id_autora=".TABLE_USER.".id_uzivatele WHERE stav = 1";
        $obj = $this->pdo->query($q);
        // pokud je null, tak vratim prazdne pole
        if($obj == null){
            return [];
        }
        return $obj->fetchAll();
    }

    /**
     *  Vrati seznam vsech clanku, ktere nebyly schvaleny spolecne s jejich autory
     *  @return array
     */
    public function getAllArticlesUsersNotApproved():array {
        $q = "SELECT * FROM ".TABLE_ARTICLE." INNER JOIN ".TABLE_USER." ON ".TABLE_ARTICLE.".id_autora=".TABLE_USER.".id_uzivatele WHERE stav = 0";
        $obj = $this->pdo->query($q);
        // pokud je null, tak vratim prazdne pole
        if($obj == null){
            return [];
        }
        return $obj->fetchAll();
    }

    /**
     *  Vrati seznam vsech clanku daneho uzivatele
     *  @return array
     */
    public function getAllArticlesOfUser(int $userID):array {
        $userID = intval($userID);

        $q = "SELECT * FROM ".TABLE_ARTICLE." INNER JOIN ".TABLE_USER." ON ".TABLE_ARTICLE.".id_autora=".TABLE_USER.".id_uzivatele WHERE id_autora =:userID;";
        $vystup = $this->pdo->prepare($q);
        $vystup->bindValue(":userID", $userID);

        if($vystup->execute()){
            // dotaz probehl v poradku
            // vsechny radky do pole a to vratim
            return $vystup->fetchAll();
        } else {
            // dotaz skoncil chybou
            return [];
        }
    }

    /**
     * Prida novy prispevek do databaze.
     * @param string $nazev
     * @param string $id_autora
     * @param string $abstrakt
     * @param string $fileName
     * @return bool             Vlozen v poradku?
     */
    public function addNewArticle(string $nazev, string $id_autora, string $abstrakt, string $fileName){
        $nazev = htmlspecialchars($nazev);
        $id_autora = intval($id_autora);
        $abstrakt = htmlspecialchars($abstrakt);
        $fileName = htmlspecialchars($fileName);

        $q = "INSERT INTO ".TABLE_ARTICLE." (nazev, id_autora, abstrakt, datum_vydani, nazev_pdf) VALUES (:nazev, :id_autora, :abstrakt, NOW(), :nazev_pdf);";
        $vystup = $this->pdo->prepare($q);
        $vystup->bindValue(":nazev", $nazev);
        $vystup->bindValue(":id_autora", $id_autora);
        $vystup->bindValue(":abstrakt", $abstrakt);
        $vystup->bindValue(":nazev_pdf", $fileName);

        if($vystup->execute()){
            // dotaz probehl v poradku
            // vsechny radky do pole a to vratim
            return 1;
        } else {
            // dotaz skoncil chybou
            return 0;
        }
    }

    /**
     * Schvaleni prispevku
     * @param int $idPrispevku
     * @return int
     */
    public function approveArticle(int $idPrispevku){
        $idPrispevku = intval($idPrispevku);

        $q = "UPDATE ".TABLE_ARTICLE." SET stav = 1 WHERE id_prispevku=:id_prispevku;";
        $vystup = $this->pdo->prepare($q);
        $vystup->bindValue(":id_prispevku", $idPrispevku);

        if($vystup->execute()){
            // dotaz probehl v poradku
            // vsechny radky do pole a to vratim
            return 1;
        } else {
            // dotaz skoncil chybou
            return 0;
        }
    }

    /**
     *  Smaze dany prispevek z DB.
     *  @param int $userId  ID uzivatele.
     */
    public function deleteArticle(int $articleId):bool {
        $articleId = intval($articleId);
        return $this->deleteFromTable(TABLE_ARTICLE, "id_prispevku = $articleId");
    }


    //////////////////////////////////////////////////////////
    //////////////////////////  Funkce s recenzemi  //////////
    //////////////////////////////////////////////////////////


    /**
     *  Vrati seznam vsech recenzi
     *  @return array Obsah uvodu.
     */
    public function getAllReviews():array {
        return $this->selectFromTable(TABLE_REVIEW, "", "");
    }

    /**
     * Vrati seznam vsech recenzi s autory k danym clankum
     * @param int $status   Chceme jiz dokoncene nebo nedokoncene recenze?
     * @return array
     */
    public function getAllReviewsArticlesUsers(int $status):array {
        $status = intval($status);
        $where = "WHERE finalni_podoba = $status";

        $q = "SELECT * FROM ".TABLE_REVIEW."
         INNER JOIN ".TABLE_USER." ON ".TABLE_REVIEW.".id_autora=".TABLE_USER.".id_uzivatele 
         INNER JOIN ".TABLE_ARTICLE." ON ".TABLE_REVIEW.".id_prispevku=".TABLE_ARTICLE.".id_prispevku $where";
        $obj = $this->pdo->query($q);
        // pokud je null, tak vratim prazdne pole
        if($obj == null){
            return [];
        }
        return $obj->fetchAll();
    }

    /**
     * Vrati seznam vsech recenzi daneho autora k danym clankum
     * @param int $userID
     * @param int $status
     * @return array
     */
    public function getAllReviewsArticlesUsersOfUser(int $userID, int $status):array {
        $userID = intval($userID);
        $status = intval($status);
        $where = "finalni_podoba = $status";

        $q = "SELECT * FROM ".TABLE_REVIEW."
         INNER JOIN ".TABLE_USER." ON ".TABLE_REVIEW.".id_autora=".TABLE_USER.".id_uzivatele 
         INNER JOIN ".TABLE_ARTICLE." ON ".TABLE_REVIEW.".id_prispevku=".TABLE_ARTICLE.".id_prispevku WHERE id_uzivatele = $userID AND $where";
        $obj = $this->pdo->query($q);
        // pokud je null, tak vratim prazdne pole
        if($obj == null){
            return [];
        }
        return $obj->fetchAll();
    }

    /**
     * Prida novou recenzi do DB
     * @param string $id_autora
     * @param string $id_prispevku
     * @return int
     */
    public function addNewReview(string $id_autora, string $id_prispevku){
        $id_autora = intval($id_autora);
        $id_prispevku = intval($id_prispevku);

        $q = "INSERT INTO ".TABLE_REVIEW." (id_autora, id_prispevku) VALUES (:id_autora, :id_prispevku);";
        $vystup = $this->pdo->prepare($q);
        $vystup->bindValue(":id_autora", $id_autora);
        $vystup->bindValue(":id_prispevku", $id_prispevku);

        if($vystup->execute()){
            // dotaz probehl v poradku
            // vsechny radky do pole a to vratim
            return 1;
        } else {
            // dotaz skoncil chybou
            return 0;
        }
    }

    /**
     * Aktualizuje udaje v recenzi
     * @param int $idRecenze
     * @param int $originalita
     * @param int $tema
     * @param int $technika
     * @param int $jazyk
     * @param int $obecne
     * @param string $poznamka
     * @return int
     */
    public function updateReview(int $idRecenze, int $originalita, int $tema, int $technika, int $jazyk, int $obecne,string $poznamka){
        $idRecenze = intval($idRecenze);
        $originalita = intval($originalita);
        $tema = intval($tema);
        $technika = intval($technika);
        $jazyk = intval($jazyk);
        $obecne = intval($obecne);
        $poznamka = htmlspecialchars($poznamka);

        $q = "UPDATE ".TABLE_REVIEW." SET zn_originalita =:originalita, zn_tema =:tema, zn_tech_kvalita =:technika, zn_jazyk=:jazyk, zn_obecne=:obecne, poznamka=:poznamka WHERE id_recenze=:id_recenze;";
        $vystup = $this->pdo->prepare($q);
        $vystup->bindValue(":originalita", $originalita);
        $vystup->bindValue(":tema", $tema);
        $vystup->bindValue(":technika", $technika);
        $vystup->bindValue(":jazyk", $jazyk);
        $vystup->bindValue(":obecne", $obecne);
        $vystup->bindValue(":poznamka", $poznamka);
        $vystup->bindValue(":id_recenze", $idRecenze);

        if($vystup->execute()){
            // dotaz probehl v poradku
            // vsechny radky do pole a to vratim
            return 1;
        } else {
            // dotaz skoncil chybou
            return 0;
        }
    }

    /**
     * Odevzdani recenze
     * @param int $idRecenze
     * @param string $idPrispevku
     * @return bool
     */
    public function finishReview(int $idRecenze, string $idPrispevku){
        $idRecenze = intval($idRecenze);
        $idPrispevku = intval($idPrispevku);

        // slozim cast s hodnotami
        $updateStatementWithValues = "finalni_podoba = 1";
        // podminka
        $whereStatement = "id_recenze=$idRecenze";
        // provedu update
        $q = $this->selectFromTable(TABLE_ARTICLE, "id_prispevku = $idPrispevku");
        $q = intval($q[0]['p_recenzi']) + 1;
        $this->updateInTable(TABLE_ARTICLE, "p_recenzi = $q", "id_prispevku = $idPrispevku");

        return $this->updateInTable(TABLE_REVIEW, $updateStatementWithValues, $whereStatement);
    }


    /**
     *  Smaze danou recenzi z DB.
     *  @param int $userId  ID uzivatele.
     */
    public function deleteReview(int $reviewId):bool {
        $reviewId = intval($reviewId);

        return $this->deleteFromTable(TABLE_REVIEW, "id_recenze = $reviewId");
    }

    //////////////////////////////////////////////////////////
    ///////////  KONEC: Prace s databazi  /////////////////////////
    //////////////////////////////////////////////////////////


}

?>
