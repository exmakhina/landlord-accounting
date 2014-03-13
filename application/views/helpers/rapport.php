<?php

class Zend_View_Helper_Rapport extends Zend_View_Helper_Abstract 
{
    public function rapport($annee, $mois)
    {
		$posteMapper = new Application_Model_PosteMapper();
		$postes = $posteMapper->fetchAll();
		
		$opMapper = new Application_Model_OperationMapper();
		if (($mois == NULL) && ($annee == NULL)) $operations = $opMapper->fetchAll();
		if (($mois == NULL) && ($annee != NULL)) $operations = $opMapper->fetchYear($annee);
		if (($mois != NULL) && ($annee != NULL)) $operations = $opMapper->fetchMonth($annee, $mois);
		
		$mvMapper = new Application_Model_MouvementMapper();
		
		$rapport = '<table class = "liste"><tr class = "titre_liste">';
		$rapport .= '<th></th>';
		$rapport .= '<th class = "poste"><div class = "cell_ldc_s">Date</div></th>';
		$rapport .= '<th class = "poste"><div class = "cell_ldc_l">Description</div></th>';
		$rapport .= '<th class = "poste"><div class = "cell_ldc_s">Reference</div></th>';
		foreach ($postes as $row) {
			$rapport .= '<th class = "poste"><div class = "cell_ldc_s">'.$row->getNom().'</div></th>';	
		}
		$rapport .= '</tr>';
		
		$pair = true;
		
		foreach ($operations as $op) {
			$actiondelete = $this->view->url(array('controller'=>'rapport', 'action'=>'confdelete', 'id' => $op->getId()));
			if ($pair) {
				$rapport .= '<tr class = "pair">';
			} else {
				$rapport .= '<tr class = "impair">';
			}
			$pair = !$pair;
			
			$rapport .= '<td><a href="'.$actiondelete.'"><div class = "delete"></div></a></td>';
			$rapport .= '<td>'.$op->getDate().'</td>';	
			$rapport .= '<td>'.$op->getDescription().'</td>';
			$rapport .= '<td>'.$op->getRef().'</td>';
		
			$mvs = $mvMapper->fetchOp($op->getId());
			
			foreach ($postes as $row) {
				$rapport .= '<td class = "montant">';
				foreach ($mvs as $mv) {
					if ($mv->getPoste() == $row->getId()) {
						$rapport .= number_format($mv->getValeur(), 2);		// affichage xx,xxx,xxx.xx
					}	
				} 
				$rapport .= '</td>';
			}
			$rapport .= '</tr>';
		}
		
		$rapport .= '</table>';
		
		return $rapport;
	}

}