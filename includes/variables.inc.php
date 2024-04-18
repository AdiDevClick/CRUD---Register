<?php declare(strict_types=1);

$url = Functions::getUrl();
$rootUrl = Functions::getRootUrl();
// $rootUrl = (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/';
if ($rootUrl === 'https://adi.ezaya.fr/') {
    $clicServer = 'ClicRepare';
} else {
    $clicServer = 'recettes';
}