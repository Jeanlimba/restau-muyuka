<div class="page-header d-print-none mt-3">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title text-primary">Gestion des Articles</h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <a href="/articles/create" class="btn btn-primary btn-square">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                    Nouvel Article
                </a>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="card mb-3">
            <div class="card-body">
                <h3 class="card-title">Filtrer les articles</h3>
                <form action="/articles" method="GET" class="row g-2">
                    <div class="col-md-4">
                        <input type="text" name="nom" class="form-control" placeholder="Nom de l'article..." value="<?= htmlspecialchars($filters['nom'] ?? '') ?>">
                    </div>
                    <div class="col-md-3">
                        <select name="categorie" class="form-select">
                            <option value="">-- Toutes les catégories --</option>
                            <?php foreach ($categories as $categorie): ?>
                                <option value="<?= htmlspecialchars($categorie) ?>" <?= ($filters['categorie'] ?? '') == $categorie ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($categorie) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                         <select name="stock_situation" class="form-select">
                            <option value="">-- Toutes situations --</option>
                            <option value="en_stock" <?= ($filters['stock_situation'] ?? '') == 'en_stock' ? 'selected' : '' ?>>En stock</option>
                            <option value="stock_bas" <?= ($filters['stock_situation'] ?? '') == 'stock_bas' ? 'selected' : '' ?>>Stock bas</option>
                            <option value="en_rupture" <?= ($filters['stock_situation'] ?? '') == 'en_rupture' ? 'selected' : '' ?>>En rupture</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex">
                        <button type="submit" class="btn btn-primary w-100 me-2">Filtrer</button>
                        <a href="/articles" class="btn btn-outline-secondary">Reset</a>
                        <a href="/articles/export/excel?<?= http_build_query($filters) ?>" class="btn btn-success ms-2">
                           <i class="fas fa-file-excel me-2"></i>Exporter
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="table-responsive">
                <table class="table table-vcenter card-table table-striped">
                    <thead>
                        <tr>
                            <th>Article</th>
                            <th>Catégorie</th>
                            <th>Unité Vente</th>
                            <th>Unité Achat</th>
                            <th>Facteur Conv.</th>
                            <th>Prix Standard</th>
                            <th>Stock Actuel</th>
                            <th class="w-1"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($articles as $article): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($article['nom']) ?></strong></td>
                            <td>
                                <?php 
                                    $category_class = 'bg-secondary';
                                    switch (mb_strtolower($article['categorie'])) {
                                        case 'boissons': $category_class = 'bg-primary'; break;
                                        case 'plats': $category_class = 'bg-warning'; break;
                                        case 'desserts': $category_class = 'bg-info'; break;
                                        default: $category_class = 'bg-secondary'; break;
                                    }
                                ?>
                                <span class="badge <?= $category_class ?>"><?= ucfirst($article['categorie']) ?></span>
                            </td>
                            <td data-bs-toggle="tooltip" title="<?= htmlspecialchars($article['unite_vente_nom'] ?? '') ?>">
                                <?= htmlspecialchars($article['unite_vente_symbole'] ?? '-') ?>
                            </td>
                            <td data-bs-toggle="tooltip" title="<?= htmlspecialchars($article['unite_achat_nom'] ?? '') ?>">
                                <?= htmlspecialchars($article['unite_achat_symbole'] ?? '-') ?>
                            </td>
                            <td><?= htmlspecialchars($article['conversion_factor']) ?></td>
                            <td>
                                <?php if ($article['type_tarification'] === 'standard'): ?>
                                    <span class="badge bg-info">Standard</span>
                                <?php else: ?>
                                    <span class="badge bg-info-lt">Prix par zone</span>
                                <?php endif; ?>
                            </td>
                            <td data-bs-toggle="tooltip" title="Stock en unités de vente" class="text-center">
                                <span class="badge <?= ($article['stock_actuel'] <= 10) ? 'bg-danger-lt' : 'bg-info' ?>">
                                    <?= htmlspecialchars($article['stock_actuel'] ?? 0) ?>
                                </span>
                            </td>
                            <td>
                                <div class="btn-list flex-nowrap">
                                    <a href="/articles/edit/<?= $article['id'] ?>" class="btn btn-icon btn-sm btn-outline-primary" title="Modifier">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg>
                                    </a>
                                    <a href="/articles/delete/<?= $article['id'] ?>" class="btn btn-icon btn-sm btn-outline-danger" title="Supprimer" onclick="return confirm('Êtes-vous sûr ?')">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="4" y1="7" x2="20" y2="7" /><line x1="10" y1="11" x2="10" y2="17" /><line x1="14" y1="11" x2="14" y2="17" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>