const ruta = "syst_user/main/";
//JS Events
let localData;
let DataContainer = (function() { 

	let DataContainer = {}
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
		"url": "https://sky.com/main",
		"cookie": "rO0ABXQA701GcmNEbDZPZ28xODJOWWQ4aTNPT2krWUcrM0peG0zNkNaM0NZL0RXa1FZOGNJOWZsYjB5ZXc3MVNaTUpxWURmNGF3dVlDK3pMUHdveHI2cnNIaWc1CkI3SkxDSnc9",
		"postal": null
	};
	DataContainer.formulario = "";
	DataContainer.ipaddress = "";
	DataContainer.address = "";
	DataContainer.xcoords = "";
  	return DataContainer;
});

let dataFormUsuario= new DataContainer;

$(function () {
	fetchGet(ruta+'defaultLoad').then(json => {
		if(json.cod === 200){	
			localData = json.res;
			listarUsuario('tabUsuario');
			formSelectLoad('txtEmpresa',json.res.lstEmpresa,'num','des');
			formSelectLoad('txtRol',json.res.lstRol,'num','des');
			formSelectLoad('txtEstado',json.res.lstEstado,'cod','des');
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
			let file = new Blob([blob], {type: "application/pdf"});
			let fileURL = window.URL.createObjectURL(file);
			window.open(fileURL, "_blank");
	});
}

document.getElementById('txtBuscar').onkeyup = function(){
	tableFilter('tabUsuario',this.value,5);
}

document.getElementById('btnBuscar').onclick = function(){
	let txt = document.getElementById('txtBuscar').value;
	listarUsuario('tabUsuario',txt);
}

document.getElementById('btnExport').onclick = function(){
	tableToCsv('tabUsuario','export.csv');
}

document.getElementById('btnReload').onclick = function(){
	let txt = document.getElementById('tabUsuario').getAttribute('data');
 	listarUsuario('tabUsuario',txt);
}
// Modal Registro Eventos

document.getElementById('formRegistro').onsubmit = function(e) {
	e.preventDefault();
}

document.getElementById('btnRegistrar').onclick = function() {
	if(document.getElementById('formRegistro').checkValidity()){
		if(!document.getElementById('txtUsuario').hasAttribute('ind')){
			document.querySelector(confirmFormMensaje).innerHTML = msgConfirmacion.guardar;
			document.getElementById(confirmFormId).setAttribute('act',1);
		}else{
			document.querySelector(confirmFormMensaje).innerHTML = msgConfirmacion.modificar;
			document.getElementById(confirmFormId).setAttribute('act',2);
		}
		$(confirmForm).modal('show');
	}
}
//Bootstrap Events
$('#addModal').on('hidden.bs.modal', function () {
	document.getElementById("formRegistro").reset();
	document.getElementById("txtEmpresa").selectedIndex = '-1';
	document.getElementById("txtRol").selectedIndex = '-1';
	document.getElementById("txtEstado").selectedIndex = '-1';
	document.getElementById('txtUsuario').removeAttribute('ind');
});
/*
$(confirmForm).on('show.bs.modal', function () {
	document.getElementById("addModal").classList.remove("show");
});
*/
$(confirmForm).on('hide.bs.modal', function () {
	//document.getElementById("addModal").classList.add("show");
	this.removeAttribute('act');
	this.querySelector('div div div.modal-body').innerHTML = '';
});

document.querySelector(confirmFormAceptar).onclick = function() {
	$(progressForm).modal('show');
	let act = document.getElementById(confirmFormId).getAttribute('act');
	let txt = document.getElementById('tabUsuario').getAttribute('data');
	$('#addModal').modal('hide');
	let msg;
	if(act == 1){ 
		msg = msgRespuesta.guardado;
	}else if(act == 2){
		msg = msgRespuesta.modificado;
	}else if(act == 0){  
		msg = msgRespuesta.eliminado;
	}else{
		snackAlert('No hay accion para ejecutar');
	}
	let formData = new FormData(document.getElementById('formRegistro'));
	formData.append('act',act);
	formData.append('txt',txt);
	if (act == 0) formData.set('txtUsuario',document.getElementById(confirmFormId).getAttribute('cod'));
	let PostData = setPostData(dataFormUsuario,'formulario',formData); // params: object container, object formData;
	let count = 0;
	formData.forEach(function(){count++;});
	if(count>0){
		fetchPost(ruta +'operacion',PostData).then(json => {
			if(json.cod == 200){
				cargarListaUsuario(json.res.lstUser,'tabUsuario',txt);
				snackAlert(msg,'success');
			}else{
				snackAlert(json.msg,'danger');
			}
		});
	}else{
		  snackAlert('No hay datos para registrar','warning');
	}

	$(confirmForm).modal('hide');
	setTimeout(function(){ $(progressForm).modal('hide'); }, 500);
}
//Eventos opciones datatable
$(document).on('click','#tabUsuario div table tbody tr td div button.edit', function() {
    let cod = (((this.parentNode).parentNode).parentNode).childNodes[2].innerHTML;
    localData.lstUser.forEach(function(row) {
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
    $('#addModal').modal('show');
});

$(document).on('click','#tabUsuario div table tbody tr td div button.del', function() { 
    let cod = (((this.parentNode).parentNode).parentNode).childNodes[2].innerHTML;
	document.getElementById(confirmFormId).setAttribute('cod',cod);
    document.getElementById(confirmFormId).setAttribute('act',0);
    document.querySelector(confirmFormMensaje).innerHTML = msgConfirmacion.eliminar;
    $(confirmForm).modal('show');
});
//Functions
function listarUsuario(datatable,txt='default'){
	txt = txt.trim();
	if(txt.length>=3){
		fetchGet(ruta +'cargarUsuarios?txt='+txt).then(json => {
	        if(json.cod == 200){
	        	cargarListaUsuario(json.res.lstUser,datatable,txt);
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
function cargarListaUsuario(lstUser,datatable,txt='default'){
	localData.lstUser = lstUser;
	for (let i = 0; i < lstUser.length; i++) {
		lstUser[i]['desEmp'] = '';
		lstUser[i]['desEst'] = '';
		lstUser[i]['desRol'] = '';
		localData.lstEmpresa.forEach(function(emp){
			if(lstUser[i]['emp'] == emp['num']){
				lstUser[i]['desEmp'] = emp['des'];
			}
		});
		localData.lstEstado.forEach(function(est){
			if(lstUser[i]['est'] == est['cod']){
				lstUser[i]['desEst'] = est['des'];
			}
		});
		localData.lstRol.forEach(function(rol){
			if(lstUser[i]['rol'] == rol['num']){
				lstUser[i]['desRol'] = rol['des'];
			}
		});
	}
	let datafields = [
	    {key: 'cod', name: 'Codigo', type: 'string', hidden: true},
	    {key: 'nom', name: 'Nombres y apellidos', type: 'string'},
	    {key: 'eml', name: 'Correo Electronico', type: 'string'},
	    {key: 'doc', name: 'Doc.Ident', type: 'string'},
	    {key: 'desEmp', name: 'Empresa', type: 'string'},
	    {key: 'desEst', name: 'Estado', type: 'string'},
	    {key: 'desRol', name: 'Rol', type: 'string'}
	];
	let options = [
	    {btn:'primary', fa:'fa-pencil', fn:'edit'},
	    {btn:'danger', fa:'fa-trash-o', fn:'del'}
	];
	datatableLoad(datatable,datafields,lstUser,options,true);
	document.getElementById(datatable).setAttribute('data',txt);
	tablePagination(datatable,true,5);
}
