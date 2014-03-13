<?php

class Application_Model_DbTable_Postes extends Zend_Db_Table_Abstract
{

    protected $_name = 'postes_template';

	public function __construct($config = array()) 
	{
		parent::__construct($config = array());
		
		// if no one is logged in postesMapper will map postes_template, otherwise, it will map postes_($user)
		$auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity()) {
            $user = $auth->getIdentity()->username;
			$this->_name	 = 'postes_'.$user;
        } 
	}

}

