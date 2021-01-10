<?php


namespace kivweb\Views;


use kivweb\ApplicationStart;

/**
 * Class SingUpTemplate View pro registraci uzivatelu
 * @package kivweb\Views
 */
class SingUpTemplate implements IView
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

        if(isset($tplData['signup'])){
            if($tplData['signup'] == 1){
                $res .= '
                <div class="alert alert-success">
                    <strong>Úspěch!</strong> Vaše registrace proběhla úspěšně!.
                </div>
                <div>
                    <a class="btn btn-primary btn-block" href="index.php?page=login" role="button"> Přihlásit se </a>
                </div>';
                $tplData['logged'] = 2;
            } elseif ($tplData['signup'] == 0){
                $res .= '
                <div class="alert alert-danger">
                    <strong>Whoops!</strong> Nepovedlo se nám navázat spojení s databází, zkuste to prosím znovu!.
                </div>';
            } else {
                $res .= '
                <div class="alert alert-danger">
                    <strong>Whoops!</strong> Zadané údaje nejsou v pořádku!.
                </div>';
            }
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
                    <label for="pwd1">Heslo:</label>
                    <input type="password" name="heslo" class="form-control" placeholder="Zadejte heslo" id="pwd1">
                </div>
                <div class="form-group">
                    <label for="pwd2">Ověření hesla:</label>
                    <input type="password" name="heslo2" class="form-control" placeholder="Zadejte opět heslo" id="pwd2">
                </div>
                <div class="form-group">
                    <label for="name">Jméno:</label>
                    <input type="text" name="jmeno" class="form-control" placeholder="Zadejte jméno" id="name">
                </div>
                <div class="form-group">
                    <label for="surname">Příjmení:</label>
                    <input type="text" name="prijmeni" class="form-control" placeholder="Zadejte příjmení" id="surname">
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" name="email" class="form-control" placeholder="Zadejte email" id="email">
                </div>
                <button type="submit" name="signup_submit" class="btn btn-primary btn-block">Zaregistrovat se</button>
                <a class="btn btn-link btn-block" href="index.php?page=login" role="button"> Již máte účet? Přihlašte se. </a>
            </form>';

            ///////////// KONEC: PRO NEPRIHLASENE UZIVATELE ///////////////

        } elseif ($tplData['logged'] == 1) {

            ///////////// PRO PRIHLASENE UZIVATELE ///////////////
            $res .= '
            <div>
                Tato stránka není pro přihlášené uživatele přístupná...
            </div>';
        }
            ///////////// KONEC: PRO PRIHLASENE UZIVATELE ///////////////

        echo $res;
        // paticka
        $tplHeaders->getHTMLFooter();

    }
}
