/************************************************************************
@file menuPrincipal.js 
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
function MenuPrincipal(){
	Iu.call(this);					//Deriva de Iu 
	this.ajax = new Ajax(URL_PAXDEMO);		//Conexión AJAX
		
	this.iniciar = function(){
	/**-	Inicializa el interfaz.
		Pone el foco en el primer campo.
	**/
		window.onerror = function (e){iu.alertar(e);};
		verAviso();
		if (iu.verCookie(kingy.NOMBRE_COOKIE) != 1)
			setTimeout("kingy.hablar('Hola. Soy Kingy y me encargaré de ayudarte a manejar esta aplicación.')", 2500);
		kingy.iniciar();

		//Ponemos el foco en el primer campo
		//$('bbdd').focus();
	}
}

var iu = new MenuPrincipal();

//Reglas de Comportamiento (Behaviour)
var reglasIU = {
}; 
Behaviour.addLoadEvent(iu.iniciar);	//Inicialización del interfaz en el window.onload
//Cargamos las reglas de comportamiento definidas para el interfaz gráfico.
Behaviour.register(reglasIU);		//Behaviour aÃ±ade su función a window.onload
