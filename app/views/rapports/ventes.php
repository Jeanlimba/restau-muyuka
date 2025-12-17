<div class="page-header">
    <div class="container-xl">
        <h2 class="page-title text-primary">
            Rapport des Ventes
        </h2>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="card mb-3">
            <div class="card-body">
                <form action="/rapports/ventes" method="GET" class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label for="start_date" class="form-label">Date de début</label>
                        <input type="date" name="start_date" id="start_date" class="form-control" value="<?= htmlspecialchars($filters['start_date']) ?>">
                    </div>
                    <div class="col-md-3">
                        <label for="end_date" class="form-label">Date de fin</label>
                        <input type="date" name="end_date" id="end_date" class="form-control" value="<?= htmlspecialchars($filters['end_date']) ?>">
                    </div>
                    <div class="col-md-3">
                        <label for="group_by" class="form-label">Regrouper par</label>
                        <select name="group_by" id="group_by" class="form-select">
                            <option value="day" <?= $filters['group_by'] == 'day' ? 'selected' : '' ?>>Jour</option>
                            <option value="month" <?= $filters['group_by'] == 'month' ? 'selected' : '' ?>>Mois</option>
                            <option value="vente" <?= $filters['group_by'] == 'vente' ? 'selected' : '' ?>>Par Vente</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex">
                        <button type="submit" class="btn btn-primary w-100 me-2">
                            <i class="fas fa-filter me-2"></i>Filtrer
                        </button>
                        <a href="/rapports/ventes/export?<?= http_build_query($filters) ?>" class="btn btn-success" title="Exporter en Excel">
                            <i class="fas fa-file-excel"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- KPIs -->
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">Chiffre d'Affaires Total</div>
                        </div>
                        <div class="h1 mb-3"><?= number_format($kpis['total_revenue'], 0, ',', ' ') ?> Fc</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">Nombre de Ventes</div>
                        </div>
                        <div class="h1 mb-3"><?= $kpis['total_sales_count'] ?></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">Panier Moyen</div>
                        </div>
                        <div class="h1 mb-3"><?= number_format($kpis['average_basket'], 0, ',', ' ') ?> Fc</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Tableau de résultats -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Données Détaillées</h3>
            </div>
            <div class="table-responsive">
                <?php if ($filters['group_by'] == 'day' || $filters['group_by'] == 'month'): ?>
                <table class="table table-vcenter card-table">
                    <thead>
                        <tr>
                            <th>Période</th>
                            <th class="text-end">Chiffre d'Affaires</th>
                            <th class="text-end">Nombre de Ventes</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($report_data as $row): ?>
                        <tr>
                            <td>
                                <?php 
                                    if ($filters['group_by'] == 'day') {
                                        echo date('d/m/Y', strtotime($row['periode']));
                                    } else {
                                        echo date('F Y', strtotime($row['periode'] . '-01'));
                                    }
                                ?>
                            </td>
                            <td class="text-end"><?= number_format($row['total_ventes'], 0, ',', ' ') ?> Fc</td>
                            <td class="text-end"><?= $row['nombre_ventes'] ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php else: // 'vente' ?>
                 <table class="table table-vcenter card-table">
                    <thead>
                        <tr>
                            <th>N° Vente</th>
                            <th>Table</th>
                            <th>Heure</th>
                            <th class="text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($report_data as $vente_id => $vente): ?>
                        <tr>
                            <td>
                                <a href="/vente/show/<?= $vente_id ?>" class="text-reset" title="Voir le détail">
                                    <?= htmlspecialchars($vente['numero_vente']) ?>
                                </a>
                            </td>
                            <td><?= htmlspecialchars($vente['table_nom']) ?></td>
                            <td><?= date('H:i', strtotime($vente['created_at'])) ?></td>
                            <td class="text-end"><?= number_format($vente['vente_total'], 0, ',', ' ') ?> Fc</td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php endif; ?>

                <?php if (empty($report_data)): ?>
                    <div class="text-center text-muted p-4">Aucune donnée pour la période sélectionnée.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
