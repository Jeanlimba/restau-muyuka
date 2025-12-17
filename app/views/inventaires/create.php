<div class="page-header">
    <div class="container-xl">
        <h2 class="page-title text-primary">
            Lancer un Nouvel Inventaire
        </h2>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <form action="/inventaires/store" method="POST" id="inventory-form">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label required">Date de l'inventaire</label>
                            <input type="date" name="date_inventaire" class="form-control" value="<?= date('Y-m-d') ?>" required>
                        </div>
                        <div class="col-md-8 mb-3">
                            <label class="form-label">Notes</label>
                            <input type="text" name="notes" class="form-control" placeholder="Ex: Inventaire mensuel du stock...">
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Saisie des quantités</h3>
                </div>
                <div class="table-responsive">
                    <table class="table table-vcenter card-table table-striped">
                        <thead>
                            <tr>
                                <th>Article</th>
                                <th class="text-center">Stock Théorique</th>
                                <th class="text-center" style="width: 15%;">Stock Physique Compté</th>
                                <th style="width: 25%;">Justification de l'écart</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($articles as $article): ?>
                            <tr>
                                <td>
                                    <div><?= htmlspecialchars($article['nom']) ?></div>
                                    <div class="text-muted small"><?= htmlspecialchars($article['unite_vente_nom']) ?></div>
                                </td>
                                <td class="text-center">
                                    <?= htmlspecialchars($article['stock_actuel'] ?? 0) ?>
                                    <input type="hidden" class="stock-theorique" value="<?= $article['stock_actuel'] ?? 0 ?>">
                                    <input type="hidden" name="articles[<?= $article['id'] ?>][stock_theorique]" value="<?= $article['stock_actuel'] ?? 0 ?>">
                                </td>
                                <td>
                                    <input type="number" step="0.01" name="articles[<?= $article['id'] ?>][stock_physique]" class="form-control form-control-sm text-center stock-physique">
                                </td>
                                <td>
                                    <input type="text" name="articles[<?= $article['id'] ?>][justification]" class="form-control form-control-sm justification" placeholder="Justification..." disabled>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Conclusion générale de l'inventaire</label>
                        <textarea name="conclusion" class="form-control" rows="3" placeholder="Ex: Casse de 2 bouteilles, erreur de saisie lors du précédent approvisionnement..."></textarea>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-between align-items-center">
                         <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="update_stock" id="update_stock" value="1" checked>
                            <label class="form-check-label" for="update_stock">
                                Mettre à jour le stock des articles avec les quantités physiques après validation.
                            </label>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Enregistrer l'Inventaire
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.stock-physique').forEach(input => {
        input.addEventListener('input', function() {
            const row = this.closest('tr');
            const theoriqueInput = row.querySelector('.stock-theorique');
            const justificationInput = row.querySelector('.justification');
            
            const stockPhysique = parseFloat(this.value);
            const stockTheorique = parseFloat(theoriqueInput.value);

            if (!isNaN(stockPhysique)) {
                const ecart = stockPhysique - stockTheorique;
                if (ecart !== 0) {
                    justificationInput.disabled = false;
                    justificationInput.focus();
                } else {
                    justificationInput.disabled = true;
                    justificationInput.value = '';
                }
            } else {
                justificationInput.disabled = true;
                justificationInput.value = '';
            }
        });
    });
});
</script>
