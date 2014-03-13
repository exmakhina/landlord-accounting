<?php

class Application_Model_MouvementMapper
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
            $this->setDbTable('Application_Model_DbTable_Mouvement');
        }
        return $this->_dbTable;
    }
	
	public function save(Application_Model_Mouvement $mouvement)
    {
        $data = array(
            'valeur'	=>	$mouvement->getValeur(),
			'poste'		=>	$mouvement->getPoste(),
			'operation'		=>	$mouvement->getOperation(),
			'user'	=> $this->_userID
        );
 
        if (null === ($id = $mouvement->getId())) {
            unset($data['id']);
            return $this->getDbTable()->insert($data);
        } else {
            $this->getDbTable()->update($data, array('id = ?' => $id));
			return $id;
        }
    }
	
	public function find($id, Application_Model_Mouvement $mouvement)
    {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();
		if ($row->user != $this->_userID) return;		// prevent an unauthorized user to access someone else's datas
		
        $mouvement->setId($row->id)
                  ->setValeur($row->valeur)
                  ->setPoste($row->poste)
				  ->setOperation($row->operation)
				  ->setUser($row->user);
    }
 
    public function fetchAll()
    {
        $resultSet = $this->getDbTable()->fetchAll();
        $entries   = array();
        foreach ($resultSet as $row) {
			if ($row->user == $this->_userID)
			{
				$entry = new Application_Model_Mouvement();
				$entry->setId($row->id)
					  ->setValeur($row->valeur)
					  ->setPoste($row->poste)
					  ->setOperation($row->operation)
					  ->setUser($row->user);
				$entries[] = $entry;
			}
        }
        return $entries;
    }
	
	public function fetchOp($op)
    {		
		$select = $this->getDbTable()	->select()
										->where('operation = ?', $op);
		//print $select->__toString();
		$stmt = $select->query();
		$resultSet = $stmt->fetchAll();
		
		$entries   = array();
        foreach ($resultSet as $row) {
			if ($row['user'] == $this->_userID) 		// prevent an unauthorized user to access someone else's datas
			{
				$entry = new Application_Model_Mouvement();
				$entry->setId($row['id'])
					  ->setValeur($row['valeur'])
					  ->setPoste($row['poste'])
					  ->setOperation($row['operation'])
					  ->setUser($row['user']);
				$entries[] = $entry;
			}
        }
        return $entries;
    }
	
	public function fetchPoste($poste)
    {		
		$select = $this->getDbTable()	->select()
										->where('poste = ?', $poste);
		//print $select->__toString();
		$stmt = $select->query();
		$resultSet = $stmt->fetchAll();
		
		$entries   = array();
        foreach ($resultSet as $row) {
			if ($row['user'] == $this->_userID) 		// prevent an unauthorized user to access someone else's datas
			{
				$entry = new Application_Model_Mouvement();
				$entry->setId($row['id'])
					  ->setValeur($row['valeur'])
					  ->setPoste($row['poste'])
					  ->setOperation($row['operation'])
					  ->setUser($row['user']);
				$entries[] = $entry;
			}
        }
        return $entries;
    }

}

