<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">➕ Nouvel Article</h3>
        </div>
        <div class="card-body">
            <form method="POST">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Nom de l'article</label>
                            <input type="text" name="nom" class="form-control" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Catégorie</label>
                            <select name="categorie" class="form-select" required>
                                <option value="boisson">Boisson</option>
                                <option value="nourriture">Nourriture</option>
                                <option value="dessert">Dessert</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Unité de vente</label>
                            <div class="input-group">
                                <select name="unite_mesure_id" class="form-select" required>
                                    <option value="">Choisir une unité</option>
                                    <?php foreach ($unites_vente as $unite): ?>
                                        <option value="<?= $unite['id'] ?>">
                                            <?= htmlspecialchars($unite['nom']) ?> (<?= htmlspecialchars($unite['symbole']) ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalUnite">
                                    +
                                </button>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Unité d'achat (optionnel)</label>
                            <select name="purchase_unite_mesure_id" class="form-select">
                                <option value="">Aucune</option>
                                <?php foreach ($unites_achat as $unite): ?>
                                    <option value="<?= $unite['id'] ?>">
                                        <?= htmlspecialchars($unite['nom']) ?> (<?= htmlspecialchars($unite['symbole']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Facteur de conversion</label>
                            <input type="number" name="conversion_factor" class="form-control" step="0.01" min="1" value="1" placeholder="Nombre d'unités de vente par unité d'achat">
                        </div>
                    </div>
                    
                    <!-- Dans la section du formulaire concernant la tarification -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Type de tarification</label>
                            <select name="type_tarification" class="form-select" id="typeTarification" required>
                                <option value="standard">Standard (même prix partout)</option>
                                <option value="varie">Varié (prix par zone)</option>
                            </select>
                        </div>+
                        
                        <div id="prixStandard" class="mb-3 ">
                            <label class="form-label">Prix Standard</label>
                            <input type="number" name="prix" class="form-control" step="0.01" min="0" placeholder="0.00 Fc">
                        </div>                        
                        <div id="prixParZone" style="display: none;">
                            <h6>Prix par zone</h6>
                            <?php foreach ($zones as $zone): ?>
                                <div class="mb-3">
                                    <label class="form-label">Prix <?= htmlspecialchars($zone['nom']) ?></label>
                                    <input type="number" name="tarifs[<?= $zone['id'] ?>]" class="form-control" step="0.01" min="0" placeholder="0.00 Fc">
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>                
                </div>
                
                <div class="text-end">
                    <a href="/articles" class="btn btn-secondary">Annuler</a>
                    <button type="submit" class="btn btn-primary">Créer l'article</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal pour créer une nouvelle unité de mesure -->
<div class="modal fade" id="modalUnite" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">➕ Nouvelle Unité de Mesure</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formUnite">
                    <div class="mb-3">
                        <label class="form-label">Nom de l'unité</label>
                        <input type="text" name="nom" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Symbole</label>
                        <input type="text" name="symbole" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" onclick="creerUnite()">Créer l'unité</button>
            </div>
        </div>
    </div>
</div>

<script>
// Gestion du type de tarification
document.getElementById('typeTarification').addEventListener('change', function() {
    const type = this.value;
    const prixStandard = document.getElementById('prixStandard');
    const prixParZone = document.getElementById('prixParZone');
    
    if (type === 'standard') {
        prixStandard.style.display = 'block';
        prixParZone.style.display = 'none';
        // Désactiver les champs de prix par zone
        prixParZone.querySelectorAll('input').forEach(input => input.disabled = true);
        prixStandard.querySelector('input').disabled = false;
    } else {
        prixStandard.style.display = 'none';
        prixParZone.style.display = 'block';
        // Désactiver le champ prix standard
        prixStandard.querySelector('input').disabled = true;
        prixParZone.querySelectorAll('input').forEach(input => input.disabled = false);
    }
});

// Initialiser l'affichage
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('typeTarification').dispatchEvent(new Event('change'));
});

// Fonction pour créer une nouvelle unité de mesure
function creerUnite() {
    const form = document.getElementById('formUnite');
    const formData = new FormData(form);
    
    fetch('/api/unites-mesure', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Ajouter la nouvelle unité au select
            const select = document.querySelector('[name="unite_mesure_id"]');
            const option = new Option(data.unite.nom + ' (' + data.unite.symbole + ')', data.unite.id);
            select.add(option);
            select.value = data.unite.id;
            
            // Fermer le modal et réinitialiser le formulaire
            bootstrap.Modal.getInstance(document.getElementById('modalUnite')).hide();
            form.reset();
        } else {
            alert('Erreur lors de la création de l\'unité: ' + data.error);
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Erreur lors de la création de l\'unité');
    });
}
</script>
