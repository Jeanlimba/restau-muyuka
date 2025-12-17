<?php

require_once __DIR__ . '/../models/Equipement.php';

class EquipementController {

    public function index() {
        $equipementModel = new Equipement();
        $equipements = $equipementModel->all();
        view('equipements/index', ['equipements' => $equipements]);
    }

    public function create() {
        view('equipements/create');
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nom' => $_POST['nom'],
                'description' => $_POST['description'],
                'quantite_en_service' => $_POST['quantite_en_service'],
                'quantite_en_reparation' => $_POST['quantite_en_reparation'],
                'quantite_hors_service' => $_POST['quantite_hors_service'],
                'date_achat' => $_POST['date_achat'],
                'valeur' => $_POST['valeur'],
                'fournisseur' => $_POST['fournisseur']
            ];

            $equipementModel = new Equipement();
            if ($equipementModel->create($data)) {
                $_SESSION['message'] = "Équipement ajouté avec succès.";
                redirect('/equipements');
            } else {
                $_SESSION['error'] = "Erreur lors de l'ajout de l'équipement.";
                redirect('/equipements/create');
            }
        }
    }

    public function edit($id) {
        $equipementModel = new Equipement();
        $equipement = $equipementModel->find($id);
        if ($equipement) {
            view('equipements/edit', ['equipement' => $equipement]);
        } else {
            http_response_code(404);
            $_SESSION['error'] = "Équipement non trouvé.";
            redirect('/equipements');
        }
    }

    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nom' => $_POST['nom'],
                'description' => $_POST['description'],
                'quantite_en_service' => $_POST['quantite_en_service'],
                'quantite_en_reparation' => $_POST['quantite_en_reparation'],
                'quantite_hors_service' => $_POST['quantite_hors_service'],
                'date_achat' => $_POST['date_achat'],
                'valeur' => $_POST['valeur'],
                'fournisseur' => $_POST['fournisseur']
            ];

            $equipementModel = new Equipement();
            if ($equipementModel->update($id, $data)) {
                $_SESSION['message'] = "Équipement mis à jour avec succès.";
                redirect('/equipements');
            } else {
                $_SESSION['error'] = "Erreur lors de la mise à jour.";
                redirect('/equipements/edit/' . $id);
            }
        }
    }

    public function delete($id) {
        $equipementModel = new Equipement();
        if ($equipementModel->delete($id)) {
            $_SESSION['message'] = "Équipement supprimé avec succès.";
        } else {
            $_SESSION['error'] = "Erreur lors de la suppression.";
        }
        redirect('/equipements');
    }

    public function etatLieu() {
        $equipementModel = new Equipement();
        $equipements = $equipementModel->all();
        view('equipements/etat_lieu', ['equipements' => $equipements]);
    }

    public function updateEtatLieu() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['equipements'])) {
            $pdo = Database::getInstance()->getConnection();
            
            try {
                $pdo->beginTransaction();

                // 1. Créer l'enregistrement d'inventaire
                $stmtInv = $pdo->prepare("INSERT INTO inventaires_equipements (date_inventaire, responsable_id) VALUES (?, ?)");
                $stmtInv->execute([date('Y-m-d'), $_SESSION['user']['id']]);
                $inventaireId = $pdo->lastInsertId();

                // 2. Parcourir les équipements, mettre à jour le statut et enregistrer les lignes d'historique
                $stmtLigne = $pdo->prepare(
                    "INSERT INTO lignes_inventaire_equipement (inventaire_id, equipement_id, equipement_nom, quantite_en_service, quantite_en_reparation, quantite_hors_service) 
                     VALUES (?, ?, ?, ?, ?, ?)"
                );
                $stmtUpdate = $pdo->prepare(
                    "UPDATE equipements SET quantite_en_service = ?, quantite_en_reparation = ?, quantite_hors_service = ? WHERE id = ?"
                );

                foreach ($_POST['equipements'] as $id => $data) {
                    $qte_service = $data['qte_service'] ?? 0;
                    $qte_reparation = $data['qte_reparation'] ?? 0;
                    $qte_hors_service = $data['qte_hors_service'] ?? 0;

                    // Mettre à jour l'équipement
                    $stmtUpdate->execute([$qte_service, $qte_reparation, $qte_hors_service, $id]);
                    
                    // Enregistrer la ligne d'historique
                    $stmtLigne->execute([$inventaireId, $id, $data['nom'], $qte_service, $qte_reparation, $qte_hors_service]);
                }

                $pdo->commit();
                $_SESSION['message'] = "L'état des lieux a été enregistré avec succès.";

            } catch (Exception $e) {
                $pdo->rollBack();
                $_SESSION['error'] = "Erreur lors de l'enregistrement : " . $e->getMessage();
            }
        }
        redirect('/equipements');
    }

    public function historique() {
        $pdo = Database::getInstance()->getConnection();
        $stmt = $pdo->query(
            "SELECT ie.*, u.nom as responsable_nom 
             FROM inventaires_equipements ie 
             LEFT JOIN users u ON ie.responsable_id = u.id 
             ORDER BY ie.date_inventaire DESC"
        );
        $inventaires = $stmt->fetchAll(PDO::FETCH_ASSOC);
        view('equipements/historique', ['inventaires' => $inventaires]);
    }

    public function showHistorique($id) {
        $pdo = Database::getInstance()->getConnection();
        $stmt = $pdo->prepare("SELECT * FROM inventaires_equipements WHERE id = ?");
        $stmt->execute([$id]);
        $inventaire = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$inventaire) {
            http_response_code(404);
            redirect('/equipements/historique');
        }

        $stmtLignes = $pdo->prepare(
            "SELECT * FROM lignes_inventaire_equipement WHERE inventaire_id = ?"
        );
        $stmtLignes->execute([$id]);
        $inventaire['lignes'] = $stmtLignes->fetchAll(PDO::FETCH_ASSOC);
        
        view('equipements/historique_show', ['inventaire' => $inventaire]);
    }
    
    public function rapport() {
        $equipementModel = new Equipement();
        $stats = $equipementModel->getGlobalStats();
        view('equipements/rapport', ['stats' => $stats]);
    }
}
