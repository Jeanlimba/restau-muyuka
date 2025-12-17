
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row">
            <div class="col">
                <h2 class="page-title text-primary">Nouvelle vente</h2>
            </div>
            <div class="col-auto">
                <a href="/ventes" class="btn btn-outline-secondary">
                    Retour à la liste des ventes
                </a>
                </div>
        </div>
        
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="row g-4">

            <!-- Colonne de gauche : Configuration de la vente et ajout d'articles -->
            <div class="col-lg-5">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Configuration de la Vente</h3>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="zone-select" class="form-label">1. Choisir une Zone</label>
                            <select id="zone-select" class="form-select"></select>
                        </div>
                        <div class="mb-3">
                            <label for="table-select" class="form-label">2. Choisir une Table</label>
                            <select id="table-select" class="form-select" disabled></select>
                        </div>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header">
                        <h3 class="card-title">3. Ajouter un Article</h3>
                    </div>
                    <div class="card-body" id="add-item-section" style="opacity: 0.5; pointer-events: none;">
                        <div class="mb-3">
                            <label for="article-search" class="form-label">Rechercher un article</label>
                            <input type="text" id="article-search" class="form-control" placeholder="Commencez à taper...">
                            <div id="article-suggestions" class="list-group mt-1" style="display: none;"></div>
                        </div>
                        <div class="mb-3">
                            <label for="article-select" class="form-label">Ou sélectionner dans la liste</label>
                            <select id="article-select" class="form-select"></select>
                        </div>
                        <button id="add-to-cart-btn" class="btn btn-primary w-100">Ajouter au panier</button>
                    </div>
                </div>
            </div>

            <!-- Colonne de droite : Panier -->
            <div class="col-lg-7">
                <div class="card" style="position: sticky; top: 80px;">
                    <div class="card-header">
                        <h3 class="card-title">Panier</h3>
                    </div>
                    <div id="cart-container" class="card-body" style="opacity: 0.5; pointer-events: none;">
                        <div id="cart-items-table" style="min-height: 30vh; max-height: 45vh; overflow-y: auto;">
                            <!-- Le tableau du panier sera injecté ici -->
                        </div>
                        <div class="mt-3">
                            <div class="d-flex justify-content-between">
                                <span>Sous-total HT</span>
                                <strong id="cart-subtotal">0.00 Fc</strong>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>TVA (20%)</span>
                                <strong id="cart-tax">0.00 Fc</strong>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between h2">
                                <span>Total TTC</span>
                                <strong id="cart-total">0.00 Fc</strong>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button id="validate-sale-btn" class="btn btn-primary w-100 btn-lg" disabled>
                            Valider la Vente
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal pour l'impression de la facture -->
<div class="modal modal-blur fade" id="invoice-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body text-center py-4">
                <h3>Vente validée !</h3>
                <div class="text-muted">La vente a été enregistrée avec succès.</div>
            </div>
            <div class="modal-footer">
                <div class="w-100">
                    <div class="row">
                        <div class="col">
                            <button class="btn w-100" data-bs-dismiss="modal">Nouvelle Vente</button>
                        </div>
                        <div class="col">
                            <button id="print-invoice-btn" class="btn btn-primary w-100">Imprimer Facture</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour l'impression de la facture -->
<div class="modal modal-blur fade" id="printable-invoice-modal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Facture</h5>
                
                <button type="button" class="btn-close ms-2" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <iframe id="invoice-iframe" style="width: 100%; height: 60vh; border: none;"></iframe>

                <div class="text-center">
                    <button type="button" id="do-print-btn" class="btn btn-primary ms-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2"></path><path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4"></path><path d="M7 13m0 2a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2v4a2 2 0 0 1 -2 2h-6a2 2 0 0 1 -2 -2z"></path></svg>
                        Imprimer
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script defer>
document.addEventListener('DOMContentLoaded', function () {
    // --- URLs de l'API ---
    const API_URLS = {
        zones: '/api/zones',
        tables: '/api/tables',
        articles: '/api/articles',
        prix: '/api/articles/prix',
        ventes: '/api/ventes'
    };

    // --- État de l'application ---
    let articles = [];
    let cart = {}; // { articleId: { data: {}, quantite: X, prix: Y }, ... }
    let selectedZoneId = null;
    let lastVenteId = null;

    // --- Éléments du DOM ---
    const zoneSelect = document.getElementById('zone-select');
    const tableSelect = document.getElementById('table-select');
    const articleSearch = document.getElementById('article-search');
    const articleSelect = document.getElementById('article-select');
    const addItemSection = document.getElementById('add-item-section');
    const addToCartBtn = document.getElementById('add-to-cart-btn');
    const cartContainer = document.getElementById('cart-container');
    const cartItemsTable = document.getElementById('cart-items-table');
    const cartSubtotalEl = document.getElementById('cart-subtotal');
    const cartTaxEl = document.getElementById('cart-tax');
    const cartTotalEl = document.getElementById('cart-total');
    const validateSaleBtn = document.getElementById('validate-sale-btn');
    const printInvoiceBtn = document.getElementById('print-invoice-btn');
    const doPrintBtn = document.getElementById('do-print-btn');
    
    const formatCurrency = (value) => {
        // On formatte comme un nombre puis on ajoute "Fc"
        return new Intl.NumberFormat('fr-FR', { minimumFractionDigits: 0 }).format(value) + ' Fc';
    };

    const renderCart = () => {
        cartItemsTable.innerHTML = '';
        let subtotal = 0;
        let tax = 0;

        if (Object.keys(cart).length === 0) {
            cartItemsTable.innerHTML = '<div class="text-center text-muted p-3">Le panier est vide.</div>';
            cartSubtotalEl.textContent = formatCurrency(0);
            cartTaxEl.textContent = formatCurrency(0);
            cartTotalEl.textContent = formatCurrency(0);
            validateSaleBtn.disabled = true;
            return;
        }

        const table = document.createElement('table');
        table.className = 'table table-vcenter';
        table.innerHTML = `
            <thead>
                <tr>
                    <th>Article</th>
                    <th style="width: 120px;">Qté</th>
                    <th style="width: 120px;">Prix U.</th>
                    <th>Total</th>
                    <th></th>
                </tr>
            </thead>
            <tbody></tbody>
        `;
        const tbody = table.querySelector('tbody');

        for (const articleId in cart) {
            const item = cart[articleId];
            const itemTotal = item.prix * item.quantite;
            subtotal += itemTotal;
            
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${item.data.nom}</td>
                <td>
                    <div class="input-group input-group-sm">
                        <button class="btn" type="button" data-action="decrease" data-id="${articleId}">-</button>
                        <input type="text" class="form-control text-center" value="${item.quantite}" readonly>
                        <button class="btn" type="button" data-action="increase" data-id="${articleId}">+</button>
                    </div>
                </td>
                <td>
                    <input type="number" class="form-control form-control-sm" value="${item.prix.toFixed(2)}" data-action="price-change" data-id="${articleId}">
                </td>
                <td>${formatCurrency(itemTotal)}</td>
                <td>
                    <button class="btn btn-sm btn-icon btn-ghost-danger" data-action="remove" data-id="${articleId}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-x" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                           <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                           <path d="M18 6l-12 12"></path>
                           <path d="M6 6l12 12"></path>
                        </svg>
                    </button>
                </td>
            `;
            tbody.appendChild(tr);
        }
        
        cartItemsTable.appendChild(table);

        tax = subtotal * 0.20;
        cartSubtotalEl.textContent = formatCurrency(subtotal);
        cartTaxEl.textContent = formatCurrency(tax);
        cartTotalEl.textContent = formatCurrency(subtotal + tax);
        validateSaleBtn.disabled = !tableSelect.value || Object.keys(cart).length === 0;
    };

    const updateCart = (articleId, action, value = null) => {
        if (!cart[articleId]) return;

        switch(action) {
            case 'increase':
                cart[articleId].quantite++;
                break;
            case 'decrease':
                cart[articleId].quantite--;
                if (cart[articleId].quantite <= 0) {
                    delete cart[articleId];
                }
                break;
            case 'remove':
                delete cart[articleId];
                break;
            case 'price-change':
                const newPrice = parseFloat(value);
                if (!isNaN(newPrice) && newPrice >= 0) {
                    cart[articleId].prix = newPrice;
                }
                break;
        }
        renderCart();
    };

    cartItemsTable.addEventListener('click', (e) => {
        const target = e.target.closest('button');
        if (target) {
            const { action, id } = target.dataset;
            if (action && id) {
                updateCart(id, action);
            }
        }
    });

    cartItemsTable.addEventListener('change', (e) => {
        if (e.target.dataset.action === 'price-change') {
            const { id } = e.target.dataset;
            updateCart(id, 'price-change', e.target.value);
        }
    });

    const init = async () => {
        const [zonesRes, articlesRes] = await Promise.all([
            fetch(API_URLS.zones),
            fetch(API_URLS.articles)
        ]);

        const zonesData = await zonesRes.json();
        if (zonesData.success) {
            zoneSelect.innerHTML = '<option value="">-- Choisir une zone --</option>';
            zonesData.zones.forEach(z => zoneSelect.innerHTML += `<option value="${z.id}">${z.nom}</option>`);
        }

        const articlesData = await articlesRes.json();
        if (articlesData.success) {
            articles = articlesData.articles;
            articleSelect.innerHTML = '<option value="">-- Choisir un article --</option>';
            articles.forEach(a => articleSelect.innerHTML += `<option value="${a.id}">${a.nom}</option>`);
        }
        renderCart(); // Initial render for empty cart
    };

    zoneSelect.addEventListener('change', async function() {
        selectedZoneId = this.value;
        tableSelect.innerHTML = '<option value="">-- Chargement... --</option>';
        cart = {}; // Vider le panier si on change de zone
        renderCart();

        if (!selectedZoneId) {
            tableSelect.disabled = true;
            addItemSection.style.opacity = 0.5;
            addItemSection.style.pointerEvents = 'none';
            tableSelect.innerHTML = '';
            return;
        }
        
        const tablesRes = await fetch(`${API_URLS.tables}?zone_id=${selectedZoneId}`);
        const tablesData = await tablesRes.json();
        if (tablesData.success) {
            tableSelect.innerHTML = '<option value="">-- Choisir une table --</option>';
            tablesData.tables.forEach(t => tableSelect.innerHTML += `<option value="${t.id}">${t.nom}</option>`);
            tableSelect.disabled = false;
        }
        
        addItemSection.style.opacity = 1;
        addItemSection.style.pointerEvents = 'auto';
    });

    const articleSuggestions = document.getElementById('article-suggestions');

    // ... (le reste du code existant jusqu'à la fonction init)

    // Logique pour la recherche d'articles en direct
    articleSearch.addEventListener('input', function() {
        const query = this.value.toLowerCase().trim();
        articleSuggestions.innerHTML = '';
        articleSuggestions.style.display = 'none';

        if (query.length < 2) {
            return;
        }

        const filteredArticles = articles.filter(a => a.nom.toLowerCase().includes(query));
        
        if (filteredArticles.length > 0) {
            articleSuggestions.style.display = 'block';
            filteredArticles.forEach(article => {
                const item = document.createElement('a');
                item.href = '#';
                item.className = 'list-group-item list-group-item-action';
                item.textContent = article.nom;
                item.dataset.id = article.id;
                
                item.addEventListener('click', async function(e) {
                    e.preventDefault();
                    articleSearch.value = '';
                    articleSuggestions.style.display = 'none';
                    
                    const articleId = this.dataset.id;
                    if (cart[articleId]) {
                        updateCart(articleId, 'increase');
                        return;
                    }

                    const prixRes = await fetch(`${API_URLS.prix}?article_id=${articleId}&zone_id=${selectedZoneId}`);
                    const prixData = await prixRes.json();
                    if (prixData.success) {
                        cart[articleId] = { data: article, quantite: 1, prix: prixData.prix };
                        renderCart();
                    } else {
                        alert(prixData.error || "Impossible de récupérer le prix.");
                    }
                });
                articleSuggestions.appendChild(item);
            });
        }
    });

    // Cacher les suggestions si on clique ailleurs
    document.addEventListener('click', function(e) {
        if (!addItemSection.contains(e.target)) {
            articleSuggestions.style.display = 'none';
        }
    });

    tableSelect.addEventListener('change', function() {
        const isReady = this.value && selectedZoneId;
        cartContainer.style.opacity = isReady ? 1 : 0.5;
        cartContainer.style.pointerEvents = isReady ? 'auto' : 'none';
        validateSaleBtn.disabled = !isReady || Object.keys(cart).length === 0;
    });
    
    addToCartBtn.addEventListener('click', async function() {
        const articleId = articleSelect.value;
        if (!articleId || !selectedZoneId) {
            alert("Veuillez sélectionner une zone, une table et un article.");
            return;
        }

        if (cart[articleId]) {
            updateCart(articleId, 'increase');
            return;
        }

        const prixRes = await fetch(`${API_URLS.prix}?article_id=${articleId}&zone_id=${selectedZoneId}`);
        const prixData = await prixRes.json();

        if (prixData.success) {
            const articleData = articles.find(a => a.id == articleId);
            cart[articleId] = { data: articleData, quantite: 1, prix: prixData.prix };
            renderCart();
            articleSelect.value = ''; // Reset select
        } else {
            alert(prixData.error || "Impossible de récupérer le prix.");
        }
    });

    validateSaleBtn.addEventListener('click', async function() {
        this.disabled = true;
        
        const saleData = {
            table_id: tableSelect.value,
            articles: Object.values(cart).map(item => ({
                article_id: item.data.id,
                quantite: item.quantite,
                prix_unitaire_ht: item.prix,
                tva: 20.00
            }))
        };
        
        try {
            const response = await fetch(API_URLS.ventes, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(saleData)
            });

            if (!response.ok) {
                // Si le statut HTTP n'est pas 2xx, on lit la réponse comme du texte
                const errorText = await response.text();
                throw new Error(`Erreur du serveur (${response.status}): ${errorText}`);
            }

            const result = await response.json();

            if (result.success) {
                lastVenteId = result.vente_id;
                const invoiceModalEl = document.getElementById('invoice-modal');
                const invoiceModal = bootstrap.Modal.getOrCreateInstance(invoiceModalEl);
                invoiceModal.show();
                // Reset state
                cart = {};
                renderCart();
                tableSelect.value = '';
                tableSelect.dispatchEvent(new Event('change'));
            } else {
                throw new Error(result.error || 'Une erreur inconnue est survenue.');
            }
        } catch (error) {
            console.error('Erreur détaillée:', error);
            alert('Erreur: ' + error.message);
        } finally {
            this.disabled = false;
        }
    });

    printInvoiceBtn.addEventListener('click', () => {
        if (lastVenteId) {
            const iframe = document.getElementById('invoice-iframe');
            iframe.src = `/vente/facture/${lastVenteId}`;

            const confirmationModal = bootstrap.Modal.getInstance(document.getElementById('invoice-modal'));
            confirmationModal.hide();

            const printableModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('printable-invoice-modal'));
            printableModal.show();
        }
    });

    doPrintBtn.addEventListener('click', () => {
        const iframe = document.getElementById('invoice-iframe');
        if (iframe.src) {
            iframe.contentWindow.print();
        }
    });

    init();
});
</script>