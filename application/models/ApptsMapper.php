<?php

class Application_Model_ApptsMapper
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
            $this->setDbTable('Application_Model_DbTable_Appts');
        }
        return $this->_dbTable;
    }
	
	public function save(Application_Model_Appts $appt)
    {
        $id = $appt->getId();
		$data = array(
			'id'	=> $id,
			'adresse'	=>	$appt->getAdresse(),
			'user'	=> $this->_userID
        );
		if (count($this->getDbTable()->find($id)) == 0)			// new entry ? 
 			return $this->getDbTable()->insert($data);
		else {
			unset($data['id']);												// existing entry ?
			$this->getDbTable()->update($data, array('id = ?' => $id));
			return $id;	
		}
    }
	
	public function find($id, Application_Model_Appts $appt)
    {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();
		if ($row->user != $this->_userID) return;		// prevent an unauthorized user to access someone else's datas
		
        $appt->setId($row->id)
          		->setAdresse($row->adresse)
              	->setUser($row->user);
    }
 
    public function fetchAll()
    {
        $resultSet = $this->getDbTable()->fetchAll();
        $entries   = array();
        foreach ($resultSet as $row) {
			if ($row->user == $this->_userID)
			{			
				$entry = new Application_Model_Appts();
				$entry->setId($row->id)
					  ->setAdresse($row->adresse)
					  ->setUser($row->user);
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

