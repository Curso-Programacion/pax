<?php
/************************************************************************
@file excepcion.php 
Copyright Ilke Benson, A.I.E. 2009
@author Miguel Jaque Barbero (mjaque@ilkebenson.com)
Revisado por: $Author$ 
Revisión: $Rev$
@date $Date$
Diseñado por: Miguel Jaque Barbero
Fecha de Diseño: 22/1/2008
**************************************************************************/
class Excepcion extends Exception{

	const MENSAJE_DEFECTO = "Reintente la operación y, si el problema persiste, póngase en contacto con el administrador.";

	//Atributos
	public $titulo;
	public $texto;
	public $solucionProgramador;
	public $solucionUsuario;

	public function __construct($titulo,$texto='',$solucionProgramador='',$solucionUsuario = self::MENSAJE_DEFECTO){
		$this->titulo = $titulo;
		$this->texto = $texto;
		$this->solucionProgramador = $solucionProgramador;
		if (!isset($solucionUsuario))
			$solucionUsuario = self::MENSAJE_DEFECTO;
		$this->solucionUsuario = $solucionUsuario;
		Registro::anotar("Se ha producido la siguiente excepción: ".get_class($this)."\n$titulo\n$texto\n$solucionProgramador\n$solucionUsuario");
		parent::__construct($this->__toString());
	}
	public function __toString(){
		$texto = get_class($this)." - ".$this->titulo;
		$texto .= "\n".$this->texto;
		$texto .= "\n".$this->solucionProgramador;
		$texto .= "\n".$this->solucionUsuario;
		return $texto;
	}
}
?>
