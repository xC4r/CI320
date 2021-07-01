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

var dataFormulario= new DataContainer;

$(function () {
	fetchGet(ruta+'defaultLoad').then(json => {
		if(json.cod === 200){	
			localData = json.res;
			cargarNotasPedido(json.res.lstNotas,'tabNotaPedido');
		}else if(json.cod == 401){
			$(expiredSession).modal('show');
		}else{
			snackAlert(json.msg,'warning');
		}
	});
});

// Default Events
document.getElementById('txtBuscar').onkeyup = function(){
	tableFilter('tabNotaPedido',this.value,5);
}

document.getElementById('btnBuscar').onclick = function(){
	txt = document.getElementById('txtBuscar').value;
	listarNotasPedido('tabNotaPedido',txt);
}

document.getElementById('btnExport').onclick = function(){
	tableToCsv('tabNotaPedido','export.csv');
}

document.getElementById('btnReload').onclick = function(){
	txt = document.getElementById('tabNotaPedido').getAttribute('data');
 	listarNotasPedido('tabNotaPedido',txt);
}
// Modal Registro Eventos
document.getElementById('formNotaPedido').onsubmit = function(e) {
	e.preventDefault();
}

//Generar PDF 
/*
document.getElementById('btnPDF').onclick = function(){
	fetch(ruta+'generarPDF')
	.then(response => response.blob())
	.then(blob => {
			var file = new Blob([blob], {type: "application/pdf"});
			var fileURL = window.URL.createObjectURL(file);
			window.open(fileURL, "_blank");
	});
}
*/

//Eventos modulo
document.getElementById('btnRegistrar').onclick = function() {
	if(document.getElementById('formNotaPedido').checkValidity()){
		if(!document.getElementById('txtNumeroCP').hasAttribute('ind')){
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
$('#modalNota').on('hidden.bs.modal', function () {
	document.getElementById("formNotaPedido").reset();
	document.getElementById("selSerieCP").selectedIndex = '-1';
	document.getElementById('txtNumeroCP').removeAttribute('ind');
	//agregar limpiar grilla de productos
});

$($confirmForm).on('hide.bs.modal', function () {
	this.removeAttribute('act');
	this.querySelector('div div div.modal-body').innerHTML = '';
});

document.querySelector(confirmFormAceptar).onclick = function() {
	$(progressForm).modal('show');
	var act = document.getElementById(confirmForm).getAttribute('act');
	var txt = document.getElementById('tabNotaPedido').getAttribute('data');
	$('#modalNota').modal('hide');
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
	if (act == 0) formData.set('txtNumeroCP',document.getElementById(confirmForm).getAttribute('cod'));
	var PostData = setPostData(dataFormulario,'formulario',formData); // params: object container, object formData;
	let count = 0;
	formData.forEach(function(){count++;});
	if(count>0){
		fetchPost(ruta +'operacion',PostData).then(json => {
			if(json.cod == 200){
				cargarNotasPedido(json.res.lstNotas,'tabNotaPedido',txt);
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
$(document).on('click','#tabNotaPedido div table tbody tr td div button.edit', function() {
    let ser = ((this.parentNode).parentNode).childNodes[1].innerHTML;
    let num = ((this.parentNode).parentNode).childNodes[2].innerHTML;
    localData.lstNotas.forEach(function(row) {
    	if(row['ser'] == ser && row['num'] == num){
    		document.getElementById('selSerieCP').value = row['ser'];
    		document.getElementById('txtNumeroCP').value = row['num'];
    		document.getElementById('txtFecha').value = row['fec'];
    		document.getElementById('txtNombre').value = row['des'];
    		//document.getElementById('txtDireccion').value = row['itm'][0][0]['des'];
    		document.getElementById('txtDocumento').value = row['rec'];
			//document.getElementById('txtDocumento').setAttribute('ind','');
    		document.getElementById('txtObservacion').value = row['obs'];
    		row.itm.forEach(function(item) {
    			if(item == 0){
    				item.forEach(function(rubro) {
    					if(rubro['rub']=='01'){
    						document.getElementById('txtDireccion').value = rubro['des'];
    					}
    				}
    			}else{
    				var lstItems = [
					    {key: 'ser', name: 'Serie', type: 'string'},
					    {key: 'num', name: 'Número', type: 'string'},
					    {key: 'fec', name: 'Fecha Emisión', type: 'date'},
					    {key: 'rec', name: 'Documento', type: 'string'},
					    {key: 'des', name: 'Nombre / Razon Social', type: 'string'},
					    {key: 'tot', name: 'Total', type: 'number'}
					];
					item.forEach(function(rubro) {
    					if(rubro['rub']=='01'){
    						
    					}
    				}
    			}
    		}
    	}
    });
    $('#modalNota').modal('show');
});

$(document).on('click','#tabNotaPedido div table tbody tr td div button.del', function() { 
    let cod = ((this.parentNode).parentNode).childNodes[1].innerHTML;
	document.getElementById(confirmForm).setAttribute('cod',cod);
    document.getElementById(confirmForm).setAttribute('act',0);
    document.querySelector(confirmFormMensaje).innerHTML = msgConfirmacion.eliminar;
    $($confirmForm).modal('show');
});
//Functions
function listarNotasPedido(datatable,txt='default'){
	txt = txt.trim();
	if(txt.length>=3){
		fetchGet(ruta +'cargarNotasPedido?txt='+txt).then(json => {
	        if(json.cod == 200){
	        	cargarNotasPedido(json.res.lstNotas,datatable,txt);
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
function cargarNotasPedido(lstNotas,datatable,txt='default'){
	localData.lstNotas = lstNotas;
	/*
	for (var i = 0; i < lstNotas.length; i++) {
		lstNotas[i]['desEst'] = '';
		localData.lstEstado.forEach(function(est){
			if(lstNotas[i]['est'] == est['cod']){
				lstNotas[i]['desEst'] = est['des'];
			}
		});
	}
	*/
	var datafields = [
	    {key: 'ser', name: 'Serie', type: 'string'},
	    {key: 'num', name: 'Número', type: 'string'},
	    {key: 'fec', name: 'Fecha Emisión', type: 'date'},
	    {key: 'rec', name: 'Documento', type: 'string'},
	    {key: 'des', name: 'Nombre / Razon Social', type: 'string'},
	    {key: 'tot', name: 'Total', type: 'number'}
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
