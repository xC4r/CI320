var ruta = "syst_user/main/";
//JS Events
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
			var localData = {};
			localData = json.res;
			localStorage.setItem("localData", JSON.stringify(localData));
			cargarListaUsuario(json.res.lstUser,'tabUsuario');
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

document.getElementById('txtBuscar').onkeyup = function(){
	tableFilter('tabUsuario',this.value,5);
}

document.getElementById('btnBuscar').onclick = function(){
	txt = document.getElementById('txtBuscar').value;
	listarUsuario('tabUsuario',txt);
}

document.getElementById('btnExport').onclick = function(){
	tableToCsv('tabUsuario','export.csv');
}

document.getElementById('btnReload').onclick = function(){
	txt = document.getElementById('tabUsuario').getAttribute('data');
 	listarUsuario('tabUsuario',txt);
}
// Modal Registro Eventos
document.getElementById('formRegistro').onsubmit = function(e) {
	e.preventDefault();
}

document.getElementById('btnRegistrar').onclick = function() {
	if(document.getElementById('formRegistro').checkValidity()){
		if(document.getElementById('txtUsuario').getAttribute('num') === null ){
			document.querySelector(confirmFormMensaje).innerHTML = confirm.guardar;
			document.getElementById(confirmForm).setAttribute('accion','registrar');
		}else{
			document.querySelector(confirmFormMensaje).innerHTML = confirm.modificar;
			document.getElementById(confirmForm).setAttribute('accion',2);
		}
		$($confirmForm).modal('show');
	}
}
//Bootstrap Events
$('#addModal').on('hidden.bs.modal', function () {
	document.getElementById("formRegistro").reset();
	document.getElementById("txtEmpresa").selectedIndex = '-1';
	document.getElementById("txtRol").selectedIndex = '-1';
	document.getElementById("txtEstado").selectedIndex = '-1';
	document.getElementById('txtUsuario').removeAttribute('num');
});

$($confirmForm).on('show.bs.modal', function () {
	document.getElementById("addModal").classList.remove("show");
});

$($confirmForm).on('hide.bs.modal', function () {
	document.getElementById("addModal").classList.add("show");
	this.removeAttribute('accion');
	this.removeAttribute('del');
	this.removeAttribute('doc');
	this.querySelector('div div div.modal-body').innerHTML = '';
});

document.querySelector(confirmFormAceptar).onclick = function() {
	$(progressForm).modal('show');
	var accion = document.getElementById(confirmForm).getAttribute('accion');
	var txt = document.getElementById('tabUsuario').getAttribute('data');
	if(accion =='registrar'){  //Accion registrar
		$('#addModal').modal('hide');
		var formData = new FormData(document.getElementById('formRegistro'));
		let count = 0;
		for (var val of formData.values()) {
		   count++;
		}
		formData.append('txt',txt);
	}else if(accion == 2){
		$('#addModal').modal('hide');
		var formData = new FormData(document.getElementById('formRegistro'));
		formData.append('action',accion);
		formData.append('txt',txt);
		var PostData = setPostData(dataFormUsuario,'formulario',formData); // params: object container, object formData;
		//console.log(PostData);
		let count=0;
		formData.forEach(function(){count++;});
		if(count>0){
			fetchPost(ruta +'operacion',PostData).then(json => {
		        if(json.cod == 200){
		        	cargarListaUsuario(json.res.lstUser,'tabUsuario',txt);
		        }else{
		            snackAlert(json.msg,'danger');
		        }
			});
		}else{
		  	snackAlert('No hay datos para registrar','warning');
		}
	}else if(accion == 'eliminar'){  //Accion eliminar
		var del = document.getElementById(confirmForm).getAttribute('del');
		var doc = document.getElementById(confirmForm).getAttribute('doc');
		if(del !== null && doc !== null){
			fetch(ruta +'desactivarUsuario',{
		        method: 'POST',
		        body: JSON.stringify({'del': del,'doc': doc,'txt':txt}),
		        headers:{
		            'Content-Type':'application/json'
		        },
		        cache:'no-cache'
		    })
		    .then(response => response.json())
		    .then(json => {
		        if(json.cod == 200){
		        	snackAlert('Registro eliminado satisfactoriamente','danger');
		        	cargarListaUsuario(json.res.lstUser,'tabUsuario',txt);
		        }else{
		            snackAlert(json.msg,'danger');
		        }
		    })
		    .catch(error => {console.log('Error:'+ error)});
		}else{
		  	snackAlert('No hay registro para eliminar','warning');
		}
	}else{
		snackAlert('No hay accion para ejecutar');
	}
	$($confirmForm).modal('hide');
	setTimeout(function(){ $(progressForm).modal('hide'); }, 500);
}
//Eventos opciones datatable
$(document).on('click','#tabUsuario div table tbody tr td button.edit', function() {
    var num = ((this.parentNode).parentNode).childNodes[1].innerHTML;
	var data = (JSON.parse(localStorage.getItem("localData"))).lstUser;
    data.forEach(function(row) {
    	if(row['num'] == num){
    		document.getElementById('txtUsuario').setAttribute('num',row['num']);
    		document.getElementById('txtNombres').value = row['nom'];
    		document.getElementById('txtDocumento').value = row['doc'];
    		document.getElementById('txtCorreo').value = row['eml'];
    		document.getElementById('txtEmpresa').value = row['emp'];
    		document.getElementById('txtUsuario').value = row['cod'];
    		document.getElementById('txtPassword').value = row['pas'];
    		document.getElementById('txtRol').value = row['rol'];
    		document.getElementById('txtEstado').value = row['est'];
    	}
    });
    $('#addModal').modal('show');
});

$(document).on('click','#tabUsuario div table tbody tr td button.del', function() { 
    num = ((this.parentNode).parentNode).childNodes[1].innerHTML;
    doc = ((this.parentNode).parentNode).childNodes[4].innerHTML;
    document.getElementById(confirmForm).setAttribute('accion','eliminar');
    document.getElementById(confirmForm).setAttribute('del',num);
    document.getElementById(confirmForm).setAttribute('doc',doc);
    document.querySelector(confirmFormMensaje).innerHTML = confirm.eliminar;
    $($confirmForm).modal('show');
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
	localData = JSON.parse(localStorage.getItem('localData'));
	localData['lstUser'] = lstUser;
	localStorage.setItem("localData", JSON.stringify(localData));
	for (var i = 0; i < lstUser.length; i++) {
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
	var datafields = [
	    {key: 'num', name: '#', type: 'number', hidden: true },
	    {key: 'nom', name: 'Nombres', type: 'string'},
	    {key: 'eml', name: 'Correo Electronico', type: 'string'},
	    {key: 'doc', name: 'Documento Identificación', type: 'string'},
	    {key: 'desEmp', name: 'Empresa', type: 'string'},
	    {key: 'desEst', name: 'Estado', type: 'string'},
	    {key: 'desRol', name: 'Rol', type: 'string'}
	];
	var options = [
	    {btn:'primary', fa:'fa-pencil', fn:'edit'},
	    {btn:'danger', fa:'fa-trash-o', fn:'del'}
	];
	datatableLoad(lstUser,datatable,datafields,options,true);
	document.getElementById(datatable).setAttribute('data',txt);
	tablePagination(datatable,true,5);	//
}
