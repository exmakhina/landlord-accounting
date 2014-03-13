<?php

class Zend_View_Helper_Bilan extends Zend_View_Helper_Abstract 
{
    public function bilan()
    {
		$session = Zend_Registry::get('session');
		$language = $session->language;
		$translationsMapper = new Application_Model_TranslationsMapper();
		$txt = $translationsMapper->getLanguage($language);
		
		$posteMapper = new Application_Model_PostesMapper();
		$mvMapper = new Application_Model_MouvementMapper();
		
		// ----------- ACTIF --------------------
		$rapport = '<div class = "section_br"><h2>'.$txt['actif'].'</h2>';
		$rapport .= '<h3>'.$txt['actif_court_terme'].'</h3>';
		
		$rapport .= '<table class = "table_br">';
		
		$postes = $posteMapper->fetchRange('1000','1299');
		$actif_court_terme = 0.00;
		
		$pair = true;
		
		foreach ($postes as $poste) {
			$moves = $mvMapper->fetchPoste($poste->getId());
			$subtotal = 0.00;
			foreach ($moves as $move) {
				$subtotal += $move->getValeur();	
			}
			
			if ($pair) {
				$rapport .= '<tr class = "pair">';
			} else {
				$rapport .= '<tr class = "impair">';
			}
			$pair = !$pair;
			
			$rapport .= '<td class = "poste">'.$txt[$poste->getNom()].'</td>';
			$rapport .= '<td class = "montant">'.number_format($subtotal, 2).'</td>';
			$rapport .= '</tr>';
			
			$actif_court_terme += $subtotal;
		}
		$rapport .= '<tr><th class = "poste">'.$txt['total_actif_court_terme'].'</th><th class = "montant">'.number_format($actif_court_terme, 2).'</th></tr>';
		
		$rapport .= '</table>';
		
		$rapport .= '<h3>'.$txt['immobilisations'].'</h3>';
		
		$rapport .= '<table class = "table_br">';
		
		$postes = $posteMapper->fetchRange('1600','1999');
		$immobilisation = 0.00;
		
		$pair = true;
		
		foreach ($postes as $poste) {
			$moves = $mvMapper->fetchPoste($poste->getId());
			$subtotal = 0.00;
			foreach ($moves as $move) {
				$subtotal += $move->getValeur();	
			}
			
			if ($pair) {
				$rapport .= '<tr class = "pair">';
			} else {
				$rapport .= '<tr class = "impair">';
			}
			$pair = !$pair;
			
			$rapport .= '<td class = "poste">'.$txt[$poste->getNom()].'</td>';
			$rapport .= '<td class = "montant">'.number_format($subtotal, 2).'</td>';
			$rapport .= '</tr>';
			
			$immobilisation += $subtotal;
		}
		$rapport .= '<tr><th class = "poste">'.$txt['total_immobilisations'].'</th><th class = "montant">'.number_format($immobilisation, 2).'</th></tr>';
		
		$rapport .= '</table>';
		
		$rapport .= '<table class = "total_categorie_br">';
		$total_actif = $immobilisation + $actif_court_terme;
		$rapport .= '<tr><th class = "poste">'.$txt['total_actif'].'</th><th class = "montant">'.number_format($total_actif, 2).'</th></tr>';
		$rapport .= '</table></div>';
		
		// ----------- PASSIF --------------------
		$rapport .= '<div class = "section_br"><h2>'.$txt['passif'].'</h2>';
		
		$rapport .= '<table class = "table_br">';
		
		$postes = $posteMapper->fetchCategorie('PASSIF');
		$passif = 0.00;
		
		$pair = true;
		
		foreach ($postes as $poste) {
			$moves = $mvMapper->fetchPoste($poste->getId());
			$subtotal = 0.00;
			foreach ($moves as $move) {
				$subtotal += $move->getValeur();	
			}
			
			if ($pair) {
				$rapport .= '<tr class = "pair">';
			} else {
				$rapport .= '<tr class = "impair">';
			}
			$pair = !$pair;
			
			$rapport .= '<td class = "poste">'.$txt[$poste->getNom()].'</td>';
			$rapport .= '<td class = "montant">'.number_format($subtotal, 2).'</td>';
			$rapport .= '</tr>';
			
			$passif += $subtotal;
		}
		
		$rapport .= '<tr><th class = "poste">'.$txt['total_passif'].'</th><th class = "montant">'.number_format($passif, 2).'</th></tr>';
		
		$rapport .= '</table></div>';
		
		// ----------- CAPITAL --------------------
		$rapport .= '<div class = "section_br"><h2>'.$txt['capital'].'</h2>';
		
		$rapport .= '<table class = "table_br">';
		
		$postes = $posteMapper->fetchCategorie('CAPITAL');
		$capital = 0.00;
		
		$pair = true;
		
		foreach ($postes as $poste) {
			$moves = $mvMapper->fetchPoste($poste->getId());
			$subtotal = 0.00;
			foreach ($moves as $move) {
				$subtotal += $move->getValeur();	
			}
			
			if ($pair) {
				$rapport .= '<tr class = "pair">';
			} else {
				$rapport .= '<tr class = "impair">';
			}
			$pair = !$pair;
			
			$rapport .= '<td class = "poste">'.$txt[$poste->getNom()].'</td>';
			$rapport .= '<td class = "montant">'.number_format($subtotal, 2).'</td>';
			$rapport .= '</tr>';
			
			$capital += $subtotal;
		}
		
		// Calcul du benefice
		$postes = $posteMapper->fetchCategorie('REVENU');
		$revenus = 0.00;
		foreach ($postes as $poste) {
			$moves = $mvMapper->fetchPoste($poste->getId());
			$subtotal = 0.00;
			foreach ($moves as $move) {
				$subtotal += $move->getValeur();	
			}
			$revenus += $subtotal;
		}
		$postes = $posteMapper->fetchCategorie('DEPENSE');
		$depenses = 0.00;
		foreach ($postes as $poste) {
			$moves = $mvMapper->fetchPoste($poste->getId());
			$subtotal = 0.00;
			foreach ($moves as $move) {
				$subtotal += $move->getValeur();	
			}
			$depenses += $subtotal;
		}
		$benefice = $revenus - $depenses;
		if ($pair) {
				$rapport .= '<tr class = "pair">';
			} else {
				$rapport .= '<tr class = "impair">';
			}
		$rapport .= '<td class = "poste">'.$txt['benefices'].'</td><td class = "montant">'.number_format($benefice, 2).'</td></tr>';
	
		$capital += $benefice;

		$rapport .= '<tr><th class = "poste">'.$txt['total_capital'].'</th><th class = "montant">'.number_format($capital, 2).'</th></tr>';
		
		$rapport .= '</table></div>';
		
		$rapport .= '<div class = "section_br"><table class = "total_categorie_br">';
		$total_passif_capital = $capital + $passif;
		$rapport .= '<tr><th class = "poste">'.$txt['total_passif_capital'].'</th><th class = "montant">'.number_format($total_passif_capital, 2).'</th></tr>';
		$rapport .= '</table></div>';
		
		return $rapport;
	}
}