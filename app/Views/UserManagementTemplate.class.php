<?php

namespace kivweb\Views;

use kivweb\Views\IView;

/**
 * Sablona pro zobrazeni stranky se spravou uzivatelu.
 * @package kivweb\Views\ClassBased
 */
class UserManagementTemplate implements IView {

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

        if(isset($tplData['delete'])){
            if($tplData['delete'] == 1){
                $res .= '
                <div class="alert alert-success">
                    <strong>Done!</strong> Uživatel s ID '.$tplData['deletedUser'].' byl odstraněn!
                </div>';
            } else {
                $res .= '
                <div class="alert alert-danger">
                    <strong>Whoops!</strong> Uživatele s ID '.$tplData['deletedUser'].' se nepodařilo odstranit...
                </div>';
            }
        } elseif (isset($tplData['update'])){
            if($tplData['update'] == 1){
                $res .= '
                <div class="alert alert-success">
                    <strong>Done!</strong> Vaše změny byly provedeny!
                </div>';
            } else {
                $res .= '
                <div class="alert alert-danger">
                    <strong>Whoops!</strong> Vaše změny nemohly být provedeny...
                </div>';
            }
        }

        //// Prihlaseny admin? ////

        if($tplData['logged'] == 1 && $tplData['allowed'] == 1) {
            $res .= '
                <h2>Uživatelé</h2>
                <br>
                <table class="table table-bordered table-striped text-center table-hover table-responsive-md">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Jméno</th>
                            <th scope="col">Příjmení</th>
                            <th scope="col">Login</th>
                            <th scope="col">E-Mail</th>
                            <th scope="col">Upravit</th>             
                            <th scope="col">Smazat</th>             
                        </tr>
                    </thead>
                    <tbody>';
            foreach($tplData['users'] as $u) {
                $target = $u["login"].$u["id_uzivatele"];
                $res .= '<tr >
                    <td > '.$u["id_uzivatele"].'</td >
                    <td > '.$u["jmeno"].'</td >
                    <td > '.$u["prijmeni"].'</td >
                    <td > '.$u["login"].'</td >
                    <td > '.$u["email"].'</td >
                    
                    <td><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#'.$target.'">Upravit</button></td>
                    
                    <td><form action="" method="post">
                        <input type="hidden" name="id_user" value='.$u["id_uzivatele"].'>
                        <button type="submit" name="action" value="delete" class="btn btn-primary">Smazat</button>
                    </td></tr>
                    
                    <div id="'.$target.'" class="modal fade">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <!-- Modal Header -->
                                            <div class="modal-header">
                                                <h4 class="modal-title">Upravit uživatele s ID '.$u["id_uzivatele"].'</h4>
                                            </div>
                                            <!-- Modal body -->
                                            <div class="modal-body">
                                                <form action="" method="post">                                                   
                                                    <div class="form-group">
                                                        <label for="name">Jméno:</label>
                                                        <input type="text" name="jmeno" class="form-control" value="'.$u["jmeno"].'" id="name">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="surname">Příjmení:</label>
                                                        <input type="text" name="prijmeni" class="form-control" value="'.$u["prijmeni"].'" id="surname">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="login">Login:</label>
                                                        <input type="login" name="login" value="'.$u["login"].'" class="form-control" id="login">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="heslo">Heslo:</label>
                                                        <input type="password" name="heslo" value="'.$u["heslo"].'" class="form-control" id="heslo">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="email">Email:</label>
                                                        <input type="email" name="email" class="form-control" value="'.$u["email"].'" id="email">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="login">Práva:</label>
                                                        <input type="login" name="id_prava" value="'.$u["id_prava"].'" class="form-control" id="login">
                                                    </div>
                                                    
                                                    <input type="hidden" name="id_uzivatele" value='.$u["id_uzivatele"].'>
                                                    <button type="submit" name="action" value="update_submit" class="btn btn-primary">Aktualizovat</button>  
                                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Zavřít</button>                                              
                                                </form>
                                            </div>       
                                        </div>
                                    </div>
                                </div>';
            }
            $res .= '</tbody>
                </table>';
            $res .= '<br>
                <h2>Recenzenti</h2>
                <br>
                <table class="table table-bordered table-striped table-hover text-center table-responsive-md">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Jméno</th>
                            <th scope="col">Příjmení</th>
                            <th scope="col">Login</th>
                            <th scope="col">E-Mail</th>
                            <th scope="col">Upravit</th>             
                            <th scope="col">Smazat</th>             
                        </tr>
                    </thead>
                    <tbody>';
                        foreach($tplData['reviewers'] as $u) {
                            $target = $u["login"].$u["id_uzivatele"];
                            $res .= '<tr >
                                <td > '.$u["id_uzivatele"].'</td >
                                <td > '.$u["jmeno"].'</td >
                                <td > '.$u["prijmeni"].'</td >
                                <td > '.$u["login"].'</td >
                                <td > '.$u["email"].'</td >
                    
                                <td><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#'.$target.'">Upravit</button></td>
                    
                                <td><form action="" method="post">
                                    <input type="hidden" name="id_user" value='.$u["id_uzivatele"].'>
                                    <button type="submit" name="action" value="delete" class="btn btn-primary">Smazat</button>
                                </td></tr>                    
                    
                                <div id="'.$target.'" class="modal fade">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <!-- Modal Header -->
                                            <div class="modal-header">
                                                <h4 class="modal-title">Upravit uživatele s ID '.$u["id_uzivatele"].'</h4>
                                            </div>
                                            <!-- Modal body -->
                                            <div class="modal-body">
                                                <form action="" method="post">                                                   
                                                    <div class="form-group">
                                                        <label for="name">Jméno:</label>
                                                        <input type="text" name="jmeno" class="form-control" value="'.$u["jmeno"].'" id="name">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="surname">Příjmení:</label>
                                                        <input type="text" name="prijmeni" class="form-control" value="'.$u["prijmeni"].'" id="surname">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="login">Login:</label>
                                                        <input type="login" name="login" value="'.$u["login"].'" class="form-control" id="login">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="heslo">Heslo:</label>
                                                        <input type="password" name="heslo" value="'.$u["heslo"].'" class="form-control" id="heslo">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="email">Email:</label>
                                                        <input type="email" name="email" class="form-control" value="'.$u["email"].'" id="email">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="login">Práva:</label>
                                                        <input type="number" name="id_prava" value="'.$u["id_prava"].'" class="form-control" id="login">
                                                    </div>
                                                    
                                                    <input type="hidden" name="id_uzivatele" value='.$u["id_uzivatele"].'>
                                                    <button type="submit" name="action" value="update_submit" class="btn btn-primary">Aktualizovat</button>  
                                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Zavřít</button>                                              
                                                </form>
                                            </div>       
                                        </div>
                                    </div>
                                </div>';
            }

            $res .= '</tbody>
                </table>';

        } else{
            $res .= '
            <div>
                K této stránce nemáte přístup...
            </div>';
        }

        echo $res;

        // paticka
        $tplHeaders->getHTMLFooter();

    }

}

?>
