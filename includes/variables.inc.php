<?php

declare(strict_types=1);

/**
 * Checks serveur path
 */

// Path
$clicServer = '';
// Page name
$url = Functions::getUrl();
// Domain name
$rootUrl = Functions::getRootUrl();

($rootUrl === 'https://adi.ezaya.fr/') ? $clicServer = 'ClicRepare' : $clicServer = 'recettes';
