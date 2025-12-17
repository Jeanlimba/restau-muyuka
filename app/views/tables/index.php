<div class="page-header d-print-none mt-3">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title text-primary">Gestion des Tables et Zones</h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="#" class="btn btn-secondary btn-square" data-bs-toggle="modal" data-bs-target="#modal-new-zone">
                        Nouvelle Zone
                    </a>
                    <a href="#" class="btn btn-primary btn-square" data-bs-toggle="modal" data-bs-target="#modal-new-table">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="12" y1="5" x2="12" y2="19" /><line x1="5" y1="12" x2="19" y2="12" /></svg>
                        Nouvelle Table
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <?php foreach ($zonesWithTables as $zone): ?>
            <div class="mb-4 border p-1 rounded border-2">
                <div class="card card-lg mb-4">
                    <div class="card-header bg-dark text-white">
                        <h3 class="card-title"><?= htmlspecialchars($zone['nom']) ?> (Préfixe: <?= htmlspecialchars($zone['prefixe'] ?? '') ?>)</h3>
                        <div class="card-actions">
                            <a href="#" class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#modal-edit-zone"
                            data-zone-id="<?= $zone['id'] ?>"
                            data-zone-nom="<?= htmlspecialchars($zone['nom']) ?>"
                            data-zone-prefixe="<?= htmlspecialchars($zone['prefixe'] ?? '') ?>"
                            data-zone-description="<?= htmlspecialchars($zone['description'] ?? '') ?>">
                                Modifier la Zone
                            </a>
                        </div>
                    </div>
                </div>
                <div class="row g-3">
                    <?php if (empty($zone['tables'])): ?>
                        <div class="col-12 text-center text-muted">Aucune table dans cette zone.</div>
                    <?php endif; ?>
                    <?php foreach ($zone['tables'] as $table): ?>
                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-body text-center">
                                    <h4 class="card-title mb-1"><?= htmlspecialchars($table['nom']) ?></h4>
                                    <p class="text-muted mb-2">Capacité: <?= htmlspecialchars($table['capacite']) ?></p>
                                    <?php
                                    $statusClass = '';
                                    switch ($table['statut']) {
                                        case 'libre':
                                            $statusClass = 'bg-success-lt';
                                            break;
                                        case 'occupee':
                                            $statusClass = 'bg-danger-lt';
                                            break;
                                        case 'en_attente_paiement':
                                            $statusClass = 'bg-warning-lt';
                                            break;
                                    }
                                    ?>
                                    <span class="badge <?= $statusClass ?>"><?= ucfirst($table['statut']) ?></span>
                                    <div class="mt-3 btn-list">
                                        <a href="#" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modal-edit-table"
                                            data-table-id="<?= $table['id'] ?>"
                                            data-table-numero="<?= $table['numero'] ?>"
                                            data-table-capacite="<?= $table['capacite'] ?>"
                                            data-table-zone-id="<?= $table['zone_id'] ?>">
                                            Modifier
                                        </a>
                                        <a href="/gestion-tables/delete/<?= $table['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette table ?')">Supprimer</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Modal Modifier Table -->
<div class="modal modal-blur fade" id="modal-edit-table" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="edit-table-form" action="/gestion-tables/edit/" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Modifier la Table</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Zone</label>
                        <select name="zone_id" id="edit-table-zone-id" class="form-select" required>
                            <?php foreach ($allZones as $zone): ?>
                            <option value="<?= $zone['id'] ?>"><?= htmlspecialchars($zone['nom']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Numéro de la table</label>
                        <input type="number" name="numero" id="edit-table-numero" class="form-control" required min="1">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Capacité</label>
                        <input type="number" name="capacite" id="edit-table-capacite" class="form-control" required min="1">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn me-auto" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Modifier Zone -->
<div class="modal modal-blur fade" id="modal-edit-zone" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="edit-zone-form" action="/zones/edit/" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Modifier la Zone</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit-zone-id">
                    <div class="mb-3">
                        <label class="form-label">Nom de la zone</label>
                        <input type="text" name="nom" id="edit-zone-nom" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Préfixe</label>
                        <input type="text" name="prefixe" id="edit-zone-prefixe" class="form-control" maxlength="5" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" id="edit-zone-description" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn me-auto" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Nouvelle Zone -->
<div class="modal modal-blur fade" id="modal-new-zone" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="/zones/create" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Nouvelle Zone</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nom de la zone</label>
                        <input type="text" name="nom" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Préfixe (pour les tables, ex: 'T' pour Terrasse)</label>
                        <input type="text" name="prefixe" class="form-control" maxlength="5" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn me-auto" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Créer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Nouvelle Table -->
<div class="modal modal-blur fade" id="modal-new-table" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nouvelle Table</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <ul class="nav nav-tabs" data-bs-toggle="tabs">
                    <li class="nav-item"><a href="#tab-single" class="nav-link active" data-bs-toggle="tab">Unique</a></li>
                    <li class="nav-item"><a href="#tab-batch" class="nav-link" data-bs-toggle="tab">En Série</a></li>
                </ul>
                <div class="tab-content">
                    <!-- Formulaire pour table unique -->
                    <div id="tab-single" class="tab-pane active show pt-3">
                        <form action="/gestion-tables/create" method="POST">
                             <div class="mb-3">
                                <label class="form-label">Zone</label>
                                <select name="zone_id" class="form-select" required>
                                    <?php foreach ($allZones as $zone): ?>
                                    <option value="<?= $zone['id'] ?>"><?= htmlspecialchars($zone['nom']) ?> (Préfixe: <?= htmlspecialchars($zone['prefixe'] ?? '') ?>)</option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Capacité</label>
                                <input type="number" name="capacite" class="form-control" value="4" required min="1">
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">Créer la table</button>
                            </div>
                        </form>
                    </div>
                    <!-- Formulaire pour tables en série -->
                    <div id="tab-batch" class="tab-pane pt-3">
                        <form action="/gestion-tables/batch-create" method="POST">
                            <div class="mb-3">
                                <label class="form-label">Zone</label>
                                <select name="zone_id" class="form-select" required>
                                    <?php foreach ($allZones as $zone): ?>
                                    <option value="<?= $zone['id'] ?>"><?= htmlspecialchars($zone['nom']) ?> (Préfixe: <?= htmlspecialchars($zone['prefixe'] ?? '') ?>)</option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="row">
                                <div class="col"><label class="form-label">Numéro de début</label><input type="number" name="numero_debut" class="form-control" placeholder="De" required min="1"></div>
                                <div class="col"><label class="form-label">Numéro de fin</label><input type="number" name="numero_fin" class="form-control" placeholder="À" required min="1"></div>
                            </div>
                            <div class="mb-3 mt-3">
                                <label class="form-label">Capacité par table</label>
                                <input type="number" name="capacite" class="form-control" value="4" required min="1">
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">Créer les tables</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var editZoneModal = document.getElementById('modal-edit-zone');
    editZoneModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var zoneId = button.getAttribute('data-zone-id');
        var zoneNom = button.getAttribute('data-zone-nom');
        var zonePrefixe = button.getAttribute('data-zone-prefixe');
        var zoneDescription = button.getAttribute('data-zone-description');

        var modalForm = editZoneModal.querySelector('form');
        modalForm.action = '/zones/edit/' + zoneId;

        editZoneModal.querySelector('#edit-zone-nom').value = zoneNom;
        editZoneModal.querySelector('#edit-zone-prefixe').value = zonePrefixe;
        editZoneModal.querySelector('#edit-zone-description').value = zoneDescription;
    });

    var editTableModal = document.getElementById('modal-edit-table');
    editTableModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var tableId = button.getAttribute('data-table-id');
        var tableNumero = button.getAttribute('data-table-numero');
        var tableCapacite = button.getAttribute('data-table-capacite');
        var tableZoneId = button.getAttribute('data-table-zone-id');

        var modalForm = editTableModal.querySelector('form');
        modalForm.action = '/gestion-tables/edit/' + tableId;

        editTableModal.querySelector('#edit-table-zone-id').value = tableZoneId;
        editTableModal.querySelector('#edit-table-numero').value = tableNumero;
        editTableModal.querySelector('#edit-table-capacite').value = tableCapacite;
    });
});
</script>
