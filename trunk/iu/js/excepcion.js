/************************************************************************
@file excepcion.js 
Copyright Ilke Benson, A.I.E. 2009
@author Miguel Jaque Barbero (mjaque@ilkebenson.com)
@author Chema Viera Godoy (chema@ilkebenson.com)
Revisado por: $Author$ 
Revisión: $Rev$
@date $Date$
Diseñado por: Miguel Jaque Barbero
Fecha de Diseño: 18/5/2009
**************************************************************************/

///	Clase de este interfaz de usuario.
function Excepcion(){
	Iu.call(this);					//Deriva de Iu 
	this.ajax = new Ajax(URL_PAXDEMO);		//Conexión AJAX
		
	this.iniciar = function(){
	/**-	Inicializa el interfaz.
		Llamamos a Kingy
	**/
		kingy.iniciar();
		kingy.alertar("Se ha producido una Excepción.<p class='notaKingy'>(Es decir, un ERROR GORDO)</p>");
		
	}
}

var iu = new Excepcion();

//Reglas de Comportamiento (Behaviour)
var reglasIU = {
}; 
Behaviour.addLoadEvent(iu.iniciar);	//Inicialización del interfaz en el window.onload
//Cargamos las reglas de comportamiento definidas para el interfaz gráfico.
Behaviour.register(reglasIU);		//Behaviour aÃ±ade su función a window.onload
