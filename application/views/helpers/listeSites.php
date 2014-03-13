<?php

class Zend_View_Helper_ListeSites extends Zend_View_Helper_Abstract 
{
    public function listeSites()
    {
		$sitesMapper = new Application_Model_SitesMapper();
		$sites = $sitesMapper->fetchAll();
		
		$rapport = '<table class = "liste"><tr class = "titre_liste">';
		$rapport .= '<th class = "poste"><div class = "cell_ldc_l">Nom</div></th>';
		$rapport .= '<th class = "poste"><div class = "cell_ldc_l">Lien</div></th>';
		$rapport .= '<th class = "poste"><div class = "cell_ldc_s">En activite</div></th>';
		$rapport .= '<th class = "poste"></th>';
		$rapport .= '</tr>';
		
		$pair = true;
		
		foreach ($sites as $site) {
			$actionmodify = $this->view->url(array('controller'=>'admin', 'action'=>'modifysite', 'id' => $site->getId()));
			
			if ($pair) {
				$rapport .= '<tr class = "pair">';
			} else {
				$rapport .= '<tr class = "impair">';
			}
			$pair = !$pair;
			
			$rapport .= '<td>'.$site->getNom().'</td>';	
			$rapport .= '<td>'.$site->getLien().'</td>';
			if ($site->getActif())
			{
				$rapport .= '<td>Actif</td>';
			}
			else
			{
				$rapport .= '<td>Inactif</td>';
			}
			
			$rapport .= '<td><div class = "edit"><a href="'.$actionmodify.'"><img src="/img/button_edit_grey.gif" /></a></div></td>';
			$rapport .= '</tr>';
		}
		
		$rapport .= '</table>';
		$rapport .= '<a class="button add" href="'.$this->view->url(array('controller'=>'admin', 'action'=>'newsite')).'">Nouveau</a>';
		
		return $rapport;
	}

}