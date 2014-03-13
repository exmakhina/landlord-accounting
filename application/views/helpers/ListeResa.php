<?php

class Zend_View_Helper_ListeResa extends Zend_View_Helper_Abstract 
{
    public function listeResa()
    {
		$rapport = '<table class = "liste"><tr class = "titre_liste">';
		$rapport .= '<th></th>';
		$rapport .= '<th class = "poste"><div class = "cell_ldc_s"><a href="'.$this->view->url(array('controller'=>'clients', 'action'=>'liste', 'orderby'=>'nom')).'">Nom</a></div></th>';
		$rapport .= '<th class = "poste"><div class = "cell_ldc_l"><a href="'.$this->view->url(array('controller'=>'clients', 'action'=>'liste', 'orderby'=>'email')).'">Email</a></div></th>';
		$rapport .= '<th class = "poste"><div class = "cell_ldc_s"><a href="'.$this->view->url(array('controller'=>'clients', 'action'=>'liste', 'orderby'=>'tel')).'">Téléphone</a></div></th>';
		$rapport .= '<th class = "poste"><div class = "cell_ldc_s"><a href="'.$this->view->url(array('controller'=>'clients', 'action'=>'liste', 'orderby'=>'pays')).'">Pays</a></div></th>';
		$rapport .= '<th class = "poste"><div class = "cell_ldc_s"><a href="'.$this->view->url(array('controller'=>'clients', 'action'=>'liste', 'orderby'=>'checkin')).'">Check-in</a></div></th>';
		$rapport .= '<th class = "poste"><div class = "cell_ldc_s"><a href="'.$this->view->url(array('controller'=>'clients', 'action'=>'liste', 'orderby'=>'checkout')).'">Check-out</a></div></th>';
		$rapport .= '<th class = "poste"><div class = "cell_ldc_s"><a href="'.$this->view->url(array('controller'=>'clients', 'action'=>'liste', 'orderby'=>'appt')).'">Appartement</a></div></th>';
		$rapport .= '<th class = "poste"><div class = "cell_ldc_s"><a href="'.$this->view->url(array('controller'=>'clients', 'action'=>'liste', 'orderby'=>'px_accpte')).'">Accompte</a></div></th>';
		$rapport .= '<th class = "poste"><div class = "cell_ldc_s"><a href="'.$this->view->url(array('controller'=>'clients', 'action'=>'liste', 'orderby'=>'paid')).'">statut</a></div></th>';
		$rapport .= '</tr>';
		
		$clientsMapper = new Application_Model_ClientsMapper();
		$clients = $clientsMapper->fetchAll($this->view->order);
		
		$paysMapper = new Application_Model_PaysMapper();
		$pays = new Application_Model_Pays();
		
		$pair = true;
		
		foreach ($clients as $client) {
			$actiondelete = $this->view->url(array('controller'=>'clients', 'action'=>'confdelete', 'id' => $client->getId()));
			if ($pair) {
				$rapport .= '<tr class = "pair">';
			} else {
				$rapport .= '<tr class = "impair">';
			}
			$pair = !$pair;
			
			$rapport .= '<td><a href="'.$actiondelete.'"><div class = "delete"></div></a></td>';
			$rapport .= '<td>'.$client->getNom().'</td>';	
			$rapport .= '<td>'.$client->getEmail().'</td>';
			
			$rapport .= '<td>'.$client->getTel().'</td>';
			$paysMapper->find($client->getPays(), $pays);
			$rapport .= '<td>'.$pays->getFr().'</td>';
			
			$rapport .= '<td>'.$client->getCheckin().'</td>';
			$rapport .= '<td>'.$client->getCheckout().'</td>';
			$rapport .= '<td>'.$client->getAppt().'</td>';
			$rapport .= '<td>'.number_format($client->getPx_accpte()).'</td>';
			if ($client->getPaid())
				$rapport .= '<td class="paid">'.'payé'.'</td>';
			else
				$rapport .= '<td class="notpaid"><a href="'.$this->view->url(array('controller'=>'clients', 'action'=>'pay', 'client'=>$client->getId())).'" class="notpaid">non payé</a></td>';
			$rapport .= '</tr>';
		}
		
		$rapport .= '</table>';
		
		return $rapport;
	}

}