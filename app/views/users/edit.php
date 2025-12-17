<div class="page-header">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col-auto">
                <a href="/users" class="btn btn-secondary d-none d-sm-inline-block">
                    <i class="fas fa-arrow-left me-2"></i>
                    Retour à la liste
                </a>
            </div>
            <div class="col">
                <h2 class="page-title text-primary">
                    Modifier l'Agent: <?= htmlspecialchars($user['nom']) ?>
                </h2>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <div class="card-body">
                <form action="/users/update/<?= $user['id'] ?>" method="POST">
                    <div class="mb-3">
                        <label class="form-label required">Nom complet</label>
                        <input type="text" name="nom" class="form-control" value="<?= htmlspecialchars($user['nom']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label required">Adresse email</label>
                        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label required">Fonction</label>
                        <div class="input-group">
                            <select name="fonction_id" class="form-select" required>
                                 <?php foreach ($fonctions as $fonction): ?>
                                    <option value="<?= $fonction['id'] ?>" <?= ($user['fonction_id'] == $fonction['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($fonction['nom']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <button class="btn" type="button" data-bs-toggle="modal" data-bs-target="#modal-add-fonction" title="Ajouter une nouvelle fonction">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Salaire de base</label>
                                <input type="number" name="salaire" class="form-control" value="<?= htmlspecialchars($user['salaire']) ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                             <div class="mb-3">
                                <label class="form-label">Date d'embauche</label>
                                <input type="date" name="date_embauche" class="form-control" value="<?= htmlspecialchars($user['date_embauche']) ?>">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nouveau mot de passe</label>
                        <small class="form-hint">Laissez vide pour ne pas changer</small>
                        <input type="password" name="password" class="form-control">
                    </div>
                    <div class="card-footer text-end">
                        <button type="submit" class="btn btn-primary">Mettre à jour</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
