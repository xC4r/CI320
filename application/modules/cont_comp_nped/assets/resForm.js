var ruta = "cont_comp_nped/main/";
//JS Events
var localData;
var DataContainer = (function() { 
	function DataContainer() {};
	var DataContainer = {}
  	DataContainer.ID = 1;
	DataContainer.Serial = IdGen(50);
	DataContainer.codUsuario = "usuariomaestro";
	DataContainer.desUsuario = "";
	DataContainer.docUsuario = "123313312";
	DataContainer.fecha = new Date();
	DataContainer.actividad = {
		"tipo": "usuario",
		"documento": "00000000",
		"nombre": "nombre_de_usuario",
		"url": "http://sky.com/main",
		"cookie": "rO0ABXQA701GcmNEbDZPZ28xODJOWWQ4aTNPT2krWUcrM0peG0zNkNaM0NZL0RXa1FZOGNJOWZsYjB5ZXc3MVNaTUpxWURmNGF3dVlDK3pMUHdveHI2cnNIaWc1CkI3SkxDSnc9",
		"postal": null
	};
	DataContainer.formulario = "";
	DataContainer.ipaddress = "";
	DataContainer.address = "";
	DataContainer.xcoords = "";
  	return DataContainer;
});

var dataFormUsuario= new DataContainer;

$(function () {
	fetchGet(ruta+'defaultLoad').then(json => {
		if(json.cod === 200){	
			//localStorage.setItem("localData", JSON.stringify(localData));
			localData = json.res;
			cargarListaUsuario(json.res.lstNotas,'tabNotaPedido');
			//formSelectLoad('txtEmpresa',json.res.lstEmpresa,'num','des');
			//formSelectLoad('txtRol',json.res.lstRol,'num','des');
			//formSelectLoad('txtEstado',json.res.lstEstado,'cod','des');
		}else if(json.cod == 401){
			$(expiredSession).modal('show');
		}else{
			snackAlert(json.msg,'warning');
		}
	});
});

document.getElementById('btnPDF').onclick = function(){
	
	fetch(ruta+'generarPDF')
	.then(response => response.blob())
	.then(blob => {
			var file = new Blob([blob], {type: "application/pdf"});
			var fileURL = window.URL.createObjectURL(file);
			window.open(fileURL, "_blank");
	});
}

document.getElementById('txtBuscar').onkeyup = function(){
	tableFilter('tabNotaPedido',this.value,5);
}

document.getElementById('btnBuscar').onclick = function(){
	txt = document.getElementById('txtBuscar').value;
	listarUsuario('tabNotaPedido',txt);
}

document.getElementById('btnExport').onclick = function(){
	tableToCsv('tabNotaPedido','export.csv');
}

document.getElementById('btnReload').onclick = function(){
	txt = document.getElementById('tabNotaPedido').getAttribute('data');
 	listarUsuario('tabNotaPedido',txt);
}
// Modal Registro Eventos
document.getElementById('formNotaPedido').onsubmit = function(e) {
	e.preventDefault();
}

document.getElementById('btnRegistrar').onclick = function() {
	if(document.getElementById('formNotaPedido').checkValidity()){
		if(!document.getElementById('txtUsuario').hasAttribute('ind')){
			document.querySelector(confirmFormMensaje).innerHTML = msgConfirmacion.guardar;
			document.getElementById(confirmForm).setAttribute('act',1);
		}else{
			document.querySelector(confirmFormMensaje).innerHTML = msgConfirmacion.modificar;
			document.getElementById(confirmForm).setAttribute('act',2);
		}
		$($confirmForm).modal('show');
	}
}
//Bootstrap Events
$('#addNotaPedido').on('hidden.bs.modal', function () {
	document.getElementById("formNotaPedido").reset();
	document.getElementById("txtEmpresa").selectedIndex = '-1';
	document.getElementById("txtRol").selectedIndex = '-1';
	document.getElementById("txtEstado").selectedIndex = '-1';
	document.getElementById('txtUsuario').removeAttribute('ind');
});

$($confirmForm).on('show.bs.modal', function () {
	document.getElementById("addNotaPedido").classList.remove("show");
});

$($confirmForm).on('hide.bs.modal', function () {
	document.getElementById("addNotaPedido").classList.add("show");
	this.removeAttribute('act');
	this.querySelector('div div div.modal-body').innerHTML = '';
});

document.querySelector(confirmFormAceptar).onclick = function() {
	$(progressForm).modal('show');
	var act = document.getElementById(confirmForm).getAttribute('act');
	var txt = document.getElementById('tabNotaPedido').getAttribute('data');
	$('#addNotaPedido').modal('hide');
	var msg;
	if(act == 1){ 
		msg = msgRespuesta.guardado;
	}else if(act == 2){
		msg = msgRespuesta.modificado;
	}else if(act == 0){  
		msg = msgRespuesta.eliminado;
	}else{
		snackAlert('No hay accion para ejecutar');
	}
	var formData = new FormData(document.getElementById('formNotaPedido'));
	formData.append('act',act);
	formData.append('txt',txt);
	if (act == 0) formData.set('txtUsuario',document.getElementById(confirmForm).getAttribute('cod'));
	var PostData = setPostData(dataFormUsuario,'formulario',formData); // params: object container, object formData;
	let count = 0;
	formData.forEach(function(){count++;});
	if(count>0){
		fetchPost(ruta +'operacion',PostData).then(json => {
			if(json.cod == 200){
				cargarListaUsuario(json.res.lstNotas,'tabNotaPedido',txt);
				snackAlert(msg,'success');
			}else{
				snackAlert(json.msg,'danger');
			}
		});
	}else{
		  snackAlert('No hay datos para registrar','warning');
	}

	$($confirmForm).modal('hide');
	setTimeout(function(){ $(progressForm).modal('hide'); }, 500);
}
//Eventos opciones datatable
$(document).on('click','#tabNotaPedido div table tbody tr td button.edit', function() {
    let cod = ((this.parentNode).parentNode).childNodes[1].innerHTML;
    localData.lstNotas.forEach(function(row) {
    	if(row['cod'] == cod){
    		document.getElementById('txtNombres').value = row['nom'];
    		document.getElementById('txtDocumento').value = row['doc'];
    		document.getElementById('txtCorreo').value = row['eml'];
    		document.getElementById('txtEmpresa').value = row['emp'];
    		document.getElementById('txtUsuario').value = row['cod'];
			document.getElementById('txtUsuario').setAttribute('ind','');
    		document.getElementById('txtPassword').value = row['pas'];
    		document.getElementById('txtRol').value = row['rol'];
    		document.getElementById('txtEstado').value = row['est'];
    	}
    });
    $('#addNotaPedido').modal('show');
});

$(document).on('click','#tabNotaPedido div table tbody tr td button.del', function() { 
    let cod = ((this.parentNode).parentNode).childNodes[1].innerHTML;
	document.getElementById(confirmForm).setAttribute('cod',cod);
    document.getElementById(confirmForm).setAttribute('act',0);
    document.querySelector(confirmFormMensaje).innerHTML = msgConfirmacion.eliminar;
    $($confirmForm).modal('show');
});
//Functions
function listarUsuario(datatable,txt='default'){
	txt = txt.trim();
	if(txt.length>=3){
		fetchGet(ruta +'cargarUsuarios?txt='+txt).then(json => {
	        if(json.cod == 200){
	        	cargarListaUsuario(json.res.lstNotas,datatable,txt);
	        }else{
	            snackAlert(json.msg,'warning');
	        }
		});
	}else if(txt.length > 0){
	  	snackAlert('La descripción es muy corta','warning');
	}else{
	  	snackAlert('La descripción esta vacia','warning');
	}
}
function cargarListaUsuario(lstNotas,datatable,txt='default'){
	localData.lstNotas = lstNotas;
	for (var i = 0; i < lstNotas.length; i++) {
		lstNotas[i]['desEmp'] = '';
		lstNotas[i]['desEst'] = '';
		lstNotas[i]['desRol'] = '';
		localData.lstEmpresa.forEach(function(emp){
			if(lstNotas[i]['emp'] == emp['num']){
				lstNotas[i]['desEmp'] = emp['des'];
			}
		});
		localData.lstEstado.forEach(function(est){
			if(lstNotas[i]['est'] == est['cod']){
				lstNotas[i]['desEst'] = est['des'];
			}
		});
		localData.lstRol.forEach(function(rol){
			if(lstNotas[i]['rol'] == rol['num']){
				lstNotas[i]['desRol'] = rol['des'];
			}
		});
	}
	var datafields = [
	    {key: 'cod', name: 'Codigo', type: 'string', hidden: true},
	    {key: 'nom', name: 'Nombres', type: 'string'},
	    {key: 'eml', name: 'Correo Electronico', type: 'string'},
	    {key: 'doc', name: 'Documento Identificación', type: 'string'},
	    {key: 'desEmp', name: 'Empresa', type: 'string'},
	    {key: 'desEst', name: 'Estado', type: 'string'},
	    {key: 'desRol', name: 'Rol', type: 'string'}
	];
	var options = [
	    {btn:'primary', fa:'fa-pencil', fn:'edit'},
		{btn:'secondary', fa:'fa-file-pdf-o', fn:'pdf'},
	    {btn:'danger', fa:'fa-trash-o', fn:'del'}
	];
	datatableLoad(lstNotas,datatable,datafields,options,true);
	document.getElementById(datatable).setAttribute('data',txt);
	tablePagination(datatable,true,5);
}
