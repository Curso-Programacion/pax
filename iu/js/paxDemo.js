/************************************************************************
@file paxDemo.js
Copyright Ilke Benson, A.I.E. 2009
@author Miguel Jaque Barbero (mjaque@ilkebenson.com)
@author Chema Viera Godoy (chema@ilkebenson.com)
Revisado por: $Author$ 
Revisión: $Rev$
@date $Date$
Diseñado por: Miguel Jaque Barbero
Fecha de Diseño: 21/4/2009
**************************************************************************/

const URL_PAXDEMO = "index.php";

//Clases propias de la aplicación

//Objeto Comunicador
kingy = new Comunicador('kingy','kingy');
kingy.NOMBRE_COOKIE = 'kingy_aparecido';	//Nombre de la cookie que indica si kingy ha aparecido.
kingy.TIEMPO_PERMANENCIA = 5;	//Segundos de permanencia del mensaje.
kingy.CSS_APARECER = 'kingyAparecer';
kingy.CSS_ESTAR = 'kingyEstar';
kingy.CSS_HABLAR = 'kingyHablar';
kingy.CSS_AVISAR = 'kingyAvisar';
kingy.CSS_ALERTAR = 'kingyAlertar';
kingy.CSS_DORMIR = 'kingyDormir';
kingy.TIEMPO_DORMIR = 10*60;	//Tiempo en segundos. (10 min) 

//Funciones Globales (para todos los interfaces)
function verAviso(){
/**-	Muestra los avisos del interfaz, si los hay
**/
	if ($('avisoTitulo').value != ''){
		textoAviso = $('avisoTitulo').value+"\n"+$('avisoTexto').value;
		alert(textoAviso);
	}
}

//Funciones Globales para el Menú de Operaciones
function menuPrincipal(){
/**-	Vuelve al menú principal
**/
	iu.ajax.irA("menuPrincipal.ver", null);
}
function salir(){
/**-	Elimina la sesión y vuelve a la página de login.
**/
	var parametros = new Array();
	
	iu.ajax.irA("login.salir", parametros);
}


var reglasIUGenerales = {
	'#bbdd' : function (elem){
		elem.onchange = function(){
			verTablas();
		}
	},
	'#menuPrincipal' : function(elem){
		elem.onclick = function(){
			menuPrincipal();
		}
	},
	'#menuExplorar' : function(elem){
		elem.onclick = function(){
			explorar();
		}
	},
	'#menuBuscar' : function(elem){
		elem.onclick = function(){
			buscar();
		}
	},
	'#menuInsertar' : function(elem){
		elem.onclick = function(){
			insertar();
		}
	},
	'#menuExportar' : function(elem){
		elem.onclick = function(){
			exportar();
		}
	},
	'#menuImportar' : function(elem){
		elem.onclick = function(){
			importar();
		}
	},
	'#menuSalir' : function(elem){
		elem.onclick = function(){
			salir();
		}
	}
}; 
//Cargamos las reglas de comportamiento definidas para el interfaz gráfico.
Behaviour.register(reglasIUGenerales);		//Behaviour añade su función a window.onload
