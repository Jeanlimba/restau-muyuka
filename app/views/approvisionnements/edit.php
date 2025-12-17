<div class="page-header d-print-none">
    <div class="container-xl">
        <h2 class="page-title text-primary">
            Modifier l'Approvisionnement
        </h2>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <form action="/approvisionnement/edit/<?= $appro['id'] ?>" method="POST">
            <div class="card">
                <div class="card-header"><h3 class="card-title">Informations Générales</h3></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="date_approvisionnement">Date</label>
                            <input type="date" name="date_approvisionnement" id="date_approvisionnement" class="form-control" value="<?= htmlspecialchars($appro['date_approvisionnement']) ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="numero_bon">Numéro du Bon</label>
                            <input type="text" name="numero_bon" id="numero_bon" class="form-control" value="<?= htmlspecialchars($appro['numero_bon']) ?>">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="fournisseur">Fournisseur</label>
                        <input type="text" name="fournisseur" id="fournisseur" class="form-control" value="<?= htmlspecialchars($appro['fournisseur']) ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="observation">Observation</label>
                        <textarea name="observation" id="observation" class="form-control" rows="2"><?= htmlspecialchars($appro['observation']) ?></textarea>
                    </div>
                    <div class="alert alert-info">
                        <strong>Note:</strong> La modification des articles individuels (quantité, prix) n'est pas autorisée pour maintenir l'intégrité du stock. Pour corriger une erreur, veuillez supprimer cet approvisionnement (ce qui ajustera le stock) et en créer un nouveau.
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary w-100">Enregistrer les Modifications</button>
                <a href="/approvisionnements" class="btn btn-link w-100 mt-2">Annuler</a>
            </div>
        </form>
    </div>
</div>
