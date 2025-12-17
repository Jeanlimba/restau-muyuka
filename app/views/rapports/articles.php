<div class="page-header">
    <div class="container-xl">
        <h2 class="page-title text-primary">
            Rapport de Gain par Article
        </h2>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="card mb-3">
            <div class="card-body">
                <form action="/rapports/articles" method="GET" class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label for="start_date" class="form-label">Date de début</label>
                        <input type="date" name="start_date" id="start_date" class="form-control" value="<?= htmlspecialchars($filters['start_date']) ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="end_date" class="form-label">Date de fin</label>
                        <input type="date" name="end_date" id="end_date" class="form-control" value="<?= htmlspecialchars($filters['end_date']) ?>">
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-filter me-2"></i>Filtrer
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="table-responsive">
                <table class="table table-vcenter card-table table-striped">
                    <thead>
                        <tr>
                            <th>Article</th>
                            <th class="text-end">Qté Vendue</th>
                            <th class="text-end">CA HT</th>
                            <th class="text-end">Coût Total HT</th>
                            <th class="text-end">Gain Brut</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            $total_ca = 0;
                            $total_cout = 0;
                            $total_gain = 0;
                        ?>
                        <?php foreach ($report_data as $row): ?>
                        <?php 
                            $total_ca += $row['chiffre_affaires_ht'];
                            $total_cout += $row['cout_total_ht'];
                            $total_gain += $row['gain_brut'];
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($row['article_nom']) ?></td>
                            <td class="text-end"><?= htmlspecialchars($row['total_quantite_vendue']) ?></td>
                            <td class="text-end"><?= number_format($row['chiffre_affaires_ht'], 0, ',', ' ') ?> Fc</td>
                            <td class="text-end text-muted"><?= number_format($row['cout_total_ht'], 0, ',', ' ') ?> Fc</td>
                            <td class="text-end fw-bold <?= $row['gain_brut'] >= 0 ? 'text-success' : 'text-danger' ?>">
                                <?= number_format($row['gain_brut'], 0, ',', ' ') ?> Fc
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr class="bg-light">
                            <th class="text-end">TOTAL</th>
                            <th></th>
                            <th class="text-end h3"><?= number_format($total_ca, 0, ',', ' ') ?> Fc</th>
                            <th class="text-end h3 text-muted"><?= number_format($total_cout, 0, ',', ' ') ?> Fc</th>
                            <th class="text-end h3 text-success"><?= number_format($total_gain, 0, ',', ' ') ?> Fc</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
