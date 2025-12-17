<div class="page-header">
    <div class="container-xl">
        <h2 class="page-title text-primary">
            Manuel Utilisateur
        </h2>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <div class="card-body">
                <h3 class="card-title">Bienvenue sur le système de gestion du Restaurant</h3>
                <p>Ce manuel vous guide à travers les fonctionnalités principales de l'application.</p>

                <h4 class="mt-4">1. Module de Vente</h4>
                <p>Ce module est le cœur de l'activité quotidienne.</p>
                <ul>
                    <li><strong>Vente Directe (POS) :</strong> Permet d'enregistrer rapidement une vente pour un client qui paie immédiatement. Sélectionnez une zone, une table, puis ajoutez des articles via la recherche ou la liste déroulante avant de valider.</li>
                    <li><strong>Post-Paiement :</strong> Idéal pour les clients qui consomment à table et paient à la fin.
                        <ol>
                            <li>Allez dans "Post-paiement" et cliquez sur "Occuper une table".</li>
                            <li>Sur la page de gestion de la table, ajoutez des articles. Chaque ajout est une "commande".</li>
                            <li>Vous pouvez ajouter plusieurs commandes au fil du temps.</li>
                            <li>Cliquez sur "Clôturer & Payer" pour finaliser la vente et libérer la table.</li>
                        </ol>
                    </li>
                    <li><strong>Liste des Ventes :</strong> Affiche toutes les ventes du jour. Vous pouvez y filtrer, voir les détails, supprimer une vente (avec confirmation) ou exporter la liste au format Excel.</li>
                </ul>

                <h4 class="mt-4">2. Module de Stock</h4>
                <ul>
                    <li><strong>Gestion Approvisionnements :</strong> Enregistrez ici toutes vos entrées de marchandises (achats). Cela mettra à jour automatiquement votre stock et le "dernier coût d'achat" de chaque article, essentiel pour le calcul des gains.</li>
                    <li><strong>Inventaires :</strong> Permet de faire le point sur votre stock réel.
                        <ol>
                            <li>Lancez un "Nouvel Inventaire".</li>
                            <li>Saisissez la quantité physique comptée pour chaque article.</li>
                            <li>Si un écart est détecté, le champ "Justification" s'active pour vous permettre d'expliquer la différence.</li>
                            <li>Ajoutez une conclusion générale et validez. Le stock sera automatiquement ajusté si vous laissez la case cochée.</li>
                        </ol>
                    </li>
                </ul>
                
                <h4 class="mt-4">3. Module de Configuration</h4>
                <p>Cette section vous permet de paramétrer le système.</p>
                <ul>
                    <li><strong>Gestion Articles :</strong> Créez et modifiez vos articles (produits finis, matières premières), définissez leur prix (standard ou par zone) et leur unité.</li>
                    <li><strong>Gestion Équipements :</strong> Répertoriez les biens durables de votre restaurant (fours, chaises...). Vous pouvez y faire un "état des lieux" pour suivre les quantités par état (en service, en réparation, hors service).</li>
                     <li><strong>Gestion Tables & Zones :</strong> Organisez la disposition de votre restaurant en créant des zones (ex: Terrasse) et des tables.</li>
                    <li><strong>Gestion Unités :</strong> Gérez les unités de mesure pour l'achat (ex: Caisse de 24) et la vente (ex: Bouteille).</li>
                    <li><strong>Gestion Serveurs :</strong> Créez, modifiez ou supprimez les comptes utilisateurs (agents, serveurs, caissiers) et définissez leurs informations (salaire, fonction...).</li>
                </ul>

                <h4 class="mt-4">4. Module de Reporting</h4>
                <p>Analysez les performances de votre restaurant.</p>
                <ul>
                    <li><strong>Rapport Périodique :</strong> Visualisez votre chiffre d'affaires sur une période donnée, regroupé par jour, par mois, ou par vente individuelle.</li>
                    <li><strong>Rapport par Article :</strong> Analysez la rentabilité de chaque article. Ce rapport vous montre la quantité vendue, le chiffre d'affaires généré, le coût total et le gain brut pour chaque produit.</li>
                </ul>

                <h4 class="mt-4">5. Foire Aux Questions (FAQ)</h4>
                <ul>
                    <li><strong>Q: Que faire si je ne trouve pas un article lors d'une vente ?</strong><br>
                        R: Assurez-vous que l'article est bien créé dans "Gestion Articles" et qu'il a un prix défini pour la zone de la table sélectionnée. Vérifiez aussi le stock si la vente est paramétrée pour le prendre en compte.</li>
                    <li><strong>Q: Pourquoi le stock n'est-il pas mis à jour après un approvisionnement ?</strong><br>
                        R: Vérifiez que la quantité et le type d'unité (achat/vente) ont été correctement saisis. Assurez-vous que l'article existe et que la transaction a été validée sans erreur.</li>
                    <li><strong>Q: Mes rapports sont vides, que faire ?</strong><br>
                        R: Vérifiez que la période sélectionnée est correcte et qu'il y a bien eu des ventes ou des opérations d'inventaire durant cette période. Assurez-vous également que votre rôle utilisateur a les permissions nécessaires.</li>
                    <li><strong>Q: J'ai supprimé une vente par erreur, puis-je la récupérer ?</strong><br>
                        R: Non, la suppression d'une vente est définitive et entraîne le réajustement du stock. Il est recommandé d'utiliser la fonction de suppression avec prudence.</li>
                    <li><strong>Q: Comment changer ma fonction ou mon salaire ?</strong><br>
                        R: Ces informations sont gérées dans le module "Gestion Serveurs" par un administrateur du système. Contactez votre responsable.</li>
                </ul>

            </div>
        </div>
    </div>
</div>
