<?php

// Définir le chemin racine du projet
define('ROOT_PATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);

// Définir l'environnement en développement (dev / prod)
putenv('APP_ENV=dev');

// Définir si vite est utilisé
// true or false
const VITE = true;

// Définir si nous somme dans une configuration de développement
// true or false
const DEV = true;

// Définir l'adresse de l'AJAX controller
const AJAXCONTROLLER = "Process_PreparationList.php";

class Config
{
    private string $website_title = "Maxi Recettes";
    private bool $vite = VITE;
    private bool $dev = DEV;

    /**
     * Vérifie si Vite doit être utilisé
     */
    public static function vite(): bool
    {
        return VITE;
    }

    /**
     * Génère les balises script/link pour les ressources Vite
     * @param string $entry Point d'entrée à charger
     * @return string Tags HTML pour charger les ressources
     */
    public static function viteAssets(string $entry): string
    {
        // Si en développement et Vite est activé, utilisez le serveur de dev Vite
        if (DEV && VITE) {
            return sprintf(
                '<script type="module" src="http://localhost:5173/@vite/client"></script>
                <script type="module" src="http://localhost:5173/%s"></script>',
                $entry
            );
        }

        // En production, utilisez les fichiers compilés via le manifeste
        $manifest = self::getViteManifest();
        if (!isset($manifest[$entry])) {
            throw new \Exception("Le point d'entrée '{$entry}' n'existe pas dans le manifeste Vite.");
        }

        $tags = [];

        // Récupérer les fichiers JS
        if (isset($manifest[$entry]['file'])) {
            $tags[] = sprintf(
                '<script type="module" src="/assets/%s"></script>',
                $manifest[$entry]['file']
            );
        }

        // Récupérer les CSS importés dans JS
        if (isset($manifest[$entry]['css']) && is_array($manifest[$entry]['css'])) {
            foreach ($manifest[$entry]['css'] as $css) {
                $tags[] = sprintf('<link rel="stylesheet" href="/assets/%s">', $css);
            }
        }

        // Récupérer les imports (dépendances)
        if (isset($manifest[$entry]['imports']) && is_array($manifest[$entry]['imports'])) {
            foreach ($manifest[$entry]['imports'] as $import) {
                if (isset($manifest[$import]['file'])) {
                    $tags[] = sprintf(
                        '<link rel="modulepreload" href="/assets/%s">',
                        $manifest[$import]['file']
                    );
                }
            }
        }

        return implode("\n", $tags);
    }

    /**
     * Récupère le manifeste Vite
     * @return array Le contenu du manifeste
     */
    private static function getViteManifest(): array
    {
        $manifestPath = ROOT_PATH . 'public/assets/manifest.json';
        if (!file_exists($manifestPath)) {
            throw new \Exception("Le fichier manifeste Vite n'existe pas.");
        }

        return json_decode(file_get_contents($manifestPath), true);
    }
}
