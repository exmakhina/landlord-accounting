<?php

class Application_Model_UsersMapper
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
            $this->setDbTable('Application_Model_DbTable_Users');
        }
        return $this->_dbTable;
    }
	
	public function save(Application_Model_Users $user)
    {
        $data = array(
            'username'	=>	$user->getUsername(),
			'password'	=>	$user->getPassword(),
			'salt'	=>	$user->getSalt(),
			'role'	=> $user->getRole(),
			'date_created' => $user->getDateCreated(),
			'firstname'	=> $user->getFirstname(),
			'lastname'		=> $user->getLastname(),
			'email'		=> $user->getEmail(),
			'pays'		=> $user->getPays()
        );
 
        if (null === ($id = $user->getId())) {
            unset($data['id']);
            return $this->getDbTable()->insert($data);
        } else {
            $this->getDbTable()->update($data, array('id = ?' => $id));
			return $id;
        }
    }
	
	public function delete($username)
	{
		$where = 'username = '.$username;
		$this->getDbTable()->delete($where);	
	}
	
	public function getId($user)
	{
		$select = $this->getDbTable()	->select()
														->where('username = ?', $user);
		//print $select->__toString();
		$stmt = $select->query();
		$resultSet = $stmt->fetchAll();
		
		$id = NULL;

        foreach ($resultSet as $row) {
			$id = $row['id'];
		}	
		
		return $id;
	}

}

