<?php


namespace kivweb\Views;

/**
 * Class ReviewsTemplate    View pro spravu recenzi
 * @package kivweb\Views
 * @author Viktor Kotlan
 */
class ReviewsTemplate implements IView
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

        if (isset($tplData['delete'])) {
            if ($tplData['delete'] == 1) {
                $res .= '
                        <div class="alert alert-success">
                            <strong>Done!</strong> Recenze s ID ' . $tplData['deletedReview'] . ' byla odstraněna!
                        </div>';
            } else {
                $res .= '
                        <div class="alert alert-danger">
                            <strong>Whoops!</strong> Recenzi s ID ' . $tplData['deletedReview'] . ' se nepodařilo odstranit...
                        </div>';
            }
        } elseif (isset($tplData['update'])) {
            if ($tplData['update'] == 1) {
                $res .= '
                        <div class="alert alert-success">
                            <strong>Done!</strong> Recenze s ID ' . $tplData['updatedReview'] . ' byla uložena!
                        </div>';
            } else {
                $res .= '
                        <div class="alert alert-danger">
                            <strong>Whoops!</strong> Recenzi s ID ' . $tplData['updatedReview'] . ' se nepodařilo uložit...
                        </div>';
            }
        } elseif (isset($tplData['submit'])) {
            if ($tplData['submit'] == 1) {
                $res .= '
                        <div class="alert alert-success">
                            <strong>Done!</strong> Recenze s ID ' . $tplData['submitedReview'] . ' byla odeslána!
                        </div>';
            } else {
                $res .= '
                        <div class="alert alert-danger">
                            <strong>Whoops!</strong> Recenzi s ID ' . $tplData['submitedReview'] . ' se nepodařilo odeslat...
                        </div>';
            }
        }

        //// Prihlasen? ////

        if ($tplData['logged'] == 1) {

            //// Admin? ////

            if ($tplData['rights'] == 3) {
                $res .= '
                 <!----- Tabulka rozpracovaných recenzí ---->

                <br>
                    <h2>Rozpracované recenze</h2>
                <br>
                <a class="btn btn-link btn-primary text-white btn-block" href="index.php?page=newreview" role="button"> Přiřadit novou recenzi </a>
                <br>
                    
                <table class="table table-bordered table-striped text-center table-hover table-responsive-md">
                    <thead class="thead-dark align-middle">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Recenzent</th>
                            <th scope="col">Příspevek</th>
                            <th scope="col">Originalita</th>
                            <th scope="col">Téma</th>
                            <th scope="col">Technické zpracování</th>
                            <th scope="col">Jazyková výbava</th>     
                            <th scope="col">Celkové doporučení</th>             
                            <th scope="col">Poznámka</th>     
                            <th scope="col">Smazat</th>                     
        
                        </tr>
                    </thead>
                    <tbody>';
                foreach ($tplData['review_work'] as $u) {
                    $target1 = $u["login"] . $u["id_recenze"]."1";
                    $target2 = $u["login"] . $u["id_recenze"]."2";
                    $res .= '<tr >
                            <td > ' . $u["id_recenze"] . '</td >
                            <td > ' . $u["login"] . '</td >
                            <td><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#'.$target1.'">Zobrazit</button></td>
                            <td > ' . $u["zn_originalita"] . '</td >
                            <td > ' . $u["zn_tema"] . '</td >
                            <td > ' . $u["zn_tech_kvalita"] . '</td >
                            <td > ' . $u["zn_jazyk"] . '</td >
                            <td > ' . $u["zn_obecne"] . '</td >
                            <td><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#'.$target2.'">Zobrazit</button></td>
                            <td><form action="" method="post">
                                <input type="hidden" name="id_recenze" value='.$u["id_recenze"].'>
                                <input type="hidden" name="id_prispevku" value='.$u["id_prispevku"].'>
                                <button type="submit" name="action" value="deleteReview" class="btn btn-primary">Smazat</button>
                            </form></td></tr> 
                            
                            <div id="'.$target1.'" class="modal fade">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <!-- Modal Header -->
                                            <div class="modal-header">
                                                <h4 class="modal-title">'.$u["nazev"].'</h4>
                                            </div>
                                            <!-- Modal body -->
                                            <div class="modal-body">
                                                '.$u["abstrakt"].'                                     
                                            </div>    
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-danger" data-dismiss="modal">Zavřít</button>                                              
                                            </div>   
                                        </div>
                                    </div>
                            </div>
                            
                            <div id="'.$target2.'" class="modal fade">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <!-- Modal Header -->
                                            <div class="modal-header">
                                                <h4 class="modal-title">Poznámky k '.$u["nazev"].'</h4>
                                            </div>
                                            <!-- Modal body -->
                                            <div class="modal-body">
                                                '.$u["poznamka"].'                                     
                                            </div>    
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-danger" data-dismiss="modal">Zavřít</button>                                              
                                            </div>   
                                        </div>
                                    </div>
                            </div>
                                                      
                             ';
                }
                $res .= '
                    </tbody>
                </table>
                
                <!----- Tabulka hotových recenzí ---->
                <br>
                    <h2>Hotové recenze</h2>
                <br>
                
                <table class="table table-bordered table-striped text-center table-hover table-responsive-md">
                    <thead class="thead-dark align-middle">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Recenzent</th>
                            <th scope="col">Příspevek</th>
                            <th scope="col">Originalita</th>
                            <th scope="col">Téma</th>
                            <th scope="col">Technické zpracování</th>
                            <th scope="col">Jazyková výbava</th>     
                            <th scope="col">Celkové doporučení</th>             
                            <th scope="col">Poznámka</th>  
                            <th scope="col">Smazat</th>                            
                        </tr>
                    </thead>
                    <tbody>';
                foreach($tplData['review_done'] as $u) {
                    $target1 = $u["login"].$u["id_recenze"]."1";
                    $target2 = $u["login"].$u["id_recenze"]."2";
                    $res .= '<tr >
                    <td > ' . $u["id_recenze"] . '</td >
                    <td > ' . $u["login"] . '</td >
                    <td><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#'.$target1.'">Zobrazit</button></td>
                    <td > ' . $u["zn_originalita"] . '</td >
                    <td > ' . $u["zn_tema"] . '</td >
                    <td > ' . $u["zn_tech_kvalita"] . '</td >
                    <td > ' . $u["zn_jazyk"] . '</td >
                    <td > ' . $u["zn_obecne"] . '</td >
                    
                    <td><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#'.$target2.'">Zobrazit</button></td>                                    
                    <td><form action="" method="post">
                                <input type="hidden" name="id_recenze" value='.$u["id_recenze"].'>
                                <button type="submit" name="action" value="deleteReview" class="btn btn-primary">Smazat</button>
                    </td></tr> 
                            
                    <div id="'.$target1.'" class="modal fade">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <!-- Modal Header -->
                                            <div class="modal-header">
                                                <h4 class="modal-title">'.$u["nazev"].'</h4>
                                            </div>
                                            <!-- Modal body -->
                                            <div class="modal-body">
                                                '.$u["abstrakt"].'                                     
                                            </div>    
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-danger" data-dismiss="modal">Zavřít</button>                                              
                                            </div>   
                                        </div>
                                    </div>
                            </div>                    
                    
                    <div id="'.$target2.'" class="modal fade">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <!-- Modal Header -->
                                            <div class="modal-header">
                                                <h4 class="modal-title">Poznámky k '.$u["nazev"].'</h4>
                                            </div>
                                            <!-- Modal body -->
                                            <div class="modal-body">
                                                 '.$u["poznamka"].'                                     
                                            </div>   
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-danger" data-dismiss="modal">Zavřít</button>                                              
                                            </div>     
                                        </div>
                                    </div>
                                </div>';
                }
                $res .= '
                    </tbody>
                </table>';

            //// Prihlaseny recenzent? ////

            } elseif ($tplData['rights'] == 2) {
                $res .= '
                
                <!----- Tabulka rozpracovaných recenzí ---->

                <br>
                    <h2>Vaše rozpracované recenze</h2>
                <br>              
                                    
                <table class="table table-bordered table-striped text-center table-hover table-responsive-md">
                    <thead class="thead-dark align-middle">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Recenzent</th>
                            <th scope="col">Příspevek</th>
                            <th scope="col">Originalita</th>
                            <th scope="col">Téma</th>
                            <th scope="col">Technické zpracování</th>
                            <th scope="col">Jazyková výbava</th>     
                            <th scope="col">Celkové doporučení</th>             
                            <th scope="col">Upravit</th>     
                            <th scope="col">Odevzdání</th>                     
        
                        </tr>
                    </thead>
                    <tbody>';
                foreach($tplData['review_work'] as $u) {
                    $target1 = $u["login"].$u["id_recenze"]."1";
                    $target2 = $u["login"].$u["id_recenze"]."2";
                    $res .= '<tr >
                    <td > ' . $u["id_recenze"] . '</td >
                    <td > ' . $u["login"] . '</td >
                    <td><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#'.$target1.'">Zobrazit</button></td>
                    <td > ' . $u["zn_originalita"] . '</td >
                    <td > ' . $u["zn_tema"] . '</td >
                    <td > ' . $u["zn_tech_kvalita"] . '</td >
                    <td > ' . $u["zn_jazyk"] . '</td >
                    <td > ' . $u["zn_obecne"] . '</td >
                    
                    <td><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#'.$target2.'">Upravit</button></td>
                    
                    <td><form action="" method="post">
                        <input type="hidden" name="id_recenze" value='.$u["id_recenze"].'>
                        <input type="hidden" name="id_prispevku" value='.$u["id_prispevku"].'>
                        <button type="submit" name="action" value="submit" class="btn btn-primary">Odevzdat</button>
                    </td></tr>                   
                    
                    <div id="'.$target1.'" class="modal fade">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <!-- Modal Header -->
                                            <div class="modal-header">
                                                <h4 class="modal-title">'.$u["nazev"].'</h4>
                                            </div>
                                            <!-- Modal body -->
                                            <div class="modal-body">
                                                '.$u["abstrakt"].'                                     
                                            </div>    
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-danger" data-dismiss="modal">Zavřít</button>                                              
                                            </div>   
                                        </div>
                                    </div>
                            </div>                    
                    
                    <div id="'.$target2.'" class="modal fade">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <!-- Modal Header -->
                                            <div class="modal-header">
                                                <h4 class="modal-title">Upravit recenzi s názvem '.$u["nazev"].'</h4>
                                            </div>
                                            <!-- Modal body -->
                                            <div class="modal-body">
                                                <form action="" method="post">                                                   
                                                    <div class="form-group">
                                                        <label for="originalita">Originalita:</label>
                                                        <input type="number" name="originalita" class="form-control" value="'.$u["zn_originalita"].'" id="originalita">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="tema">Téma:</label>
                                                        <input type="number" name="tema" class="form-control" value="'.$u["zn_tema"].'" id="tema">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="technika">Technikcé zpracování:</label>
                                                        <input type="number" name="technika" class="form-control" value="'.$u["zn_tech_kvalita"].'" id="technika">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="jazyk">Jazyková výbava:</label>
                                                        <input type="number" name="jazyk" class="form-control" value="'.$u["zn_jazyk"].'" id="jazyk">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="celkem">Celkové doporučení:</label>
                                                        <input type="number" name="celkem" class="form-control" value="'.$u["zn_obecne"].'" id="celkem">
                                                    </div>                                            
                                                    <div class="form-group">
                                                        <label for="poznamka">Poznámka:</label>
                                                        <input type="text" name="poznamka" class="form-control" value="'.$u["poznamka"].'" id="poznamka">
                                                    </div>
                                                    
                                                    <input type="hidden" name="id_recenze" value='.$u["id_recenze"].'>
                                                    <button type="submit" name="action" value="update_submit" class="btn btn-primary">Uložit</button>  
                                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Zavřít</button>                                              
                                                </form>
                                            </div>       
                                        </div>
                                    </div>
                                </div>';
                }
                $res .= '
                    </tbody>
                </table>
                
                <!----- Tabulka hotových recenzí ---->
                <br>
                    <h2>Vaše hotové recenze</h2>
                <br>
                
                <table class="table table-bordered table-striped text-center table-hover table-responsive-md">
                    <thead class="thead-dark align-middle">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Recenzent</th>
                            <th scope="col">Příspevek</th>
                            <th scope="col">Originalita</th>
                            <th scope="col">Téma</th>
                            <th scope="col">Technické zpracování</th>
                            <th scope="col">Jazyková výbava</th>     
                            <th scope="col">Celkové doporučení</th>             
                            <th scope="col">Poznámka</th>         
                        </tr>
                    </thead>
                    <tbody>';
                foreach($tplData['review_done'] as $u) {
                    $target1 = $u["login"].$u["id_recenze"]."1";
                    $target2 = $u["login"].$u["id_recenze"]."2";
                    $res .= '<tr >
                    <td > ' . $u["id_recenze"] . '</td >
                    <td > ' . $u["login"] . '</td >
                    <td><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#'.$target1.'">Zobrazit</button></td>
                    <td > ' . $u["zn_originalita"] . '</td >
                    <td > ' . $u["zn_tema"] . '</td >
                    <td > ' . $u["zn_tech_kvalita"] . '</td >
                    <td > ' . $u["zn_jazyk"] . '</td >
                    <td > ' . $u["zn_obecne"] . '</td >
                    
                    <td><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#'.$target2.'">Zobrazit</button></td>                                    
                    
                    <div id="'.$target1.'" class="modal fade">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <!-- Modal Header -->
                                            <div class="modal-header">
                                                <h4 class="modal-title">'.$u["nazev"].'</h4>
                                            </div>
                                            <!-- Modal body -->
                                            <div class="modal-body">
                                                '.$u["abstrakt"].'                                     
                                            </div>    
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-danger" data-dismiss="modal">Zavřít</button>                                              
                                            </div>   
                                        </div>
                                    </div>
                            </div>                    
                    
                    <div id="'.$target2.'" class="modal fade">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <!-- Modal Header -->
                                            <div class="modal-header">
                                                <h4 class="modal-title">Poznámky k '.$u["nazev"].'</h4>
                                            </div>
                                            <!-- Modal body -->
                                            <div class="modal-body">
                                                 '.$u["poznamka"].'                                     
                                            </div>  
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-danger" data-dismiss="modal">Zavřít</button>                                              
                                            </div>        
                                        </div>
                                    </div>
                                </div>';
                }
                $res .= '
                    </tbody>
                </table>';
            } else {
                $res .= '
            <div>
                K této stránce nemáte přístup...
            </div>';
            }

        } else {
            $res .= '
            <div>
                Pro přístup k této stránce se musíte přihlásit...
            </div>';
        }

        echo $res;

        // paticka
        $tplHeaders->getHTMLFooter();

    }
}