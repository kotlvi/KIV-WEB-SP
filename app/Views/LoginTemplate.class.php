<?php

namespace kivweb\Views;

use kivweb\Views\IView;

/**
 * Class LoginTemplate      View pro prihlaseni
 * @package kivweb\Views
 */
class LoginTemplate implements IView
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

        if(isset($tplData['login'])){
            if($tplData['login'] == 1) {
                $res .= '
                <div class="alert alert-success">
                    <strong>Úspěch!</strong> Vaše přihlášení proběhlo úspěšně!.
                </div>';
            } else {
                $res .= '
                <div class="alert alert-danger">
                    <strong>Whoops!</strong> Vaše jméno nebo heslo nejsou v pořádku!.
                </div>';
            }
        } elseif (isset($tplData['logout'])){
            $res .= '
                <div class="alert alert-success">
                    <strong>Úspěch!</strong> Vaše odhlášení proběhlo úspěšně!.
                </div>';
        } elseif (isset($tplData['unknown'])){
            $res .= '
                <div class="alert alert-info">
                    <strong>Error!</strong> Neznámá akce!.
                </div>';
        }

        ///////////// PRO NEPRIHLASENE UZIVATELE ///////////////
        // pokud uzivatel neni prihlasen nebo nebyla ziskana jeho data, tak vypisu prihlasovaci formular
        if($tplData['logged'] == 0){

            $res .= '
            <form action="" method="POST">
                <div class="form-group">
                    <label for="login">Login:</label>
                    <input type="login" name="login" class="form-control" placeholder="Zadejte login" id="login">
                </div>
                <div class="form-group">
                    <label for="pwd">Heslo:</label>
                    <input type="password" name="heslo" class="form-control" placeholder="Zadejte heslo" id="pwd">
                </div>
                <button type="submit" name="action" value="login" class="btn btn-primary btn-block">Přihlásit se</button>
            </form>';
            $res .= '<a class="btn btn-link btn-block" href="index.php?page=signup" role="button"> Nemáte účet? Zaregistujte se. </a>';
            $res .= '</div>';

            ///////////// KONEC: PRO NEPRIHLASENE UZIVATELE ///////////////

        } else {

            ///////////// PRO PRIHLASENE UZIVATELE /////////////
            // ziskam nazev prava uzivatele, abych ho mohl vypsat

            $res .= '
            <h2>Vaše osobní údaje</h2>
            <br>
            <table class="table table-bordered">
                <tbody>
                <tr>
                    <td>Jméno:</td>
                    <td>'.$tplData['login_user']['jmeno'].'</td>
                </tr>
                <tr>
                    <td>Příjmení:</td>
                    <td>'.$tplData['login_user']['prijmeni'].'</td>
                </tr>
                <tr>
                    <td>Login:</td>
                    <td>'.$tplData['login_user']['login'].'</td>
                </tr>
                <tr>
                    <td>Email:</td>
                    <td>'.$tplData['login_user']['email'].'</td>
                </tr>
                <tr>
                    <td>Právo:</td>
                    <td>'.$tplData['login_prava']['nazev'].'</td>
                </tr>
                </tbody>
            </table>

            <form action="" method="POST">
                 <button type="submit" name="action" value="logout" class="btn btn-primary btn-block">Odhlásit se</button>

            </form>';
        }

        ///////////// KONEC: PRO PRIHLASENE UZIVATELE /////////////

        echo $res;
        // paticka
        $tplHeaders->getHTMLFooter();

    }
}