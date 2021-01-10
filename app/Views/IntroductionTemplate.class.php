<?php

namespace kivweb\Views;

use kivweb\Views\IView;

/**
 * Sablona pro zobrazeni uvodni stranky.
 * @package kivweb\Views\ClassBased
 * @author Viktor Kotlan
 */
class IntroductionTemplate implements IView {

    /**
     * Zajisti vypsani HTML sablony prislusne stranky.
     * @param array $tplData Data stranky.
     */
    public function printOutput(array $tplData)
    {
        $tplHeaders = new TemplateBasics();
        // hlavicka
        $tplHeaders->getHTMLHeader($tplData['title']);
        $res = "";
        $res .= '<div class="container">';

        if(array_key_exists('articles', $tplData)) {
            foreach ($tplData['articles'] as $d) {
                $res .= '<div class="jumbotron">';
                    $res .= "<h2>$d[nazev]</h2>";
                    $res .= "<b>Autor:</b> $d[jmeno] $d[prijmeni] (" . date("d. m. Y, H:i.s", strtotime($d['datum_vydani'])) . ")<br><br>";
                    $res .= '<div class="row">
                        <div class="btn-group">
                            <button class="btn btn-link btn-lg btn-primary text-light" data-toggle="collapse" data-target="#'.$d["login"].$d["id_prispevku"].'">
                            <span class="fa fa-plus-circle"></span> Ukaž víc </button>
                            <button class="btn btn-lg btn-outline-primary"">
                            <span class="fa fa-download"></span> Stáhnout PDF </button>
                        </div>
                        <div id="'.$d["login"].$d["id_prispevku"].'" class="col-12 collapse text-justify">
                            '.$d["abstrakt"].'
                        </div>
                    </div>';
                    

                $res .= '</div>';
            }
        } else {
            $res .= "Články nenalezeny";
        }
        $res .= '</div>';
        echo $res;


        // paticka
        $tplHeaders->getHTMLFooter();
    }

}

?>


