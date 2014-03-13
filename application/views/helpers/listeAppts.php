<?php

class Zend_View_Helper_ListeAppts extends Zend_View_Helper_Abstract 
{
    public function listeAppts()
    {
		$apptsMapper = new Application_Model_ApptsMapper();
		$appts = $apptsMapper->fetchAll();
		
		$rapport = '<table class = "liste"><tr class = "titre_liste">';
		$rapport .= '<th class = "poste"><div class = "cell_ldc_s">Numero</th>';
		$rapport .= '<th class = "poste"><div class = "cell_ldc_l">Adresse complete</th>';
		$rapport .= '<th></th><th></th>';
		$rapport .= '</tr>';
		
		$pair = true;
		
		foreach ($appts as $appt) {
			$actionmodify = $this->view->url(array('controller'=>'admin', 'action'=>'modifyappt', 'id' => $appt->getId()));
			$actiondelete = $this->view->url(array('controller'=>'admin', 'action'=>'deleteappt', 'id' => $appt->getId()));
			
			if ($pair) {
				$rapport .= '<tr class = "pair">';
			} else {
				$rapport .= '<tr class = "impair">';
			}
			$pair = !$pair;
			
			$rapport .= '<td>'.$appt->getNumero().'</td>';	
			$rapport .= '<td>'.$appt->getAdresse().'</td>';
			
			$rapport .= '<td><div class = "edit"><a href="'.$actionmodify.'"><img src="/img/button_edit_grey.gif" /></a></div></td>';
			$rapport .= '<td><a href="'.$actiondelete.'"><div class = "delete"></div></a></td>';
			
			$rapport .= '</tr>';
		}
		
		$rapport .= '</table>';
		$rapport .= '<a class="button add" href="'.$this->view->url(array('controller'=>'admin', 'action'=>'newappt')).'">Nouveau</a>';
		
		return $rapport;
	}

}