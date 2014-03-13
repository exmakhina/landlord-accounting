<?php

class Zend_View_Helper_showResa extends Zend_View_Helper_Abstract 
{
	public function showResa($id)
    {
		$clientsMapper = new Application_Model_ClientsMapper();
		$client = new Application_Model_Clients();
		$clientsMapper->find($id, $client);
		
		$rapport = '<table class = "liste"><tr class = "titre_liste">';
		$rapport .= '<th class = "poste"><div class = "cell_ldc_s">Nom</div></th>';
		$rapport .= '<th class = "poste"><div class = "cell_ldc_l">Email</a></div></th>';
		$rapport .= '<th class = "poste"><div class = "cell_ldc_s">Téléphone</a></div></th>';
		$rapport .= '<th class = "poste"><div class = "cell_ldc_s">Pays</a></div></th>';
		$rapport .= '<th class = "poste"><div class = "cell_ldc_s">Check-in</a></div></th>';
		$rapport .= '<th class = "poste"><div class = "cell_ldc_s">Check-out</a></div></th>';
		$rapport .= '<th class = "poste"><div class = "cell_ldc_s">Appartement</a></div></th>';
		$rapport .= '<th class = "poste"><div class = "cell_ldc_s">statut</a></div></th>';
		$rapport .= '</tr>';
		
		$paysMapper = new Application_Model_PaysMapper();
		$pays = new Application_Model_Pays();
		
		$rapport .= '<tr class = "pair">';
		
		$rapport .= '<td>'.$client->getNom().'</td>';	
		$rapport .= '<td>'.$client->getEmail().'</td>';
		
		$rapport .= '<td>'.$client->getTel().'</td>';
		$paysMapper->find($client->getPays(), $pays);
		$rapport .= '<td>'.$pays->getFr().'</td>';
		
		$rapport .= '<td>'.$client->getCheckin().'</td>';
		$rapport .= '<td>'.$client->getCheckout().'</td>';
		$rapport .= '<td>'.$client->getAppt().'</td>';
		if ($client->getPaid())
			$rapport .= '<td class="paid">'.'payé'.'</td>';
		else
			$rapport .= '<td class="notpaid">non payé</td>';
		
		$rapport .= '</tr>';
		
		$rapport .= '</table>';
		
		return $rapport;
	}
}