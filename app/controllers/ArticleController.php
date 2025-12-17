<?php
class ArticleController {
    public function index() {
        $articleModel = new Article();
        
        // Récupérer les filtres depuis la requête GET
        $filters = [
            'nom' => $_GET['nom'] ?? null,
            'categorie' => $_GET['categorie'] ?? null,
            'stock_situation' => $_GET['stock_situation'] ?? null
        ];

        $articles = $articleModel->all($filters);
        $categories = $articleModel->getDistinctCategories();

        view('articles/index', [
            'articles' => $articles,
            'categories' => $categories,
            'filters' => $filters
        ]);
    }
    
    public function create() {
        if ($_POST) {
            $pdo = Database::getInstance()->getConnection();
            try {
                $pdo->beginTransaction();
                $data = $this->prepareArticleData($_POST);
                (new Article())->create($data);
                $pdo->commit();
                $_SESSION['message'] = "Article créé avec succès!";
            } catch (Exception $e) {
                $pdo->rollBack();
                $_SESSION['error'] = "Erreur : " . $e->getMessage();
            }
            redirect('/articles');
        }
        
        $uniteModel = new UniteMesure();
        view('articles/create', [
            'zones' => (new Zone())->findAll(),
            'unites_vente' => $uniteModel->findByType('vente'),
            'unites_achat' => $uniteModel->findByType('achat')
        ]);
    }
    
    public function edit($id) {
        $articleModel = new Article();
        if ($_POST) {
            $pdo = Database::getInstance()->getConnection();
            try {
                $pdo->beginTransaction();
                $data = $this->prepareArticleData($_POST);
                $articleModel->update($id, $data);
                $pdo->commit();
                $_SESSION['message'] = "Article modifié avec succès!";
            } catch (Exception $e) {
                $pdo->rollBack();
                $_SESSION['error'] = "Erreur : " . $e->getMessage();
            }
            redirect('/articles');
        }
        
        $uniteModel = new UniteMesure();
        $tarifs = (new Tarif())->findByArticleId($id);
        $tarifsPourVue = [];
        foreach ($tarifs as $tarif) {
            $tarifsPourVue[$tarif['zone_id']] = $tarif['prix'];
        }

        view('articles/edit', [
            'article' => $articleModel->find($id),
            'zones' => (new Zone())->findAll(),
            'tarifs' => $tarifsPourVue,
            'unites_vente' => $uniteModel->findByType('vente'),
            'unites_achat' => $uniteModel->findByType('achat')
        ]);
    }

    public function delete($id) {
        (new Article())->delete($id);
        $_SESSION['message'] = "Article supprimé avec succès!";
        redirect('/articles');
    }

    public function exportExcel() {
        require_once __DIR__ . '/../services/ExcelExporter.php';

        $articleModel = new Article();
        
        $filters = [
            'nom' => $_GET['nom'] ?? null,
            'categorie' => $_GET['categorie'] ?? null,
            'stock_situation' => $_GET['stock_situation'] ?? null
        ];

        $articles = $articleModel->all($filters);

        // Préparer les données pour l'export
        $data_to_export = [];
        $data_to_export[] = [
            'Nom de l\'article', 
            'Catégorie', 
            'Unité Vente', 
            'Prix Standard', 
            'Type Tarification', 
            'Stock Actuel'
        ];

        foreach ($articles as $article) {
            $data_to_export[] = [
                $article['nom'],
                $article['categorie'],
                $article['unite_vente_nom'],
                number_format($article['prix'] ?? 0, 0, ',', ' ') . ' Fc',
                $article['type_tarification'],
                $article['stock_actuel'] ?? 0
            ];
        }

        $filename = "Export_Articles_" . date('Y-m-d') . ".xlsx";
        ExcelExporter::download($filename, $data_to_export);
    }

    private function prepareArticleData($postData) {
        $data = [
            'nom' => $postData['nom'],
            'categorie' => $postData['categorie'],
            'unite_mesure_id' => $postData['unite_mesure_id'],
            'purchase_unite_mesure_id' => $postData['purchase_unite_mesure_id'],
            'conversion_factor' => $postData['conversion_factor'],
            'type_tarification' => $postData['type_tarification']
        ];

        if ($postData['type_tarification'] === 'standard') {
            $data['prix'] = $postData['prix'] ?? 0;
        } else {
            $data['prix'] = 0; 
            $data['tarifs'] = $postData['tarifs'] ?? [];
        }

        return $data;
    }
}
