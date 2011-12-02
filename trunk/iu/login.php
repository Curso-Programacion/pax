<?php
/************************************************************************
@file login.php 
Copyright Ilke Benson, A.I.E. 2009
@author Miguel Jaque Barbero (mjaque@ilkebenson.com)
@author Chema Viera Godoy (chema@ilkebenson.com)
Revisado por: $Author$ 
Revisión: $Rev$
@date $Date$
Diseñado por: Miguel Jaque Barbero
Fecha de Diseño: 21/4/2009
**************************************************************************/

class Login extends PaxDemoIu{
/**-	Interfaz para la Identificación de Usuarios.
**/
	protected $interfaz = 'xhtml/login.xml';	// Archivo con el interfaz de la clase

	public function autenticar(){
	/**-	Autentica a un usuario por su nombre y clave.
		Espera por GET los parámetros nombre y clave.
	**/
//Depurador::mostrar($_REQUEST);
		$parametro = new Parametro('nombre',false, INPUT_GET);
		$nombre = $parametro->verValor();
		$parametro = new Parametro('clave',false,INPUT_GET);
		$clave = $parametro->verValor();
//Depurador::mostrar($_REQUEST);
		try{
			$respuesta = $this->controlador->loginAutenticar($nombre,$clave);
		}
		catch(Exception $e){
			$iu = new ExcepcionIu($e);
			$iu->ver();
		}
	}
	public function procesarInterfaz($DOMInterfaz){
	/**-	Método de plantilla para procesar el interfaz antes de presentarlo.
		@param &$DOMInterfaz DOMDocument del interfaz.
	**/
		//Añadimos el script para md5
		$this->ponerScript($DOMInterfaz, 'iu/js/md5.js');

		//Ponemos el valor del uniqueID para encriptar la clave
		$_SESSION['uniqid'] = uniqid('PAX_',true);
		$this->ponerValorCampo($DOMInterfaz, 'uniqid', $_SESSION['uniqid']);
		

		//Quitamos los campos que no deben estar
		$this->quitarNodo($DOMInterfaz,'cabecera'); 
		$this->quitarNodo($DOMInterfaz,'caminoMigas'); 
		$this->quitarNodo($DOMInterfaz,'menuOperacion'); 
	}
	public function salir(){
	/**-	Cierra la sesión de un usuario
	**/
		$this->controlador->desconectar();
		$this->ver();	//Volvemos al login
	}
}
?>
