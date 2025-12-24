<?php

class ApiController {

    public function getZones() {
        header('Content-Type: application/json');
        try {
            $zones = (new Zone())->findAll();
            echo json_encode(['success' => true, 'zones' => $zones]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => 'Erreur interne du serveur.']);
        }
    }

    public function getArticles() {
        header('Content-Type: application/json');
        try {
            $articles = (new Article())->all([]);
            echo json_encode(['success' => true, 'articles' => $articles]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => 'Erreur interne du serveur.']);
        }
    }

    public function getTables() {
        header('Content-Type: application/json');
        try {
            $tableModel = new Table();
            $tables = [];
            if (isset($_GET['zone_id']) && is_numeric($_GET['zone_id'])) {
                $tables = $tableModel->getTablesByZone((int)$_GET['zone_id']);
            } else {
                $tables = $tableModel->findAll();
            }
            echo json_encode(['success' => true, 'tables' => $tables]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => 'Erreur interne du serveur.']);
        }
    }

    public function getArticlePriceForZone() {
        header('Content-Type: application/json');
        if (!isset($_GET['article_id']) || !isset($_GET['zone_id'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Les paramètres article_id et zone_id sont requis.']);
            return;
        }

        try {
            $prix = (new Article())->getPrixPourZone((int)$_GET['article_id'], (int)$_GET['zone_id']);
            if ($prix !== null) {
                echo json_encode(['success' => true, 'prix' => $prix]);
            } else {
                http_response_code(404);
                echo json_encode(['success' => false, 'error' => 'Prix non trouvé pour cet article dans cette zone.']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => 'Erreur interne du serveur.']);
        }
    }

    public function createVente() {
        header('Content-Type: application/json');
        $input = json_decode(file_get_contents('php://input'), true);
    
        if (json_last_error() !== JSON_ERROR_NONE) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Données JSON invalides.']);
            return;
        }
    
        if (empty($input['table_id']) || !isset($input['articles']) || !is_array($input['articles']) || empty($input['articles'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Données manquantes ou incorrectes (table ou articles).']);
            return;
        }
    
        $pdo = Database::getInstance()->getConnection();

        try {
            // Étape 1: Récupérer les prix et valider les articles (hors transaction)
            $tableModel = new Table();
            $table = $tableModel->find((int)$input['table_id']);
            if (!$table) {
                throw new Exception("Table non trouvée.", 404);
            }
            $zoneId = $table['zone_id'];
    
            $articleModel = new Article();
            foreach ($input['articles'] as $article) {
                $articleInfo = $articleModel->find($article['article_id']);
                if (!$articleInfo) {
                     throw new Exception("Article avec ID {$article['article_id']} non trouvé.", 404);
                }
                
                $prix = $articleModel->getPrixPourZone($article['article_id'], $zoneId);
                if ($prix === null) {
                    $nomArticle = $articleInfo['nom'] ?? "ID {$article['article_id']}";
                    throw new Exception("Prix non trouvé pour l'article \"{$nomArticle}\".", 400);
                }
    
                $articlesAvecPrixReel[] = [
                    'article_id' => $article['article_id'],
                    'quantite' => $article['quantite'],
                    'prix_unitaire_ht' => $prix,
                    'cout_achat_unitaire' => $articleInfo['dernier_cout_achat'] ?? 0,
                    'tva' => 20.00
                ];
            }

            // Étape 2: Tout enregistrer dans une transaction
            $pdo->beginTransaction();

            $venteModel = new Vente();
            $commandeModel = new Commande();
            $ligneVenteModel = new LigneVente();

            $totalTtc = array_reduce($articlesAvecPrixReel, function ($sum, $article) {
                return $sum + ($article['prix_unitaire_ht'] * $article['quantite'] * (1 + $article['tva'] / 100));
            }, 0);

            $venteId = $venteModel->create([
                'table_id' => $input['table_id'],
                'user_id' => $_SESSION['user']['id'],
                'total' => $totalTtc,
                'statut' => 'payee'
            ]);
            if (!$venteId) throw new Exception("La création de la vente a échoué.");

            $commandeId = $commandeModel->create([
                'vente_id' => $venteId,
                'user_id' => $_SESSION['user']['id']
            ]);
            if (!$commandeId) throw new Exception("La création de la commande a échoué.");

            $ligneVenteModel->create($commandeId, $articlesAvecPrixReel);

            foreach ($articlesAvecPrixReel as $article) {
                $articleModel->decrementStock($article['article_id'], $article['quantite']);
            }

            $pdo->commit();
            echo json_encode(['success' => true, 'vente_id' => $venteId]);

        } catch (Exception $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            $errorCode = $e->getCode() >= 400 && $e->getCode() < 600 ? $e->getCode() : 500;
            http_response_code($errorCode);
            error_log($e->getMessage());
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    public function getFacture($id) {
        header('Content-Type: application/json');
        try {
            $venteModel = new Vente();
            $vente = $venteModel->findWithDetails((int)$id);

            if ($vente) {
                echo json_encode(['success' => true, 'facture' => $vente]);
            } else {
                http_response_code(404);
                echo json_encode(['success' => false, 'error' => 'Vente non trouvée.']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => 'Erreur interne du serveur.']);
        }
    }
    
    public function creerUniteMesure() {
        header('Content-Type: application/json');
        
        if ($_POST) {
            try {
                $data = [
                    'nom' => $_POST['nom'],
                    'symbole' => $_POST['symbole'],
                    'description' => $_POST['description'] ?? ''
                ];
                
                $uniteId = (new UniteMesure())->create($data);
                $unite = (new UniteMesure())->find($uniteId);
                
                echo json_encode(['success' => true, 'unite' => $unite]);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            }
        } else {
            echo json_encode(['success' => false, 'error' => 'Données manquantes']);
        }
    }
    
    public function prochainsNumerosTables() {
        header('Content-Type: application/json');
        
        $tableModel = new Table();
        $zoneModel = new Zone();
        $zones = $zoneModel->findAll();
        $prochainsNumeros = [];

        foreach($zones as $zone) {
            // "Grande Salle" -> "salle"
            $cle = strtolower(str_replace('Grande ', '', $zone['nom']));
            $prochainsNumeros[$cle] = $tableModel->getNextTableNumber($zone['id']);
        }
        
        echo json_encode($prochainsNumeros);
    }
}