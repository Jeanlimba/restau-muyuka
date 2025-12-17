<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture #<?= htmlspecialchars($facture['id']) ?></title>
    <style>
        /* Style minimaliste pour ticket de caisse thermique */
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px; /* Increased base font size */
            color: #000;
            margin: 0; /* Remove default margins */
            padding: 5px; /* Minimal padding */
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .header, .footer { text-align: center; margin-bottom: 10px; }
        h1 { font-size: 16px; margin: 0; }
        h2 { font-size: 14px; margin: 5px 0; border-bottom: 1px dashed #000; padding-bottom: 5px;}
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 2px 0; /* Reduced padding */
        }
        .items thead {
            border-top: 1px dashed #000;
            border-bottom: 1px dashed #000;
        }
        .items th {
            text-align: left;
        }
        .totals {
            margin-top: 10px;
            border-top: 1px solid #000;
        }
        .totals td:first-child {
            text-align: left;
        }
        .totals td:last-child {
            text-align: right;
            font-weight: bold;
        }
        .footer { margin-top: 10px; border-top: 1px dashed #000; padding-top: 5px; }

        @media print {
            body {
                width: 100%;
                margin: 0;
                padding: 0;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>

    <header class="header">
        <h1>RESTAURANT MUYAK</h1>
        <p>Votre adresse ici<br>Tel: +221 00 000 00 00</p>
    </header>

    <h2>Ticket #<?= htmlspecialchars($facture['id']) ?></h2>
    <p>
        Date: <?= date('d/m/Y H:i', strtotime($facture['created_at'])) ?><br>
        Lieu: <?= htmlspecialchars($facture['table_nom']) ?> (<?= htmlspecialchars($facture['table_zone']) ?>)
    </p>

    <table class="items">
        <thead>
            <tr>
                <th>Article</th>
                <th>Qte</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $subtotal = 0;
            foreach ($facture['lignes'] as $ligne): 
                $totalLigne = $ligne['prix_unitaire_ht'] * $ligne['quantite'];
                $subtotal += $totalLigne;
            ?>
            <tr>
                <td><?= htmlspecialchars($ligne['article_nom']) ?></td>
                <td><?= htmlspecialchars($ligne['quantite']) ?></td>
                <td class="text-right"><?= number_format($totalLigne, 0, ',', ' ') ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <table class="totals">
        <tr>
            <td><strong>TOTAL</strong></td>
            <td class="text-right"><strong><?= number_format($subtotal, 0, ',', ' ') ?> Fc</strong></td>
        </tr>
    </table>

    <footer class="footer">
        <p>Merci de votre visite !</p>
    </footer>

    <script>
        window.onload = function() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('print') && urlParams.get('print') === 'true') {
                window.print();
                // Using setTimeout to give the print dialog a moment to open/close before trying to close the window
                setTimeout(function() {
                    window.close();
                }, 100); 
            }
        };
    </script>

</body>
</html>
