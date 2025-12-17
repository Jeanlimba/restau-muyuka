<?php

class UniteMesureController {
    
    public function index() {
        $uniteModel = new UniteMesure();
        $unites_vente = $uniteModel->findByType('vente');
        $unites_achat = $uniteModel->findByType('achat');

        view('unites_mesure/index', [
            'unites_vente' => $unites_vente,
            'unites_achat' => $unites_achat
        ]);
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            (new UniteMesure())->create($_POST);
            $_SESSION['message'] = "Unité de mesure créée avec succès.";
        }
        redirect('/gestion-unites');
    }

    public function delete($id) {
        (new UniteMesure())->delete($id);
        $_SESSION['message'] = "Unité de mesure supprimée avec succès.";
        redirect('/gestion-unites');
    }

    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            (new UniteMesure())->update($id, $_POST);
            $_SESSION['message'] = "Unité de mesure mise à jour avec succès.";
        }
        redirect('/gestion-unites');
    }
}
