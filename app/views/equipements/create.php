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
                    Ajouter un Nouvel Équipement
                </h2>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <div class="card-body">
                <form action="/equipements/store" method="POST">
                    <div class="mb-3">
                        <label class="form-label required">Nom de l'équipement</label>
                        <input type="text" name="nom" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>
                    <fieldset class="form-fieldset">
                        <legend>Quantités par état</legend>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Quantité en service</label>
                                <input type="number" name="quantite_en_service" class="form-control" value="0">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Quantité en réparation</label>
                                <input type="number" name="quantite_en_reparation" class="form-control" value="0">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Quantité hors service</label>
                                <input type="number" name="quantite_hors_service" class="form-control" value="0">
                            </div>
                        </div>
                    </fieldset>
                     <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Date d'achat</label>
                            <input type="date" name="date_achat" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Valeur (Fc)</label>
                            <input type="number" name="valeur" class="form-control" placeholder="0">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Fournisseur</label>
                        <input type="text" name="fournisseur" class="form-control">
                    </div>
                    <div class="card-footer text-end">
                        <button type="submit" class="btn btn-primary">Ajouter l'équipement</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
