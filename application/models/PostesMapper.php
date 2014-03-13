<?php

class Application_Model_PostesMapper
{
	protected $_dbTable;
	protected $_dbadapter = NULL;
	
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
            $this->setDbTable('Application_Model_DbTable_Postes');
        }
        return $this->_dbTable;
    }
	
	public function save(Application_Model_Postes $poste)
    {
        $data = array(
            'numero'		=>	$poste->getNumero(),
			'type'			=>	$poste->getType(),
			'nom'			=>	$poste->getNom(),
        );
 
        if (null === ($id = $poste->getId())) {
            unset($data['id']);
            $this->getDbTable()->insert($data);
        } else {
            $this->getDbTable()->update($data, array('id = ?' => $id));
        }
    }
	
	public function find($id, Application_Model_Postes $poste)
    {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();
        $poste->setId($row->id)
			  ->setNumero($row->numero)
			  ->setType($row->type)
			  ->setNom($row->nom);
    }
 
    public function fetchAll()
    {
        $select = $this->getDbTable()	->select()
										->order('numero');
		//print $select->__toString();
		$stmt = $select->query();
		$resultSet = $stmt->fetchAll();
		
		$entries   = array();
        foreach ($resultSet as $row) {
            $entry = new Application_Model_Postes();
            $entry->setId($row['id'])
                  ->setNumero($row['numero'])
                  ->setType($row['type'])
				  ->setNom($row['nom']);
            $entries[] = $entry;
        }
        return $entries;
    }
	
	public function fetchCategorie($categorie)
    {
        $select = $this->getDbTable()	->select()
										->where('type = ?', $categorie);
		//print $select->__toString();
		$stmt = $select->query();
		$resultSet = $stmt->fetchAll();
		
        $entries   = array();
        foreach ($resultSet as $row) {
            $entry = new Application_Model_Postes();
            $entry->setId($row['id'])
                  ->setNumero($row['numero'])
                  ->setType($row['type'])
				  ->setNom($row['nom']);
            $entries[] = $entry;
        }
        return $entries;
    }
	
	public function fetchRange($low, $high)
    {
        $select = $this->getDbTable()	->select()
										->where('numero >= ?', $low)
										->where('numero <= ?', $high)
										->order('numero');
		//print $select->__toString();
		$stmt = $select->query();
		$resultSet = $stmt->fetchAll();
		
        $entries   = array();
        foreach ($resultSet as $row) {
            $entry = new Application_Model_Postes();
            $entry->setId($row['id'])
                  ->setNumero($row['numero'])
                  ->setType($row['type'])
				  ->setNom($row['nom']);
            $entries[] = $entry;
        }
        return $entries;
    }
}

