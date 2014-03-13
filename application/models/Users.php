<?php

class Application_Model_Users
{
	protected $_id;
	protected $_username;
	protected $_password;
	protected $_salt;
	protected $_role;
	protected $_date_created;
	protected $_firstname;
	protected $_lastname;
	protected $_email;
	protected $_pays;
	
	public function __construct(array $options = null)
    {
        if (is_array($options)) {
            $this->setOptions($options);
        }
    }
 
    public function __set($name, $value)
    {
        $method = 'set' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid Users property');
        }
        $this->$method($value);
    }
 
    public function __get($name)
    {
        $method = 'get' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid Users property');
        }
        return $this->$method();
	}
	
	public function setOptions(array $options)
    {
        $methods = get_class_methods($this);
        foreach ($options as $key => $value) {
            $method = 'set' . ucfirst($key);
            if (in_array($method, $methods)) {
                $this->$method($value);
            }
        }
        return $this;
    }
	
	public function setId($id)
    {
        $this->_id = (int) $id;
        return $this;
    }
 
    public function getId()
    {
        return $this->_id;
    }

	public function setUsername($username)
    {
        $this->_username = $username;
        return $this;
    }
 
    public function getUsername()
    {
        return $this->_username;
    }
	
	public function setPassword($password)
    {
        $this->_password = $password;
        return $this;
    }
 
    public function getPassword()
    {
        return $this->_password;
    }
	
	public function setSalt($salt)
    {
        $this->_salt = $salt;
        return $this;
    }
 
    public function getSalt()
    {
        return $this->_salt;
    }
	
	public function setRole($role)
    {
        $this->_role = $role;
        return $this;
    }
 
    public function getRole()
    {
        return $this->_role;
    }
	
	public function setDateCreated($date_created)
    {
        $this->_date_created = $date_created;
        return $this;
    }
 
    public function getDateCreated()
    {
        return $this->_date_created;
    }
	
	public function setFirstname($firstname)
    {
        $this->_firstname = $firstname;
        return $this;
    }
 
    public function getFirstname()
    {
        return $this->_firstname;
    }
	
	public function setLastname($lastname)
    {
        $this->_lastname = $lastname;
        return $this;
    }
 
    public function getLastname()
    {
        return $this->_lastname;
    }
	
	public function setEmail($email)
    {
        $this->_email = $email;
        return $this;
    }
 
    public function getEmail()
    {
        return $this->_email;
    }
	
	public function setPays($pays)
    {
        $this->_pays = $pays;
        return $this;
    }
 
    public function getPays()
    {
        return $this->_pays;
    }
	
}

