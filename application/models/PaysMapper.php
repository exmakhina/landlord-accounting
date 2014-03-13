<?php

class Application_Model_PaysMapper
{
	protected $_dbTable;
	protected $_dbadapter;
	
	public function setAdapter($dbadapter)
	{
		$this->_dbadapter = $dbadapter;
	} 
 
    public function setDbTable($dbTable)
    {
        if (is_string($dbTable)) {
            $dbTable = new $dbTable($this->_dbadapter);
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
            $this->setDbTable('Application_Model_DbTable_Pays');
        }
        return $this->_dbTable;
    }
	
	public function find($id, Application_Model_Pays $pays)
    {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();
        $pays	->setId($row->id)
				->setCode_pays($row->code_pays)
          		->setFr($row->fr)
				->setEn($row->en);
    }
 
    public function fetchAll()
    {
        $resultSet = $this->getDbTable()->fetchAll();
        $entries   = array();
        foreach ($resultSet as $row) {
            $entry = new Application_Model_Pays();
            $entry	->setId($row->id)
					->setCode_pays($row->code_pays)
          			->setFr($row->fr)
					->setEn($row->en);
            $entries[] = $entry;
        }
        return $entries;
    }

}

