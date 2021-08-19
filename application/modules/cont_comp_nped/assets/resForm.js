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
			json.res.lstNotas.forEach(function(row,x) {
				var list = [];
				row.itm.forEach(function(item,y) {
					if(y == 0){
						item.forEach(function(rubro) {
							if(rubro['rub']=='01'){
								json.res.lstNotas[x].dir = rubro['des'];
							}
						});
					}else{
						var i = {};
						item.forEach(function(rubro) {
							if(rubro['rub']=='81'){
								i.cnt = rubro['val'];
							}
							if(rubro['rub']=='83'){
								i.cod = rubro['des'];
							}
							if(rubro['rub']=='84'){
								i.des = rubro['des'];
							}
							if(rubro['rub']=='85'){
								i.pun = rubro['val'];
							}
							if(rubro['rub']=='99'){
								i.imp = rubro['val'];
							}
						});
						list.push(i);
					}
				});
				json.res.lstNotas[x].lst = list;
			});
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
document.getElementById('txtFecha').value = dateToday();
document.getElementById('txtFecha').setAttribute('min',dateSubtractYear(dateToday(),1)); //No working Safari
document.getElementById('txtFecha').setAttribute('max',dateToday());//No working Safari

document.getElementById('txtBuscar').onkeyup = function(){
	tableFilter('tabNotaPedido',this.value,5);
}

document.getElementById('btnBuscar').onclick = function(){
	txt = document.getElementById('txtBuscar').value;
	listarNotasPedido('tabNotaPedido',txt);
}

document.getElementById('btnReload').onclick = function(){
	txt = document.getElementById('tabNotaPedido').getAttribute('data');
 	listarNotasPedido('tabNotaPedido',txt);
}

document.getElementById('btnExport').onclick = function(){
	tableToCsv('tabNotaPedido','export.csv');
}

// Modal Registro Eventos
document.getElementById('formNotaPedido').onsubmit = function(e) {
	e.preventDefault();
}

document.getElementById('formAddProducto').onsubmit = function(e) {
	e.preventDefault();
}

//Eventos modulo
document.getElementById('btnRegistrar').onclick = function() {
	if(document.getElementById('formNotaPedido').checkValidity()){
		if(!document.getElementById('formNotaPedido').hasAttribute('cpe')){
			document.querySelector(confirmFormMensaje).innerHTML = msgConfirmacion.guardar;
			document.getElementById(confirmForm).setAttribute('act',1);
		}else{
			document.querySelector(confirmFormMensaje).innerHTML = msgConfirmacion.modificar;
			document.getElementById(confirmForm).setAttribute('act',2);
		}
		$($confirmForm).modal('show');
	}
}

document.getElementById('btnAgregar').onclick = function() {
	if(document.getElementById('formAddProducto').checkValidity()){
		formAlertRemove('formAddProducto');
		let coditem = document.getElementById('txtCodigo').value;
		var rows = document.querySelectorAll('#tabItems div table tbody tr');
		var itemExist = false;
		var json = {
			cod: document.getElementById('txtCodigo').value, 
			des: document.getElementById('txtProducto').value,
			cnt: document.getElementById('txtCantidad').value, 
			pun: document.getElementById('txtPrecio').value, 
			imp: document.getElementById('txtCantidad').value * document.getElementById('txtPrecio').value
		};
		var datafields = [
			{key: 'cod', name: 'Código', type: 'string'},
			{key: 'des', name: 'Descripción', type: 'string'},
			{key: 'cnt', name: 'Cant', type: 'decimal'},
			{key: 'pun', name: 'P.Unit', type: 'decimal'},
			{key: 'imp', name: 'Importe', type: 'decimal'}
		];
		var options = [
			{btn:'danger', fa:'fa-trash-o', fn:'del'}
		];

		if(coditem.trim() !==''){
			rows.forEach(function(row) {
				if((row.children[1].innerHTML).trim() == coditem.trim()){
					itemExist = true;
				}
			});
			if(itemExist){
				formAlertShow('formAddProducto','Ya tiene el producto en la lista');
			}else{
				tableAddRow('tabItems',datafields,json,options,false);
				if (rows.length>4){
					tablePagination('tabItems',true,5);
				}
			}
		}else{
			tableAddRow('tabItems',datafields,json,options,false);
			if (rows.length>4){
				tablePagination('tabItems',true,5);
			}
		}
	}
}

//Bootstrap Events
$('#modalNota').on('hidden.bs.modal', function () {
	document.getElementById("formNotaPedido").reset();
	document.getElementById('formNotaPedido').removeAttribute('cpe');
	document.getElementById('txtFecha').value = dateToday();
	var datafields = [
		{key: 'cod', name: 'Código'},
		{key: 'des', name: 'Descripción'},
		{key: 'cnt', name: 'Cant'},
		{key: 'pun', name: 'P.Unit'},
		{key: 'imp', name: 'Importe'}
	];
	datatableLoad('tabItems',datafields);
	tablePagination('tabItems',true,5);
});

$('#modalProducto').on('hidden.bs.modal', function () {
	document.getElementById("formAddProducto").reset();
	//agregar limpiar grilla de productos
});

$($confirmForm).on('hidden.bs.modal', function () {
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
    let ser = (((this.parentNode).parentNode).parentNode).childNodes[2].innerHTML;
    let num = (((this.parentNode).parentNode).parentNode).childNodes[3].innerHTML;
	document.getElementById('formNotaPedido').setAttribute('cpe',ser.trim()+'-'+num.trim());
    localData.lstNotas.forEach(function(row) {
    	if(row['ser'] == ser && row['num'] == num){
    		document.getElementById('selSerieCP').value = row['ser'];
    		document.getElementById('txtNumeroCP').value = row['num'];
    		document.getElementById('txtFecha').value = row['fec'];
    		document.getElementById('txtNombre').value = row['des'];
    		document.getElementById('txtDireccion').value = row['dir'];
    		document.getElementById('txtDocumento').value = row['rec'];
    		document.getElementById('txtObservacion').value = row['obs'];
			var datafields = [
				{key: 'cod', name: 'Código', type: 'string'},
				{key: 'des', name: 'Descripción', type: 'string'},
				{key: 'cnt', name: 'Cant', type: 'decimal'},
				{key: 'pun', name: 'P.Unit', type: 'decimal'},
				{key: 'imp', name: 'Importe', type: 'decimal'}
			];
			var options = [
				{btn:'danger', fa:'fa-trash-o', fn:'del'}
			];
			datatableLoad('tabItems',datafields,row.lst,options,false,'table-sm text-nowrap','thead-light','');
			document.getElementById('tabItems').setAttribute('cpe',row['ser']+'-'+row['num']);
			tablePagination('tabItems',true,5);
    	}
    });
    $('#modalNota').modal('show');
});

$(document).on('click','#tabNotaPedido div table tbody tr td div button.del', function() { 
	let ser = (((this.parentNode).parentNode).parentNode).childNodes[2].innerHTML;
    let num = (((this.parentNode).parentNode).parentNode).childNodes[3].innerHTML;
	document.getElementById(confirmForm).setAttribute('cpe',ser.trim()+'-'+num.trim());
    document.getElementById(confirmForm).setAttribute('act',0);
    document.querySelector(confirmFormMensaje).innerHTML = msgConfirmacion.eliminar;
    $($confirmForm).modal('show');
});
//Generar PDF 
$(document).on('click','#tabNotaPedido div table tbody tr td div button.pdf', function() {
	fetch(ruta+'generarPDF')
	.then(response => response.blob())
	.then(blob => {
			var file = new Blob([blob], {type: "application/pdf"});
			var fileURL = window.URL.createObjectURL(file);
			window.open(fileURL, "_blank");
	});
});

$(document).on('click','#tabItems div table tbody tr td div button.del', function() {
	(((this.parentNode).parentNode).parentNode).remove();
	tablePagination('tabItems',true,5);
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
	    {key: 'tot', name: 'Total', type: 'decimal'}
	];
	var options = [
	    {btn:'primary', fa:'fa-pencil', fn:'edit'},
		{btn:'secondary', fa:'fa-file-pdf-o', fn:'pdf'},
	    {btn:'danger', fa:'fa-trash-o', fn:'del'}
	];
	datatableLoad(datatable,datafields,lstNotas,options,true,'text-nowrap');
	document.getElementById(datatable).setAttribute('data',txt);
	tablePagination(datatable,true,5);
}
