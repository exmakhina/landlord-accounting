<?php

class Zend_View_Helper_ListeClients extends Zend_View_Helper_Abstract 
{
    public function listeClients()
    {
		$rapport = '<table class = "liste"><tr class = "titre_liste">';
		$rapport .= '<th class = "poste"><div class = "cell_ldc_s"><a href="'.$this->view->url(array('controller'=>'clients', 'action'=>'listeclients', 'orderby'=>'nom')).'">Nom</a></div></th>';
		$rapport .= '<th class = "poste"><div class = "cell_ldc_l"><a href="'.$this->view->url(array('controller'=>'clients', 'action'=>'listeclients', 'orderby'=>'email')).'">Email</a></div></th>';
		$rapport .= '<th class = "poste"><div class = "cell_ldc_l"><a href="'.$this->view->url(array('controller'=>'clients', 'action'=>'listeclients', 'orderby'=>'adresse')).'">Adresse</a></div></th>';
		$rapport .= '<th class = "poste"><div class = "cell_ldc_s"><a href="'.$this->view->url(array('controller'=>'clients', 'action'=>'listeclients', 'orderby'=>'tel')).'">Téléphone</a></div></th>';
		$rapport .= '<th class = "poste"><div class = "cell_ldc_s"><a href="'.$this->view->url(array('controller'=>'clients', 'action'=>'listeclients', 'orderby'=>'pays')).'">Pays</a></div></th>';
		$rapport .= '</tr>';
		
		$clientsMapper = new Application_Model_ClientsMapper();
		$clients = $clientsMapper->fetchAll($this->view->order);
		
		$paysMapper = new Application_Model_PaysMapper();
		$pays = new Application_Model_Pays();
		
		$pair = true;
		
		foreach ($clients as $client) {
			if ($pair) {
				$rapport .= '<tr class = "pair">';
			} else {
				$rapport .= '<tr class = "impair">';
			}
			$pair = !$pair;
			
			$rapport .= '<td>'.$client->getNom().'</td>';	
			$rapport .= '<td>'.$client->getEmail().'</td>';
			$rapport .= '<td>'.$client->getAdresse().'</td>';
			
			$rapport .= '<td>'.$client->getTel().'</td>';
			$paysMapper->find($client->getPays(), $pays);
			$rapport .= '<td>'.$pays->getFr().'</td>';
			
			$rapport .= '</tr>';
		}
		
		$rapport .= '</table>';
		
		return $rapport;
	}

}