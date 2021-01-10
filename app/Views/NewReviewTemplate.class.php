<?php


namespace kivweb\Views;

/**
 * Class NewReviewTemplate  View pro prideleni nove recenze
 * @package kivweb\Views
 * @author Viktor Kotlan
 */
class NewReviewTemplate implements IView
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

        if(isset($tplData['review'])){
            if($tplData['review'] == "1"){
                $res .= '
                <div class="alert alert-success">
                    <strong>Úspěch!</strong> Vybraná recenze byla přiřazena!
                </div>
                <div>
                    <a class="btn btn-primary btn-block" href="index.php?page=reviews" role="button"> Seznam příspěvků </a>
                </div>';
                $tplData['logged'] = 2;
            } else{
                $res .= '
                <div class="alert alert-danger">
                    <strong>Whoops!</strong> Přiřazení recenze se nepovedlo!
                </div>';
            }
        }

        //// Prihlaseny admin? ////

        if($tplData['logged'] == 1 && $tplData['allowed'] == 1){
            $res .= '
            <form action="" method="POST"">
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text">Článek</span>
                    </div>
                    <select name="articleIn" class="form-control">';
                        foreach ($tplData['articles'] as $u) {

                            $res .= '
                            <option value="'.$u['id_prispevku'].'">'.$u["nazev"].' od '.$u["login"].'</option>
                            
                            ';
                        }
                    $res .= '</select>

                </div>
                <div class="input-group mb-0">
                    <div class="input-group-prepend">
                      <span class="input-group-text">Recenzent</span>
                    </div>                    
                    <select name="reviewerIn" class="form-control">';
                        foreach ($tplData['reviewers'] as $u) {

                            $res .= '
                            <option value="'.$u['id_uzivatele'].'">'.$u["jmeno"].' '.$u["prijmeni"].'</option>
                            
                            ';
                        }
                    $res .= '</select>                     
                </div>
                <br><button type="submit" name="action" value="newreview_submit" class="btn btn-primary btn-block">Přidělit recenzi</button>
                <a class="btn btn-link btn-block" href="index.php?page=articles" role="button"> Zpět na seznam článků. </a>
            </form>';
        } elseif($tplData['logged'] == 0 || $tplData['allowed'] == 0) {

            $res .= '
            <div>
                Pro přidání recenze nemáte dostatečná práva...
            </div>';
        }

        echo $res;
        // paticka
        $tplHeaders->getHTMLFooter();

    }
}
