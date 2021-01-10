<?php


namespace kivweb\Views;

/**
 * Class NewArticleTemplate     View pro pridani noveho clanku
 * @package kivweb\Views
 */
class NewArticleTemplate implements IView
{
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

        if(isset($tplData['newarticle'])){
            if($tplData['newarticle'] == "1"){
                $res .= '
                <div class="alert alert-success">
                    <strong>Úspěch!</strong> Váš příspěvek byl úspěšně odeslán k recenzi!
                </div>
                <div>
                    <a class="btn btn-primary btn-block" href="index.php?page=articles" role="button"> Seznam příspěvků </a>
                </div>';
                $tplData['logged'] = 2;
            } elseif ($tplData['newarticle'] == "2"){
                $res .= '
                <div class="alert alert-danger">
                    <strong>Whoops!</strong> Vyskytla se chyba při komunikaci se serverem!
                </div>';
            } elseif ($tplData['newarticle'] == "3"){
                $res .= '
                <div class="alert alert-danger">
                    <strong>Whoops!</strong> Nahraný soubor není typu PDF!
                </div>';
            } elseif ($tplData['newarticle'] == "4"){
                $res .= '
                <div class="alert alert-danger">
                    <strong>Whoops!</strong> Zadané parametry nejsou správné!
                </div>';
            }
        }

        //// Prihlaseny autor? ////

        if($tplData['logged'] == 1 && $tplData['allowed'] == 1){
            $res .= '
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="nazev">Název:</label>
                    <input type="text" name="nazev" class="form-control" placeholder="Zadejte název" id="nazev">
                </div>
                <div class="form-group">
                <div class="form-group">
                    <label for="exampleFormControlTextarea1">Abstrakt</label>
                    <textarea class="form-control" name="abstrakt" id="exampleFormControlTextarea1" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label for="pdf">PDF:</label>
                    <input type="file" name="pdf" class="form-control" placeholder="Nahrajte PDF" id="pdf">
                </div>
                <button type="submit" name="newarticle_submit" value="Poslat k recenzi" class="btn btn-primary btn-block">Poslat k recenzi</button>
                <a class="btn btn-link btn-block" href="index.php?page=articles" role="button"> Zpět na seznam článků. </a>
            </form>';
        } elseif($tplData['logged'] == 0 || $tplData['allowed'] == 0) {

            $res .= '
            <div>
                Pro přidání příspěvku se musíte přihlásit k účtu uživatele...
            </div>';
        }

        echo $res;
        // paticka
        $tplHeaders->getHTMLFooter();

    }
}
