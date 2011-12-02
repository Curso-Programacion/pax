/************************************************************************
@file login.js 
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
function Login(){
	Iu.call(this);					//Deriva de Iu 
	this.ajax = new Ajax(URL_PAXDEMO);
		
	this.iniciar = function(){
	/**-	Inicializa el interfaz.
		Pone el foco en el primer campo.
	**/
		window.onerror = function (e){iu.alertar(e);};
		$('nombre').focus();
	}
	this.acceder = function(){
	/**-	Envía los datos del login al servidor.
	**/
		parametros = new Array();
		parametros.push(new Parametro('nombre',$('nombre').value)); 
		uniqid = $('uniqid').value;
		parametros.push(new Parametro('clave',hex_md5(hex_md5($('clave').value))));	//La clave se envía encriptada
		//parametros.push(new Parametro('clave',hex_md5(uniqid+hex_md5($('clave').value))));	//La clave se envía encriptada
		this.ajax.irA('login.autenticar',parametros);	
	}
}

var iu = new Login();

//Reglas de Comportamiento (Behaviour)
var reglasIU = {
	'#botonAcceder' : function (elem){
		elem.onclick = function(){
			iu.acceder();
		}
	},
}; 
Behaviour.addLoadEvent(iu.iniciar);	//Inicialización del interfaz en el window.onload
//Cargamos las reglas de comportamiento definidas para el interfaz gráfico.
Behaviour.register(reglasIU);		//Behaviour aÃ±ade su función a window.onload
