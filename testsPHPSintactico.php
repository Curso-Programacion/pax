<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es" lang="es">
	<head>
		<title>Pruebas Sintácticas de PHP</title>
		<script type="text/JavaScript">
			intervalo = 1000;
			script = new Array();
			//{ Una lista con todos los archivos que hay que revisar
			//Lo ideal sería que se generase automáticamente con php
			script.push("config.php");
			<?php
				procesarDirectorio(getcwd());	//Empezamos en este directorio
				
				function procesarDirectorio($dir){
				// La recursión es humana, la iteración divina.
					$gd = opendir($dir);
					while (($nombreArchivo = readdir($gd)) !== false){
						if ($nombreArchivo == 'testsPHPSintactico.php') continue;	//Nos evitamos
						if ($nombreArchivo == 'PHPUnit') continue;			//Evitamos este directorio

						$pathArchivo = $dir.'/'.$nombreArchivo;
						if (is_dir($pathArchivo)){
							if (($nombreArchivo == '.') || ($nombreArchivo == '..')) continue;
							procesarDirectorio($pathArchivo);
						}
						if (is_dir($pathArchivo)) procesarDirectorio($pathArchivo);
						if (substr($nombreArchivo,strlen($nombreArchivo)-4,4) == '.php'){
							//Convertimos el path en URL
							$url = substr($pathArchivo,strlen('/home/mjaque/Personal/Ilke_Benson/'));
							$url = 'http://localhost/'.$url;
							echo "script.push('$url');";
						}
					}
				}
			?>
			//}
			indice = 0;

			function cargar(){
				if (indice < script.length){
					escribir(indice+" - Cargando "+script[indice]);
					document.getElementById("iframeTest").src = script[indice];
					indice++;
				}
			}
			function escribir(texto, tipo){
				salida = "<p";
				if (tipo == 'error')
					salida += " style='margin-left:25px;color:red'>";
				else
					salida += ">";
				salida += texto+"</p>";
				document.getElementById("salida").innerHTML += salida;
			}
			function comprobar(){
			/**-	Comprueba la corrección de un script de php después de cargarlo.
				Y pasa al siguiente.
			**/
				codigo = document.getElementById("iframeTest").contentDocument.firstChild.innerHTML;
				if (codigo != undefined){
					if (codigo.match(/Parse error/)){
						escribir("Error Sintáctico", 'error');
						return;
					}
					if (codigo.match(/Can't use method/)){
						escribir("Error Fatal", 'error');
						return;
					}
				}
				window.setTimeout(cargar, intervalo);
			}

			window.setTimeout(cargar, intervalo);

			//TODO: Detectar el 404 en el iframe, por si falta algún script o su nombre está mal escrito.
		</script>
	</head>
	<body>
		<div id="salida" style="width:40%; float:right; font-size:10pt; line-height:1em;">Resultados:<br/></div>
		<iframe style="width:40%; height:400px; float:left;" id="iframeTest" onload="comprobar()"/>
	</body>
	
</html>
