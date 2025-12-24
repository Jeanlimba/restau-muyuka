<?php

class FacturePdf extends FPDF
{
    private $vente;
    protected $javascript;
    protected $n_js;

        public function __construct($venteData)
    {
        $this->vente = $venteData; // Doit être défini tôt pour _groupLignes

        // --- Calcul de la hauteur dynamique de la page ---
        $lignesGroupees = $this->_groupLignes();
        
        // Regrouper par catégorie pour le calcul de la hauteur
        $categories = [];
        foreach ($lignesGroupees as $ligne) {
            $categories[$ligne['categorie']][] = $ligne;
        }

        // Crée une instance FPDF temporaire pour les calculs de largeur de texte
        $tempPdf = new FPDF('P', 'mm', [80, 80]);
        $tempPdf->AddPage();
        $tempPdf->SetFont('Arial', '', 8);

        $articleRowsHeight = 0;
        $multiCellWidth = 30; // Largeur de la cellule "Désignation" de la méthode generate()
        $lineHeight = 5;      // Hauteur de ligne dans MultiCell de la méthode generate()

        foreach ($lignesGroupees as $ligne) {
            $text = $this->toIso(stripslashes($ligne['article_nom']));
            $words = explode(' ', $text);
            $lineCount = 1;
            $currentLine = '';
            foreach ($words as $word) {
                $sep = $currentLine === '' ? '' : ' ';
                if ($tempPdf->GetStringWidth($currentLine . $sep . $word) < $multiCellWidth) {
                    $currentLine .= $sep . $word;
                } else {
                    $lineCount++;
                    $currentLine = $word;
                }
            }
            $articleRowsHeight += $lineCount * $lineHeight;
        }
        unset($tempPdf);

        // Hauteur ajoutée par les en-têtes de catégorie (Ln(2) + Cell(5))
        $categoryHeaderHeight = count($categories) * 7;
        // Hauteur ajoutée par les sous-totaux de catégorie (Cell(6) + Ln(2))
        $subtotalHeight = count($categories) * 8;

        $headerHeight = 50;
        $contentStaticHeight = 25;
        $footerHeight = 15;
        $margins = 10;

        $totalHeight = $headerHeight + $contentStaticHeight + $articleRowsHeight + $categoryHeaderHeight + $subtotalHeight + $footerHeight + $margins;
        // --- Fin du calcul ---

        parent::__construct('P', 'mm', [80, $totalHeight]);
        $this->AddPage();
        $this->SetAutoPageBreak(true, 5);
        $this->SetMargins(5, 5, 5);
    }

    private function _groupLignes()
    {
        $lignesGroupees = [];
        if (isset($this->vente['commandes'])) {
            foreach ($this->vente['commandes'] as $commande) {
                if (isset($commande['lignes'])) {
                    foreach ($commande['lignes'] as $ligne) {
                        $articleId = $ligne['article_id'];
                        if (isset($lignesGroupees[$articleId])) {
                            $lignesGroupees[$articleId]['quantite'] += $ligne['quantite'];
                        } else {
                            $lignesGroupees[$articleId] = [
                                'article_nom' => $ligne['article_nom'],
                                'quantite' => $ligne['quantite'],
                                'prix_unitaire_ht' => $ligne['prix_unitaire_ht'],
                                'categorie' => $ligne['article_categorie'] ?? 'Divers'
                            ];
                        }
                    }
                }
            }
        }
        return $lignesGroupees;
    }

    private function toIso($string)
    {
        return iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $string);
    }

    public function Header()
    {
        // Logo
        $this->Image(BASE_PATH . 'public/img/logo.png', 30, 5, 20);
        // Saut de ligne
        $this->Ln(15);

        $this->SetFont('Arial', 'B', 10);
        $this->Cell(0, 5, $this->toIso('RESTAURANT DANIEL\'S SERVICES'), 0, 1, 'C');
        
        $this->SetFont('Arial', '', 8);
        $this->Cell(0, 4, $this->toIso('(+243) 999 989 867, 824 315 846'), 0, 1, 'C');
        $this->Cell(0, 4, $this->toIso('Av. SUNDI N°7, Q/MAKELELE, BANDALUNGWA'), 0, 1, 'C');
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
            
                    $lignesGroupees = $this->_groupLignes();
                    
                    $categories = [];
                    foreach ($lignesGroupees as $ligne) {
                        $categories[$ligne['categorie']][] = $ligne;
                    }

                    $this->SetFont('Arial', 'B', 8);
                    $this->Cell(30, 6, $this->toIso("Désignation"), 'B', 0, 'L');
                    $this->Cell(8, 6, $this->toIso("Qte"), 'B', 0, 'C');
                    $this->Cell(16, 6, $this->toIso("P.U."), 'B', 0, 'R');
                    $this->Cell(16, 6, $this->toIso("P.T."), 'B', 1, 'R');
            
                    $this->SetFont('Arial', '', 8);
                    $montantTotal = 0;
                    
                    foreach ($categories as $nomCategorie => $lignesDeLaCategorie) {
                        $this->Ln(2);
                        $this->SetFont('Arial', 'B', 8);
                        // Style du titre de la catégorie
                        $titreCategorie = '----- ' . $this->toIso(ucfirst($nomCategorie)) . ' -----';
                        $this->Cell(0, 5, $titreCategorie, 0, 1, 'C');
                        $this->SetFont('Arial', '', 8);

                        $montantCategorie = 0;
                        foreach ($lignesDeLaCategorie as $ligne) {
                            $totalLigne = $ligne['prix_unitaire_ht'] * $ligne['quantite'];
                            $montantCategorie += $totalLigne;
                
                            $y_before = $this->GetY();
                            $this->MultiCell(30, 5, $this->toIso(stripslashes($ligne['article_nom'])), 0, 'L');
                            $y_after = $this->GetY();
                            $height = $y_after - $y_before;
                            
                            $this->SetXY($this->GetX() + 30, $y_before); 
                
                            $this->Cell(8, $height, $this->toIso($ligne['quantite']), 0, 0, 'C');
                            $this->Cell(16, $height, number_format($ligne['prix_unitaire_ht'], 0, ',', ' '), 0, 0, 'R');
                            $this->Cell(16, $height, number_format($totalLigne, 0, ',', ' '), 0, 1, 'R');
                        }
                        
                        // Afficher le sous-total de la catégorie
                        $this->SetFont('Arial', 'B', 8);
                        $this->Cell(54, 6, $this->toIso("Sous-total"), 0, 0, 'R');
                        $this->Cell(16, 6, number_format($montantCategorie, 0, ',', ' ') . ' Fc', 0, 1, 'R');
                        
                        $montantTotal += $montantCategorie;
                    }
            
                    $this->Ln(2);
                    $this->SetFont('Arial', 'B', 9);
                    $this->Cell(0, 0, '', 'T', 1);
                    $this->Ln(1);
                    
                    $this->Cell(54, 6, "TOTAL", 0, 0, 'L');
                    $this->Cell(16, 6, number_format($montantTotal, 0, ',', ' ') . ' Fc', 0, 1, 'R');
                }

    public function Footer()
    {
        $this->SetY(-8);
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
