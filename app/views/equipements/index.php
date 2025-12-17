<div class="page-header d-print-none mt-3">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title text-primary">
                    Gestion des Équipements
                </h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="/equipements/historique" class="btn">
                        <i class="fas fa-history me-2"></i>Historique
                    </a>
                    <a href="/equipements/rapport" class="btn">
                        <i class="fas fa-chart-pie me-2"></i>Voir le Rapport
                    </a>
                    <a href="/equipements/etat-lieu" class="btn btn-outline-secondary">
                        <i class="fas fa-tasks me-2"></i>Faire l'état des lieux
                    </a>
                    <a href="/equipements/create" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Nouvel Équipement
                    </a>
                </div>
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
                            <th>Nom</th>
                            <th class="text-center">Quantité Totale</th>
                            <th>Date d'achat</th>
                            <th>Valeur</th>
                            <th class="w-1">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($equipements as $equipement): ?>
                        <?php
                            $qte_service = $equipement['quantite_en_service'] ?? 0;
                            $qte_reparation = $equipement['quantite_en_reparation'] ?? 0;
                            $qte_hors_service = $equipement['quantite_hors_service'] ?? 0;
                            $quantite_totale = $qte_service + $qte_reparation + $qte_hors_service;
                        ?>
                        <tr>
                            <td>
                                <div><?= htmlspecialchars($equipement['nom']) ?></div>
                                <div class="text-muted small">
                                    <span class="badge bg-info">En service: <?= $qte_service ?></span>
                                    <span class="badge bg-warning">En réparation: <?= $qte_reparation ?></span>
                                    <span class="badge bg-danger">Hors service: <?= $qte_hors_service ?></span>
                                </div>
                            </td>
                            <td class="text-center fw-bold"><?= $quantite_totale ?></td>
                            <td><?= htmlspecialchars($equipement['date_achat'] ? date('d/m/Y', strtotime($equipement['date_achat'])) : 'N/A') ?></td>
                            <td class="text-end"><?= htmlspecialchars(number_format((float)($equipement['valeur'] ?? 0) * $quantite_totale, 0, ',', ' ')) ?> Fc</td>
                            <td>
                                <div class="btn-list flex-nowrap">
                                    <a href="/equipements/edit/<?= $equipement['id'] ?>" class="btn btn-sm">Modifier</a>
                                    <a href="/equipements/delete/<?= $equipement['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Voulez-vous vraiment supprimer cet équipement ?')">Supprimer</a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
