<?php

class Zend_View_Helper_MenuBar extends Zend_View_Helper_Abstract 
{
    public function menuBar ()
    {
		$operation = $this->view->url(array('controller'=>'operation', 'action'=>'index'));
		if ($this->view->courant == 'operation') {
        	$operation_tab = '<li class="current">';
        } else {
        	$operation_tab = '<li>';
        }
		$operation_tab .= '<a href="'.$operation.'">Dépenses</a></li>';
        
        $rapport = $this->view->url(array('controller'=>'rapport', 'action'=>'index'));
		if ($this->view->courant == 'rapport') {
			$rapport_tab = '<li class="current">';
        } else {
        	$rapport_tab = '<li>';
        }
		$rapport_tab .= '<a href="'.$rapport.'">Rapports</a></li>';
		
		$clients = $this->view->url(array('controller'=>'clients', 'action'=>'index'));
		if ($this->view->courant == 'clients') {
        	$clients_tab = '<li class="current">';
        } else {
        	$clients_tab = '<li>';
        }
		$clients_tab .= '<a href="'.$clients.'">Réservations</a></li>';
		
		$admin = $this->view->url(array('controller'=>'admin', 'action'=>'index'));
		if ($this->view->courant == 'admin') {
        	$admin_tab = '<li class="current">';
        } else {
        	$admin_tab = '<li>';
        }
		$admin_tab .= '<a href="'.$admin.'">Administration</a></li>';
		
		$menu = $operation_tab.$rapport_tab.$clients_tab.$admin_tab;
		
		return $menu;
    }
}