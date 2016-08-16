<?php

Class AbstractModel
{
	
	protected $_dbuser = "root";
	protected $_dbpass = "";
	protected $_dbname = "applicant_project";
	protected $_dbhost = "localhost";
	protected $db;
	
	public $id, $name, $email;
	
	function __construct()
	{
		$this->db = new PDO("mysql:host=$this->_dbhost;dbname=$this->_dbname;charset=utf8mb4", $this->_dbuser, $this->_dbpass);	
		
	}
	
    public function save()
	{
		$params = array($this->name, $this->email);
		if (isset($this->id))
		{
			$stmt = $this->db->prepare("UPDATE contacts SET name=?, email=? WHERE id = " . $this->id);
			$stmt->execute($params);
		}
		else 
		{
			$stmt = $this->db->prepare("INSERT INTO contacts (name, email) VALUES (?,?)");
			$stmt->execute($params);
			$this->id = $this->db->lastInsertId();			
		}
		
	}
	
	
    public function load($id)
	{
		$stmt = $this->db->prepare('SELECT * FROM contacts WHERE id=?');
		$stmt->execute([$id]);
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		$this->setData($result);
		return($this);
	}
	
    public function delete($id=false)
	{
		$stmt = $this->db->prepare('DELETE FROM contacts WHERE id=?');
		$stmt->execute([$this->id]);		
	}

    public function getData($key=false)
	{
		if (!$key)
		{
			foreach (array('id','name','email') as $val)
			{
				echo "$val => " . $this->{$val};
				if ($val != 'email')
				  echo ",<br />";
			}
		}
		else echo "$key => " . $this->{$key};
	}

    public function setData($arr, $value=false)
	{	
		if ($value)
			$this->{$arr} = $value;
		else 
			foreach ($arr as $key => $val)
				$this->{$key} = $val;
				
		return $this;
	}

}