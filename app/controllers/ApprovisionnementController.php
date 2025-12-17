<?php

class ApprovisionnementController {

    public function index() {
        $approvisionnements = (new Approvisionnement())->findAllGrouped();
        view('approvisionnements/index', ['approvisionnements' => $approvisionnements]);
    }

    public function show($id) {
        $appro = (new Approvisionnement())->findWithLignes((int)$id);
        if (!$appro) {
            http_response_code(404);
            $_SESSION['error'] = "Approvisionnement non trouvé.";
            redirect('/approvisionnements');
            return;
        }
        view('approvisionnements/show', ['appro' => $appro]);
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_SESSION['user_id'])) {
                $_SESSION['error'] = "Votre session a expiré. Veuillez vous reconnecter.";
                redirect('/login');
                return;
            }

            $data = $this->prepareDataFromPost($_POST, $_SESSION['user_id']);
            if (empty($data['lignes'])) {
                $_SESSION['error'] = "Vous devez ajouter au moins un article.";
                redirect('/approvisionnements/create');
                return;
            }

            $this->executeTransaction($data);
        } else {
            $articles = (new Article())->all();
            view('approvisionnements/create', ['articles' => $articles]);
        }
    }

    public function edit($id) {
        $appro = (new Approvisionnement())->findWithLignes((int)$id);
        if (!$appro) {
            http_response_code(404);
            $_SESSION['error'] = "Approvisionnement non trouvé.";
            redirect('/approvisionnements');
            return;
        }
        view('approvisionnements/edit', ['appro' => $appro]);
    }

    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'numero_bon' => $_POST['numero_bon'],
                'date_approvisionnement' => $_POST['date_approvisionnement'],
                'fournisseur' => $_POST['fournisseur'],
                'observation' => $_POST['observation'],
            ];

            if ((new Approvisionnement())->update((int)$id, $data)) {
                $_SESSION['message'] = "Approvisionnement mis à jour avec succès.";
            } else {
                $_SESSION['error'] = "Erreur lors de la mise à jour.";
            }
            redirect('/approvisionnements');
        }
    }

    public function delete($id) {
        $approModel = new Approvisionnement();
        $appro = $approModel->findWithLignes((int)$id);

        if (!$appro) {
            http_response_code(404);
            $_SESSION['error'] = "Approvisionnement non trouvé.";
            redirect('/approvisionnements');
            return;
        }

        $pdo = Database::getInstance()->getConnection();
        $pdo->beginTransaction();

        try {
            // Décrémenter le stock pour chaque article
            $articleModel = new Article();
            foreach ($appro['lignes'] as $ligne) {
                $articleModel->decrementStock($ligne['article_id'], $ligne['quantite']);
            }
            
            // Supprimer l'approvisionnement (les lignes seront supprimées en cascade)
            $approModel->delete((int)$id);

            $pdo->commit();
            $_SESSION['message'] = "Approvisionnement supprimé avec succès. Le stock a été ajusté.";
        } catch (Exception $e) {
            $pdo->rollBack();
            $_SESSION['error'] = "Erreur lors de la suppression : " . $e->getMessage();
        }
        redirect('/approvisionnements');
    }

    private function prepareDataFromPost(array $post, int $userId): array {
        $data = [
            'numero_bon' => $post['numero_bon'],
            'date_approvisionnement' => $post['date_approvisionnement'],
            'fournisseur' => $post['fournisseur'],
            'observation' => $post['observation'],
            'user_id' => $userId,
            'lignes' => []
        ];
        foreach ($post['article_id'] as $index => $articleId) {
            if (!empty($articleId) && !empty($post['quantite'][$index])) {
                $data['lignes'][] = [
                    'article_id' => $articleId,
                    'quantite' => $post['quantite'][$index],
                    'prix_achat' => $post['prix_achat'][$index],
                    'unit_type' => $post['unit_type'][$index]
                ];
            }
        }
        return $data;
    }

    private function executeTransaction(array $data) {
        $pdo = Database::getInstance()->getConnection();
        $pdo->beginTransaction();

        try {
            (new Approvisionnement())->create($data);
            
            $articleModel = new Article();
            foreach ($data['lignes'] as $ligne) {
                // Récupérer l'article pour connaître son facteur de conversion
                $article = $articleModel->find($ligne['article_id']);
                if (!$article) {
                    throw new Exception("Article avec ID {$ligne['article_id']} non trouvé.");
                }

                $quantite_en_unites_de_vente = $ligne['quantite'];
                
                // Si l'approvisionnement a été fait en unité d'achat, on convertit
                if ($ligne['unit_type'] === 'achat') {
                    $quantite_en_unites_de_vente *= $article['conversion_factor'];
                }

                $articleModel->incrementStock($ligne['article_id'], $quantite_en_unites_de_vente);
                $articleModel->updateLastCost($ligne['article_id'], $ligne['prix_achat']);
            }

            $pdo->commit();
            $_SESSION['message'] = "Approvisionnement enregistré avec succès.";
            redirect('/approvisionnements');
        } catch (Exception $e) {
            $pdo->rollBack();
            $_SESSION['error'] = "Erreur : " . $e->getMessage();
            redirect('/approvisionnements/create');
        }
    }
}
