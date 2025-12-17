<div class="page-header d-print-none">
    <div class="container-xl">
        <h2 class="page-title text-primary">Gestion des Unités de Mesure</h2>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="row g-4">
            <!-- Unités de Vente -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header bg-secondary text-white">
                        <h3 class="card-title">Unités de Vente</h3>
                        <div class="card-actions">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-new-unit" data-unit-type="vente">
                                + Ajouter
                            </button>
                        </div>
                    </div>
                    <div class="list-group list-group-flush">
                        <?php foreach ($unites_vente as $unite): ?>
                        <div class="list-group-item">
                            <div class="row align-items-center">
                                <div class="col">
                                    <strong><?= htmlspecialchars($unite['nom']) ?></strong>
                                    <div class="text-muted"><?= htmlspecialchars($unite['symbole']) ?></div>
                                </div>
                                <div class="col-auto">
                                    <button class="btn btn-sm btn-outline-primary" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#modal-edit-unit"
                                            data-id="<?= $unite['id'] ?>"
                                            data-nom="<?= htmlspecialchars($unite['nom']) ?>"
                                            data-symbole="<?= htmlspecialchars($unite['symbole']) ?>"
                                            data-type="vente">
                                        Modifier
                                    </button>
                                    <a href="/gestion-unites/delete/<?= $unite['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Voulez-vous vraiment supprimer cette unité ?');">Supprimer</a>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Unités d'Achat -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header bg-secondary text-white">
                        <h3 class="card-title">Unités d'Achat</h3>
                        <div class="card-actions">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-new-unit" data-unit-type="achat">
                                + Ajouter
                            </button>
                        </div>
                    </div>
                    <div class="list-group list-group-flush">
                        <?php foreach ($unites_achat as $unite): ?>
                        <div class="list-group-item">
                            <div class="row align-items-center">
                                <div class="col">
                                    <strong><?= htmlspecialchars($unite['nom']) ?></strong>
                                    <div class="text-muted"><?= htmlspecialchars($unite['symbole']) ?></div>
                                </div>
                                <div class="col-auto">
                                     <button class="btn btn-sm btn-outline-primary" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#modal-edit-unit"
                                            data-id="<?= $unite['id'] ?>"
                                            data-nom="<?= htmlspecialchars($unite['nom']) ?>"
                                            data-symbole="<?= htmlspecialchars($unite['symbole']) ?>"
                                            data-type="achat">
                                        Modifier
                                    </button>
                                    <a href="/gestion-unites/delete/<?= $unite['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Voulez-vous vraiment supprimer cette unité ?');">Supprimer</a>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Nouvelle Unité -->
<div class="modal modal-blur fade" id="modal-new-unit" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="/gestion-unites/create" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-unit-title">Nouvelle Unité</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="type" id="unit-type-input">
                    <div class="mb-3">
                        <label class="form-label">Nom</label>
                        <input type="text" name="nom" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Symbole</label>
                        <input type="text" name="symbole" class="form-control" required>
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

<!-- Modal Modifier Unité -->
<div class="modal modal-blur fade" id="modal-edit-unit" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="edit-unit-form" action="" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Modifier l'Unité</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="type" id="edit-unit-type-input">
                    <div class="mb-3">
                        <label class="form-label">Nom</label>
                        <input type="text" name="nom" id="edit-unit-nom" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Symbole</label>
                        <input type="text" name="symbole" id="edit-unit-symbole" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn me-auto" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Mettre à jour</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Script pour la modale de création
    var newUnitModal = document.getElementById('modal-new-unit');
    newUnitModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var unitType = button.getAttribute('data-unit-type');
        
        var modalTitle = newUnitModal.querySelector('#modal-unit-title');
        var typeInput = newUnitModal.querySelector('#unit-type-input');

        if (unitType === 'vente') {
            modalTitle.textContent = 'Nouvelle Unité de Vente';
            typeInput.value = 'vente';
        } else {
            modalTitle.textContent = 'Nouvelle Unité d\'Achat';
            typeInput.value = 'achat';
        }
    });

    // Script pour la modale de modification
    var editUnitModal = document.getElementById('modal-edit-unit');
    editUnitModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        
        // Extraire les données du bouton
        var id = button.getAttribute('data-id');
        var nom = button.getAttribute('data-nom');
        var symbole = button.getAttribute('data-symbole');
        var type = button.getAttribute('data-type');

        // Récupérer les éléments de la modale
        var form = editUnitModal.querySelector('#edit-unit-form');
        var nomInput = editUnitModal.querySelector('#edit-unit-nom');
        var symboleInput = editUnitModal.querySelector('#edit-unit-symbole');
        var typeInput = editUnitModal.querySelector('#edit-unit-type-input');

        // Mettre à jour l'action du formulaire et les valeurs des champs
        form.action = '/gestion-unites/update/' + id;
        nomInput.value = nom;
        symboleInput.value = symbole;
        typeInput.value = type;
    });
});
</script>
