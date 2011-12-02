<?php
/************************************************************************
@file index.php 
Copyright Ilke Benson, A.I.E. 2009
@author Miguel Jaque Barbero (mjaque@ilkebenson.com)
Revisado por: $Author$ 
Revisión: $Rev$
@date $Date$
Diseñado por: Miguel Jaque Barbero
Fecha de Diseño: 28/1/2009
**************************************************************************/

//phpinfo();exit;					//Por si queremos comprobar algo.
header("Content-type: text/html; charset=utf-8");	//Trabajamos en utf-8
require('serv/config.php'); 				//Cargamos la configuración
$config = Config::verInstancia();
ini_set('date.timezone', $config->ver('general', 'zonaHoraria'));

//Respuesta a la petición Ajax de test
if ((MODO_DESARROLLO) AND ($_REQUEST['PaxAccion'] == 'test')){
	echo true;
	exit;
}

//Comprobación de condiciones de ejecución

//Inicializamos el Registro
require($config->ver('dir','serv')."autoload.php");
Registro::anotar("Inicio");

//Iniciamos la sesión
//La sesión se debe iniciar después de cargar autoload. 
//De lo contrario, PHP no será capaz de instanciar los objetos que encuentre en $_SESSION
session_name($config->ver('general','nombre'));
session_start();
//Depurador::mostrar($_SESSION);
Registro::anotar($_REQUEST['PaxAccion']);
//Lanzamos la acción
$lanzador = new Lanzador($config->ver('dir','iu'),$config->ver('general','claseIuDefecto'), $config->ver('general','metodoIuDefecto'));
set_error_handler(array($lanzador,'gestionarError'));

try{
	$lanzador->ejecutar();
}
catch (Exception $e){//Control de errores
	//... Aquí el control de errores.
	Registro::anotar($e);
	if ($_REQUEST['PaxFormatoRespuesta'] == 'xml'){	// Hemos recibido una petición AJAX, enviamos la excepción por AJAX.
		if (isset($_SESSION['iuActivo']))
			$_SESSION['iuActivo']->responderPorAjax($e);	// Enviamos la excepción por AJAX
		else throw($e);
	}
	else{	// La petición no es AJAX, enviamos la excepción por la vía normal.
		if (class_exists("ExcepcionIu")){
			$excepcion = new ExcepcionIu($e);	// Mostramos un interfaz de excepción.
			$excepcion->ver();
		}
		else throw($e);
	}
}
?>
