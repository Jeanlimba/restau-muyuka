<?php

require_once __DIR__ . '/../models/Inventaire.php';
require_once __DIR__ . '/../models/LigneInventaire.php';
require_once __DIR__ . '/../models/Article.php';

class InventaireController {

    public function index() {
        $inventaireModel = new Inventaire();
        $inventaires = $inventaireModel->all();
        view('inventaires/index', ['inventaires' => $inventaires]);
    }

    public function create() {
        $articleModel = new Article();
        $articles = $articleModel->all();
        view('inventaires/create', ['articles' => $articles]);
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/inventaires/create');
            return;
        }

        $pdo = Database::getInstance()->getConnection();
        $inventaireModel = new Inventaire();
        $ligneInventaireModel = new LigneInventaire();
        $articleModel = new Article();

        try {
            $pdo->beginTransaction();

            // 1. Créer l'inventaire principal
            $inventaireId = $inventaireModel->create([
                'date_inventaire' => $_POST['date_inventaire'],
                'responsable_id' => $_SESSION['user']['id'],
                'notes' => $_POST['notes'],
                'conclusion' => $_POST['conclusion']
            ]);

            // 2. Préparer et insérer les lignes d'inventaire
            $lignes = [];
            foreach ($_POST['articles'] as $articleId => $data) {
                if (isset($data['stock_physique']) && $data['stock_physique'] !== '') {
                    $lignes[] = [
                        'article_id' => $articleId,
                        'stock_theorique' => $data['stock_theorique'],
                        'stock_physique' => $data['stock_physique'],
                        'justification' => $data['justification']
                    ];
                }
            }
            $ligneInventaireModel->createMany($inventaireId, $lignes);

            // 3. (Optionnel) Mettre à jour le stock des articles
            if (isset($_POST['update_stock'])) {
                foreach ($lignes as $ligne) {
                    $articleModel->updateStock($ligne['article_id'], $ligne['stock_physique']);
                }
            }
            
            // 4. Finaliser l'inventaire
            $inventaireModel->updateStatus($inventaireId, 'Terminé');

            $pdo->commit();
            $_SESSION['message'] = "Inventaire enregistré avec succès.";
            redirect('/inventaires/show/' . $inventaireId);

        } catch (Exception $e) {
            $pdo->rollBack();
            $_SESSION['error'] = "Erreur lors de l'enregistrement de l'inventaire: " . $e->getMessage();
            redirect('/inventaires/create');
        }
    }

    public function show($id) {
        $inventaireModel = new Inventaire();
        $inventaire = $inventaireModel->findWithDetails($id);
        
        if (!$inventaire) {
            http_response_code(404);
            $_SESSION['error'] = "Inventaire non trouvé.";
            redirect('/inventaires');
            return;
        }
        
        view('inventaires/show', ['inventaire' => $inventaire]);
    }

    public function exportExcel($id) {
        require_once __DIR__ . '/../services/ExcelExporter.php';

        $inventaireModel = new Inventaire();
        $inventaire = $inventaireModel->findWithDetails($id);

        if (!$inventaire) {
            $_SESSION['error'] = "Inventaire non trouvé.";
            redirect('/inventaires');
            return;
        }

        $data_to_export = [];
        $date = date('d/m/Y', strtotime($inventaire['date_inventaire']));
        
        $titre = "Détail de l'Inventaire du " . $date;
        $data_to_export[] = ['<style bgcolor="#2d2d2d" color="#FFFFFF" border="thin"><b>' . $titre . '</b></style>'];
        $data_to_export[] = [];

        $data_to_export[] = [
            '<style bgcolor="#595959" color="#FFFFFF" border="thin"><b>Article</b></style>',
            '<style bgcolor="#595959" color="#FFFFFF" border="thin"><b>Stock Théorique</b></style>',
            '<style bgcolor="#595959" color="#FFFFFF" border="thin"><b>Stock Physique</b></style>',
            '<style bgcolor="#595959" color="#FFFFFF" border="thin"><b>Écart</b></style>',
            '<style bgcolor="#595959" color="#FFFFFF" border="thin"><b>Justification</b></style>'
        ];

        foreach ($inventaire['lignes'] as $ligne) {
            $data_to_export[] = [
                '<style border="thin">' . $ligne['article_nom'] . '</style>',
                '<style border="thin">' . $ligne['stock_theorique'] . '</style>',
                '<style border="thin">' . $ligne['stock_physique'] . '</style>',
                '<style border="thin">' . $ligne['ecart'] . '</style>',
                '<style border="thin">' . ($ligne['justification'] ?? '') . '</style>'
            ];
        }

        if (!empty($inventaire['conclusion'])) {
            $data_to_export[] = [];
            $data_to_export[] = ['<style bgcolor="#f2f2f2" border="thin"><b>Conclusion générale</b></style>'];
            $data_to_export[] = ['<style border="thin">' . $inventaire['conclusion'] . '</style>'];
        }

        $filename = "Inventaire_" . date('Y-m-d', strtotime($inventaire['date_inventaire'])) . ".xlsx";
        $col_widths = [1 => 40, 2 => 15, 3 => 15, 4 => 10, 5 => 40];
        ExcelExporter::download($filename, $data_to_export, $col_widths);
    }
}
