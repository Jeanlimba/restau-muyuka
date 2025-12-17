<?php

class TableController {

    public function index() {
        $zonesWithTables = (new Table())->getZonesWithTables();
        $allZones = (new Zone())->findAll();
        view('tables/index', [
            'zonesWithTables' => $zonesWithTables,
            'allZones' => $allZones
        ]);
    }

    public function createZone() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            (new Zone())->create($_POST);
            $_SESSION['message'] = "Zone créée avec succès.";
        }
        redirect('/gestion-tables');
    }

    public function createTable() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            (new Table())->create($_POST);
            $_SESSION['message'] = "Table créée avec succès.";
        }
        redirect('/gestion-tables');
    }

    public function createTableBatch() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($_POST['numero_fin'] < $_POST['numero_debut']) {
                $_SESSION['error'] = "Le numéro de fin doit être supérieur ou égal au numéro de début.";
            } else {
                (new Table())->createMultiple($_POST);
                $_SESSION['message'] = "Tables créées en série avec succès.";
            }
        }
        redirect('/gestion-tables');
    }

    public function updateTable($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            (new Table())->update($id, $_POST);
            $_SESSION['message'] = "Table mise à jour avec succès.";
        }
        redirect('/gestion-tables');
    }

    public function updateStatut($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            (new Table())->updateStatut($id, $_POST['statut']);
            $_SESSION['message'] = "Statut de la table mis à jour.";
        }
        redirect('/gestion-tables');
    }

        public function delete($id) {

            (new Table())->delete($id);

            $_SESSION['message'] = "Table supprimée avec succès.";

            redirect('/gestion-tables');

        }

    

        public function updateZone($id) {

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                (new Zone())->update($id, $_POST);

                $_SESSION['message'] = "Zone mise à jour avec succès.";

            }

            redirect('/gestion-tables');

        }

    }

    