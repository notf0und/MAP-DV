function datediff(fromDate,toDate,interval) { 
	/*
	 * DateFormat month/day/year hh:mm:ss
	 * ex.
	 * datediff('01/01/2011 12:00:00','01/01/2011 13:30:00','seconds');
	 */
	var second=1000, minute=second*60, hour=minute*60, day=hour*24, week=day*7; 
	fromDate = new Date(fromDate); 
	toDate = new Date(toDate); 
	var timediff = toDate - fromDate; 
	if (isNaN(timediff)) return NaN; 
	switch (interval) { 
		case "years": return toDate.getFullYear() - fromDate.getFullYear(); 
		case "months": return ( 
			( toDate.getFullYear() * 12 + toDate.getMonth() ) 
			- 
			( fromDate.getFullYear() * 12 + fromDate.getMonth() ) 
		); 
		case "weeks"  : return Math.floor(timediff / week); 
		case "days"   : return Math.floor(timediff / day);  
		case "hours"  : return Math.floor(timediff / hour);  
		case "minutes": return Math.floor(timediff / minute); 
		case "seconds": return Math.floor(timediff / second); 
		default: return undefined; 
	} 
}

function setSessionValue(nombre, valor, fallBack) {
	$.post("../lib/sessionLib.php", { nombre: nombre, valor: valor }, fallBack );
}

function deleteRowEvent(tabla, nombre, valor) {
	var r=confirm("Confirma eliminar?")
	if (r==true){
		if (document.forms.namedItem(tabla+'Form').elements[nombre] == null) {
			$('form[name="'+tabla+'Form"]').append('<input type="hidden" name='+nombre+'>');
		}
		$('form[name="'+tabla+'Form"]').append('<input type="hidden" name="accion" value="'+tabla+'Delete">');
		document.forms.namedItem(tabla+'Form').elements[nombre].value = valor;
		document.forms.namedItem(tabla+'Form').submit();
	}
	//alert(tabla + ': ' + nombre + ': ' + valor);
}

function modifyRowEvent(tabla, nombre, valor) {
	if (document.forms.namedItem(tabla+'Form').elements[nombre] == null) {
		$('form[name="'+tabla+'Form"]').append('<input type="hidden" name='+nombre+'>');
	}
	$('form[name="'+tabla+'Form"]').append('<input type="hidden" name="accion" value="'+tabla+'Modify">');
	document.forms.namedItem(tabla+'Form').elements[nombre].value = valor;
	document.forms.namedItem(tabla+'Form').submit();
	//alert(tabla + ': ' + nombre + ': ' + valor);
}
/*
function modifyRowEvent(tabla, nombre, valor) {

		if (document.forms.namedItem(tabla+'Form').elements[nombre] == null) {
			$('form[name="'+tabla+'Form"]').append('<input type="hidden" name='+nombre+'>');
		}
		$('form[name="'+tabla+'Form"]').append('<input type="hidden" name="accion" value="'+tabla+'Modify">');
		document.forms.namedItem(tabla+'Form').elements[nombre].value = valor;
		document.forms.namedItem(tabla+'Form').submit();
		alert(tabla + ': ' + nombre + ': ' + valor);
}
*/
function modificarAccion(objetivoIndex, accionID, urlOrigen) {
		$('form[name="formulario"]').append('<input type="hidden" name="accionID" value="'+accionID+'">');
		$('form[name="formulario"]').append('<input type="hidden" name="urlOrigen" value="'+urlOrigen+'">');
		if ( accionID == '-1' ) {
			document.forms.formulario.accion.value='nuevaAccion';
		} else {
			document.forms.formulario.accion.value='modificarAccion';
		}
		document.forms.formulario.objetivoIndex.value=objetivoIndex;
		document.forms.formulario.submit();
}

function modifyUsuario(tabla, nombre, valor) {
		if (document.forms.namedItem(tabla+'Form').elements[nombre] == null) {
			$('form[name="'+tabla+'Form"]').append('<input type="hidden" name=modifyUsuario['+nombre+']>');
		}
		document.forms.namedItem(tabla+'Form').elements['modifyUsuario['+nombre+']'].value = valor;
		document.forms.namedItem(tabla+'Form').submit();
	//	alert(tabla + ': ' + nombre + ': ' + valor);
}

function agregarContacto() {
	setSessionValue('contacto', {'id': -1});
	myForm=document.createElement("form");
	myForm.method='post';
	input1=document.createElement("input");
	input1.type='hidden';
	input1.name='accion';
	input1.value='agregarContacto';
	myForm.appendChild(input1);
	myForm.submit();
}

function agregarSesion() {
	setSessionValue('sesion', {'id': -1});
	myForm=document.createElement("form");
	myForm.method='post';
	input1=document.createElement("input");
	input1.type='hidden';
	input1.name='accion';
	input1.value='agregarSesion';
	myForm.appendChild(input1);
	myForm.submit();
}

function agregarAviso() {
	setSessionValue('aviso', {'id': -1});
	input1=document.createElement("input");
	input1.type='hidden';
	input1.name='accion';
	input1.value='agregarAviso';
	document.forms.Alta.appendChild(input1);
	document.forms.Alta.submit();
}

function modificarSesion(sesionID) {
	setSessionValue('sesion', {'id': sesionID}, function(data) {
	myForm=document.createElement("form");
	myForm.method='post';
	input1=document.createElement("input");
	input1.type='hidden';
	input1.name='accion';
	input1.value='modificarSesion';
	myForm.appendChild(input1);
	myForm.submit();
		});
}

function modificarAviso(avisoID) {
	setSessionValue('aviso', {'id': avisoID}, function(data) {
	myForm=document.createElement("form");
	myForm.method='post';
	input1=document.createElement("input");
	input1.type='hidden';
	input1.name='accion';
	input1.value='modificarAviso';
	myForm.appendChild(input1);
	myForm.submit();
		});
}

function modificarContacto(contactoID) {
	setSessionValue('contacto', {'id': contactoID}, function(data) {
	myForm=document.createElement("form");
	myForm.method='post';
	input1=document.createElement("input");
	input1.type='hidden';
	input1.name='accion';
	input1.value='modificarContacto';
	myForm.appendChild(input1);
	myForm.submit();
		});
}

function guardarSesion() {
	input1=document.createElement("input");
	input1.type='hidden';
	input1.name='accion';
	input1.value='guardarSesion';
	document.forms.Alta.appendChild(input1);
	document.forms.Alta.submit();
}

function guardarContacto() {
	if (contactoValido()) {
		input1=document.createElement("input");
		input1.type='hidden';
		input1.name='accion';
		input1.value='guardarContacto';
		document.forms.Alta.appendChild(input1);
		document.forms.Alta.submit();
	}
}

function guardarAviso() {
	input1=document.createElement("input");
	input1.type='hidden';
	input1.name='accion';
	input1.value='guardarAviso';
	document.forms.Alta.appendChild(input1);
	document.forms.Alta.submit();
}

function cancelarContacto() {
	myForm=document.createElement("form");
	myForm.method='post';
	input1=document.createElement("input");
	input1.type='hidden';
	input1.name='accion';
	input1.value='cancelarContacto';
	myForm.appendChild(input1);
	myForm.submit();
}

function cancelarSesion() {
	myForm=document.createElement("form");
	myForm.method='post';
	input1=document.createElement("input");
	input1.type='hidden';
	input1.name='accion';
	input1.value='cancelarSesion';
	myForm.appendChild(input1);
	myForm.submit();
}

function cancelarAviso() {
	input1=document.createElement("input");
	input1.type='hidden';
	input1.name='accion';
	input1.value='cancelarAviso';
	document.forms.Alta.appendChild(input1);
	document.forms.Alta.submit();
}

function setDiasDeAviso() {
	idAviso = document.forms.Alta.elements.namedItem('aviso[idrr_avisos]').value;
	fecha = document.forms.Alta.elements.namedItem('sesion[fecha]').value;
	$.post("../lib/sessionLib.php", { accion: 'fechaDeAviso', idAviso: idAviso, fechaDeSesion: fecha }, 
		function (data) {
			document.forms.Alta.elements.namedItem('aviso[fechaDeAviso]').value = $.trim(data);
			} );
}

function contactoValido() {
  if (document.forms.Alta.nombres.value==''){
    alert('Te olvidaste ingresar el nombre');
    document.forms.Alta.nombres.focus();
    return false;
  }
  if (document.forms.Alta.email.value==''){
    alert('Te olvidaste ingresar el mail');
    document.forms.Alta.email.focus();
    return false;
  }
  return true;
}
