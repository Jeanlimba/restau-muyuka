<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title text-primary">
                    Historique des Approvisionnements
                </h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <a href="/approvisionnements/create" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                    Nouvel Approvisionnement
                </a>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <div class="table-responsive">
                <table class="table table-vcenter card-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>N° Bon</th>
                            <th>Fournisseur</th>
                            <th>Nbr Articles</th>
                            <th>Coût Total</th>
                            <th>Opérateur</th>
                            <th class="w-1"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($approvisionnements)): ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted">Aucun approvisionnement enregistré.</td>
                            </tr>
                        <?php endif; ?>
                        <?php foreach ($approvisionnements as $item): ?>
                        <tr>
                            <td class="text-muted"><?= htmlspecialchars(date('d/m/Y', strtotime($item['date_approvisionnement']))) ?></td>
                            <td><?= htmlspecialchars($item['numero_bon']) ?></td>
                            <td><?= htmlspecialchars($item['fournisseur']) ?></td>
                            <td><?= htmlspecialchars($item['count_articles']) ?></td>
                            <td><?= htmlspecialchars(number_format($item['total_achat'], 0, ',', ' ')) ?> Fc</td>
                            <td><?= htmlspecialchars($item['user_nom']) ?></td>
                            <td>
                                <div class="btn-list flex-nowrap">
                                    <a href="/approvisionnement/<?= $item['id'] ?>" class="btn btn-sm">Voir</a>
                                    <a href="/approvisionnement/edit/<?= $item['id'] ?>" class="btn btn-sm btn-outline-secondary">Modifier</a>
                                    <a href="/approvisionnement/delete/<?= $item['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet approvisionnement ? Cette action réduira le stock des articles concernés.')">Supprimer</a>
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
