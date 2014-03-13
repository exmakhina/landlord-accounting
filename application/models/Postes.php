<?php

class Application_Model_Postes
{
	protected $_id;
	protected $_numero;
	protected $_type;
	protected $_nom;
	
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
            throw new Exception('Invalid postes property');
        }
        $this->$method($value);
    }
 
    public function __get($name)
    {
        $method = 'get' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid postes property');
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
	
	public function setNumero($numero)
    {
        $this->_numero = (int) $numero;
        return $this;
    }
 
    public function getNumero()
    {
        return $this->_numero;
    }
	
	public function setType($type)
    {
        $this->_type = (string) $type;
        return $this;
    }
 
    public function getType()
    {
        return $this->_type;
    }

	public function setNom($nom)
    {
        $this->_nom = (string) $nom;
        return $this;
    }
 
    public function getNom()
    {
        return $this->_nom;
    }
}

