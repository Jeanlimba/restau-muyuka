<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title text-primary">
                    Détails de l'Approvisionnement
                </h2>
                <div class="text-muted mt-1">Bon N°: <?= htmlspecialchars($appro['numero_bon']) ?></div>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <a href="/approvisionnements" class="btn btn-outline-secondary">
                    Retour à la liste
                </a>
                <a href="/approvisionnement/edit/<?= $appro['id'] ?>" class="btn btn-primary ms-2">
                    Modifier
                </a>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="card mb-3">
            <div class="card-header">
                <h3 class="card-title">Informations Générales</h3>
            </div>
            <div class="card-body">
                <p><strong>Date:</strong> <?= htmlspecialchars(date('d/m/Y', strtotime($appro['date_approvisionnement']))) ?></p>
                <p><strong>Fournisseur:</strong> <?= htmlspecialchars($appro['fournisseur'] ?: 'N/A') ?></p>
                <p><strong>Observation:</strong> <?= htmlspecialchars($appro['observation'] ?: 'Aucune') ?></p>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Articles Inclus</h3>
            </div>
            <div class="table-responsive">
                <table class="table table-vcenter card-table">
                    <thead>
                        <tr>
                            <th>Article</th>
                            <th>Quantité</th>
                            <th>Prix d'Achat Unitaire</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $total_general = 0;
                        foreach ($appro['lignes'] as $ligne): 
                            $total_ligne = $ligne['quantite'] * $ligne['prix_achat'];
                            $total_general += $total_ligne;
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($ligne['article_nom']) ?></td>
                            <td><?= htmlspecialchars($ligne['quantite']) ?></td>
                            <td><?= htmlspecialchars(number_format($ligne['prix_achat'], 0, ',', ' ')) ?> Fc</td>
                            <td><?= htmlspecialchars(number_format($total_ligne, 0, ',', ' ')) ?> Fc</td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3" class="text-end">Coût Total</th>
                            <th><?= htmlspecialchars(number_format($total_general, 0, ',', ' ')) ?> Fc</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
