<?php

class FacturePdf extends FPDF
{
    private $vente;
    protected $javascript;
    protected $n_js;

    public function __construct($venteData)
    {
        parent::__construct('P', 'mm', [80, 250]); 
        $this->vente = $venteData;
        $this->AddPage();
        $this->SetAutoPageBreak(true, 5);
        $this->SetMargins(5, 5, 5);
    }

    private function toIso($string)
    {
        return iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $string);
    }

    public function Header()
    {
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(0, 5, $this->toIso('RESTAURANT MUYAK'), 0, 1, 'C');
        
        $this->SetFont('Arial', '', 8);
        $this->Cell(0, 4, $this->toIso('(+243) 99 99 89 867, 81 00 33 337'), 0, 1, 'C');
        $this->Cell(0, 4, $this->toIso('danielsservice@gmail.com'), 0, 1, 'C');
        $this->Cell(0, 4, $this->toIso('RCCM:CD/KIN/RCCM/15-A-2344'), 0, 1, 'C');
        $this->Cell(0, 4, $this->toIso('Bandal synkin, avenue Sundi'), 0, 1, 'C');
        $this->Ln(2);
        
        $this->Cell(0, 2, '--------------------------------------------------', 0, 1, 'C');

        $waiterName = $_SESSION['user']['nom'] ?? 'Inconnu';
        $this->Cell(0, 4, 'Servi par: ' . $this->toIso($waiterName), 0, 1, 'C');
        $this->Cell(0, 4, 'Table: ' . $this->toIso($this->vente['table_nom']), 0, 1, 'C');
        $this->Ln(5);
    }

                public function generate()

                {

                    $this->SetFont('Arial', 'B', 10);

                    $this->Cell(0, 6, $this->toIso("FACTURE N° " . $this->vente['numero_vente']), 0, 1, 'C');

                    $this->Ln(2);

            

                    // --- Logique de groupement des articles par article_id sur toute la vente ---

                    $lignesGroupees = [];

                    if (isset($this->vente['commandes'])) {

                        foreach ($this->vente['commandes'] as $commande) {

                            if (isset($commande['lignes'])) {

                                foreach ($commande['lignes'] as $ligne) {

                                    $articleId = $ligne['article_id'];

                                    if (isset($lignesGroupees[$articleId])) {

                                        // L'article existe déjà, on additionne la quantité

                                        $lignesGroupees[$articleId]['quantite'] += $ligne['quantite'];

                                    } else {

                                        // C'est un nouvel article, on l'ajoute au tableau

                                        $lignesGroupees[$articleId] = [

                                            'article_nom' => $ligne['article_nom'],

                                            'quantite' => $ligne['quantite'],

                                            'prix_unitaire_ht' => $ligne['prix_unitaire_ht']

                                        ];

                                    }

                                }

                            }

                        }

                    }

                    // --- Fin de la logique de groupement ---

            

                    // En-tête du tableau

                    $this->SetFont('Arial', 'B', 8);

                    $this->Cell(30, 6, $this->toIso("Désignation"), 'B', 0, 'L');

                    $this->Cell(8, 6, $this->toIso("Qte"), 'B', 0, 'C');

                    $this->Cell(16, 6, $this->toIso("P.U."), 'B', 0, 'R');

                    $this->Cell(16, 6, $this->toIso("P.T."), 'B', 1, 'R');

            

                    $this->SetFont('Arial', '', 8);

                    $montant = 0;

                    

                    // Boucle sur les articles maintenant groupés

                    foreach ($lignesGroupees as $ligne) {

                        $totalLigne = $ligne['prix_unitaire_ht'] * $ligne['quantite'];

                        $montant += $totalLigne;

            

                        $y_before = $this->GetY();

                        $this->MultiCell(30, 5, $this->toIso(stripslashes($ligne['article_nom'])), 0, 'L');

                        $y_after = $this->GetY();

                        $height = $y_after - $y_before;

                        

                        $this->SetXY($this->GetX() + 30, $y_before); 

            

                        $this->Cell(8, $height, $this->toIso($ligne['quantite']), 0, 0, 'C');

                        $this->Cell(16, $height, number_format($ligne['prix_unitaire_ht'], 0, ',', ' '), 0, 0, 'R');

                        $this->Cell(16, $height, number_format($totalLigne, 0, ',', ' '), 0, 1, 'R');

                    }

            

                    $this->Ln(2);

                    $this->SetFont('Arial', 'B', 9);

                    $this->Cell(0, 0, '', 'T', 1);

                    $this->Ln(1);

                    

                    $this->Cell(54, 6, "TOTAL", 0, 0, 'L');

                    $this->Cell(16, 6, number_format($montant, 0, ',', ' ') . ' Fc', 0, 1, 'R');

                }

    public function Footer()
    {
        $this->SetY(-25);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 4, $this->toIso('Merci et à bientôt'), 0, 1, 'C');
        
        $this->SetFont('Arial', '', 7);
        $this->Cell(0, 4, date('d/m/Y H:i:s'), 0, 0, 'C');
    }

    // --- Methods for JavaScript Embedding ---
    function IncludeJS($script) {
        $this->javascript = $script;
    }

    function _putjavascript() {
        $this->_newobj();
        $this->n_js = $this->n;
        $this->_put('<<');
        $this->_put('/Names [(EmbeddedJS) ' . ($this->n + 1) . ' 0 R]');
        $this->_put('>>');
        $this->_put('endobj');
        $this->_newobj();
        $this->_put('<<');
        $this->_put('/S /JavaScript');
        $this->_put('/JS ' . $this->_textstring($this->javascript));
        $this->_put('>>');
        $this->_put('endobj');
    }

    function _putresources() {
        parent::_putresources();
        if (!empty($this->javascript)) {
            $this->_putjavascript();
        }
    }

    function _putcatalog() {
        parent::_putcatalog();
        if (!empty($this->javascript)) {
            $this->_put('/Names <</JavaScript ' . ($this->n_js) . ' 0 R>>');
        }
    }
    
    public function AutoPrint()
    {
        // Embed JavaScript to print the document automatically
        $this->IncludeJS("print(true);");
    }
}
