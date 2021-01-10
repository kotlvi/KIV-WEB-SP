<?php
// nactu funkci vlastniho autoloaderu trid
// pozn.: protoze je pouzit autoloader trid, tak toto je (vyjma TemplateBased sablon) jediny soubor aplikace, ktery pouziva funkci require_once
require_once("myAutoloader.inc.php");

// nactu vlastni nastaveni webu
require_once("settings.inc.php");

// spustim aplikaci
$app = new \kivweb\ApplicationStart();
$app->appStart();

?>
