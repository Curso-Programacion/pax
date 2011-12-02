<?php
/************************************************************************
@file menuPrincipal.php 
Copyright Ilke Benson, A.I.E. 2009
@author Miguel Jaque Barbero (mjaque@ilkebenson.com)
@author Chema Viera Godoy (chema@ilkebenson.com)
Revisado por: $Author$ 
Revisión: $Rev$
@date $Date$
Diseñado por: Miguel Jaque Barbero
Fecha de Diseño: 21/4/2009
**************************************************************************/
class MenuPrincipal extends PaxDemoIu{
/**-	Interfaz para la Identificación de Usuarios.
**/
	protected $interfaz = 'xhtml/menuPrincipal.xml';	// Archivo con el interfaz de la clase

	public function procesarInterfaz($DOMInterfaz){
	/**-	Método de plantilla para procesar el interfaz antes de presentarlo.
		@param &$DOMInterfaz DOMDocument del interfaz.
	**/
//Depurador::mostrar($_SESSION['usuario']);
		//Si hay excepción de aviso, la ponemos en los campos ocultos para ello.
		$this->ponerValorCampo($DOMInterfaz,'avisoTitulo', $this->verAviso('titulo'));
		$textoAviso = $this->verAviso('texto')."\n".$this->verAviso('solucionUsuario');
		$this->ponerValorCampo($DOMInterfaz,'avisoTexto', $textoAviso);
	}
}
?>
