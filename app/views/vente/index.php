<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center mt-3">
            <div class="col">
                <h2 class="page-title text-primary">
                    Ventes du Jour (<?= htmlspecialchars(date('d/m/Y', strtotime($date_filtre))) ?>)
                </h2>
            </div>
            <div class="col-auto">
                <div class="btn-list flex-nowrap">
                    <a href="/post-paiement" class="btn btn-outline-primary">
                        Post-paiement
                    </a>
                    <a href="/ventes/export/excel?date=<?= htmlspecialchars($date_filtre) ?>" class="btn btn-success">
                        <i class="fas fa-file-excel me-2"></i>Exporter
                    </a>
                    <a href="/vente/pos" class="btn btn-primary">
                        Nouvelle Vente
                    </a>
                </div>
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
                            <th>Heure</th>
                            <th>N° Vente</th>
                            <th>Table</th>
                            <th>Nbr Articles</th>
                            <th>Total</th>
                            <th>Statut</th>
                            <th class="w-1">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($ventes)): ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted">Aucune vente enregistrée pour aujourd'hui.</td>
                            </tr>
                        <?php endif; ?>
                        <?php foreach ($ventes as $vente): ?>
                        <tr>
                            <td class="text-muted"><?= htmlspecialchars(date('H:i', strtotime($vente['created_at']))) ?></td>
                            <td><strong><?= htmlspecialchars($vente['numero_vente']) ?></strong></td>
                            <td><?= htmlspecialchars($vente['table_nom']) ?></td>
                            <td><?= htmlspecialchars($vente['count_articles']) ?></td>
                            <td><?= htmlspecialchars(number_format($vente['total'], 0, ',', ' ')) ?> Fc</td>
                            <td><span class="badge bg-success me-1"></span> <?= ucfirst($vente['statut']) ?></td>
                            <td>
                                <div class="btn-list flex-nowrap">
                                    <a href="/vente/show/<?= $vente['id'] ?>" class="btn btn-sm">Voir</a>
                                    <a href="/vente/edit/<?= $vente['id'] ?>" class="btn btn-sm">Modifier</a>
                                    <button class="btn btn-sm btn-outline-secondary btn-reimprimer" data-vente-id="<?= $vente['id'] ?>">Réimprimer</button>
                                    <a href="#" 
                                       class="btn btn-sm btn-outline-danger btn-delete-vente" 
                                       data-bs-toggle="modal" 
                                       data-bs-target="#confirm-modal"
                                       data-id="<?= $vente['id'] ?>"
                                       data-numero-vente="<?= htmlspecialchars($vente['numero_vente']) ?>"
                                       data-total="<?= htmlspecialchars(number_format($vente['total'], 0, ',', ' ')) ?> Fc">
                                        Supprimer
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

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.btn-reimprimer').forEach(button => {
        button.addEventListener('click', function () {
            const venteId = this.getAttribute('data-vente-id');
            const pdfUrl = `/vente/facture/${venteId}`;

            // Remove any old print iframe
            const oldIframe = document.getElementById('print-iframe');
            if (oldIframe) {
                oldIframe.parentNode.removeChild(oldIframe);
            }

            const iframe = document.createElement('iframe');
            iframe.style.display = 'none';
            iframe.id = 'print-iframe';
            iframe.src = pdfUrl;

            document.body.appendChild(iframe);

            iframe.onload = function() {
                try {
                    const iframeWindow = iframe.contentWindow;

                    // This function will be called after the print dialog is closed
                    const handleAfterPrint = () => {
                        // Remove the iframe from the DOM
                        if (iframe.parentNode) {
                            iframe.parentNode.removeChild(iframe);
                        }
                        // Clean up the event listener
                        iframeWindow.removeEventListener('afterprint', handleAfterPrint);
                    };

                    // Listen for the 'afterprint' event
                    iframeWindow.addEventListener('afterprint', handleAfterPrint);

                    // A short delay helps ensure the PDF viewer is fully rendered
                    setTimeout(() => {
                        iframeWindow.print();
                    }, 500);

                    // Fallback for browsers that don't support afterprint well
                    setTimeout(handleAfterPrint, 30000); // Clean up after 30 seconds regardless

                } catch (e) {
                    console.error("Impression directe a échoué:", e);
                    document.body.removeChild(iframe); // Clean up on error
                    window.open(pdfUrl, '_blank');
                }
            };
        });
    });
});

// Auto-impression en arrière-plan après la redirection
<?php if (isset($_SESSION['print_facture_id'])): ?>
document.addEventListener('DOMContentLoaded', function () {
    const venteIdToPrint = "<?= $_SESSION['print_facture_id'] ?>";
    console.log('Lancement de l\'impression pour la vente ID:', venteIdToPrint);

    const pdfUrl = `/vente/facture/${venteIdToPrint}`;
    
    const iframe = document.createElement('iframe');
    iframe.style.display = 'none';
    iframe.id = 'print-iframe-auto';
    iframe.src = pdfUrl;

    document.body.appendChild(iframe);

    iframe.onload = function() {
        try {
            const iframeWindow = iframe.contentWindow;

            const handleAfterPrint = () => {
                if (iframe.parentNode) {
                    iframe.parentNode.removeChild(iframe);
                }
                iframeWindow.removeEventListener('afterprint', handleAfterPrint);
            };

            iframeWindow.addEventListener('afterprint', handleAfterPrint);
            
            setTimeout(() => {
                iframeWindow.print();
            }, 500); // Délai pour assurer le chargement complet du PDF

        } catch (e) {
            console.error("Impression automatique a échoué:", e);
            if (iframe.parentNode) {
                iframe.parentNode.removeChild(iframe);
            }
            window.open(pdfUrl, '_blank'); // fallback
        }
    };
});
<?php unset($_SESSION['print_facture_id']); // Nettoyer pour éviter la réimpression ?>
<?php endif; ?>
</script>
