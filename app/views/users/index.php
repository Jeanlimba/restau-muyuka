<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title text-primary">
                    Gestion des Serveurs et Agents
                </h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <a href="/users/create" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Nouvel Agent
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
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Fonction</th>
                            <th>Salaire</th>
                            <th>Date d'embauche</th>
                            <th class="w-1">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= htmlspecialchars($user['nom']) ?></td>
                            <td class="text-muted"><?= htmlspecialchars($user['email']) ?></td>
                            <td><?= htmlspecialchars(ucfirst($user['fonction'] ?? 'N/A')) ?></td>
                            <td><?= htmlspecialchars(number_format($user['salaire'] ?? 0, 0, ',', ' ')) ?> Fc</td>
                            <td><?= htmlspecialchars($user['date_embauche'] ? date('d/m/Y', strtotime($user['date_embauche'])) : 'N/A') ?></td>
                            <td>
                                <div class="btn-list flex-nowrap">
                                    <a href="/users/edit/<?= $user['user_id'] ?>" class="btn btn-sm">Modifier</a>
                                    <a href="/users/delete/<?= $user['user_id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Voulez-vous vraiment supprimer cet utilisateur ?')">Supprimer</a>
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
