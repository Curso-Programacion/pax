/************************************************************************
@file creditos.js 
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
function Creditos(){
	Iu.call(this);					//Deriva de Iu 
	this.ajax = new Ajax(URL_ILKM);			//Conexión AJAX
}

var iu = new Creditos();

//Reglas de Comportamiento (Behaviour)
var reglasIU = {
}; 
//Behaviour.addLoadEvent(iu.iniciar);	//Inicialización del interfaz en el window.onload
//Cargamos las reglas de comportamiento definidas para el interfaz gráfico.
Behaviour.register(reglasIU);		//Behaviour aÃ±ade su función a window.onload
