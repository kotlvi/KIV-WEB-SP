<?php

/** Automaticka registrace pozadovanych trid */
spl_autoload_register(function ($className){
    // Uprava nazvu tridy za vychozi adresar aplikace
    $className = str_replace(BASE_NAMESPACE_NAME, BASE_APP_DIR_NAME, $className);

    // Pridani cele cesty k souboru bez pripony
    $fileName = dirname(__FILE__) ."\\". $className;

    // Pridani koncovky pro tridu nebo interface
    foreach(FILE_EXTENSIONS as $ext) {
        if (file_exists($fileName . $ext)) {
            $fileName .= $ext;
            break;
        }
    }

    // Pripojeni souboru s pozadovanou tridou
    require_once($fileName);
});

?>
