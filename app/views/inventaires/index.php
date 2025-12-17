<div class="page-header d-print-none mt-3">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title text-primary">
                    Historique des Inventaires
                </h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <a href="/inventaires/create" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Nouvel Inventaire
                </a>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <div class="table-responsive">
                <table class="table table-vcenter card-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Responsable</th>
                            <th>Statut</th>
                            <th>Notes</th>
                            <th class="w-1"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($inventaires as $inventaire): ?>
                        <tr>
                            <td><?= htmlspecialchars(date('d/m/Y', strtotime($inventaire['date_inventaire']))) ?></td>
                            <td><?= htmlspecialchars($inventaire['responsable_nom'] ?? 'N/A') ?></td>
                            <td>
                                <?php 
                                    $statut_class = 'bg-secondary';
                                    if ($inventaire['statut'] === 'Terminé') $statut_class = 'bg-success';
                                    if ($inventaire['statut'] === 'Annulé') $statut_class = 'bg-danger';
                                ?>
                                <span class="badge <?= $statut_class ?>"><?= htmlspecialchars($inventaire['statut']) ?></span>
                            </td>
                            <td class="text-muted"><?= nl2br(htmlspecialchars($inventaire['notes'] ?? '')) ?></td>
                            <td>
                                <a href="/inventaires/show/<?= $inventaire['id'] ?>" class="btn btn-sm">Voir Détails</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
