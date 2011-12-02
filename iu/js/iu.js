/************************************************************************
@file iu.js 
	Fichero con varias clases, constantes y funciones.
Copyright Ilke Benson, A.I.E. 2009
@author Miguel Jaque Barbero (mjaque@ilkebenson.com)
@author Chema Viera Godoy (chema@ilkebenson.com)
Revisado por: $Author$ 
Revisión: $Rev$
@date $Date$
Diseñado por: Miguel Jaque Barbero
Fecha de Diseño: 6/2/2008
**************************************************************************/
function $(id){
/**-	Función auxiliar para facilitar la escritura de 'document.getElementById'.
	Devuelve una referencia al elemento identificado por id.
	@param id Nombre del identificador del elemento buscado.
	@return La referencia al elemento (lo mismo que document.getElementById)
**/
	return document.getElementById(id);
}
// Constantes de PAX{
const PAX2_ASYNC = true; 
const PAX2_SYNC = false;
//const PAX2_OK = "Ok";
const PAX2_ERROR = "Error";
//const PAX2_MSG_TIMEOUT = "El servidor no responde";
//const PAX2_MSG ="Se ha producido un error. \nReintente la operaciÃ³n y si el problema persiste, contacte con el administrador informÃ¡ndole del siguiente texto:\n";
const PAX2_TEXTO = "texto";
const PAX2_XML = "xml";
const PAX2_JSON = "json";
//const PAX2_EXCP_ERROR = "PAX2_EXCP_ERROR";
//const PAX2_COMM_OK = 1;
//const PAX2_COMM_AVISO = 2;
//const PAX2_COMM_ERROR = 3;
//const PAX2_COMM_DESASTRE = 4;
//const PAX2_ESTADO_ESPERAR = 0;
//const PAX2_ESTADO_CREAR = 1;
//const PAX2_ESTADO_EDITAR = 2;
//const PAX2_ESTADO_ELIMINAR = 3;
//const PAX2_CABECERA_XML = "<?xml version=\"1.0\" encoding=\"iso-8859-15\"?>";

//}
function Iu(){
	const DIV_AVISO = "avisos";	// Identificador del Div de Avisos
	const DIV_EXCEPCION = "excepcion";	// Identificador del Div de Excepciones
	this.alertar = function(excepcion){
	/**-	Función por defecto para presentar excepciones
	**/
//TODO: Este método debería ser de plantilla.
		if (excepcion.paxTipo)
			if (excepcion.paxTipo.substr(0,5) == "Excep")
				excepcion = excepcion.titulo+'<br/>'+excepcion.texto+'<br/>'+excepcion.solucionUsuario;
		//kingy.alertar(excepcion);
		alert(excepcion);
	}
	this.cambiarCursor = function(tipo){
	/**-	Cambia el tipo del cursor
		@param tipo Texto con el tipo del cursor (wait, default, auto, ...)
	**/
		body = document.getElementsByTagName('body').item(0);
		body.style.cursor = tipo;
	}
	this.imprimir = function(){
	/**-	Imprime la pÃ¡gina actual.
	**/
		window.print();
	}
	this.verCookie = function(nombre){
	/**-	Devuelve el valor de una cookie.
		Código de www.w3schools.com
	**/
		if (document.cookie.length > 0){
			inicio = document.cookie.indexOf(nombre + "=");
			if (inicio != -1){ 
				inicio = inicio + nombre.length+1;
				fin = document.cookie.indexOf(";",inicio);
				if (fin == -1) fin = document.cookie.length;
				return unescape(document.cookie.substring(inicio, fin));
			}
		}
		return "";
	}
	this.ponerCookie = function(nombre,valor,duracion){
	/**-	Pone una cookie.
		@param nombre Nombre de la cookie.
		@param valor Valor de la cookie (texto).
		@param duracion Número de días hasta que la cookie caduque.
		Código de www.w3schools.com
	**/
		var exdate=new Date();
		exdate.setDate(exdate.getDate()+duracion);
		document.cookie=nombre+ "=" +escape(valor)+
		((duracion==null) ? "" : ";expires="+exdate.toGMTString());
	}
	this.borrarCookie = function (nombre){
	/**-	Borra una cookie.
		@param nombre Nombre de la cookie.
	**/
		document.cookie=nombre+ "="+";expires=Thu, 01-Jan-1970 00:00:01 GMT";
	}

	this.serializar = function ( mixed_value ) {
	// http://kevin.vanzonneveld.net
	// +   original by: Arpad Ray (mailto:arpad@php.net)
	// +   improved by: Dino
	// +   bugfixed by: Andrej Pavlovic
	// +   bugfixed by: Garagoth
	// %          note: We feel the main purpose of this function should be to ease the transport of data between php & js
	// %          note: Aiming for PHP-compatibility, we have to translate objects to arrays
	// *     example 1: serialize(['Kevin', 'van', 'Zonneveld']);
	// *     returns 1: 'a:3:{i:0;s:5:"Kevin";i:1;s:3:"van";i:2;s:9:"Zonneveld";}'
	// *     example 2: serialize({firstName: 'Kevin', midName: 'van', surName: 'Zonneveld'});
	// *     returns 2: 'a:3:{s:9:"firstName";s:5:"Kevin";s:7:"midName";s:3:"van";s:7:"surName";s:9:"Zonneveld";}'
 
		var _getType = function( inp ) {
			var type = typeof inp, match;
			var key;
			if (type == 'object' && !inp) {
				return 'null';
			}
			if (type == "object") {
			if (!inp.constructor) {
				return 'object';
			}
			var cons = inp.constructor.toString();
            if (match = cons.match(/(\w+)\(/)) {
                cons = match[1].toLowerCase();
            }
            var types = ["boolean", "number", "string", "array"];
            for (key in types) {
                if (cons == types[key]) {
                    type = types[key];
                    break;
                }
            }
        }
        return type;
    };
    var type = _getType(mixed_value);
    var val, ktype = '';
    
    switch (type) {
        case "function": 
            val = ""; 
            break;
        case "undefined":
            val = "N";
            break;
        case "boolean":
            val = "b:" + (mixed_value ? "1" : "0");
            break;
        case "number":
            val = (Math.round(mixed_value) == mixed_value ? "i" : "d") + ":" + mixed_value;
            break;
        case "string":
            val = "s:" + mixed_value.length + ":\"" + mixed_value + "\"";
            break;
        case "array":
        case "object":
            val = "a";
            /*
            if (type == "object") {
                var objname = mixed_value.constructor.toString().match(/(\w+)\(\)/);
                if (objname == undefined) {
                    return;
                }
                objname[1] = serialize(objname[1]);
                val = "O" + objname[1].substring(1, objname[1].length - 1);
            }
            */
            var count = 0;
            var vals = "";
            var okey;
            var key;
            for (key in mixed_value) {
                ktype = _getType(mixed_value[key]);
                if (ktype == "function") { 
                    continue; 
                }
                
                okey = (key.match(/^[0-9]+$/) ? parseInt(key, 10) : key);
                vals += this.serializar(okey) +
                        this.serializar(mixed_value[key]);
                count++;
            }
            val += ":" + count + ":{" + vals + "}";
            break;
    }
    if (type != "object" && type != "array") {
      val += ";";
  }
    return val;
}
	this.deserializar = function(data){
    // http://kevin.vanzonneveld.net
    // +     original by: Arpad Ray (mailto:arpad@php.net)
    // +     improved by: Pedro Tainha (http://www.pedrotainha.com)
    // +     bugfixed by: dptr1988
    // +      revised by: d3x
    // +     improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +      input by: Brett Zamir (http://brettz9.blogspot.com)
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // %            note: We feel the main purpose of this function should be to ease the transport of data between php & js
    // %            note: Aiming for PHP-compatibility, we have to translate objects to arrays 
    // *       example 1: unserialize('a:3:{i:0;s:5:"Kevin";i:1;s:3:"van";i:2;s:9:"Zonneveld";}');
    // *       returns 1: ['Kevin', 'van', 'Zonneveld']
    // *       example 2: unserialize('a:3:{s:9:"firstName";s:5:"Kevin";s:7:"midName";s:3:"van";s:7:"surName";s:9:"Zonneveld";}');
    // *       returns 2: {firstName: 'Kevin', midName: 'van', surName: 'Zonneveld'}
    
    var error = function (type, msg, filename, line){throw new window[type](msg, filename, line);};
    var read_until = function (data, offset, stopchr){
        var buf = [];
        var chr = data.slice(offset, offset + 1);
        var i = 2;
        while (chr != stopchr) {
            if ((i+offset) > data.length) {
                error('Error', 'Invalid');
            }
            buf.push(chr);
            chr = data.slice(offset + (i - 1),offset + i);
            i += 1;
        }
        return [buf.length, buf.join('')];
    };
    var read_chrs = function (data, offset, length){
        var buf;
        
        buf = [];
        for(var i = 0;i < length;i++){
            var chr = data.slice(offset + (i - 1),offset + i);
            buf.push(chr);
        }
        return [buf.length, buf.join('')];
    };
    var _unserialize = function (data, offset){
        var readdata;
        var readData;
        var chrs = 0;
        var ccount;
        var stringlength;
        var keyandchrs;
        var keys;
 
        if(!offset) offset = 0;
        var dtype = (data.slice(offset, offset + 1)).toLowerCase();
        
        var dataoffset = offset + 2;
        var typeconvert = new Function('x', 'return x');
        
        switch(dtype){
            case "i":
                typeconvert = new Function('x', 'return parseInt(x)');
                readData = read_until(data, dataoffset, ';');
                chrs = readData[0];
                readdata = readData[1];
                dataoffset += chrs + 1;
            break;
            case "b":
                typeconvert = new Function('x', 'return (parseInt(x) == 1)');
                readData = read_until(data, dataoffset, ';');
                chrs = readData[0];
                readdata = readData[1];
                dataoffset += chrs + 1;
            break;
            case "d":
                typeconvert = new Function('x', 'return parseFloat(x)');
                readData = read_until(data, dataoffset, ';');
                chrs = readData[0];
                readdata = readData[1];
                dataoffset += chrs + 1;
            break;
            case "n":
                readdata = null;
            break;
            case "s":
                ccount = read_until(data, dataoffset, ':');
                chrs = ccount[0];
                stringlength = ccount[1];
                dataoffset += chrs + 2;
                
                readData = read_chrs(data, dataoffset+1, parseInt(stringlength));
                chrs = readData[0];
                readdata = readData[1];
                dataoffset += chrs + 2;
                if(chrs != parseInt(stringlength) && chrs != readdata.length){
                    error('SyntaxError', 'String length mismatch');
                }
            break;
            case "a":
                readdata = {};
                
                keyandchrs = read_until(data, dataoffset, ':');
                chrs = keyandchrs[0];
                keys = keyandchrs[1];
                dataoffset += chrs + 2;
                
                for(var i = 0;i < parseInt(keys);i++){
                    var kprops = _unserialize(data, dataoffset);
                    var kchrs = kprops[1];
                    var key = kprops[2];
                    dataoffset += kchrs;
                    
                    var vprops = _unserialize(data, dataoffset);
                    var vchrs = vprops[1];
                    var value = vprops[2];
                    dataoffset += vchrs;
                    
                    readdata[key] = value;
                }
                
                dataoffset += 1;
            break;
            default:
                error('SyntaxError', 'Unknown / Unhandled data type(s): ' + dtype);
            break;
        }
        return [dtype, dataoffset - offset, typeconvert(readdata)];
    };
    return _unserialize(data, 0)[2];
}
	this.urlencode = function( str ) {
	// http://kevin.vanzonneveld.net
	// +   original by: Philip Peterson
	// +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	// +      input by: AJ
	// +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	// +   improved by: Brett Zamir (http://brettz9.blogspot.com)
	// +   bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	// +      input by: travc
	// +      input by: Brett Zamir (http://brettz9.blogspot.com)
	// +   bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	// +   improved by: Lars Fischer
	// %          note 1: info on what encoding functions to use from: http://xkr.us/articles/javascript/encode-compare/
	// *     example 1: urlencode('Kevin van Zonneveld!');
	// *     returns 1: 'Kevin+van+Zonneveld%21'
	// *     example 2: urlencode('http://kevin.vanzonneveld.net/');
	// *     returns 2: 'http%3A%2F%2Fkevin.vanzonneveld.net%2F'
	// *     example 3: urlencode('http://www.google.nl/search?q=php.js&ie=utf-8&oe=utf-8&aq=t&rls=com.ubuntu:en-US:unofficial&client=firefox-a');
	// *     returns 3: 'http%3A%2F%2Fwww.google.nl%2Fsearch%3Fq%3Dphp.js%26ie%3Dutf-8%26oe%3Dutf-8%26aq%3Dt%26rls%3Dcom.ubuntu%3Aen-US%3Aunofficial%26client%3Dfirefox-a'
					     
		var histogram = {}, tmp_arr = [], unicodeStr='', hexEscStr='';
		var ret = (str+'').toString();
		var replacer = function(search, replace, str) {
		var tmp_arr = [];
		tmp_arr = str.split(search);
		return tmp_arr.join(replace);
		};
		// The histogram is identical to the one in urldecode.{
		histogram["'"]   = '%27';
		histogram['(']   = '%28';
		histogram[')']   = '%29';
		histogram['*']   = '%2A';
		histogram['~']   = '%7E';
		histogram['!']   = '%21';
		histogram['%20'] = '+';
		histogram['\u00DC'] = '%DC';
		histogram['\u00FC'] = '%FC';
		histogram['\u00C4'] = '%D4';
		histogram['\u00E4'] = '%E4';
		histogram['\u00D6'] = '%D6';
		histogram['\u00F6'] = '%F6';
		histogram['\u00DF'] = '%DF';
		histogram['\u20AC'] = '%80';
		histogram['\u0081'] = '%81';
		histogram['\u201A'] = '%82';
		histogram['\u0192'] = '%83';
		histogram['\u201E'] = '%84';
		histogram['\u2026'] = '%85';
		histogram['\u2020'] = '%86';
		histogram['\u2021'] = '%87';
		histogram['\u02C6'] = '%88';
		histogram['\u2030'] = '%89';
		histogram['\u0160'] = '%8A';
		histogram['\u2039'] = '%8B';
		histogram['\u0152'] = '%8C';
		histogram['\u008D'] = '%8D';
		histogram['\u017D'] = '%8E';
		histogram['\u008F'] = '%8F';
		histogram['\u0090'] = '%90';
		histogram['\u2018'] = '%91';
		histogram['\u2019'] = '%92';
		histogram['\u201C'] = '%93';
		histogram['\u201D'] = '%94';
		histogram['\u2022'] = '%95';
		histogram['\u2013'] = '%96';
		histogram['\u2014'] = '%97';
		histogram['\u02DC'] = '%98';
		histogram['\u2122'] = '%99';
		histogram['\u0161'] = '%9A';
		histogram['\u203A'] = '%9B';
		histogram['\u0153'] = '%9C';
		histogram['\u009D'] = '%9D';
		histogram['\u017E'] = '%9E';
		histogram['\u0178'] = '%9F';
		//}
		// Begin with encodeURIComponent, which most resembles PHP's encoding functions
		ret = encodeURIComponent(ret);
		for (unicodeStr in histogram) {
		hexEscStr = histogram[unicodeStr];
		ret = replacer(unicodeStr, hexEscStr, ret); // Custom replace. No regexing
		}
		// Uppercase for full PHP compatibility
		return ret.replace(/(\%([a-z0-9]{2}))/g, function(full, m1, m2) {
		return "%"+m2.toUpperCase();
		});
		return ret;
	}
}
function Lista(){
///	Clase de Interfaz de Usuario que representa un campo de lista desplegable (select).
	this.elementoXML = 'item';	//Nombre del elemento XML de cada item de la lista.
	this.valor;			//Valor del campo.
	
	this.buscarTextoPorValor = function(valor){
	/**-	Busca el texto de la opciÃ³n de la lista con el valor indicado.
		@param valor Valor de la opciÃ³n.
		@return Texto de la opciÃ³n con el valor indicado o false si no se encuentra.
	**/
		for(var i=0; i<this.options.length; i++)
			if (this.options[i].value == valor){
				return this.options[i].text;
			}
		return false;
	}
	this.cargar = function(datos){
	/**-	Carga la lista apartir de un array de datos.
		@param datos Array de objetos. Cada objeto debe tener los atributos "valor" y "texto". 
	**/
		this.limpiar();
		if (this.nulo)
			this.pon("Sin definir", "-1");
		if (datos.length)
			for (var i=0; i<datos.length; i++)
				this.pon(datos[i].texto, datos[i].valor);
		this.seleccionarPorValor(this.valorInicial);
	}
	this.cargarListaAuxiliar = function(listaAuxiliar){
	/**-	Carga la lista apartir de una respuesta AJAX.
		@param listaAuxiliar El objeto respuesta de AJAX, un objeto tipo ListaAuxiliar con un elemento items que contiene un array de item, cada uno de ellos con los atributos id y nombre.
	**/
		this.limpiar();
		lista = listaAuxiliar.items[0].item;
		if (this.nulo) this.pon("Sin definir", "-1");
		if (lista.length)
			for (var i=0; i<lista.length; i++)
				this.pon(lista[i].nombre, lista[i].id);
		this.seleccionarPorValor(this.valorInicial);
	}
	this.pon = function(texto,valor){
		var opcion = document.createElement("option");
		opcion.setAttribute("value", valor);
		opcion.setAttribute("tooltiptext", texto);
		nodoTexto = document.createTextNode(texto);
		opcion.appendChild(nodoTexto);
		//this.campo.appendChild(opcion);
		this.appendChild(opcion);
		//this.campo.selectedIndex = 0;
	}
	this.limpiar = function(){
	/**-	Elimina todas las opciones de la lista.
	**/
		//while(this.campo.hasChildNodes())
		while(this.hasChildNodes())
			//this.campo.removeChild(this.campo.firstChild);
			this.removeChild(this.firstChild);
	}
	this.seleccionarPorValor = function(valor){
	/**-	Selecciona la opciÃ³n de la lista con el valor indicado.
		La selecciÃ³n es por valor, no por el texto de la opciÃ³n. Si varias opciones tienen el mismo valor, se selecciona la primera de ellas.
		@param valor Valor de la opciÃ³n que se quiere seleccionar.
	**/
		for(var i=0; i<this.options.length; i++)
			if (this.options[i].value == valor){
				this.selectedIndex = this.options[i].index;
				this.valor = this.options[i].value;
				return;
			}
		this.selectedIndex = -1;
	}
	this.seleccionarPorTexto = function(texto){
	/**-	Selecciona la opciÃ³n de la lista con el texto indicado.
		La selecciÃ³n es por texto, no por el valor de la opciÃ³n. Si varias opciones tienen el mismo texto, se selecciona la primera de ellas.
		@param texto Texto de la opciÃ³n que se quiere seleccionar.
	**/
		for(var i=0; i<this.options.length; i++)
			if (this.options[i].text == texto){
				this.selectedIndex = this.options[i].index;
				this.valor = this.options[i].value;
				break;
			}
	}
	this.alCambiar = function(){
		this.valor = this.options[this.selectedIndex].value;
	}
	// Eventos de la clase
	if (this.addEventListener){
		this.addEventListener('change',this.alCambiar,false);
	}
	else{ //Para IE
		this.attachEvent('onchange',this.alCambiar);
		this.attachEvent('onkeypress',this._borrar);
	}

	//Establecemos el valor inicial, si lo tiene.
	if (this.getAttribute('valor')){
		this.seleccionarPorValor(this.getAttribute('valor'));
		if (this.onchange)	//Si está definido
			this.onchange();	//Lanzamos el evento de usuario
	}
}
function ListaDesplegableAmpliable(){
/**-	Clase de interfaz que representa una lista ampliable.
**/
	this.id = this.attributes.getNamedItem("id").nodeValue;
	this.input = this.childNodes.item(0);
	this.input.listaAmpliable = this;
	this.select = this.childNodes.item(1);
	this.select.listaAmpliable = this;
	this.boton = this.childNodes.item(2);
	this.boton.listaAmpliable = this;
	Lista.call(this.select);		//Sobrecargamos el select con la clase Lista.
	this.valor;
	this.imagenEditar = 'iu/img/edit.png';
	this.imagenCancelar = 'iu/img/editdelete.png';

	this.cambiar = function(){
	/**-	Cambia el estado de la lista mostrando el input si se mostraba la lista y viceversa.
	 **/
	 	if (this.input.style.display == 'none'){
			this.select.style.display = 'none';
			this.input.style.display = 'inline';
			this.boton.src = this.imagenCancelar;
		}
		else{
			this.input.style.display = 'none';
			this.select.style.display = 'inline';
			this.boton.src = this.imagenEditar;
		}
	}
	this.limpiar = function(){
	/**-	Elimina todas las opciones de la lista, borra el campo de texto y pone la visualizaciÃ³n inicial.
	**/
		this.valor = "";
		this.input.value = "";
		this.select.limpiar();
		this.input.style.display = 'none';
		this.select.style.display = 'inline';
		this.boton.src = this.imagenEditar;
	}
	this.alPulsar = function(){
		this.listaAmpliable.cambiar();
	}
	this.alSeleccionar = function(){
	/**-	Evento que atiende la modificaciÃ³n de la lista.
		Establece el valor del objeto.
	**/
		this.listaAmpliable.valor = this.options[this.selectedIndex].value;
	}
	this.alCambiarTexto = function(){
	/**-	Evento que atiende la modificaciÃ³n del valor del campo de texto.
		Establece el valor del objeto.
	**/
		this.listaAmpliable.valor = this.value;
	}
	this.alCambiarValor = function(atributo, valorActual, valorNuevo){
	/**-	Evento que atiende la modificaciÃ³n del atributo valor.
	**/
		this.input.value = valorNuevo;
		this.select.seleccionarPorValor(valorNuevo);
		this.valor = valorNuevo;
		if(this.onchange)	//Lanzamos el evento, si lo hay.
			this.onchange();
		return valorNuevo;	//Para hacer efectivo el cambio de valor.
	}
	this._borrar = function(evento){
	/**-	Borra la lista de opciones y el campo de texto si la tecla pulsada ha sido backspace.
	**/
		if (window.event)	//Es IE
			keynum = evento.keyCode;
		else keynum = evento.which;
		if (keynum == 8){
			this.listaAmpliable.limpiar();	//this es el select en el evento keypress
			evento.stopPropagation();
			evento.preventDefault();
		}
		//keychar = String.fromCharCode(keynum);
	}
	
	// Eventos de la clase
	if (this.boton.addEventListener){
		this.boton.addEventListener('click',this.alPulsar,false);
		this.select.addEventListener('change',this.alSeleccionar,false);
		this.input.addEventListener('change',this.alCambiarTexto,false);
		this.select.addEventListener('keypress', this._borrar, false);
	}
	else{ //Para IE
		this.input.attachEvent('onclick',this.alPulsar);
	}
	
	// Seguimiento de cambios al atributo valor
	this.watch('valor', this.alCambiarValor);

	// Establecemos el estado inicial
	this.input.style.display = 'none';
	this.select.style.display = 'inline';

	//Establecemos el valor inicial
	if (this.getAttribute('valor'))
		this.valor = this.getAttribute("valor");
}
function Ajax(url){
/**-	Clase que implementa una comunicaciÃ³n AJAX.
**/

	this.url = url;	//La url del servidor.
	
	this.pedir = function (accion, parametros, formatoRespuesta, async, manejador, entorno){
	/**-	Realiza una petición AJAX al servidor.
	 	El tratamiento de la respuesta y de los errores lo realiza la función global atenderRespuestaAJAX.
		@param accion Nombre de la acción a ejecutar, en el formato 'objeto.método'.
		@param parametros Array de objetos Parametro con los parámetros requeridos para la acción.
		@param formatoRespuesta Texto indicando el formato en el que se solicita la respuesta del servidor (XML, JSON, Texto).
		@param async Indica si la acción es síncrona o asíncrona. Si es síncrona, ejecutar devuelve el resultado. Si es asíncrona, pasará el resultado al manejador.
		@param manejador Nombre de la función (objeto.método) a la que se pasará el resultado si la llamada es asícrona.
		@param entorno Referencia al objeto que se pasará como entorno de trabajo al manejador. ¡OBSOLETO!
		@return Si la llamada es síncrona, ejecutar devuelve la respuesta del servidor. En caso contrario, no devuelve nada.
	**/
		
		//Código de http://developer.mozilla.org/en/docs/AJAX:Getting_Started
		var peticion;

		if (window.XMLHttpRequest) { // Mozilla, Safari, ...
			peticion = new XMLHttpRequest();
			if (peticion.overrideMimeType) {
				peticion.overrideMimeType('text/xml');
			}
		} 
		else if (window.ActiveXObject) { // IE
			try {
				peticion = new ActiveXObject("Msxml2.XMLHTTP");
			} 
			catch (e) {
				try {
					peticion = new ActiveXObject("Microsoft.XMLHTTP");
				} 
				catch (e) {}
			}
		}
		if (!peticion) {
			alert('Su navegador no tiene un soporte conocido para AJAX. No es posible comunicar con el servidor.');
			return false;
		}
		peticion.manejador = manejador;
		peticion.entorno = entorno;
		peticion.async = async;
		peticion.formatoRespuesta = formatoRespuesta;
		peticion.onerror = function(){throw("Error de AJAX al ejecutar " + accion);};
		if (async) peticion.addEventListener("load", function(){iu.ajax.atenderRespuesta.call(iu.ajax, peticion);}, false);
		peticion.open('POST', this.url, async);		//Peticiones posteriores, no se concatenan a la base de datos

		peticion.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=utf-8");
		var mensaje = "PaxAccion=" + accion;
		
		mensaje += this.verParametros(parametros);
	
		if (formatoRespuesta == '') formatoRespuesta = PAX2_XML;	// Por defecto, es AJAX 
		mensaje += "&PaxFormatoRespuesta=" + formatoRespuesta;

		respuesta = peticion.send(mensaje);		//Enviamos la peticiÃ³n.

		if(!async) 
			return this.atenderRespuesta(peticion);

	};
	this.verParametros = function (parametros){
	/**-	Devuelve el texto de URL para transmitir los parámetros por GET.
		@param parametros Array de objetos Parametro.
		@return Texto para anexar a la URL.
	**/
		mensaje = "";
		if (parametros)
			for (var i=0; i<parametros.length; i++)
				mensaje += "&" + parametros[i].nombre+ "=" + parametros[i].valor;
		return mensaje;
	}
	this.irA = function (accion,parametros){
	/**-	Carga una nueva página llamando a la acción indicada con los parámetros indicados.
		@param accion Nombre de la acción.
		@param parametros Array indexado con los parámetros a enviar. 
	**/
		window.location.href = this.url+"?PaxAccion="+accion+this.verParametros(parametros);
	};
	this.atenderRespuesta = function(peticion){
	/**-	Función global para atender las respuestas AJAX. Esta función es llamada en el evento onreadystatechange de la petición AJAX.
		Analiza los posibles errores y, si todo es correcto, llama al manejador con la respuesta.
		@param peticion Petición XMLHttpRequest realizada. Incluye atributos añadidos al realizar la petición.
	**/
		//alert(peticion.responseText);
		if (peticion.readyState == 4) { //Petición Completada
			if (peticion.status == 200) {
				//Tratamiento de la respuesta.
				var respuesta;
				switch (peticion.formatoRespuesta){
					case (PAX2_TEXTO):
						respuesta = peticion.responseText;
						break;
					case (PAX2_JSON):
						respuesta = eval(peticion.responseText);
						break;
					case (PAX2_XML):
					default:
						if (peticion.responseXML.firstChild.tagName == 'parsererror')	// Respuesta vacÃ­a
							respuesta = null;
						else{
							respuesta = this.hacerObjeto(peticion.responseXML.firstChild);
							//if (respuesta.paxTipo.substr(0,5)== "Excep")
							//	iu.alertar(respuesta);
						}
				}
	  			if( peticion.manejador != null ){ 
					var trozos = peticion.manejador.split(".");
					if (trozos.length == 1)		//Llamada a una funciÃ³n global
						eval(peticion.manejador+"(respuesta)");
					else{				//Llamada a un mÃ©todo de un objeto.
						//Hacemos este berenjenal porque sino, la llamada al mÃ©todo pierde el this.
						var metodo = trozos[trozos.length-1];
						var objeto = trozos[0];
						for (var i=1; i<trozos.length-1; i++)
							objeto += "."+trozos[i];
						//alert(objeto+"."+metodo+".call("+objeto+",respuesta)");
						eval(objeto+"."+metodo+".call("+objeto+",respuesta)");
						return;
					}
				}
		  		else 
					return respuesta;
			}
			else
				throw('Excepción AJAX: El estado es '+peticion.statusText);
		}
	};
	this.hacerObjeto = function (nodoXML){
	/**-	Recibe un nodoXML y devuelve un objeto con los atributos del nodoXML en un array indexado.
		Por ahora no admite nodos de texto.
		@param nodoXML El nodo XML a transformar.
	**/
		var objeto = new Object();
		if (!objeto.paxTipo)
			objeto.paxTipo = nodoXML.localName;
		if (nodoXML.hasAttributes())
			//Meto todos los atributos del objeto en un array indexado
			for (var i=0; i<nodoXML.attributes.length; i++)
				objeto[nodoXML.attributes.item(i).nodeName] = nodoXML.attributes.item(i).nodeValue;

		if (nodoXML.hasChildNodes())
			//Cojo cada subnodo y lo proceso
			for (var i=0; i<nodoXML.childNodes.length; i++){
				var nodo = nodoXML.childNodes.item(i);
				if (nodo.nodeType == 3)	//Nodo de Texto
					return nodo.nodeValue;	//TODO: No funcionarÃ­a si el nodoXML combina nodos de texto y normales.
				//Miro si ya existe un array con el nombre del elemento
				if (!objeto[nodo.nodeName]) objeto[nodo.nodeName] = new Array();
				objeto[nodo.nodeName].push(this.hacerObjeto(nodo));
			}
		
		return objeto;
	}
};
function Parametro(nombre, valor){
/**- 	Clase que implementa un parÃ¡metro.
	Los parÃ¡metros se utilizan para enviar datos al servidor. Constan de nombre y valor.
	@param	nombre Texto con el nombre del parÃ¡metro.
	@param	valor Texto con el valor del parÃ¡metro.
**/
	this.nombre = nombre;
	if (valor == undefined)
		this.valor = '';	//Evitamos los undefined
	else
		this.valor = valor;
}
function AreaTabs(){
/**-	Clase de interfaz de usuario que representa un área de pestañas.
**/
	this.tabs = new Array();
	this.tabpanels = new Array();
	this.claseTabSeleccionado = '';
	this.claseTabNoSeleccionado = '';

	this.registrar = function (tab, tabpanel){
	/**-	Registra un tab y su tabpanel como miembros del área de tabs
	**/
		//Ocultamos el tabpanel
		tabpanel.style.display = 'none';

		if (this.tabs.length == 0){	//Si es el primero
			tab.childNodes.item(0).setAttribute("class", 'active');
			tabpanel.style.display = 'block';
		}
		tab.indiceTab = this.tabs.length;	//Ponemos el índice en el tab
		tab.areaTab = this;			//Ponemos una referencia al propio elemento
		this.tabs.push(tab);
		this.tabpanels.push(tabpanel);

		//Añadimos comportamiento al tab
		tab.addEventListener('click',this.cambiar,false);
	}
	this.cambiar = function(event){
		//En el evento, this es el elemento pulsado (tab)
		//Mostramos el tab pulsado
		for (i=0; i<this.areaTab.tabpanels.length; i++)
			if (i != event.currentTarget.indiceTab){
				this.areaTab.tabpanels[i].style.display = 'none';
				this.areaTab.tabs[i].childNodes.item(0).removeAttribute("class");
			}
			else{
				this.areaTab.tabpanels[i].style.display = 'block';
				this.areaTab.tabs[i].childNodes.item(0).setAttribute('class',"active");
			}
	}
}
function ListaDesplegableBuscador(){
/**-	Clase de Interfaz de Usuario que representa un campo de selección que realiza búsquedas para completar su lista de opciones.
	Se llama mediante ListaDesplegableBuscador.call(spanListaDesplegableBuscador). Por lo que en la clase, this es el span.
	@param span Referencia al objeto span que representa la ListaDesplegableBuscador.
**/
	this.id = this.attributes.getNamedItem('id').nodeValue;
	this.operacionBusqueda = this.attributes.getNamedItem('buscar').nodeValue;
	this.input = this.childNodes.item(0);
	this.input.listaDesplegableBuscador = this;
	this.select = this.childNodes.item(1);
	this.select.listaDesplegableBuscador = this;
	Lista.call(this.select);		//Sobrecargamos el select con la clase Lista.

	this.buscar = function(){
	/**-	Realiza una petición AJAX al servidor para pedir la lista de resultados que coinciden con el texto indicado.
		Esta función se ejecuta al modificar (onchange) el campo.
		Como se llama desde un evento capturado por el input, "this" es el elemento input.
	**/ 
		if (!this.listaDesplegableBuscador.operacionBusqueda) throw("No hay definida ninguna operación de búsqueda");
		if (this.value == ''){ //Si han borrado el campo, borramos el valor y volvemos.
			this.listaDesplegableBuscador.valor = undefined;
			return;	
		}
		texto = this.value;
		var parametros = new Array();
		parametros.push(new Parametro("texto",texto));
		var manejador = "$('"+this.listaDesplegableBuscador.id+"').cargar";
		iu.ajax.pedir(this.listaDesplegableBuscador.operacionBusqueda, parametros, PAX2_XML, PAX2_ASYNC, manejador, null);
	};
	this.cargar = function(respuesta){
		var datos = new Array();
		if (respuesta.item)
			for(var i=0; i<respuesta.item.length; i++){
				var item = new Object();
				item.valor = respuesta.item[i].id;
				item.texto = respuesta.item[i].nombre;
				datos.push(item)
			}
		this.select.cargar(datos);
		this.cambiar();
		this.valor = null;
		if (datos.length == 1){	//Si sólo hay un resultado, lo seleccionamos.
			this.select.selectedIndex = 0;
			this.valor = this.select.options[this.select.selectedIndex].value;	//Establecemos el valor del campo.
		}
	};
	this.cambiar = function(){
	/**-	Cambia los campos visibles. Si el input es visible, pasa a invisible y el select a visible. Y viceversa.
	**/
		if (this.input.style.display == 'none'){
			this.select.style.display = 'none';
			this.input.style.display = 'inline';
		}
		else{
			this.input.style.display = 'none';
			this.select.style.display = 'inline';
		}
		this.enfocar();

	};
	this.enfocar = function(){
	/**-	Pone el foco en el campo visible.
	**/
		if (this.input.style.display == 'none')
			this.select.focus();
		else
			this.input.focus();
	}
	this.habilitar = function(habilitar){
	/**-	Habilita o deshabilita el campo.
	 	@param habilitar Booleano indicado si se habilita (true) o se deshabilita (false) el campo.
	**/
		this.input.disabled = !habilitar;
		this.select.disabled = !habilitar;
	};
	this._borrar = function(evento){
		if (window.event)	//Es IE
			keynum = evento.keyCode;
		else keynum = evento.which;
		if (keynum == 8){
			this.limpiar();	//this es el select en el evento keypress
			this.listaDesplegableBuscador.cambiar();
			evento.stopPropagation();
			evento.preventDefault();
		}
		//keychar = String.fromCharCode(keynum);
	}
	this.borrar = function(){
		this.select.limpiar();
		this.input.value = "";
		this.select.style.display = 'none';
		this.input.style.display = 'inline';
		this.habilitar(false);
	}
	this.atr = function(atributo){
		return 'entrada';
		//if (atributo == 'valor') return this.select.options[this.select.selectedIndex].value;
	}
	this.alSeleccionar = function(){
		this.listaDesplegableBuscador.valor = this.options[this.selectedIndex].value;
	}
	// Eventos de la clase
	if (this.input.addEventListener){
		this.input.addEventListener('change',this.buscar,false);
		this.select.addEventListener('keypress', this._borrar, false);
		this.select.addEventListener('change', this.alSeleccionar, false);
	}
	else{ //Para IE
		this.input.attachEvent('onchange',this.buscar);
		this.input.attachEvent('onkeypress',this._borrar);
	}

	//Establecemos el valor inicial, si lo tiene
	if (this.getAttribute('value')){
		this.valor = this.getAttribute('value');
		this.input.value = this.getAttribute('value');
		//Lanzamos la búsqueda, pero no nos vale this.buscar porque el this no es el input ahora
		var parametros = new Array();
		parametros.push(new Parametro("texto",this.input.value));
		//Hacemos la llamada síncrona para evitar que se mueva el foco.
		respuesta = iu.ajax.pedir(this.operacionBusqueda, parametros, PAX2_XML, PAX2_SYNC, null, null);
		$(this.id).cargar(respuesta);
	}
}
function Hora(){
/**-	Clase de Interfaz de Usuario que representa un campo de hora y minutos.
	Se llama mediante Hora.call(campo). Por lo que en la clase, this es el span que agrupa los inputs.
**/
	this.id = this.attributes.getNamedItem('id').nodeValue;
	this.inputHora = this.childNodes.item(0);
	this.inputMin = this.childNodes.item(2);
	this.valor = null;

	this.enfocar = function(){
	/**-	Pone el foco en el campo visible.
	**/
		this.inputHora.focus();
	}
	this.limpiar = function(){
	/**- 	Borra el array de horarios y limpia la tabla quitando la clase
	**/
		this.inputHora = '';
		this.inputMin = '';
	}
	this.ver = function(){
	/**-	Devuelve el valor de la hora (HH:MM).
		@return Texto con la hora y minuto.
	**/
		return this.inputHora.value+':'+this.inputMin.value;
	}
	this.alCambiarHora = function(evento){
	/**-	Atiende el evento de cambio en el input de hora.
		Evita horas inválidas.
		this es el input
	**/
		if ( (parseInt(this.value) >= 0) && (parseInt(this.value) < 24))
			this.value = parseInt(this.value);
		else this.value  = '00';
	}
	this.alCambiarMin = function(evento){
	/**-	Atiende el evento de cambio en el input de minutos.
		Evita los minutos inválidos.
	**/
		if ( (parseInt(this.value) >= 0) && (parseInt(this.value) < 60))
			this.value = parseInt(this.value);
		else this.value  = '00';
	}
	// Eventos de la clase
	if (this.addEventListener){
		this.inputHora.addEventListener('change',this.alCambiarHora,false);
		this.inputMin.addEventListener('change',this.alCambiarMin,false);
	}
	else{ //Para IE
		this.inputHora.attachEvent('onchange',this.alCambiarHora);
		this.inputMin.attachEvent('onchange',this.alCambiarMin);
	}
}
function Horario(){
/**-	Clase de Interfaz de Usuario que representa un campo de horario semanal.
	Se llama mediante Horario.call(horario). Por lo que en la clase, this es el input.
**/
	this.id = this.attributes.getNamedItem('id').nodeValue;
	this.img = this.nextSibling;
	this.img.horario = this;
	this.div = this.img.nextSibling;
	this.div.horario = this;
	//Los iconos están en un div dentro del div
	this.iconoAceptar = this.div.childNodes.item(0).childNodes.item(0);
	this.iconoAceptar.horario = this;
	this.iconoCancelar = this.div.childNodes.item(0).childNodes.item(1);
	this.iconoCancelar.horario = this;
	this.periodos = new Array();	//Array de periodos del horario.

	this.arrastrando = false;
	this.celdasArrastradas = new Array();	//Array con las celdas involucradas en el arrastre actual. (Para poder eliminarlas al cancelar el arrastre)
	this.ultimaYArrastre = 0;

	this.enfocar = function(){
	/**-	Pone el foco en el campo visible.
	**/
		this.focus();
	}
	this.habilitar = function(habilitar){
	/**-	Habilita o deshabilita el campo.
	 	@param habilitar Booleano indicado si se habilita (true) o se deshabilita (false) el campo.
	**/
		this.disabled = !habilitar;
	};
	this.limpiar = function(){
	/**- 	Borra el array de horarios y limpia la tabla quitando la clase
	**/
		this.celdasArrastradas = new Array();
		this.arrastrando = false;
		this.periodos = new Array();
		tds = this.div.getElementsByTagName('td');
		for (var i=0; i<tds.length; i++){
			tds[i].removeAttribute('class');
			while(tds[i].hasChildNodes())
				tds[i].removeChild(tds[i].firstChild);
		}
	}
	this.mostrarHorario = function(){
	/**-	Muestra el horario especificado en el campo de texto en el div.
		Descompone el texto del horario y crea el array de periodos.
	**/
		//Migración a JavaScript del constructor de la clase Horario de PHP.
		this.limpiar();
		textoHorario = this.value;
		if (textoHorario != ""){ //Si NO viene cadena vacía, creamos el objeto con la cadena introducida
			//Creamos el array de periodos
			trozos = textoHorario.split(';');
			for(var i=0; i<trozos.length; i++){
				posParentesis1 = trozos[i].indexOf('(');
				posParentesis2 = trozos[i].indexOf(')');
				dias = trozos[i].substr(0, posParentesis1);
				textoHoras = trozos[i].substr(posParentesis1+1,(posParentesis2-posParentesis1)-1);
				rangoHoras = textoHoras.split(',');
				for(var j=0;j<rangoHoras.length;j++){
					horas = rangoHoras[j].split('-');
					if(horas.length != 2) throw("Excepción de Horario: Formato de Mal Construido ("+rangoHoras[j]+")");
					horaInicio = horas[0];
					horaFin = horas[1];
					//Por cada hora de inicio y de fin, con el día que corresponda y creamos el objeto Periodo
					for(var k=0;k<dias.length;k++){
						dia = dias[k];
						periodo = new Periodo(dia, horaInicio, horaFin);
						this.periodos.push(periodo);
					}
				}
			}
			//Mostramos cada periodo
			for (var i=0;i<this.periodos.length;i++){
				dia = this.periodos[i].dia;
				hora = this.periodos[i].horaInicio.split(':')[0];
				min = this.periodos[i].horaInicio.split(':')[1];
				horaFin = this.periodos[i].horaFin.split(':')[0];
				minFin = this.periodos[i].horaFin.split(':')[1];
				//celdaInicial = $(this.id+'_'+dia+hora+min);
				//celda.setAttribute('class','horarioActivo');
				inicial = true;
				while (parseInt(hora+min) < parseInt(horaFin+minFin)){
					celda = $(this.id+'_'+dia+hora+min);
					min = parseInt(min)+15;
					if (min == 60){
						min = '00';
						hora++;
					}
					if (inicial){
						this.ponerCabecera(celda,horaFin+minFin);
						if (parseInt(hora+min) == parseInt(horaFin+minFin))
							celda.setAttribute('class','horarioActivoUnico');
						else
							celda.setAttribute('class','horarioActivoInicial');
					}
					else{
						if (parseInt(hora+min) == parseInt(horaFin+minFin))
							celda.setAttribute('class','horarioActivoFin');
						else
							celda.setAttribute('class','horarioActivo');
					}
					inicial = false;
				}
			}
		}//Si no (viene cadena vacía), ho hacemos nada, o sea, devolvemos obj. vacío (array vacío)
	}
	this.ponerCabecera = function(celda,horaFin){
	/**-	Pone la cabecera de un evento en la celda.
		@param celda Referencia a la celda en la que se pondrá la cabecera.
		@param horaFin Texto con la hora de finalización.
	**/
		//Borramos
		while(celda.hasChildNodes())
			celda.removeChild(celda.firstChild);
		//Ponemos el icono y el texto
		icono = document.createElement('img');
		icono.setAttribute('src','iu/img/bot_cancel.png');
		icono.setAttribute('class','eliminar');
		icono.addEventListener('click',this.eliminar,false);
		icono.horario = this;
		celda.appendChild(icono);
		horaInicio = celda.id.substr(celda.id.indexOf('_')+2,4)
		if(horaInicio.length == 3)
			horaInicio2 = horaInicio[0]+':'+horaInicio[1]+horaInicio[2];
		else
			horaInicio2 = horaInicio[0]+horaInicio[1]+':'+horaInicio[2]+horaInicio[3];
		if (horaFin != ''){
			if(horaFin.length == 3)
				horaFin2 = horaFin[0]+':'+horaFin[1]+horaFin[2];
			else
				horaFin2 = horaFin[0]+horaFin[1]+':'+horaFin[2]+horaFin[3];
		}
		else horaFin2 = '';
		etiqueta = document.createTextNode(horaInicio2+" - "+horaFin2);
		celda.appendChild(etiqueta);
	}
	this.eliminar = function(evento){
		celda = evento.explicitOriginalTarget.parentNode;
		hora = celda.id.substr(celda.id.indexOf('_')+2,4)
		trozoId = celda.id.substr(0, celda.id.indexOf('_')+2);
		//Quitamos la celda de cabecera
		celda.removeAttribute('class');
		while (celda.hasChildNodes())
			celda.removeChild(celda.firstChild);
		hora = this.horario.horaMas15(hora);
		var celda = $(trozoId+hora);
		while(celda != undefined){
			celda.removeAttribute('class');
			hora = this.horario.horaMas15(hora);	
			celda = $(trozoId+hora);
		}
	}
	this.alPulsar = function(){
		if (this.horario.disabled) return;
		if (this.horario.div.style.display == 'block')
			this.horario.div.style.display = 'none';
		else{
			this.horario.mostrarHorario();
			this.horario.div.style.display = 'block';
		}
	}
	this.anadirCeldaArrastrada = function(celda){
	/**-	Añade una celda al array de celdas arrastradas, si no está ya previamente en él.
		@param celda Referencia a la celda a añadir.
		@return Booleano indicando si la celda se añadió (true) o por el contrario ya estaba (false).
	**/
		for (var i=0; i< this.celdasArrastradas.length; i++)
			if (this.celdasArrastradas[i] == celda) return false;
		this.celdasArrastradas.push(celda);
		return true;
	}
	this.quitarCeldaArrastrada = function(celda){
	/**-	Quita una celda al array de celdas arrastradas.
		@param celda Referencia a la celda a añadir.
		@return Booleano indicando si la celda estaba en el array.
	**/
		for (var i=0; i< this.celdasArrastradas.length; i++)
			if (this.celdasArrastradas[i] == celda){
				this.celdasArrastradas[i] = null;
				return true;
			}
		return false;
	}
	this.iniciarArrastre = function(evento){
	/**-	Inicia el estado de arrastre, pero no registra ninguna celda.
	**/
		if (evento.explicitOriginalTarget.id.indexOf(this.horario.id) != 0) return;
		if (evento.explicitOriginalTarget.localName != 'TD') return;
		if (evento.explicitOriginalTarget.getAttribute('class') != null) return;
		this.horario.arrastrando = true;
		this.horario.celdasArrastradas = new Array();
		this.horario.ultimaYArrastre = evento.clientY;
		evento.stopPropagation();
	}
	this.arrastrar = function(evento){
	/**-	Si estamos en estado de arrastre, registra las celdas.
	**/
		if (!evento.explicitOriginalTarget.id) return;
		if (evento.explicitOriginalTarget.id.indexOf(this.horario.id) != 0) return;
		if (evento.explicitOriginalTarget.localName != 'TD') return;
		if (!this.horario.arrastrando) return;
		if (evento.clientY > this.horario.ultimaYArrastre){	//Estamos bajando, añadimos la celda
			if (this.horario.celdasArrastradas.length == 0){	//Es la inicial
				evento.explicitOriginalTarget.setAttribute('class','horarioActivoInicial');
				horaFin = evento.explicitOriginalTarget.id.substr(evento.explicitOriginalTarget.id.indexOf('_')+2,4)
				this.horario.ponerCabecera(evento.explicitOriginalTarget, this.horario.horaMas15(horaFin));
				this.horario.anadirCeldaArrastrada(evento.explicitOriginalTarget);
			}
			else{	//No es la celda inicial
				dia = evento.explicitOriginalTarget.id.substr(evento.explicitOriginalTarget.id.indexOf('_')+1,1);
				diaInicial = this.horario.celdasArrastradas[0].id.substr(this.horario.celdasArrastradas[0].id.indexOf('_')+1,1);
				if (dia != diaInicial) return;
				if (this.horario.anadirCeldaArrastrada(evento.explicitOriginalTarget)){
					evento.explicitOriginalTarget.setAttribute('class','horarioActivo');
					horaFin = evento.explicitOriginalTarget.id.substr(evento.explicitOriginalTarget.id.indexOf('_')+2,4)
					this.horario.ponerCabecera(this.horario.celdasArrastradas[0],this.horario.horaMas15(horaFin));
				}
			}
		}
		else{	//Estamos subiendo, quitamos la celda
			if (this.horario.celdasArrastradas.length != 0){
				dia = evento.explicitOriginalTarget.id.substr(evento.explicitOriginalTarget.id.indexOf('_')+1,1);
				diaInicial = this.horario.celdasArrastradas[0].id.substr(this.horario.celdasArrastradas[0].id.indexOf('_')+1,1);
				if (dia != diaInicial) return;
				this.horario.quitarCeldaArrastrada(evento.explicitOriginalTarget);
				evento.explicitOriginalTarget.removeAttribute('class');
				horaFin = evento.explicitOriginalTarget.id.substr(evento.explicitOriginalTarget.id.indexOf('_')+2,4)
				this.horario.ponerCabecera(this.horario.celdasArrastradas[0],horaFin);
			}
		}
		this.horario.ultimaYArrastre = evento.clientY;
		evento.stopPropagation();
	}
	this.finalizarArrastre = function(evento){
		if (!this.horario.arrastrando) return;
		if (evento.explicitOriginalTarget.id.indexOf(this.horario.id) != 0) return;
		if (evento.explicitOriginalTarget.localName != 'TD') return;
		if (this.horario.celdasArrastradas.length == 0) return;
		this.horario.arrastrando = false;
		dia = evento.explicitOriginalTarget.id.substr(evento.explicitOriginalTarget.id.indexOf('_')+1,1);
		diaInicial = this.horario.celdasArrastradas[0].id.substr(this.horario.celdasArrastradas[0].id.indexOf('_')+1,1);
		if (dia != diaInicial) return;
		evento.explicitOriginalTarget.setAttribute('class','horarioActivoFin');
		horaFin = evento.explicitOriginalTarget.id.substr(evento.explicitOriginalTarget.id.indexOf('_')+2,4)
		this.horario.ponerCabecera(this.horario.celdasArrastradas[0],this.horario.horaMas15(horaFin));
		this.horario.celdasArrastradas = new Array();
		evento.stopPropagation();
	}
	this.horaMas15 = function(textoHora){
	/**-	Devuelve una hora incrementada en 15 minutos
		@param hora Texto de la hora en formato 845.
		@return Texto de hora incrementado en 15 minutos en formato 900
	**/
		if (textoHora.length == 4){
			hora = textoHora.substr(0,2);
			min = textoHora.substr(2,2);
		}
		else{
			hora = textoHora.substr(0,1);
			min = textoHora.substr(1,2);
		}
		if (min == '45'){
			min = '00';
			hora = parseInt(hora)+1;
		}else
			min = parseInt(min)+15
		return hora+min;
	}
	this.ver = function ver(){
	/**-	Muestra el horario en el formato de texto a partir del array de periodos.
		@return Texto con el horario.
	**/
		//Migración de Horario->ver en PHP
		rangos = new Array(); 	//array de rangos horarios coincidentes en horas
		patron = ""; 		//texto formado por "horaInicio-horaFin" del objeto periodo para las comparaciones
		patrones = new Array(); //array de textos formado por horaInicio-horaFin ($patron)
		dias = ""; 		//texto donde guardamos los dias de los periodos que coincidan en el horario
		//Recorremos array de Periodos comparándolos entre ellos y los vamos agrupando (burbuja)
		for(var i=0; i<this.periodos.length; i++){
			patron = this.periodos[i].horaInicio+"-"+this.periodos[i].horaFin;
			var esta = false;
			for (var j=0; j<patrones.length; j++)
				if (patrones[j] == patron){
					esta = true;
					break;
				}
			if (!esta){ 
				//Como no está en patrones, lo insertamos y empezamos a comparar
				patrones.push(patron);
				dias = ""; //texto donde guardamos los dias de los periodos que coincidan en el horario
				for(var j=0; j<this.periodos.length; j++){
					patronBusqueda = this.periodos[j].horaInicio+"-"+this.periodos[j].horaFin;
					if (patron == patronBusqueda) //Coinciden, insertamos el dia
						dias += this.periodos[j].dia;
				}
				//Ya tenemos los dias del horario de $patron, insertamos el rango en su formato
				rango = dias+"("+patron+")";
				//Recorremos el array de rangos para ver si coinciden los dias para agruparlos
				agrupado = false; //bandera para indicar si el rango se ha agrupado
				for(var j=0;j<rangos.length;j++){
					posParentesis = rangos[j].indexOf('(');
					diasBusqueda = rangos[j].substr(0,posParentesis);
					if (dias == diasBusqueda){ //Coinciden, agrupamos las horas
						posParentesis2 = rangos[j].indexOf(')');
						textoHoras = rangos[j].substr(posParentesis+1,(posParentesis2-posParentesis)-1);
						rangos[j] = dias+"("+textoHoras+","+patron+")";
						agrupado = true;
					}
				}
				if (!agrupado)
					rangos.push(rango);
			} 
			//Ya tenemos un array de rangos horarios
		}
		//Convertimos los rangos en una sóla cadena
		horario = "";
		for (var i=0; i<rangos.length; i++)
			horario += rangos[i]+";";
		if (horario != "") horario = horario.substr(0, horario.length-1);
//echo "\n\n EL horario es : $horario.\n\n";
	return horario;	
	}
	this.aceptar = function(){
	/**-	Procesa la aceptación del horario modificado.
		Crea un array de peridos buscando en el div de horario y transforma el array en un texto de valor.
		this es la imagen del icono.
	**/
		this.horario.value = 'procesando...';
		tds = this.horario.div.getElementsByTagName('td');
		this.horario.periodos = new Array();
		for (var i=0; i<tds.length; i++){
			celda = tds[i];
			if (celda.getAttribute('class') == 'horarioActivoInicial'){
				var texto = celda.childNodes.item(1).nodeValue; 
				var horas = texto.split('-');
				var dia = celda.id.substr(celda.id.indexOf('_')+1,1)
				periodo = new Periodo(dia,horas[0].replace(' ',''),horas[1].replace(' ',''));
				this.horario.periodos.push(periodo);
			}
		}
		this.horario.value = this.horario.ver();
		this.horario.div.style.display = 'none';
		this.horario.onchange();	//Lanzamos el evento.
	}
	// Eventos de la clase
	if (this.img.addEventListener){
		this.img.addEventListener('click',this.alPulsar,false);
		this.iconoCancelar.addEventListener('click',this.alPulsar,false);
		this.iconoAceptar.addEventListener('click',this.aceptar,false);
		this.div.addEventListener('mousedown',this.iniciarArrastre,false);
		this.div.addEventListener('mousemove',this.arrastrar,false);
		this.div.addEventListener('mouseup',this.finalizarArrastre,false);
	}
	else{ //Para IE
		this.img.attachEvent('onclick',this.alPulsar);
		this.iconoCancelar.attachEvent('onclick',this.alPulsar);
	}
}
function Periodo(dia, horaInicio, horaFin){
/**-	Clase que implementa un periodo de tiempo.
	Migración de la clase Periodo de PHP.
	@param dia Caracter con la letra que indica el día del periodo.
	@param horaInicio Hora de inicio del periodo, con el formato HH:MM en 24 horas.
	@param horaFin Hora de fin del periodo, con el formato HH:MM en 24 horas.
**/
	this.dia;
	this.horaInicio;
	this.horaFin;
	this.dias = new Array('L','M','X','J','V','S','D');
	this.formatoHora = /^([0-9]|0[0-9]|1\d|2[0-3]):([0-5]\d)$/;	//Expresión regular de formato de hora

	//Comprobamos el día
	esta = false;
	for(var i=0;i<this.dias.length;i++)
		if (this.dias[i] == dia.toUpperCase()){
			esta = true;
			break;
		}
	if (!esta) throw('Excepción de Perido: Día Inválido('+dia+')');

	//Comprobamos los formatos de la horaInicio y horaFin
	if(!this.formatoHora.test(horaInicio)) throw('Excepción de Periodo: Formato de Hora de Inicio Inválido('+horaInicio+')');
	if(!this.formatoHora.test(horaFin)) throw('Excepción de Periodo: Formato de Hora de Fin Inválido('+horaFin+')');

	this.dia = dia.toUpperCase();
	//Quitamos los '0' por la izquierda a $horaInicio
	if ((horaInicio[0] == '0') && (horaInicio.length > 4)) horaInicio = horaInicio.substr(1, horaInicio.length -1);
	this.horaInicio = horaInicio;
	//Quitamos los '0' por la izquierda a $horaFin
	if ((horaFin[0] == '0') && (horaFin.length > 4)) horaFin = horaFin.substr(1, horaFin.length -1);
	this.horaFin = horaFin;
}
function Comunicador(id, nombre){
/**-	Crea un objeto de comunicación
	@param id Nombre del Identificador del elemento <img> del comunicador.
	@param nombre Nombre de la variable JavaScript que representa el Comunicador.
**/
	this.id = id;
	this.nombre = nombre;

	this.TEXTO = 1;
	this.AVISO = 2;
	this.ALERTA = 3;
	
	this.contadorEspera = null;
	this.contadorDormir = null;
	this.estado = 'espera';
	this.alerta_comprobacion = false;

	this.iniciar = function(){
		this.img = $(this.id+'Img');
		this.texto = $(this.id+'Texto');
		this.texto.innerHTML = '';
		this.pico = $(this.id+'Pico');
		if (iu.verCookie(this.NOMBRE_COOKIE) != 1)	//Comprobamos si hemos hecho la aparición.
			this.aparecer();
		else
			this.esperar();
	}
	this.aparecer = function(){
	/**-	Hace aparecer el comunicador y lo pone en modo espera.
	**/
		iu.ponerCookie(this.NOMBRE_COOKIE, 1);
		this.img.setAttribute("class",this.CSS_APARECER);
		this.ponerTimerEsperar();
	}
	this.ponerTimerDormir = function(){
	/**-	Activa un contador para poner al comunicador en espera
		También quita el contador de espera anterior.
	**/
		if (this.contadorDormir)
			clearTimeout(this.contadorDormir);
		this.contadorDormir = setTimeout(this.nombre + '.dormir()', this.TIEMPO_DORMIR*1000);

	}
	this.ponerTimerEsperar = function(){
	/**-	Activa un contador para ponerse a esperar
		También quita el contador anterior si lo hay para evitar que se borre antes el mensaje.
	**/
		if (this.contadorEsperar)
			clearTimeout(this.contadorEsperar);
		this.contadorEsperar = setTimeout(this.nombre + '.esperar()', this.TIEMPO_PERMANENCIA*1000);
	}
	this.esperar = function(){
	/**-	Actitud normal del Comunicador.
	**/
		this.estado = 'espera'
		this.texto.innerHTML = ''; //Borramos el texto, si lo hay
		this.cambiarClase(this.CSS_ESTAR);
	}
	this.cambiarClase = function(clase){
	/**-	Cambia de clase CSS a todos los elementos del comunicador
		@param clase Nombre de la clase
	**/
		this.img.setAttribute('class',clase);
		this.texto.setAttribute('class',clase);
		this.pico.setAttribute('class',clase);
	}
	this.decir = function(tipo, texto){
	/**- 	Expresa el texto por el comunicador.
		@param tipo Tipo de comunicación. Uno de los tipos predefinidos.
		@param texto Texto del mensaje a expresar.
	**/
		if (typeof(texto)=="object"){
			var aleatorio = Math.floor(Math.random()*texto.length); 		
			this.texto.innerHTML = texto[aleatorio]+"<br>" + this.texto.innerHTML;				
			}		
		else{
			if (this.texto.innerHTML == '')
				this.texto.innerHTML = texto;
			else
				this.texto.innerHTML = texto +"<hr/>" + this.texto.innerHTML;
		}
		this.ponerTimerEsperar();	
		this.ponerTimerDormir();
		}
	this.hablar = function(texto){
		if (this.estado == 'espera')
			this.cambiarClase(this.CSS_HABLAR);
		this.decir(this.TEXTO,texto);
	}
	this.avisar = function(texto){
		if (this.estado != 'alerta'){
			this.cambiarClase(this.CSS_AVISAR);
			this.estado = 'aviso';
		}
		this.decir(this.AVISO,texto);
	}
	this.alertar = function(texto){
		this.estado = 'alerta';
		this.cambiarClase(this.CSS_ALERTAR);
		this.decir(this.ALERTA,texto);
	}
	this.dormir = function(){
	/**-	Pone el Comunicador en modo dormir.
	**/
		this.cambiarClase(this.CSS_DORMIR);
	}
}
