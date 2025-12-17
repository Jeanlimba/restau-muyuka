<div class="page-header d-print-none">
    <div class="container-xl">
        <h2 class="page-title text-primary">
            Nouvel Approvisionnement
        </h2>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <form action="/approvisionnements/create" method="POST">
            <div class="card mb-3">
                <div class="card-header"><h3 class="card-title">Informations Générales</h3></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="date_approvisionnement">Date du jour</label>
                            <input type="date" name="date_approvisionnement" id="date_approvisionnement" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="numero_bon">Numéro du Bon d'Entrée</label>
                            <input type="text" name="numero_bon" id="numero_bon" class="form-control" placeholder="Ex: BE-00123">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="fournisseur">Fournisseur</label>
                        <input type="text" name="fournisseur" id="fournisseur" class="form-control" placeholder="Nom du fournisseur (optionnel)">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="observation">Observation</label>
                        <textarea name="observation" id="observation" class="form-control" rows="2" placeholder="Ajouter une note... (optionnel)"></textarea>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><h3 class="card-title">Articles</h3></div>
                <div class="card-body">
                    <div id="article-lines-container">
                        <!-- Les lignes d'articles seront ajoutées ici par JS -->
                    </div>
                    <button type="button" id="add-article-btn" class="btn btn-outline-primary mt-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                        Ajouter un article
                    </button>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary w-100">Enregistrer l'Approvisionnement</button>
                <a href="/approvisionnements" class="btn btn-link w-100 mt-2">Annuler</a>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('date_approvisionnement').value = today;

    const container = document.getElementById('article-lines-container');
    const addButton = document.getElementById('add-article-btn');
    const articlesData = <?= json_encode($articles) ?>;
    let lineIndex = 0;

    const addArticleLine = () => {
        const lineId = `line-${lineIndex}`;
        const newLigne = document.createElement('div');
        newLigne.classList.add('row', 'mb-3', 'align-items-end', 'g-2');
        newLigne.id = lineId;

        const articleSelectOptions = articlesData.map(a => `<option value="${a.id}">${a.nom}</option>`).join('');

        newLigne.innerHTML = `
            <div class="col-md-4">
                <label class="form-label">Article</label>
                <select name="article_id[]" class="form-select article-select" required>
                    <option value="">-- Sélectionner --</option>
                    ${articleSelectOptions}
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Quantité</label>
                <input type="number" name="quantite[]" class="form-control" required min="1">
            </div>
            <div class="col-md-3">
                <label class="form-label">Unité</label>
                <select name="unit_type[]" class="form-select unit-type-select">
                    <!-- Options chargées par JS -->
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Prix d'achat U.</label>
                <input type="number" name="prix_achat[]" class="form-control" required min="0">
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-danger btn-icon" onclick="document.getElementById('${lineId}').remove()">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M18 6l-12 12" /><path d="M6 6l12 12" /></svg>
                </button>
            </div>
        `;
        container.appendChild(newLigne);
        lineIndex++;
    };

    container.addEventListener('change', function(e) {
        if (e.target && e.target.classList.contains('article-select')) {
            const articleId = e.target.value;
            const article = articlesData.find(a => a.id == articleId);
            const unitSelect = e.target.closest('.row').querySelector('.unit-type-select');
            
            unitSelect.innerHTML = '';
            
            if (article) {
                // Ajoute l'unité de vente par défaut
                const saleUnitName = article.unite_vente_nom || 'Unité';
                unitSelect.innerHTML += `<option value="vente">${saleUnitName}</option>`;
                
                // Ajoute l'unité d'achat si elle existe et est différente
                if (article.purchase_unite_mesure_id && article.purchase_unite_mesure_id !== article.unite_mesure_id) {
                    const purchaseUnitName = article.unite_achat_nom || 'Unité Achat';
                    unitSelect.innerHTML += `<option value="achat">${purchaseUnitName}</option>`;
                }
            }
        }
    });

    addButton.addEventListener('click', addArticleLine);
    addArticleLine(); // Ajouter une première ligne
});
</script>
