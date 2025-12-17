<?php

require_once __DIR__ . '/../models/Vente.php';

class RapportController {

    public function ventes() {
        // Définir les dates par défaut (le mois en cours)
        $start_date = $_GET['start_date'] ?? date('Y-m-01');
        $end_date = $_GET['end_date'] ?? date('Y-m-t');
        $group_by = $_GET['group_by'] ?? 'day'; // 'day', 'month', or 'vente'

        $venteModel = new Vente();
        $report_data = [];
        
        if ($group_by === 'day' || $group_by === 'month') {
            $report_data = $venteModel->getSalesReport($start_date, $end_date, $group_by);
        } else { // group_by 'vente'
            $report_data = $venteModel->findAllDetailedByDate($start_date, $_SESSION['user']);
        }
        
        // Calculer les KPIs (basé sur le rapport de ventes agrégé pour la performance)
        $kpi_data = $venteModel->getSalesReport($start_date, $end_date, 'day');
        $total_revenue = array_sum(array_column($kpi_data, 'total_ventes'));
        $total_sales_count = array_sum(array_column($kpi_data, 'nombre_ventes'));
        $average_basket = ($total_sales_count > 0) ? $total_revenue / $total_sales_count : 0;

        $kpis = [
            'total_revenue' => $total_revenue,
            'total_sales_count' => $total_sales_count,
            'average_basket' => $average_basket
        ];

        view('rapports/ventes', [
            'report_data' => $report_data,
            'kpis' => $kpis,
            'filters' => [
                'start_date' => $start_date,
                'end_date' => $end_date,
                'group_by' => $group_by
            ]
        ]);
    }

    public function exportVentes() {
        require_once __DIR__ . '/../services/ExcelExporter.php';

        $start_date = $_GET['start_date'] ?? date('Y-m-01');
        $end_date = $_GET['end_date'] ?? date('Y-m-t');
        $group_by = $_GET['group_by'] ?? 'day';

        $venteModel = new Vente();
        $data_to_export = [];
        
        $titre = "Rapport de Ventes du " . date('d/m/Y', strtotime($start_date)) . " au " . date('d/m/Y', strtotime($end_date));
        $data_to_export[] = ['<style bgcolor="#2d2d2d" color="#FFFFFF" border="thin"><b>' . $titre . '</b></style>'];
        $data_to_export[] = [];

        if ($group_by === 'day' || $group_by === 'month') {
            $report_data = $venteModel->getSalesReport($start_date, $end_date, $group_by);
            $data_to_export[] = [
                '<style bgcolor="#595959" color="#FFFFFF" border="thin"><b>Période</b></style>',
                '<style bgcolor="#595959" color="#FFFFFF" border="thin"><b>Chiffre d\'Affaires</b></style>',
                '<style bgcolor="#595959" color="#FFFFFF" border="thin"><b>Nombre de Ventes</b></style>'
            ];
            foreach($report_data as $row) {
                 $data_to_export[] = [
                    '<style border="thin">' . $row['periode'] . '</style>',
                    '<style border="thin">' . $row['total_ventes'] . '</style>',
                    '<style border="thin">' . $row['nombre_ventes'] . '</style>'
                 ];
            }
        } else { // 'vente'
            $ventes = $venteModel->findAllDetailedByDate($start_date, $_SESSION['user']);
            foreach ($ventes as $vente) {
                $header_vente = 'Vente ' . $vente['numero_vente'] . ' (' . $vente['table_nom'] . ') - ' . date('H:i', strtotime($vente['created_at']));
                $data_to_export[] = ['<style bgcolor="#f2f2f2" border="thin"><b>' . $header_vente . '</b></style>'];
                $data_to_export[] = [
                    '<style bgcolor="#595959" color="#FFFFFF" border="thin"><b>Article</b></style>',
                    '<style bgcolor="#595959" color="#FFFFFF" border="thin"><b>Qté</b></style>',
                    '<style bgcolor="#595959" color="#FFFFFF" border="thin"><b>P.U.</b></style>',
                    '<style bgcolor="#595959" color="#FFFFFF" border="thin"><b>Total Ligne</b></style>'
                ];
                foreach ($vente['lignes'] as $ligne) {
                    $data_to_export[] = [
                        '<style border="thin">' . $ligne['article_nom'] . '</style>',
                        '<style border="thin">' . $ligne['quantite'] . '</style>',
                        '<style border="thin">' . $ligne['prix_unitaire_ht'] . '</style>',
                        '<style border="thin">' . ($ligne['quantite'] * $ligne['prix_unitaire_ht']) . '</style>'
                    ];
                }
                $data_to_export[] = ['', '', '<style border="thin"><b>Sous-total</b></style>', '<style border="thin"><b>' . $vente['vente_total'] . '</b></style>'];
                $data_to_export[] = [];
            }
        }
        $filename = "Rapport_Ventes_" . $start_date . "_au_" . $end_date . ".xlsx";
        ExcelExporter::download($filename, $data_to_export, [1 => 40, 2 => 15, 3 => 15, 4 => 15]);
    }

    public function articles() {
        $start_date = $_GET['start_date'] ?? date('Y-m-01');
        $end_date = $_GET['end_date'] ?? date('Y-m-t');

        $articleModel = new Article();
        $report_data = $articleModel->getProfitReport($start_date, $end_date);

        view('rapports/articles', [
            'report_data' => $report_data,
            'filters' => [
                'start_date' => $start_date,
                'end_date' => $end_date
            ]
        ]);
    }
}
