<?php


namespace kivweb\Views;

/**
 * Class ArticlesTemplate   View pro prehled prispevku
 * @package kivweb\Views
 * @author Vitkor Kotlan
 */
class ArticlesTemplate implements IView
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


        if(isset($tplData['delete'])){
            if($tplData['delete'] == 1){
                $res .= '
                        <div class="alert alert-success">
                            <strong>Done!</strong> Článek s ID '.$tplData['deletedArticle'].' byl odstraněn!
                        </div>';
            } else {
                $res .= '
                        <div class="alert alert-danger">
                            <strong>Whoops!</strong> Článek s ID '.$tplData['deletedArticle'].' se nepodařilo odstranit...
                        </div>';
            }
        } elseif (isset($tplData['approve'])){
            if($tplData['approve'] == 1){
                $res .= '
                        <div class="alert alert-success">
                            <strong>Done!</strong> Článek s ID '.$tplData['approvedArticle'].' byl schválen!
                        </div>';
            } else {
                $res .= '
                        <div class="alert alert-danger">
                            <strong>Whoops!</strong> Článek s ID '.$tplData['approvedArticle'].' se nepodařilo schválit...
                        </div>';
            }
        }

        if ($tplData['logged'] == 1) {


            //////// Verze pro uzivatele ////////


            if($tplData['rights'] == 1){
                $res .= '
                        <br>
                    <h2>Vaše články čekající na schválení</h2>
                    <br>
                        <a class="btn btn-link btn-primary text-white btn-block" href="index.php?page=newarticle" role="button"> Nový článek </a>
                <br>
                <table class="table table-bordered table-striped table-hover table-responsive-md">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Název</th>
                            <th scope="col">Abstrakt</th>
                            <th scope="col">Datum vydání</th>
                            <th scope="col">Název souboru</th>
                            <th scope="col">Smazat</th>             
                        </tr>
                    </thead>
                    <tbody>';
                foreach ($tplData['articles'] as $u) {
                    if ($u['stav'] == 0) {
                        $target = $u["login"] . $u["id_prispevku"];
                        $res .= '<tr >
                            <td > ' . $u["id_prispevku"] . '</td >
                            <td > ' . $u["nazev"] . '</td >
                            <td><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#' . $target . '">Zobrazit</button></td>
                            <td > ' . $u["datum_vydani"] . '</td >
                            <td > ' . $u["nazev_pdf"] . '</td >   
                            <td><form action="" method="post">
                                <input type="hidden" name="id_prispevku" value="' . $u["id_prispevku"] . '">
                                <button type="submit" name="action" value="delete" class="btn btn-primary">Smazat</button>
                            </form></td></tr>                                                

                            <div id="' . $target . '" class="modal fade">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <!-- Modal Header -->
                                        <div class="modal-header">
                                            <h5 class="modal-title">Abstrakt:<b> ' . $u["nazev"] . '</b></h5>
                                        </div>
                                        <!-- Modal body -->
                                        <div class="modal-body">
                                            ' . $u["abstrakt"] . '
                                        </div>
                                        <div class="modal-footer">
                                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>                                              
                                        </div>        
                                    </div>
                                </div>
                            </div>
                        ';
                    }
                }
                $res .= '
                    </tbody>
                </table>              
                ';

                $res .= '
                        <br>
                    <h2>Vaše schválené články</h2>
                    <br>
                <table class="table table-bordered table-striped table-hover table-responsive-lg">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Název</th>
                            <th scope="col">Abstrakt</th>
                            <th scope="col">Datum vydání</th>
                            <th scope="col">Název souboru</th>
                            <th scope="col">Počet dokončených recenzí</th>
                            <th scope="col">Smazat</th>             
                        </tr>
                    </thead>
                    <tbody>';
                    foreach ($tplData['articles'] as $u) {
                        if ($u['stav'] == 1) {
                            $target = $u["login"] . $u["id_prispevku"];
                            $res .= '   <tr>
                                        <td > ' . $u["id_prispevku"] . '</td >
                                        <td > ' . $u["nazev"] . '</td >
                                        <td><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#' . $target . '">Zobrazit</button></td>
                                        <td > ' . $u["datum_vydani"] . '</td >
                                        <td > ' . $u["nazev_pdf"] . '</td >   
                                        <td > ' . $u["p_recenzi"] . '</td >                                                                                                                                                                      
                                                                                 
                                        <td><form action="" method="post">
                                            <input type="hidden" name="id_prispevku" value="' . $u["id_prispevku"] . '">
                                            <button type="submit" name="action" value="delete" class="btn btn-primary">Smazat</button>
                                        </form></td></tr>                                                
            
                                        <div id="' . $target . '" class="modal fade">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <!-- Modal Header -->
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Abstrakt:<b> ' . $u["nazev"] . '</b></h5>
                                                    </div>
                                                    <!-- Modal body -->
                                                    <div class="modal-body">
                                                        ' . $u["abstrakt"] . '
                                                    </div>
                                                    <div class="modal-footer">
                                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>                                              
                                                    </div>        
                                                </div>
                                            </div>
                                        </div>                                   
                                        
                                
                                        ';
                        }
                    }
                    $res .= '
                    </tbody>
                </table>';


            //////// Verze pro administrátory ////////


            } elseif($tplData['rights'] == 3){
                $res .= '                    
                    <br>
                    <h2>Dosud neschválené články</h2>
                    <br>
                    <a class="btn btn-link btn-primary text-white btn-block" href="index.php?page=newreview" role="button"> Přiřadit článek k recenzi </a>
                    <br>
                <table class="table table-bordered table-striped table-hover table-responsive-lg">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Název</th>
                            <th scope="col">Autor</th>
                            <th scope="col">Abstrakt</th>
                            <th scope="col">Datum vydání</th>
                            <th scope="col">Název souboru</th>
                            <th scope="col">Počet dokončených recenzí</th>
                            <th scope="col">Schválit</th>                        
                            <th scope="col">Smazat</th>             
                        </tr>
                    </thead>
                    <tbody>';
                foreach ($tplData['articles'] as $u) {
                    if($u['stav'] == 0) {
                        $target = $u["login"] . $u["id_prispevku"];
                        $res .= '<tr>
                                <td > ' . $u["id_prispevku"] . '</td >
                                <td > ' . $u["nazev"] . '</td >
                                <td > ' . $u["login"] . '</td >
                                <td><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#' . $target . '">Zobrazit</button></td>
                                <td > ' . $u["datum_vydani"] . '</td >
                                <td > ' . $u["nazev_pdf"] . '</td >   
                                <td > ' . $u["p_recenzi"] . '</td > 
                                
                                <td><form action="" method="post">
                                    <input type="hidden" name="id_prispevku" value="' . $u["id_prispevku"] . '">
                                    <button type="submit" name="action" value="submit" class="btn btn-primary">Schválit</button>
                                </form></td>                                                                                                                                                
                                                                         
                                <td><form action="" method="post">
                                    <input type="hidden" name="id_prispevku" value="' . $u["id_prispevku"] . '">
                                    <button type="submit" name="action" value="delete" class="btn btn-primary">Smazat</button>
                                </form></td></tr>                                                
    
                                <div id="' . $target . '" class="modal fade">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <!-- Modal Header -->
                                            <div class="modal-header">
                                                <h5 class="modal-title">Abstrakt:<b> ' . $u["nazev"] . '</b></h5>
                                            </div>
                                            <!-- Modal body -->
                                            <div class="modal-body">
                                                ' . $u["abstrakt"] . '
                                            </div>
                                            <div class="modal-footer">
                                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>                                              
                                            </div>        
                                        </div>
                                    </div>
                                </div>                                   
                                
                        
                                ';
                    }
                }
                $res .= '
                    </tbody>
                </table>';

                $res .= '
                        <br>
                    <h2>Schválené články</h2>
                    <br>
                <table class="table table-bordered table-striped table-hover table-responsive-lg">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Název</th>
                            <th scope="col">Autor</th>
                            <th scope="col">Abstrakt</th>
                            <th scope="col">Datum vydání</th>
                            <th scope="col">Název souboru</th>
                            <th scope="col">Počet dokončených recenzí</th>
                            <th scope="col">Smazat</th>             
                        </tr>
                    </thead>
                    <tbody>';
                foreach ($tplData['articles'] as $u) {
                    if ($u['stav'] == 1) {
                        $target = $u["login"] . $u["id_prispevku"];
                        $res .= '   <tr>
                                    <td > ' . $u["id_prispevku"] . '</td >
                                    <td > ' . $u["nazev"] . '</td >
                                    <td > ' . $u["login"] . '</td >
                                    <td><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#' . $target . '">Zobrazit</button></td>
                                    <td > ' . $u["datum_vydani"] . '</td >
                                    <td > ' . $u["nazev_pdf"] . '</td >   
                                    <td > ' . $u["p_recenzi"] . '</td >                                                                                                                                                                      
                                                                             
                                    <td><form action="" method="post">
                                        <input type="hidden" name="id_prispevku" value="' . $u["id_prispevku"] . '">
                                        <button type="submit" name="action" value="delete" class="btn btn-primary">Smazat</button>
                                    </form></td></tr>                                                
        
                                    <div id="' . $target . '" class="modal fade">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <!-- Modal Header -->
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Abstrakt:<b> ' . $u["nazev"] . '</b></h5>
                                                </div>
                                                <!-- Modal body -->
                                                <div class="modal-body">
                                                    ' . $u["abstrakt"] . '
                                                </div>
                                                <div class="modal-footer">
                                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>                                              
                                                </div>        
                                            </div>
                                        </div>
                                    </div>                                   
                                    
                            
                                    ';
                    }
                }
                $res .= '
                    </tbody>
                </table>                
                ';


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