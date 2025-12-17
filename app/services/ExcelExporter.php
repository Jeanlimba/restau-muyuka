<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Shuchkin\SimpleXLSXGen;

class ExcelExporter {

    /**
     * Génère et télécharge un fichier Excel à partir d'un tableau de données.
     *
     * @param string $filename Le nom du fichier à télécharger.
     * @param array $data Un tableau de tableaux. La première ligne doit contenir les en-têtes.
     */
    public static function download(string $filename, array $data, array $col_widths = []) {
        if (empty($data)) {
            $_SESSION['error'] = "Aucune donnée à exporter.";
            redirect($_SERVER['HTTP_REFERER'] ?? '/');
            return;
        }
        
        // Appliquer le style à la ligne d'en-tête (en gras, fond gris, texte blanc)
        // Note: Le style est maintenant géré dynamiquement dans le contrôleur

        // 1. Créer l'objet et y ajouter les données avec le nom de la feuille
        $xlsx = SimpleXLSXGen::fromArray( $data, 'Rapport Détaillé' );

        // 2. Appeler les méthodes de configuration
        $xlsx->setAuthor('Daniel\'s Services Restaurant');
        $xlsx->setDefaultFont('Calibri');
        $xlsx->setDefaultFontSize(11);

        // Définir la largeur des colonnes si fournie
        if (!empty($col_widths)) {
            foreach($col_widths as $col_index => $width) {
                $xlsx->setColWidth($col_index, $width);
            }
        }

        // 3. Déclencher le téléchargement
        $xlsx->downloadAs($filename);
    }
}

