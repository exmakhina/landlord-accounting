<?php

class Application_Model_SitesMapper
{
	protected $_dbTable;
	protected $_userID = NULL;
	
	public function __construct() {
		// if no one is logged in postesMapper will map postes_template, otherwise, it will map postes_($user)
		$auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity()) {
            $user = $auth->getIdentity()->username;
			$userMapper = new Application_Model_UsersMapper();
			$this->_userID	 = $userMapper->getId($user);
        } 	
	}
 
    public function setDbTable($dbTable)
    {
        if (is_string($dbTable)) {
            $dbTable = new $dbTable();
        }
        if (!$dbTable instanceof Zend_Db_Table_Abstract) {
            throw new Exception('Invalid table data gateway provided');
        }
        $this->_dbTable = $dbTable;
        return $this;
    }
 
    public function getDbTable()
    {
        if (null === $this->_dbTable) {
            $this->setDbTable('Application_Model_DbTable_Sites');
        }
        return $this->_dbTable;
    }
	
	public function save(Application_Model_Sites $site)
    {
        $data = array(
			'nom'	=>	$site->getNom(),
			'lien'	=>	$site->getLien(),
			'actif'	=>	$site->getActif(),
			'user'	=> $this->_userID
        );
 
        if ($site->getId() == NULL) {
            unset($data['id']);
            return $this->getDbTable()->insert($data);
        } else {
			$this->getDbTable()->update($data, array('id = ?' => $site->getId()));
			return $site->getId();
        }
    }
	
	public function find($id, Application_Model_Sites $site)
    {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();
		if ($row->user != $this->_userID) return;		// prevent an unauthorized user to access someone else's datas
		
        $site	->setId($row->id)
				->setNom($row->nom)
				->setLien($row->lien)
				->setActif($row->actif)
				->setUser($row->user);
    }
 
    public function fetchAll()
    {
        $resultSet = $this->getDbTable()->fetchAll();
        $entries   = array();
        foreach ($resultSet as $row) {
            if ($row->user == $this->_userID)		// prevent an unauthorized user to access someone else's datas
			{
				$entry = new Application_Model_Sites();
				$entry	->setId($row->id)
						->setNom($row->nom)
						->setLien($row->lien)
						->setActif($row->actif)
						->setUser($row->user);
				$entries[] = $entry;
			}
        }
        return $entries;
    }

}

