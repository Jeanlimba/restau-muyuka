
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col-auto">
                <a href="/post-paiement" class="btn btn-secondary d-none d-sm-inline-block">
                    <i class="fas fa-arrow-left me-2"></i>
                    Retour aux tables
                </a>
            </div>
            <div class="col">
                <h2 class="page-title text-primary">
                    <span class="text-muted fw-light">Table /</span> <?= htmlspecialchars($table['nom']) ?>
                </h2>
                <div class="text-muted mt-1">
                    Statut: 
                    <?php
                    $statusClass = $table['statut'] === 'libre' ? 'success' : 'danger';
                    ?>
                    <span class="badge bg-<?= $statusClass ?>-lt"><?= ucfirst($table['statut']) ?></span>
                </div>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <?php if ($vente): ?>
                    <form action="/close-vente/<?= $vente['id'] ?>" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir clôturer cette vente et libérer la table ?');">
                        <button type="submit" class="btn btn-success d-none d-sm-inline-block">
                            <i class="fas fa-check-circle me-2"></i>Clôturer & Payer
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="row g-4">
            <!-- Colonne de la Commande Actuelle -->
            <div class="col-lg-7">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-receipt me-2"></i>Détails de la Commande
                        </h3>
                    </div>
                    <div class="card-body">
                        <?php if ($vente && !empty($vente['commandes'])): ?>
                            <?php foreach ($vente['commandes'] as $index => $commande): ?>
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <h4 class="card-title">
                                            Commande #<?= $index + 1 ?> 
                                            <span class="text-muted small">(<?= date('d/m/Y H:i', strtotime($commande['date_commande'])) ?>)</span>
                                        </h4>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-vcenter table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Article</th>
                                                    <th class="text-center">Quantité</th>
                                                    <th class="text-end">Prix U.</th>
                                                    <th class="text-end">Total</th>
                                                    <th class="text-center">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($commande['lignes'] as $ligne): ?>
                                                    <tr>
                                                        <td><?= htmlspecialchars($ligne['article_nom']) ?></td>
                                                        <td class="text-center"><?= $ligne['quantite'] ?></td>
                                                        <td class="text-end"><?= number_format($ligne['prix_unitaire_ht'], 0, ',', ' ') ?> F</td>
                                                        <td class="text-end fw-bold"><?= number_format($ligne['prix_unitaire_ht'] * $ligne['quantite'], 0, ',', ' ') ?> F</td>
                                                        <td class="text-center">
                                                            <a href="/remove-ligne/<?= $ligne['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?');">
                                                                Supprimer
                                                            </a>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                            
                            <div class="d-flex justify-content-end mt-4 pt-3 border-top">
                                <div>
                                    <div class="text-end h1 text-primary mb-1">TOTAL: <?= number_format($vente['total'], 0, ',', ' ') ?> F</div>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="text-center text-muted p-5">
                                <i class="fas fa-info-circle fa-2x mb-3"></i>
                                <p>Aucune commande n'a encore été passée pour cette table.</p>
                                <p>Utilisez le formulaire à droite pour ajouter des articles.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Colonne pour Ajouter des Articles -->
            <div class="col-lg-5">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-plus-circle me-2"></i>Ajouter des Articles
                        </h3>
                    </div>
                    <div class="card-body" id="pos-app">
                        <div class="mb-3">
                            <div class="input-icon">
                                <span class="input-icon-addon"><i class="fas fa-search"></i></span>
                                <input type="text" id="article-search" class="form-control" placeholder="Chercher un article...">
                            </div>
                        </div>
                        <div id="search-results" class="list-group list-group-flush overflow-auto" style="max-height: 200px;"></div>
                        
                        <hr class="my-4">

                        <h4 class="mb-3"><i class="fas fa-shopping-basket me-2"></i>Panier</h4>
                        <div id="cart" class="mb-3">
                             <div class="text-center text-muted">Le panier est vide.</div>
                        </div>
                        <button id="add-to-order" class="btn btn-primary w-100 mt-3" disabled>
                           <i class="fas fa-plus me-2"></i> Ajouter à la commande
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .cursor-pointer { cursor: pointer; }
    .list-group-item-action:hover { background-color: #f5f7fb; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('article-search');
    const searchResults = document.getElementById('search-results');
    const cartDiv = document.getElementById('cart');
    const addToOrderBtn = document.getElementById('add-to-order');
    const currentZoneId = <?= json_encode($table['zone_id']) ?>;

    let cart = [];
    let articlesCache = []; // Cache for article search

    // Function to render the cart display
    function renderCart() {
        if (cart.length === 0) {
            cartDiv.innerHTML = `
                <div class="empty">
                    <div class="empty-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="6" cy="19" r="2" /><circle cx="17" cy="19" r="2" /><path d="M17 17h-11v-14h-2" /><path d="M6 5l14 1l-1 7h-13" /></svg>
                    </div>
                    <p class="empty-title">Le panier est vide</p>
                    <p class="empty-subtitle text-muted">
                        Utilisez la recherche ci-dessus pour ajouter des articles.
                    </p>
                </div>`;
            addToOrderBtn.disabled = true;
            return;
        }

        const cartTable = `
            <table class="table table-sm">
                <tbody>
                    ${cart.map((item, index) => `
                        <tr>
                            <td>${item.nom}</td>
                            <td style="width: 80px;">
                                <input type="number" value="${item.quantite}" min="1" class="form-control form-control-sm text-center" data-cart-index="${index}">
                            </td>
                            <td class="text-end fw-bold" style="width: 90px;">${(item.prix_unitaire_ht * item.quantite).toLocaleString('fr-FR')} F</td>
                            <td class="text-center" style="width: 40px;">
                                <a href="#" class="text-danger" data-remove-index="${index}" title="Supprimer">
                                    <i class="fas fa-times-circle"></i>
                                </a>
                            </td>
                        </tr>
                    `).join('')}
                </tbody>
            </table>`;
        cartDiv.innerHTML = cartTable;
        addToOrderBtn.disabled = false;
    }

    // Function to add an article to the cart with the correct price
    function addToCart(article, price) {
        const existingItem = cart.find(item => item.article_id === article.id);
        if (existingItem) {
            existingItem.quantite++;
        } else {
            cart.push({
                article_id: article.id,
                nom: article.nom,
                prix_unitaire_ht: price, // Use the fetched, correct price
                quantite: 1
            });
        }
        renderCart();
    }
    
    // Debounce function to limit API calls
    function debounce(func, delay) {
        let timeout;
        return function(...args) {
            const context = this;
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(context, args), delay);
        };
    }

    // Event handler for the search input
    const handleSearch = debounce(async function (query) {
        if (query.length < 2) {
            searchResults.innerHTML = '';
            return;
        }

        // Use cache if available
        if (articlesCache.length === 0) {
            const response = await fetch('/api/articles');
            const data = await response.json();
            if(data.success) {
                articlesCache = data.articles;
            }
        }
        
        const filtered = articlesCache.filter(a => a.nom.toLowerCase().includes(query));
        searchResults.innerHTML = '';

        if (filtered.length === 0) {
            searchResults.innerHTML = '<div class="text-muted text-center p-3">Aucun article trouvé.</div>';
            return;
        }

        filtered.slice(0, 10).forEach(article => { // Limit results to 10
            const itemEl = document.createElement('a');
            itemEl.href = '#';
            itemEl.className = 'list-group-item list-group-item-action';
            itemEl.dataset.articleId = article.id;
            itemEl.innerHTML = `<strong>${article.nom}</strong>`; // Price is removed as it can be misleading
            
            itemEl.addEventListener('click', async function(e) {
                e.preventDefault();
                const selectedArticle = articlesCache.find(a => a.id == article.id);
                
                // Fetch the correct price for the zone
                try {
                    const priceResponse = await fetch(`/api/articles/prix?article_id=${article.id}&zone_id=${currentZoneId}`);
                    const priceData = await priceResponse.json();

                    if(priceData.success) {
                        addToCart(selectedArticle, priceData.prix);
                        searchInput.value = '';
                        searchResults.innerHTML = '';
                    } else {
                        alert(`Erreur: ${priceData.error}`);
                    }
                } catch (error) {
                    alert('Impossible de récupérer le prix de l\'article.');
                }
            });
            searchResults.appendChild(itemEl);
        });
    }, 250);

    searchInput.addEventListener('input', (e) => handleSearch(e.target.value.toLowerCase().trim()));

    // Event handlers for cart interactions
    cartDiv.addEventListener('input', function(e) {
        if (e.target.matches('input[type="number"]')) {
            const index = e.target.dataset.cartIndex;
            const newQuantity = parseInt(e.target.value, 10);
            if (newQuantity > 0) {
                cart[index].quantite = newQuantity;
            } else {
                cart.splice(index, 1);
            }
            renderCart();
        }
    });

    cartDiv.addEventListener('click', function(e) {
        const removeLink = e.target.closest('a[data-remove-index]');
        if (removeLink) {
            e.preventDefault();
            const index = removeLink.dataset.removeIndex;
            cart.splice(index, 1);
            renderCart();
        }
    });

    // Event handler for adding items to the order
    addToOrderBtn.addEventListener('click', function () {
        if (cart.length === 0) return;

        fetch('/add-order/<?= $table['id'] ?>', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ articles: cart })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            } else {
                alert('Erreur: Impossible d\'ajouter les articles.');
            }
        });
    });

    renderCart(); // Initial render
});
</script>
