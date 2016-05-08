<?php

class Zend_View_Helper_Resultats extends Zend_View_Helper_Abstract 
{
    public function resultats($annee)
    {
		$session = Zend_Registry::get('session');
		$language = $session->language;
		$translationsMapper = new Application_Model_TranslationsMapper();
		$txt = $translationsMapper->getLanguage($language);
		
		$posteMapper = new Application_Model_PostesMapper();
		$opMapper = new Application_Model_OperationMapper();
		$mvMapper = new Application_Model_MouvementMapper();
		
		$operations = $opMapper->fetchYear($annee);
		
		$rapport = '<div class = "section_br"><h2>Revenus</h2>';
		$postes = $posteMapper->fetchCategorie('REVENU');
		
		$rapport .= '<table class = "table_br">';
		
		$total_revenu = 0.00;
		$pair = true;

		foreach ($postes as $row) {
			$subtotal = 0.00;
			if ($pair) {
				$rapport .= '<tr class = "pair">';
			} else {
				$rapport .= '<tr class = "impair">';
			}
			$pair = !$pair;
	
			$rapport .= '<td class = "poste">'.$txt[$row->getNom()].'</td><td class = "montant">';	
			foreach ($operations as $operation) {
				$moves = $mvMapper->fetchOp($operation->getId());
				foreach ($moves as $move) {
					if ($move->getPoste() == $row->getId()) $subtotal += $move->getValeur();
				}
			}
			$rapport .= number_format($subtotal, 2);
			$rapport .= '</td></tr>';
		
			$total_revenu += $subtotal;
		}
		
		$rapport .= '<tr>';
		$rapport .= '<th class = "poste">TOTAL DES REVENUS</th>';	
		$rapport .= '<th class = "montant">'.number_format($total_revenu, 2).'</th>';
		$rapport .= '</tr>';
			
		$rapport .= '</table></div>';
		
		$rapport .= '<div class = "section_br"><h2>Depenses</h2>';
		$postes = $posteMapper->fetchCategorie('DEPENSE');
		
		$rapport .= '<table class = "table_br">';
		
		$total_depenses = 0.00;
		$pair = true;
		
		foreach ($postes as $row) {
			$subtotal = 0.00;
			if ($pair) {
				$rapport .= '<tr class = "pair">';
			} else {
				$rapport .= '<tr class = "impair">';
			}
			$pair = !$pair;
			
			$rapport .= '<td class = "poste">'.$txt[$row->getNom()].'</td><td class = "montant">';	
			foreach ($operations as $operation) {
				$moves = $mvMapper->fetchOp($operation->getId());
				foreach ($moves as $move) {
					if ($move->getPoste() == $row->getId()) $subtotal += $move->getValeur();
				}
			}
			$rapport .= number_format($subtotal, 2);
			$rapport .= '</td></tr>';
			
			$total_depenses += $subtotal;
		}
		
		$rapport .= '<tr>';
		$rapport .= '<th class = "poste">TOTAL DES DEPENSES</th>';	
		$rapport .= '<th class = "montant">'.number_format($total_depenses, 2).'</th>';
		$rapport .= '</tr>';
		
		$rapport .= '</table></div>';
		
		$rapport .= '<div class = "section_br"><h2>Benefices</h2>';
		$benefices = $total_revenu - $total_depenses;
		$rapport .= '<table class = "total_categorie_br"><tr>';
		$rapport .= '<th class = "poste">TOTAL DES BENEFICES(PERTES)</th>';	
		$rapport .= '<th class = "montant">'.number_format($benefices, 2).'</th>';
		$rapport .= '</tr></table></div>';
		
		return $rapport;
	}

}