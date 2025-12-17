<div class="page-header">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title text-primary">
                    Rapport sur l'État des Équipements
                </h2>
                <div class="text-muted mt-1">
                    Synthèse du parc d'équipements par état.
                </div>
            </div>
            <div class="col-auto">
                <a href="/equipements" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>
                    Retour
                </a>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="row g-4">
            <div class="col-md-6 col-xl-4">
                 <div class="card card-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-blue text-white avatar"><i class="fas fa-box-open"></i></span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium">
                                    Total Équipements
                                </div>
                                <div class="text-muted">
                                    <?= htmlspecialchars($stats['total_equipements'] ?? 0) ?> unités
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-4">
                 <div class="card card-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-green text-white avatar"><i class="fas fa-dollar-sign"></i></span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium">
                                    Valeur Totale du Parc
                                </div>
                                <div class="text-muted">
                                    <?= number_format($stats['total_valeur'] ?? 0, 0, ',', ' ') ?> Fc
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h3 class="card-title">Détails par État</h3>
            </div>
            <div class="table-responsive">
                <table class="table table-vcenter card-table">
                    <thead>
                        <tr>
                            <th>État</th>
                            <th class="text-center">Nombre d'unités</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>En service</td>
                            <td class="text-center"><?= htmlspecialchars($stats['total_en_service'] ?? 0) ?></td>
                        </tr>
                         <tr>
                            <td>En réparation</td>
                            <td class="text-center"><?= htmlspecialchars($stats['total_en_reparation'] ?? 0) ?></td>
                        </tr>
                         <tr>
                            <td>Hors service</td>
                            <td class="text-center"><?= htmlspecialchars($stats['total_hors_service'] ?? 0) ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
