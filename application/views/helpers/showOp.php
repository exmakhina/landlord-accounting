<?php

class Zend_View_Helper_showOp extends Zend_View_Helper_Abstract 
{
	public function showOp($id)
    {
		$session = Zend_Registry::get('session');
		$language = $session->language;
		$translationsMapper = new Application_Model_TranslationsMapper();
		$txt = $translationsMapper->getLanguage($language);
		
		$posteMapper = new Application_Model_PostesMapper();
		$postes = $posteMapper->fetchAll();
		
		$opMapper = new Application_Model_OperationMapper();
		$op = new Application_Model_Operation();
		$opMapper->find($id, $op);
		
		$mvMapper = new Application_Model_MouvementMapper();
		
		$rapport = '<table class = "liste"><tr class = "titre_liste">';
		$rapport .= '<th class = "poste"><div class = "cell_ldc_s">Date</div></th>';
		$rapport .= '<th class = "poste"><div class = "cell_ldc_l">Description</div></th>';
		$rapport .= '<th class = "poste"><div class = "cell_ldc_s">Reference</div></th>';
		foreach ($postes as $row) {
			$rapport .= '<th class = "poste"><div class = "cell_ldc_s">'.$txt[$row->getNom()].'</div></th>';	
		}
		$rapport .= '<tr class = "pair">';
	
		$rapport .= '<td>'.$op->getDate().'</td>';	
		$rapport .= '<td>'.$op->getDescription().'</td>';
		$rapport .= '<td>'.$op->getRef().'</td>';
	
		$mvs = $mvMapper->fetchOp($op->getId());
		
		foreach ($postes as $row) {
			$rapport .= '<td class = "montant">';
			foreach ($mvs as $mv) {
				if ($mv->getPoste() == $row->getId()) {
					$rapport .= $mv->getValeur();	
				}	
			} 
			$rapport .= '</td>';
		}
		$rapport .= '</tr>';
		
		$rapport .= '</table>';
		
		return $rapport;
	}
}