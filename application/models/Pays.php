<?php

class Application_Model_Pays
{
	protected $_id;
	protected $_code_pays;
	protected $_fr;
	protected $_en;
	
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
            throw new Exception('Invalid Pays property');
        }
        $this->$method($value);
    }
 
    public function __get($name)
    {
        $method = 'get' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid Pays property');
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
	
	public function setCode_pays($code_pays)
	{
		$this->_code_pays = $code_pays;
		return $this;
	}
	
	public function getCode_pays()
	{
		return $this->_code_pays;
	}
	
	public function setFr($fr)
	{
		$this->_fr = $fr;
		return $this;
	}
	
	public function getFr()
	{
		return $this->_fr;	
	}
	
	public function setEn($en)
	{
		$this->_en = $en;
		return $this;
	}
	
	public function getEn()
	{
		return $this->_en;	
	}

}

