<?php

class Application_Model_OperationMapper
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
            $this->setDbTable('Application_Model_DbTable_Operation');
        }
        return $this->_dbTable;
    }
	
	public function save(Application_Model_Operation $operation)
    {
        $data = array(
            'date'			=>	$operation->getDate(),
			'description'	=>	$operation->getDescription(),
			'ref'			=>	$operation->getRef(),
			'user'	=> $this->_userID
        );
 
        if ($operation->getId() == NULL) {
            unset($data['id']);
            return $this->getDbTable()->insert($data);
        } else {
			$this->getDbTable()->update($data, array('id = ?' => $operation->getId()));
			return $operation->getId();
        }
    }
	
	public function find($id, Application_Model_Operation $operation)
    {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();
		if ($row->user != $this->_userID) return;		// prevent an unauthorized user to access someone else's datas
		
        $operation->setId($row->id)
                  ->setDate($row->date)
                  ->setDescription($row->description)
				  ->setRef($row->ref)
				  ->setUser($row->user);
    }
 
    public function fetchAll()
    {
        $select = $this->getDbTable()	->select()
														->order('date');
		//print $select->__toString();
		$stmt = $select->query();
		$resultSet = $stmt->fetchAll();
		
		$entries   = array();
        foreach ($resultSet as $row) {
			if ($row['user'] == $this->_userID) 		// prevent an unauthorized user to access someone else's datas
			{
				$entry = new Application_Model_Operation();
				$entry->setId($row['id'])
					  ->setDate($row['date'])
					  ->setDescription($row['description'])
					  ->setRef($row['ref'])
					  ->setUser($row['user']);
				$entries[] = $entry;
			}
        }
        return $entries;
    }

	public function fetchYear($annee)
    {
        $date_debut = $annee.'-01-01';
		$date_fin = $annee.'-12-31';
		
		$select = $this->getDbTable()	->select()
										->where('date >= ?', $date_debut)
										->where('date <= ?', $date_fin)
										->order('date');
		//print $select->__toString();
		$stmt = $select->query();
		$resultSet = $stmt->fetchAll();
		
		$entries   = array();
        foreach ($resultSet as $row) {
			if ($row['user'] == $this->_userID) 		// prevent an unauthorized user to access someone else's datas
			{
				$entry = new Application_Model_Operation();
				$entry->setId($row['id'])
					  ->setDate($row['date'])
					  ->setDescription($row['description'])
					  ->setRef($row['ref'])
					  ->setUser($row['user']);
				$entries[] = $entry;
			}
        }
        return $entries;
    }
	
	public function fetchMonth($annee, $mois)
    {
        $date_debut = $annee.'-'.$mois.'-01';
		$date_fin = $annee.'-'.$mois.'-31';
		
		$select = $this->getDbTable()	->select()
										->where('date >= ?', $date_debut)
										->where('date <= ?', $date_fin)
										->order('date');
		//print $select->__toString();
		$stmt = $select->query();
		$resultSet = $stmt->fetchAll();
		
		$entries   = array();
        foreach ($resultSet as $row) {
			if ($row['user'] == $this->_userID) 		// prevent an unauthorized user to access someone else's datas
			{
				$entry = new Application_Model_Operation();
				$entry->setId($row['id'])
					  ->setDate($row['date'])
					  ->setDescription($row['description'])
					  ->setRef($row['ref'])
					  ->setUser($row['user']);
				$entries[] = $entry;
			}
        }
        return $entries;
    }
	
	public function delete($id)
	{
		$where = 'id = '.$id;
		$this->getDbTable()->delete($where);	
	}
}

