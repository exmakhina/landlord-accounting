<?php

class Zend_View_Helper_NewRegistrationConf extends Zend_View_Helper_Abstract 
{
    public function newRegistrationConf()
    {
		// Have to do this construct due to how items are stored in session
		// namespaces

		$confirmation = '';
		foreach($this->view->info as $info) {
			foreach ($info as $form=>$data) {
				$confirmation .= '<h4>'.ucfirst($form).'</h4>';
				foreach ($data as $key=>$value) {
					$confirmation .= '<dt>'.ucfirst($key).'</dt>';
					if (is_array($value))
						foreach ($value as $label=>$val) $confirmation .= '<dd>'.$val.'</dd>';
					else
						$confirmation .= '<dd>'.$this->view->escape($value).'</dd>'; 
				}	
			}	
		}
		$confirmation .= '<a class="button save" href="'.$this->view->url(array('controller'=>'registration', 'action'=>'save')).'">Enregistrer les informations</a>';
		return $confirmation;
	}
}
