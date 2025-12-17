<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mot de passe oublié - Muyak</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .login-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            padding: 2rem;
        }
        .logo {
            text-align: center;
            margin-bottom: 2rem;
        }
        .logo h1 {
            color: #333;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-12">
                <div class="login-container">
                    <div class="logo">
                        <h1>🔐 Muyak</h1>
                        <p class="text-muted">Réinitialisation du mot de passe</p>
                    </div>

                    <?php if (isset($success) && $success): ?>
                        <div class="alert alert-success">
                            <h5>✅ Mot de passe réinitialisé !</h5>
                            <p>
                                Un nouveau mot de passe a été généré pour <strong><?= htmlspecialchars($user['email']) ?></strong>.
                            </p>
                            <div class="alert alert-info mt-3">
                                <strong>Nouveau mot de passe :</strong> <code><?= htmlspecialchars($nouveau_mot_de_passe) ?></code>
                            </div>
                            <p class="mb-0">
                                <a href="/login" class="btn btn-primary">Se connecter</a>
                            </p>
                        </div>
                    <?php elseif (isset($error)): ?>
                        <div class="alert alert-danger">
                            <h5>❌ Erreur</h5>
                            <p><?= htmlspecialchars($error) ?></p>
                        </div>
                    <?php endif; ?>

                    <?php if (!isset($success) || !$success): ?>
                        <form method="POST" action="/forgot-password">
                            <div class="mb-3">
                                <label for="email" class="form-label">Adresse email</label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       placeholder="Entrez votre adresse email" required
                                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                                <div class="form-text">
                                    Saisissez votre adresse email pour recevoir un nouveau mot de passe.
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    🔑 Réinitialiser le mot de passe
                                </button>
                                <a href="/login" class="btn btn-outline-secondary">
                                    ← Retour à la connexion
                                </a>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>