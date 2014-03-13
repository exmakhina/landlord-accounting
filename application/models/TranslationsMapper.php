<?php

class Application_Model_TranslationsMapper
{
	protected $_dbTable;
 
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
            $this->setDbTable('Application_Model_DbTable_Translations');
        }
        return $this->_dbTable;
    }
	
	public function save(Application_Model_Translations $translation)
    {
        $data = array(
            'label'	=>	$translation->getLabel(),
			'en'	=>	$translation->getEn(),
			'fr'	=>	$translation->getFr()
        );
 
        if (null === ($id = $translation->getId())) {
            unset($data['id']);
            return $this->getDbTable()->insert($data);
        } else {
            $this->getDbTable()->update($data, array('id = ?' => $id));
			return $id;
        }
    }
	
	public function find($id, Application_Model_Translations $translation)
    {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();
        $translation->setId($row->id)
          			->setLabel($row->label)
              		->setEn($row->en)
					->setFr($row->fr);
    }
 
    public function fetchAll()
    {
        $resultSet = $this->getDbTable()->fetchAll();
        $entries   = array();
        foreach ($resultSet as $row) {
            $entry = new Application_Model_Translations();
            $entry->setId($row->id)
                  ->setLabel($row->label)
                  ->setEn($row->en)
				  ->setFr($row->fr);
            $entries[] = $entry;
        }
        return $entries;
    }

	public function delete($id)
	{
		$where = 'id = '.$id;
		$this->getDbTable()->delete($where);	
	}
	
	public function getLanguage($lang)
	{
		$select = $this->getDbTable()	->select()
										->from(	array('translations'),
												array('label', $lang));
		//print $select->__toString();
		$stmt = $select->query();
		$resultSet = $stmt->fetchAll();
		
		$entries   = array();
        foreach ($resultSet as $row) {
            $i = $row['label'];
			$value = $row[$lang];
			$entries[$i] = $value;
        }
        return $entries;	
	}

}

