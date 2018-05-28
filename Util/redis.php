<?php
class RS{
	private static $host='bbs.dbmaster.golo365.com';
	private static $port='6381';
	private  $handler = null;
    private  $resource = null;
	 function __construct()
	{
		$this->handler=new \Redis();
		$this->handler->connect(self::$host,self::$port);
		$this->handler->auth('golo*12345');  
	}

	public  function setValue($key,$value)
	{
		$this->handler->set($key,$value);
	}
	public  function getValue($key)
	{
		return $this->handler->get($key);
	}
}
