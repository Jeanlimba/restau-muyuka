<div class="page-header">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col-auto">
                <a href="/equipements" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>
                    Retour à la liste
                </a>
            </div>
            <div class="col">
                <h2 class="page-title text-primary">
                    Modifier: <?= htmlspecialchars($equipement['nom']) ?>
                </h2>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <div class="card-body">
                <form action="/equipements/update/<?= $equipement['id'] ?>" method="POST">
                    <div class="mb-3">
                        <label class="form-label required">Nom de l'équipement</label>
                        <input type="text" name="nom" class="form-control" value="<?= htmlspecialchars($equipement['nom']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3"><?= htmlspecialchars($equipement['description']) ?></textarea>
                    </div>
                    <fieldset class="form-fieldset">
                        <legend>Quantités par état</legend>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Quantité en service</label>
                                <input type="number" name="quantite_en_service" class="form-control" value="<?= htmlspecialchars($equipement['quantite_en_service']) ?>">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Quantité en réparation</label>
                                <input type="number" name="quantite_en_reparation" class="form-control" value="<?= htmlspecialchars($equipement['quantite_en_reparation']) ?>">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Quantité hors service</label>
                                <input type="number" name="quantite_hors_service" class="form-control" value="<?= htmlspecialchars($equipement['quantite_hors_service']) ?>">
                            </div>
                        </div>
                    </fieldset>
                     <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Date d'achat</label>
                            <input type="date" name="date_achat" class="form-control" value="<?= htmlspecialchars($equipement['date_achat']) ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Valeur (Fc)</label>
                            <input type="number" name="valeur" class="form-control" value="<?= htmlspecialchars($equipement['valeur']) ?>">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Fournisseur</label>
                        <input type="text" name="fournisseur" class="form-control" value="<?= htmlspecialchars($equipement['fournisseur']) ?>">
                    </div>
                    <div class="card-footer text-end">
                        <button type="submit" class="btn btn-primary">Mettre à jour</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
