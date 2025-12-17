<div class="page-header">
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
                    État des Lieux des Équipements
                </h2>
                <div class="text-muted mt-1">
                    Mettez à jour le statut de chaque équipement puis sauvegardez les changements.
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <form action="/equipements/etat-lieu" method="POST">
            <div class="card">
                <div class="table-responsive">
                    <table class="table table-vcenter card-table table-striped">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th class="text-center" style="width: 15%;">En service</th>
                                <th class="text-center" style="width: 15%;">En réparation</th>
                                <th class="text-center" style="width: 15%;">Hors service</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($equipements as $equipement): ?>
                            <tr>
                                <td>
                                    <div><?= htmlspecialchars($equipement['nom']) ?></div>
                                </td>
                                <td>
                                    <input type="hidden" name="equipements[<?= $equipement['id'] ?>][nom]" value="<?= htmlspecialchars($equipement['nom']) ?>">
                                    <input type="number" name="equipements[<?= $equipement['id'] ?>][qte_service]" class="form-control form-control-sm text-center" value="<?= htmlspecialchars($equipement['quantite_en_service']) ?>">
                                </td>
                                <td>
                                    <input type="number" name="equipements[<?= $equipement['id'] ?>][qte_reparation]" class="form-control form-control-sm text-center" value="<?= htmlspecialchars($equipement['quantite_en_reparation']) ?>">
                                </td>
                                <td>
                                    <input type="number" name="equipements[<?= $equipement['id'] ?>][qte_hors_service]" class="form-control form-control-sm text-center" value="<?= htmlspecialchars($equipement['quantite_hors_service']) ?>">
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Enregistrer les Changements
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
