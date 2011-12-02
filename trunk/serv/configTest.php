<?php
require_once('serv/config.php'); 		//Cargamos la configuración
$config = Config::verInstancia();
require_once($config->ver('dir','serv').'PHPUnit/Framework.php');
require_once($config->ver('dir','serv')."autoload.php");

class ConfigTest extends PHPUnit_Framework_TestCase{
	protected $fixture;

	public function testConfigVerInstancia(){
		$config1 = Config::verInstancia();
		$config2 = Config::verInstancia();
		$this->assertEquals($config1, $config2);	//Es un singleton
	}
	public function testConfigVer(){
		$config = Config::verInstancia();
		$this->assertEquals($config->ver('general','nombre'), 'paxDemo');
	}
}
?>
