<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daniel's Services Restaurant</title>
    <link rel="icon" href="/img/logo.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/tabler@latest/dist/css/tabler.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/tabler@latest/dist/js/tabler.min.js"></script>
</head>
<body>
    <?php if (isset($_SESSION['user']) && is_array($_SESSION['user'])): ?>
    <header class="navbar navbar-expand-md navbar-dark d-print-none" style="background-color: #2d2d2d;">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <a href="/" class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3">
                <img src="/img/logo.png" alt="Daniel's Services Restaurant Logo" style="height: 40px;">
            </a>
            <div class="navbar-nav flex-row order-md-last">
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown">
                        <div class="d-none d-xl-block ps-2">
                            <div><?= htmlspecialchars($_SESSION['user']['nom'] ?? 'Utilisateur') ?></div>
                            <div class="mt-1 small text-muted"><?= ucfirst($_SESSION['user']['fonction'] ?? 'user') ?></div>
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end">
                        <a class="dropdown-item" href="/logout">
                            <i class="fas fa-sign-out-alt me-2"></i>Se déconnecter
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="navbar-expand-md">
        <div class="collapse navbar-collapse" id="navbar-menu">
            <div class="navbar navbar-dark">
        <div class="container-fluid">
                    <ul class="navbar-nav">
                        <!-- Tableau de Bord -->
                        <li class="nav-item">
                            <a class="nav-link" href="/">
                                <span class="nav-link-title">Tableau de Bord</span>
                            </a>
                        </li>


                        <!-- Configuration -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#navbar-config" data-bs-toggle="dropdown" role="button" aria-expanded="false">
                                <span class="nav-link-title">Configuration</span>
                            </a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="/gestion-tables">Gestion Tables</a>
                                <a class="dropdown-item" href="/gestion-unites">Gestion Unités</a>
                                <a class="dropdown-item" href="/articles">Gestion Articles</a>
                                <a class="dropdown-item" href="/equipements">Gestion Équipements</a>
                                <a class="dropdown-item" href="/users">Gestion Serveurs</a>
                            </div>
                        </li>

                        
                        <!-- Vente -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#navbar-vente" data-bs-toggle="dropdown" role="button" aria-expanded="false">
                                <span class="nav-link-title">Vente</span>
                            </a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="/ventes">Liste des ventes</a>
                                <a class="dropdown-item" href="/vente/pos">Vente directe</a>
                                <a class="dropdown-item" href="/post-paiement">Post-paiement</a>
                            </div>
                        </li>

                        <!-- Stock -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#navbar-stock" data-bs-toggle="dropdown" role="button" aria-expanded="false">
                                <span class="nav-link-title">Stock</span>
                            </a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="/approvisionnements">Gestion Appro.</a>
                                <a class="dropdown-item" href="/inventaires">Inventaires</a>
                            </div>
                        </li>

                        <!-- Reporting -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#navbar-reports" data-bs-toggle="dropdown" role="button" aria-expanded="false">
                                <span class="nav-link-title">Reporting</span>
                            </a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="/rapports/ventes">Rapport Périodique</a>
                                <a class="dropdown-item" href="/rapports/articles">Rapport par Article</a>
                            </div>
                        </li>
                        
                        <!-- Aide -->
                        <li class="nav-item">
                            <a class="nav-link" href="/aide/manuel">
                                <span class="nav-link-title">Aide</span>
                            </a>
                        </li>
                    </ul>
        </div>
    </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="page-wrapper">
                <div class="container-fluid" id="page-content">
                    <?= $content ?>
                </div>
            </div>
        </div>
        
        <!-- Toast Container -->
        <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1055">
            <?php if (isset($_SESSION['message']) || isset($_SESSION['error'])): ?>
                <?php 
                    $is_success = isset($_SESSION['message']);
                    $message = $is_success ? $_SESSION['message'] : $_SESSION['error'];
                    $bg_class = $is_success ? 'bg-success' : 'bg-danger';
                    $icon = $is_success ? 'fa-check-circle' : 'fa-times-circle';
                ?>
                <div id="session-toast" class="toast text-white <?= $bg_class ?>" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="5000" data-bs-autohide="true">
                    <div class="d-flex">
                        <div class="toast-body">
                            <i class="fas <?= $icon ?> me-2"></i>
                            <?= htmlspecialchars($message) ?>
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
                <?php 
                    unset($_SESSION['message']);
                    unset($_SESSION['error']); 
                ?>
            <?php endif; ?>
        </div>
        
        <!-- Modal pour ajouter une fonction -->
        <div class="modal modal-blur fade" id="modal-add-fonction" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Ajouter une nouvelle fonction</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form id="add-fonction-form">
                            <div class="mb-3">
                                <label class="form-label required">Nom de la fonction</label>
                                <input type="text" id="new-fonction-nom" class="form-control" required>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn me-auto" data-bs-dismiss="modal">Annuler</button>
                        <button type="button" id="submit-fonction-form" class="btn btn-primary">Ajouter</button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Modal de Confirmation Générique -->
        <div class="modal modal-blur fade" id="confirm-modal" tabindex="-1">
            <div class="modal-dialog modal-sm modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center py-4">
                        <i class="fas fa-exclamation-triangle text-danger fa-3x mb-3"></i>
                        <h3 class="modal-title mb-2">Confirmation requise</h3>
                        <div class="text-muted" id="modal-body-content"></div>
                    </div>
                    <div class="modal-footer">
                        <div class="w-100">
                            <div class="row">
                                <div class="col">
                                    <button type="button" class="btn w-100" data-bs-dismiss="modal">Annuler</button>
                                </div>
                                <div class="col">
                                    <a href="#" id="confirm-button" class="btn btn-danger w-100">Supprimer</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <script>
        document.addEventListener('DOMContentLoaded', function () {            // Initialisation du toast de session
            const sessionToastEl = document.getElementById('session-toast');
            if (sessionToastEl) {
                const sessionToast = new bootstrap.Toast(sessionToastEl);
                sessionToast.show();
            }
        
            // Gestion du formulaire de la modale pour ajouter une fonction
            const submitButton = document.getElementById('submit-fonction-form');
                if (submitButton) {
                    submitButton.addEventListener('click', function() {
                        const fonctionNomInput = document.getElementById('new-fonction-nom');
                        const fonctionNom = fonctionNomInput.value.trim();
                        const fonctionSelect = document.querySelector('select[name="fonction_id"]');
            
                        if (!fonctionNom) {
                            alert('Le nom de la fonction ne peut pas être vide.');
                            return;
                        }
            
                        fetch('/api/fonctions', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ nom: fonctionNom })
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('La requête a échoué. Statut: ' + response.status);
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                const newOption = new Option(data.fonction.nom, data.fonction.id, true, true);
                                fonctionSelect.appendChild(newOption);
                                const modal = bootstrap.Modal.getInstance(document.getElementById('modal-add-fonction'));
                                modal.hide();
                                fonctionNomInput.value = '';
                            } else {
                                alert('Erreur: ' + (data.message || 'Impossible d\'ajouter la fonction.'));
                            }
                        })
                        .catch(error => {
                            console.error("Erreur lors de l'ajout de la fonction:", error);
                            alert('Une erreur technique est survenue.');
                        });
                    });
                }
            });
            
            // Script pour la modale de confirmation générique
            document.addEventListener('DOMContentLoaded', function() {
                const confirmModal = document.getElementById('confirm-modal');
                if (confirmModal) {
                    confirmModal.addEventListener('show.bs.modal', function (event) {
                        const button = event.relatedTarget; // Bouton qui a déclenché la modale
                        const id = button.getAttribute('data-id');
                        const numeroVente = button.getAttribute('data-numero-vente');
                        const total = button.getAttribute('data-total');
            
                        const modalBodyContent = confirmModal.querySelector('#modal-body-content');
                        const confirmButton = confirmModal.querySelector('#confirm-button');
            
                        if (modalBodyContent && confirmButton) {
                            modalBodyContent.innerHTML = `Voulez-vous vraiment supprimer la vente N° <strong>${numeroVente}</strong> (Total: <strong>${total}</strong>) ? <br>Le stock sera réajusté.`;
                            confirmButton.href = `/vente/delete/${id}`;
                        }
                    });
                }
            });        </script>
</body>
</html>
