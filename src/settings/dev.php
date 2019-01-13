<?php
$devSettings = include __DIR__ . '/settings.php';

/*---------------------------------------------*\
    Configuration Slim
\*---------------------------------------------*/
// Affiche le détail des erreurs
$devSettings['settings.displayErrorDetails'] = true;
// Permet au server web d'envoyer le header content-length
$devSettings['settings.addContentLengthHeader'] = true;
/*---------------------------------------------*\
    Configuration de Twig
\*---------------------------------------------*/
$devSettings['view.twig'] = [
    'cache' => false,
    'debug' => true,
    'strict_variables' => false,
];
return $devSettings;
