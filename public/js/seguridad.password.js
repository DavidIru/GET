var numeros = "0123456789";
var letras = "abcdefghyjklmnñopqrstuvwxyz";
var letras_mayusculas = "ABCDEFGHYJKLMNÑOPQRSTUVWXYZ";
var simbolos = ".:,;-_[]{} ¡!¿?#@|$%&/()=\\<>*+^ç\"'"; 

function tiene_numeros(texto) {
   for(i = 0; i < texto.length; i++) {
      if(numeros.indexOf(texto.charAt(i), 0) != -1) {
         return 1;
      }
   }
   return 0;
} 

function tiene_letras(texto) {
   texto = texto.toLowerCase();
   for( i = 0; i < texto.length; i++) {
      if(letras.indexOf(texto.charAt(i), 0) != -1) {
         return 1;
      }
   }
   return 0;
} 

function tiene_minusculas(texto) {
   for(i = 0; i < texto.length; i++) {
      if(letras.indexOf(texto.charAt(i), 0) != -1) {
         return 1;
      }
   }
   return 0;
} 

function tiene_mayusculas(texto) {
   for(i = 0; i < texto.length; i++) {
      if(letras_mayusculas.indexOf(texto.charAt(i), 0) != -1) {
         return 1;
      }
   }
   return 0;
}

function tiene_simbolos(texto) {
   for(i = 0; i < texto.length; i++) {
      if(simbolos.indexOf(texto.charAt(i), 0) != -1) {
         return 1;
      }
   }
   return 0;
}

function seguridad_clave(clave) {
	var seguridad = 0;
	if(clave.length != 0) {
		if(tiene_numeros(clave) && tiene_letras(clave)) {
			seguridad += 20;
		}
		if(tiene_minusculas(clave) && tiene_mayusculas(clave)) {
			seguridad += 20;
      }
      if(tiene_simbolos(clave)) {
         seguridad += 25;
      }
		if(clave.length >= 4 && clave.length <= 6) {
			seguridad += 10;
		}
      else {
			if(clave.length >= 7 && clave.length <= 10) {
				seguridad += 20;
			}
         else {
				if(clave.length > 8) {
					seguridad += 35;
				}
			}
		}
	}
	return seguridad				
}