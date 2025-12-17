<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col-auto">
                <a href="/ventes" class="btn btn-secondary d-none d-sm-inline-block">
                    <i class="fas fa-arrow-left me-2"></i>
                    Retour à la liste des ventes
                </a>
            </div>
            <div class="col">
                <h2 class="page-title text-primary">
                    Tables avec Commandes en Cours
                </h2>
                <div class="text-muted mt-1">
                    Sélectionnez une table pour voir les détails ou ajouter des articles.
                </div>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-occuper-table">
                    <i class="fas fa-plus me-2"></i>Occuper une table
                </a>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <?php if (empty($zonesWithOccupiedTables)): ?>
            <div class="text-center text-muted p-5 card">
                <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                <h3>Toutes les tables sont libres !</h3>
                <p>Aucune commande n'est actuellement en cours de post-paiement.</p>
            </div>
        <?php else: ?>
            <?php foreach ($zonesWithOccupiedTables as $zone): ?>
                <div class="card card-lg mb-4">
                    <div class="card-header">
                        <h3 class="card-title"><?= htmlspecialchars($zone['nom']) ?></h3>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <?php foreach ($zone['tables'] as $table): ?>
                                <div class="col-md-3">
                                    <a href="/manage-table/<?= $table['id'] ?>" class="card card-link card-link-pop">
                                        <div class="card-body text-center">
                                            <h3 class="card-title mb-1"><?= htmlspecialchars($table['nom']) ?></h3>
                                            <p class="text-muted mb-2">Capacité: <?= htmlspecialchars($table['capacite']) ?></p>
                                            <?php
                                            $statusClass = 'bg-danger';
                                            if ($table['statut'] === 'en_attente_paiement') {
                                                $statusClass = 'bg-warning';
                                            }
                                            ?>
                                            <span class="badge <?= $statusClass ?>"><?= ucfirst(str_replace('_', ' ', $table['statut'])) ?></span>
                                        </div>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<!-- Modal pour Occuper une table -->
<div class="modal modal-blur fade" id="modal-occuper-table" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="/initiate-post-payment" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Occuper une table</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Sélectionnez une table disponible :</label>
                        <select name="table_id" class="form-select" required>
                            <?php if (empty($zonesWithFreeTables)): ?>
                                <option value="">Aucune table disponible</option>
                            <?php else: ?>
                                <?php foreach ($zonesWithFreeTables as $zone): ?>
                                    <optgroup label="<?= htmlspecialchars($zone['nom']) ?>">
                                        <?php foreach ($zone['tables'] as $table): ?>
                                            <option value="<?= $table['id'] ?>"><?= htmlspecialchars($table['nom']) ?> (Cap. <?= $table['capacite'] ?>)</option>
                                        <?php endforeach; ?>
                                    </optgroup>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn me-auto" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary" <?php echo empty($zonesWithFreeTables) ? 'disabled' : ''; ?>>Occuper la table</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.card-link {
    display: block;
    text-decoration: none;
    color: inherit;
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}
.card-link-pop:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
}
</style>
