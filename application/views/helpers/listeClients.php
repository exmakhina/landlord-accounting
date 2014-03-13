<?php

class Zend_View_Helper_ListeClients extends Zend_View_Helper_Abstract 
{
    public function listeClients()
    {
		$clientsMapper = new Application_Model_ClientsMapper();
		$clients = $clientsMapper->fetchAll();
		
		$rapport = '<table class = "liste"><tr class = "titre_liste">';
		$rapport .= '<th class = "poste"><div class = "cell_ldc_s">Nom</div></th>';
		$rapport .= '<th class = "poste"><div class = "cell_ldc_l">Email</div></th>';
		$rapport .= '<th class = "poste"><div class = "cell_ldc_l">Adresse</div></th>';
		$rapport .= '<th class = "poste"><div class = "cell_ldc_s">Pays</div></th>';
		$rapport .= '<th class = "poste"><div class = "cell_ldc_s">Telephone</div></th>';
		$rapport .= '<th class = "poste"><div class = "cell_ldc_s">Date</div></th>';
		$rapport .= '</tr>';
		
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
			
			$paysMapper->find($client->getPays(), $pays);
			$rapport .= '<td>'.$pays->getFr().'</td>';
			
			$rapport .= '<td>'.$client->getTel().'</td>';
			$rapport .= '<td>'.$client->getDate().'</td>';
			$rapport .= '</tr>';
		}
		
		$rapport .= '</table>';
		
		return $rapport;
	}

}