<?php

require_once __DIR__ . '/../services/FacturePdf.php';

class VenteController {

    public function index() {
        $date_du_jour = date('Y-m-d');
        // On passe les infos de l'utilisateur pour le filtrage
        $ventes = (new Vente())->findAllByDateGrouped($date_du_jour, $_SESSION['user']);
        
        view('vente/index', [
            'ventes' => $ventes,
            'date_filtre' => $date_du_jour
        ]);
    }
    
    public function pos() {
        view('vente/pos');
    }

    public function facture($id) {
        $venteModel = new Vente();
        $factureData = $venteModel->findWithDetails((int)$id);

        if (!$factureData) {
            http_response_code(404);
            echo "Facture non trouvée.";
            return;
        }

        $pdf = new FacturePdf($factureData);
        $pdf->generate();
        
        if (isset($_GET['print']) && $_GET['print'] === 'true') {
            $pdf->AutoPrint();
        }

        // Output the PDF
        $pdf->Output('I', 'facture-' . $factureData['numero_vente'] . '.pdf');
    }

    public function print_facture($id) {
        view('vente/print_facture', ['venteId' => $id]);
    }

    public function show($id) {
        $vente = (new Vente())->findWithDetails((int)$id);
        if (!$vente) {
            http_response_code(404);
            $_SESSION['error'] = "Vente non trouvée.";
            redirect('/ventes');
            return;
        }
        view('vente/show', ['vente' => $vente]);
    }

    public function edit($id) {
        $vente = (new Vente())->find((int)$id);
        if (!$vente) {
            http_response_code(404);
            $_SESSION['error'] = "Vente non trouvée.";
            redirect('/ventes');
            return;
        }
        view('vente/edit', ['vente' => $vente]);
    }

    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $statut = $_POST['statut'];
            if ((new Vente())->updateStatut((int)$id, $statut)) {
                $_SESSION['message'] = "Statut de la vente mis à jour.";
            } else {
                $_SESSION['error'] = "Erreur lors de la mise à jour.";
            }
            redirect('/ventes');
        }
    }

    public function post_paiement()
    {
        $tableModel = new Table();
        $zonesWithOccupiedTables = $tableModel->getZonesWithTablesByStatuses(['occupee', 'en_attente_paiement']);
        $zonesWithFreeTables = $tableModel->getZonesWithTablesAndSpecificStatus('libre');

        view('vente/post_paiement', [
            'zonesWithOccupiedTables' => $zonesWithOccupiedTables,
            'zonesWithFreeTables' => $zonesWithFreeTables
        ]);
    }

    public function initiate_post_payment()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $tableId = $_POST['table_id'] ?? null;
            if (!$tableId) {
                $_SESSION['error'] = "Aucune table sélectionnée.";
                redirect('/post-paiement');
            }

            $tableModel = new Table();
            $table = $tableModel->find($tableId);

            if (!$table || $table['statut'] !== 'libre') {
                $_SESSION['error'] = "La table n'est pas disponible pour le post-paiement.";
                redirect('/post-paiement');
            }

            $venteModel = new Vente();
            $data = [
                'table_id' => $tableId,
                'user_id' => $_SESSION['user']['id']
            ];
            
            $venteId = $venteModel->createForPostPayment($data);

            if ($venteId) {
                $_SESSION['message'] = "Table {$table['nom']} occupée pour post-paiement.";
                redirect('/manage-table/' . $tableId);
            } else {
                $_SESSION['error'] = "Erreur lors de l'initialisation du post-paiement.";
                redirect('/post-paiement');
            }
        }
    }

    public function manage_table($tableId)
    {
        $venteModel = new Vente();
        $vente = $venteModel->getOpenVenteForTable($tableId);
        $table = (new Table())->find($tableId);

        if ($vente) {
            $vente = $venteModel->findWithDetails($vente['id']);
        }

        view('vente/manage_table', [
            'table' => $table,
            'vente' => $vente
        ]);
    }

    public function add_order($tableId) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return; // Should not happen
        }
        
        $data = json_decode(file_get_contents('php://input'), true);
        if (empty($data['articles'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Aucun article à ajouter.']);
            return;
        }

        $pdo = Database::getInstance()->getConnection();
        
        try {
            $pdo->beginTransaction();

            $venteModel = new Vente();
            $vente = $venteModel->getOpenVenteForTable($tableId);
            
            if (!$vente) {
                // Si aucune vente n'existe, on ne fait rien (le cas de création de vente est dans `initiate_post_payment`)
                 throw new Exception("Aucune vente ouverte trouvée pour cette table.");
            }

            // 1. Créer la commande
            $commandeModel = new Commande();
            $commandeId = $commandeModel->create([
                'vente_id' => $vente['id'],
                'user_id' => $_SESSION['user']['id']
            ]);

            // 2. Créer les lignes de vente
            $ligneVenteModel = new LigneVente();
            $articleModel = new Article();
            $articles_with_cost = [];
            foreach($data['articles'] as $article) {
                $article_info = $articleModel->find($article['article_id']);
                $article['cout_achat_unitaire'] = $article_info['dernier_cout_achat'] ?? 0;
                $articles_with_cost[] = $article;
            }
            $ligneVenteModel->create($commandeId, $articles_with_cost);

            // 3. Mettre à jour le stock
            foreach ($data['articles'] as $article) {
                $articleModel->decrementStock($article['article_id'], $article['quantite']);
            }
            
            // 4. Mettre à jour le total de la vente
            $venteModel->updateTotal($vente['id']);
            
            $pdo->commit();
            header('Content-Type: application/json');
            echo json_encode(['success' => true]);

        } catch (Exception $e) {
            $pdo->rollBack();
            http_response_code(500);
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function remove_ligne($ligneId)
    {
        $pdo = Database::getInstance()->getConnection();
        
        try {
            $pdo->beginTransaction();
            
            $ligneVenteModel = new LigneVente();
            $ligne = $ligneVenteModel->find((int)$ligneId);

            if (!$ligne) {
                throw new Exception("Ligne de vente non trouvée.");
            }
            
            // On doit trouver la vente parente pour la redirection et la mise à jour
            $stmt = $pdo->prepare("SELECT v.id as vente_id, v.table_id FROM ventes v JOIN commandes c ON v.id = c.vente_id WHERE c.id = ?");
            $stmt->execute([$ligne['commande_id']]);
            $venteInfo = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$venteInfo) {
                throw new Exception("Vente associée non trouvée.");
            }

            // 1. Supprimer la ligne
            $ligneVenteModel->delete((int)$ligneId);

            // 2. Réajuster le stock
            (new Article())->incrementStock($ligne['article_id'], $ligne['quantite']);

            // 3. Mettre à jour le total de la vente principale
            (new Vente())->updateTotal($venteInfo['vente_id']);

            $pdo->commit();
            $_SESSION['message'] = "Article supprimé de la commande.";

        } catch (Exception $e) {
            $pdo->rollBack();
            $_SESSION['error'] = "Erreur lors de la suppression: " . $e->getMessage();
        }

        redirect('/manage-table/' . ($venteInfo['table_id'] ?? 0));
    }

    public function close_vente($venteId)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $venteModel = new Vente();
            if ($venteModel->close($venteId)) {
                $_SESSION['message'] = "Vente clôturée avec succès.";
                // On prépare l'impression en arrière-plan
                $_SESSION['print_facture_id'] = $venteId;
                redirect('/ventes');
            } else {
                $_SESSION['error'] = "Erreur lors de la clôture de la vente.";
                redirect('/post-paiement');
            }
        }
    }

    public function delete($id) {
        $pdo = Database::getInstance()->getConnection();
        $venteModel = new Vente();
        
        try {
            $pdo->beginTransaction();

            $vente = $venteModel->findWithDetails((int)$id);
            if (!$vente) {
                throw new Exception("Vente non trouvée.");
            }

            // Réajuster le stock en parcourant la nouvelle structure
            $articleModel = new Article();
            if (isset($vente['commandes'])) {
                foreach ($vente['commandes'] as $commande) {
                    if (isset($commande['lignes'])) {
                        foreach ($commande['lignes'] as $ligne) {
                            $articleModel->incrementStock($ligne['article_id'], $ligne['quantite']);
                        }
                    }
                }
            }
            
            // Supprimer la vente (la cascade s'occupera des commandes et lignes)
            $venteModel->delete((int)$id);

            $pdo->commit();
            $_SESSION['message'] = "Vente #{$id} supprimée et stock réajusté.";

        } catch (Exception $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            $_SESSION['error'] = "Erreur lors de la suppression : " . $e->getMessage();
        }
        redirect('/ventes');
    }

    public function exportExcel() {
        require_once __DIR__ . '/../services/ExcelExporter.php';

        $date_filtre = $_GET['date'] ?? date('Y-m-d');
        $user_filtre = $_SESSION['user'];
        
        $ventes = (new Vente())->findAllDetailedByDate($date_filtre, $user_filtre);

        // Préparer les données pour l'export
        $data_to_export = [];
        $grand_total = 0;

        // Titre principal du document
        $titre = "Rapport de Ventes Détaillé du " . date('d/m/Y', strtotime($date_filtre));
        $data_to_export[] = ['<style bgcolor="#2d2d2d" color="#FFFFFF" border="thin"><b>' . $titre . '</b></style>'];
        $data_to_export[] = []; // Ligne vide

        foreach ($ventes as $vente) {
            // En-tête pour chaque vente
            $header_vente = 'Vente ' . $vente['numero_vente'] . ' (' . $vente['table_nom'] . ') - ' . date('H:i', strtotime($vente['created_at']));
            $data_to_export[] = ['<style bgcolor="#f2f2f2" border="thin"><b>' . $header_vente . '</b></style>'];
            
            // Sous-en-têtes pour les articles
            $data_to_export[] = [
                '<style bgcolor="#595959" color="#FFFFFF" border="thin"><b>Article</b></style>',
                '<style bgcolor="#595959" color="#FFFFFF" border="thin"><b>Qté</b></style>',
                '<style bgcolor="#595959" color="#FFFFFF" border="thin"><b>P.U.</b></style>',
                '<style bgcolor="#595959" color="#FFFFFF" border="thin"><b>Total Ligne</b></style>'
            ];

            // Lignes d'articles
            foreach ($vente['lignes'] as $ligne) {
                $data_to_export[] = [
                    '<style border="thin">' . $ligne['article_nom'] . '</style>',
                    '<style border="thin">' . $ligne['quantite'] . '</style>',
                    '<style border="thin">' . $ligne['prix_unitaire_ht'] . '</style>',
                    '<style border="thin">' . ($ligne['quantite'] * $ligne['prix_unitaire_ht']) . '</style>'
                ];
            }

            // Sous-total par vente
            $data_to_export[] = [
                '<style border="thin"></style>',
                '<style border="thin"></style>',
                '<style border="thin"><b>Sous-total</b></style>',
                '<style border="thin"><b>' . $vente['vente_total'] . '</b></style>'
            ];
            $data_to_export[] = []; // Ligne vide
            $grand_total += $vente['vente_total'];
        }
        
        // Grand Total
        $data_to_export[] = [];
        $data_to_export[] = [
            '<style bgcolor="#2d2d2d" color="#FFFFFF" border="thin"></style>',
            '<style bgcolor="#2d2d2d" color="#FFFFFF" border="thin"></style>',
            '<style bgcolor="#2d2d2d" color="#FFFFFF" border="thin"><b>GRAND TOTAL</b></style>',
            '<style bgcolor="#2d2d2d" color="#FFFFFF" border="thin"><b>' . $grand_total . '</b></style>'
        ];

        $filename = "Rapport_Detaille_Ventes_" . date('Y-m-d', strtotime($date_filtre)) . ".xlsx";
        
        $col_widths = [1 => 40, 2 => 10, 3 => 15, 4 => 15];
        ExcelExporter::download($filename, $data_to_export, $col_widths);
    }
}
