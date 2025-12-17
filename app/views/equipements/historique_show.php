<div class="page-header">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
             <div class="col-auto">
                <a href="/equipements/historique" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>
                    Retour à l'historique
                </a>
            </div>
            <div class="col">
                <h2 class="page-title text-primary">
                    Détail de l'Inventaire du <?= htmlspecialchars(date('d/m/Y', strtotime($inventaire['date_inventaire']))) ?>
                </h2>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="card">
             <div class="card-header">
                <h3 class="card-title">État des équipements à cette date</h3>
            </div>
            <div class="table-responsive">
                <table class="table table-vcenter card-table table-striped">
                    <thead>
                        <tr>
                            <th>Équipement</th>
                            <th class="text-center">En service</th>
                            <th class="text-center">En réparation</th>
                            <th class="text-center">Hors service</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($inventaire['lignes'] as $ligne): ?>
                        <tr>
                            <td><?= htmlspecialchars($ligne['equipement_nom']) ?></td>
                            <td class="text-center"><?= htmlspecialchars($ligne['quantite_en_service']) ?></td>
                            <td class="text-center"><?= htmlspecialchars($ligne['quantite_en_reparation']) ?></td>
                            <td class="text-center"><?= htmlspecialchars($ligne['quantite_hors_service']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
