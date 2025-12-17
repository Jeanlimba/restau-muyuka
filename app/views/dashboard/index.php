<div class="container-fluid">
    <!-- En-tête du Dashboard -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">📊 Tableau de Bord Analytique</h2>
                <div class="text-muted mt-1">Vue d'ensemble des performances du restaurant</div>
            </div>
            <div class="col-auto">
                <div class="btn-list">
                    <span class="badge bg-green-lt">
                        <i class="fas fa-calendar me-1"></i>
                        <?= date('d/m/Y') ?>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Cartes de Statistiques -->
    <div class="row">
        <!-- Chiffre d'Affaires du Jour -->
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="subheader">💰 CA du Jour</div>
                    </div>
                    <div class="h1 mb-3"><?= number_format($stats['chiffre_affaires'] ?? 0, 2) ?> Fc</div>
                    <div class="d-flex mb-2">
                        <div>Ventes aujourd'hui</div>
                        <div class="ms-auto">
                            <span class="text-green d-inline-flex align-items-center lh-1">
                                <?= $stats['ventes_du_jour'] ?? 0 ?> commandes
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tables Occupées -->
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="subheader">🏢 Occupation</div>
                    </div>
                    <div class="h1 mb-3"><?= $stats['tables_occupees'] ?? 0 ?></div>
                    <div class="d-flex mb-2">
                        <div>Tables occupées</div>
                        <div class="ms-auto">
                            <span class="text-orange d-inline-flex align-items-center lh-1">
                                <?= $stats['tables_occupees'] ?? 0 ?> / <?= $stats['total_tables'] ?? 30 ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ventes Moyennes -->
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="subheader">📈 Panier Moyen</div>
                    </div>
                    <div class="h1 mb-3">
                        <?php 
                        $ventes_jour = $stats['ventes_du_jour'] ?? 0;
                        $ca_jour = $stats['chiffre_affaires'] ?? 0;
                        $panier_moyen = $ventes_jour > 0 ? $ca_jour / $ventes_jour : 0;
                        echo number_format($panier_moyen, 2); 
                        ?> Fc
                    </div>
                    <div class="d-flex mb-2">
                        <div>Par commande</div>
                        <div class="ms-auto">
                            <span class="text-blue d-inline-flex align-items-center lh-1">
                                <?= $ventes_jour ?> transactions
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Performance Zones -->
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="subheader">📍 Zones Actives</div>
                    </div>
                    <div class="h1 mb-3"><?= $stats['zones_actives'] ?? 3 ?></div>
                    <div class="d-flex mb-2">
                        <div>Zones en service</div>
                        <div class="ms-auto">
                            <span class="text-purple d-inline-flex align-items-center lh-1">
                                Salle · Terrasse · VIP
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphiques et Détails -->
    <div class="row">
        <!-- Répartition par Zone -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">📊 Répartition des Ventes par Zone</h3>
                </div>
                <div class="card-body">
                    <div class="chart-pie">
                        <!-- Placeholder pour graphique circulaire -->
                        <div class="text-center py-4">
                            <div class="row">
                                <div class="col-4">
                                    <div class="h4 text-blue">45%</div>
                                    <div class="text-muted">Salle</div>
                                </div>
                                <div class="col-4">
                                    <div class="h4 text-green">35%</div>
                                    <div class="text-muted">Terrasse</div>
                                </div>
                                <div class="col-4">
                                    <div class="h4 text-purple">20%</div>
                                    <div class="text-muted">VIP</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Articles -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">🏆 Top 5 des Articles</h3>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <?php foreach ($stats['top_articles'] ?? [] as $index => $article): ?>
                        <div class="list-group-item">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <span class="badge bg-blue">#<?= $index + 1 ?></span>
                                </div>
                                <div class="col">
                                    <div class="font-weight-medium"><?= htmlspecialchars($article['nom']) ?></div>
                                    <div class="text-muted">
                                        <?= $article['quantite'] ?> ventes
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="text-success">
                                        <?= number_format($article['total'], 2) ?> Fc
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        
                        <?php if (empty($stats['top_articles'])): ?>
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-chart-bar fa-2x mb-2"></i>
                            <div>Aucune donnée de vente aujourd'hui</div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques Temporelles -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">⏱️ Performance Horaires</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-vcenter">
                            <thead>
                                <tr>
                                    <th>Période</th>
                                    <th>Commandes</th>
                                    <th>Chiffre d'Affaires</th>
                                    <th>Panier Moyen</th>
                                    <th>Taux d'Occupation</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Matin (8h-12h)</td>
                                    <td>12</td>
                                    <td>245,50 Fc</td>
                                    <td>20,46 Fc</td>
                                    <td>
                                        <div class="progress progress-sm">
                                            <div class="progress-bar" style="width: 35%"></div>
                                        </div>
                                        <div class="small text-muted">35%</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Midi (12h-14h)</td>
                                    <td>28</td>
                                    <td>680,75 Fc</td>
                                    <td>24,31 Fc</td>
                                    <td>
                                        <div class="progress progress-sm">
                                            <div class="progress-bar bg-success" style="width: 85%"></div>
                                        </div>
                                        <div class="small text-muted">85%</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Après-midi (14h-18h)</td>
                                    <td>15</td>
                                    <td>320,25 Fc</td>
                                    <td>21,35 Fc</td>
                                    <td>
                                        <div class="progress progress-sm">
                                            <div class="progress-bar" style="width: 45%"></div>
                                        </div>
                                        <div class="small text-muted">45%</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Soir (18h-22h)</td>
                                    <td>32</td>
                                    <td>890,40 Fc</td>
                                    <td>27,83 Fc</td>
                                    <td>
                                        <div class="progress progress-sm">
                                            <div class="progress-bar bg-success" style="width: 92%"></div>
                                        </div>
                                        <div class="small text-muted">92%</div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.chart-pie {
    min-height: 200px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.progress-sm {
    height: 6px;
}
</style>