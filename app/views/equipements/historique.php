<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col-auto">
                <a href="/equipements" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>
                    Retour
                </a>
            </div>
            <div class="col">
                <h2 class="page-title text-primary">
                    Historique des Inventaires d'Équipements
                </h2>
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
                            <th>Date de l'inventaire</th>
                            <th>Responsable</th>
                            <th>Notes</th>
                            <th class="w-1"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($inventaires as $inventaire): ?>
                        <tr>
                            <td><?= htmlspecialchars(date('d/m/Y', strtotime($inventaire['date_inventaire']))) ?></td>
                            <td><?= htmlspecialchars($inventaire['responsable_nom'] ?? 'N/A') ?></td>
                            <td class="text-muted"><?= htmlspecialchars($inventaire['notes'] ?? '') ?></td>
                            <td>
                                <a href="/equipements/historique/<?= $inventaire['id'] ?>" class="btn btn-sm">Voir le détail</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
