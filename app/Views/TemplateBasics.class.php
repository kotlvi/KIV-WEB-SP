<?php

namespace kivweb\Views;

use kivweb\Controllers\MenuContentController;

/**
 * Trida vypisujici HTML hlavicku a paticku stranky.
 * @package kivweb\Views\ClassBased
 */
class TemplateBasics {

    /** @var MenuContentController $db  Sprava databaze. */
    private $menu;

    public function __construct() {
        $this->menu = new MenuContentController();
    }

    /**
     *  Vrati vrsek stranky az po oblast, ve ktere se vypisuje obsah stranky.
     *  @param string $pageTitle    Nazev stranky.
     */
    public function getHTMLHeader(string $pageTitle) {
        ?>

        <!doctype html>
        <html>
            <head>
                <meta charset='utf-8'>
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <meta name="viewport" content="width=device-width, initial-scale=1">
                <title><?php echo $pageTitle; ?></title>

                <link rel="stylesheet" href="npm\node_modules\bootstrap\dist\css\bootstrap.css">
                <link rel="stylesheet" href="npm\node_modules\font-awesome\css\font-awesome.min.css">

            </head>
            <body>
                <nav class="navbar navbar-expand-md bg-dark navbar-dark sticky-top">
                    <div class="container">
                        <!-- Brand -->
                        <a class="navbar-brand" href="index.php">
                            <i class="fa fa-eercast"></i>
                            HERNÍ WEB
                        </a>

                        <!-- Toggler/collapsibe Button-->
                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
                            <span class="navbar-toggler-icon"></span>
                        </button>

                        <!-- Navbar links -->
                        <div class="collapse navbar-collapse" id="collapsibleNavbar">
                            <ul class="navbar-nav">
                                <?php
                                $pages = $this->menu->getMenuContent();
                                // vypis menu
                                if($pages != null){
                                    foreach($pages as $key => $pInfo){
                                        echo '<li class="nav-item text-center">';
                                        echo "<a class='nav-link' href='index.php?page=$key'>$pInfo[title]</a>";
                                        echo '</li>';
                                    }
                                }
                                ?>
                            </ul>
                        </div>
                        <div class="collapse navbar-collapse  justify-content-end" id="collapsibleNavbar">
                            <ul class="navbar-nav ">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-outline-primary btn-link text-light" data-toggle="modal" data-target="#search">
                                        <i class="fa fa-search" aria-hidden="true"></i>
                                    </button>
                                    <a href="index.php?page=login" type="button" class="btn btn-primary btn-link text-light">
                                        <i class="fa fa-user-circle-o" aria-hidden="true"></i>
                                    </a>

                                </div>
                            </ul>
                        </div>
                    </div>
                </nav>
                <br>

                <div id="search" class="modal fade text-justify">
                    <div class="modal-dialog">
                        <div class="modal-content">

                            <!-- Modal Header -->
                            <div class="modal-header">
                                <h4 class="modal-title">Vyhledávání</h4>
                            </div>

                            <!-- Modal body -->
                            <div class="modal-body">
                                    <form class="form-inline" action="/action_page.php">
                                        <input class="form-control" type="text" placeholder="Hledat">
                                    </form>
                            </div>

                            <!-- Modal footer -->
                            <div class="modal-footer">
                                <button class="btn btn-success" type="submit">Hledat</button>
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Zavřít</button>
                            </div>

                        </div>
                    </div>
                </div>
        <?php
    }
    
    /**
     *  Vrati paticku stranky.
     */
    public function getHTMLFooter(){
        ?>
            <br>
            <footer class="footer">
                <div class="container-fluid bg-dark text-white text-center font-weight-bold fixed-bottom">
                    (c) 2021
                </div>
            </footer>

            <script src="npm\node_modules\jquery\dist\jquery.js"></script>
            <script src="npm\node_modules\popper.js\dist\popper.js"></script>
            <script src="npm\node_modules\bootstrap\dist\js\bootstrap.js"></script>

            </body>
        </html>

        <?php
    }
        
}

?>
