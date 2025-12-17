<div class="page-header d-print-none">
    <div class="container-xl">
        <h2 class="page-title text-primary">
            Modifier la Vente #<?= htmlspecialchars($vente['id']) ?>
        </h2>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <form action="/vente/edit/<?= $vente['id'] ?>" method="POST">
            <div class="card">
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label" for="statut">Statut de la vente</label>
                        <select name="statut" id="statut" class="form-select">
                            <option value="payee" <?= $vente['statut'] === 'payee' ? 'selected' : '' ?>>Payée</option>
                            <option value="annulee" <?= $vente['statut'] === 'annulee' ? 'selected' : '' ?>>Annulée</option>
                        </select>
                    </div>
                    <div class="alert alert-warning">
                        <strong>Attention:</strong> Changer le statut à "annulée" n'ajuste pas automatiquement le stock. Pour cela, vous devez supprimer la vente.
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary w-100">Enregistrer</button>
                <a href="/ventes" class="btn btn-link w-100 mt-2">Annuler</a>
            </div>
        </form>
    </div>
</div>
