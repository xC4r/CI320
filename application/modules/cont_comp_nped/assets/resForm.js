var ruta = "cont_comp_nped/main/";
//JS Events
var localData;
var today = new Date();
var date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();
var time = today.getHours() + ":" + today.getMinutes();
var todayDateTime = date+' '+time;
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

var dataFormulario= new DataContainer;
/*
var catalogo = [
	{cod: 'P001', des: 'Fierro 1/2 Acero corrugado', pun: '32.5'},
	{cod: 'P002', des: 'Fierro 3/8 Acero corrugado', pun: '32.5'},
	{cod: 'P003', des: 'Alambra N° 16',	pun: '32.5'},
	{cod: 'P004', des: 'Claves acero 2-4', pun: '32.5'},
	{cod: 'P005', des: 'Barril de Brea 50 kg',pun: '32.5'},
	{cod: 'P006', des: 'Calaminas galvanizada 3600x1800',pun: '32.5'}
];
*/
var lisdest = [
	{key: 'cod', dest: 'txtCodigo',},
	{key: 'des', dest: 'txtProducto', ind:true},
	{key: 'pun', dest: 'txtPrecio'}
];

$(function () {
	fetchGet(ruta+'defaultLoad').then(json => {
		if(json.cod === 200){	
			localData = json.res;
			cargarNotasPedido(json.res.lstNotas,'tabNotaPedido');
			formSelectLoad('selSerieCP',json.res.lstSeries,'ser','ser');
			document.getElementById("selSerieCP").selectedIndex = 0;
		}else if(json.cod == 401){
			$(expiredSession).modal('show');
		}else{
			snackAlert(json.msg,'warning');
		}
	});
});

document.getElementById('txtProducto').onkeyup = function(){autocomplete('txtProducto','autoProducto',localData.lstProdserv,lisdest);}
//document.getElementById('txtProducto').onfocusout = function(){};

// Default Events
document.getElementById('txtFecha').value = dateToday();
document.getElementById('txtFecha').setAttribute('min',dateSubtractYear(dateToday(),1)); //No working Safari
document.getElementById('txtFecha').setAttribute('max',dateToday());//No working Safari
/*
document.getElementById('selPeriodo').onchange = function(){
	per = document.getElementById('selPeriodo').value;
	listarNotasPedido('tabNotaPedido',per);
}
*/
$('#selPeriodo').datepicker({
    autoclose: true,
	startView: 1,
    minViewMode: 1,
    format: 'yyyymm',
	startDate: '-1y',
	//endDate: todayDateTime,
	endDate: '+0m',
    maxViewMode: 2,
    language: "es",
    orientation: "bottom auto"
}).datepicker('setDate', 'today').on('changeDate', function(e) {  
    //console.log(e.format());
	listarNotasPedido('tabNotaPedido',e.format());
});

document.getElementById('txtBuscar').onkeyup = function(){
	tableFilter('tabNotaPedido',this.value,5);
}
/*
document.getElementById('btnBuscar').onclick = function(){
	txt = document.getElementById('txtBuscar').value;
	listarNotasPedido('tabNotaPedido',txt);
}
*/
document.getElementById('btnReload').onclick = function(){
	per = document.getElementById('tabNotaPedido').getAttribute('data');
 	listarNotasPedido('tabNotaPedido',per);
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
		if(Object.keys(tableToJson('tabItems')).length !== 0){
			if(!document.getElementById('formNotaPedido').hasAttribute('cpe')){
				document.querySelector(confirmFormMensaje).innerHTML = msgConfirmacion.guardar;
				document.getElementById(confirmFormId).setAttribute('ope',1);
			}else{
				document.querySelector(confirmFormMensaje).innerHTML = msgConfirmacion.modificar;
				document.getElementById(confirmFormId).setAttribute('ope',2);
			}
			$(confirmForm).modal('show');
		}else{
			document.querySelector(alertFormMensaje).innerHTML = 'Debe agregar al menos un producto';
			$(alertForm).modal('show');
		}
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
			imp: Math.round(document.getElementById('txtCantidad').value * document.getElementById('txtPrecio').value*100)/100
		};
		var datafields = [
			{key: 'cod', name: 'Código', type: 'string', rub:'83'},
			{key: 'des', name: 'Descripción', type: 'string', rub:'84'},
			{key: 'cnt', name: 'Cant', type: 'decimal', rub:'81'},
			{key: 'pun', name: 'P.Unit', type: 'decimal', rub:'85'},
			{key: 'imp', name: 'Importe', type: 'decimal', rub:'99'}
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
				var items = document.querySelectorAll('#tabItems div table tbody tr');
				if (items.length>5){
					tablePagination('tabItems',true,5);
				}
				$('#modalProducto').modal('hide');
				var mtoTotal = 0;
				items.forEach(function(row) {
					mtoTotal += (parseInt(row.children[5].innerHTML*100))/100;
				});
				document.getElementById('txtTotal').value = mtoTotal.toFixed(2);
			}
		}else{
			tableAddRow('tabItems',datafields,json,options,false);
			var items = document.querySelectorAll('#tabItems div table tbody tr');
			if (items.length>5){
				tablePagination('tabItems',true,5);
			}
			$('#modalProducto').modal('hide');
			var mtoTotal = 0;
			items.forEach(function(row) {
				mtoTotal += (parseInt(row.children[5].innerHTML*100))/100;
			});
			document.getElementById('txtTotal').value = mtoTotal.toFixed(2);
		}
	}
}

//Bootstrap Events
$('#modalNota').on('hidden.bs.modal', function () {
	document.getElementById("formNotaPedido").reset();
	document.getElementById('formNotaPedido').removeAttribute('cpe');
	document.getElementById('txtFecha').value = dateToday();
	var datafields = [
		{key: 'cod', name: 'Código', type: 'string', rub:'83'},
		{key: 'des', name: 'Descripción', type: 'string', rub:'84'},
		{key: 'cnt', name: 'Cant', type: 'decimal', rub:'81'},
		{key: 'pun', name: 'P.Unit', type: 'decimal', rub:'85'},
		{key: 'imp', name: 'Importe', type: 'decimal', rub:'99'}
	];
	var options = [
		{btn:'danger', fa:'fa-trash-o', fn:'del'}
	];
	datatableLoad('tabItems',datafields,'',options,false,{'tableSet':'table-sm text-nowrap'});
	tablePagination('tabItems',true,5);
});

$('#modalProducto').on('hidden.bs.modal', function () {
	document.getElementById("formAddProducto").reset();
	//agregar limpiar grilla de productos
});

$(confirmForm).on('hidden.bs.modal', function () {
	this.removeAttribute('ope');
	this.querySelector('div div div.modal-body').innerHTML = '';
});

document.querySelector(confirmFormAceptar).onclick = function() {
	$(progressForm).modal('show');
	var ope = document.getElementById(confirmFormId).getAttribute('ope');
	var per = document.getElementById('tabNotaPedido').getAttribute('data');
	$('#modalNota').modal('hide');
	var msg;
	if(ope == 1){ 
		msg = msgRespuesta.guardado;
	}else if(ope == 2){
		msg = msgRespuesta.modificado;
	}else if(ope == 0){  
		msg = msgRespuesta.eliminado;
	}else if(ope == 'pdf'){  
		msg = msgRespuesta.generadoPdf;
	}else{
		snackAlert('No hay accion para ejecutar');
	}
	var formData = new FormData(document.getElementById('formNotaPedido'));
	formData.append('itm',JSON.stringify(tableToJson('tabItems')));
	//console.log(JSON.stringify(tableToJson('tabItems')));
	formData.append('ope',ope);
	formData.append('per',per);
	if (ope == 0 || ope == 'pdf') {
		let cpeid = document.getElementById(confirmFormId).getAttribute('cpe').split('-');
		formData.set('selSerieCP',cpeid[0]);
		formData.set('txtNumeroCP',cpeid[1]);
	};
	var PostData = setPostData(dataFormulario,'formulario',formData); // params: object container, object formData;
	let count = 0;
	formData.forEach(function(){count++;});
	if(count>0){
		if (ope !== 'pdf') {
			fetchPost(ruta +'operacion',PostData).then(json => {
				if(json.cod == 200){
					cargarNotasPedido(json.res.lstNotas,'tabNotaPedido',per);
					snackAlert(msg,'success');
				}else{
					snackAlert(json.msg,'danger');
				}
			});
		}else{
			fetchPostPdf(ruta +'operacion',PostData).then(blob => {
					var file = new Blob([blob], {type: "application/pdf"});
					var fileURL = window.URL.createObjectURL(file);
					window.open(fileURL, "_blank");
			});	
		}
		
	}else{
		  snackAlert('No hay datos para registrar','warning');
	}

	$(confirmForm).modal('hide');
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
    		document.getElementById('txtDocumento').value = row['rec'];
			document.getElementById('txtTotal').value = row['tot'];
			if(row['dir'])document.getElementById('txtDireccion').value = row['dir'];
    		document.getElementById('txtObservacion').value = (row['obs']) ? row['obs']:'';
			var datafields = [
				{key: 'cod', name: 'Código', type: 'string', rub:'83'},
				{key: 'des', name: 'Descripción', type: 'string', rub:'84'},
				{key: 'cnt', name: 'Cant', type: 'decimal', rub:'81'},
				{key: 'pun', name: 'P.Unit', type: 'decimal', rub:'85'},
				{key: 'imp', name: 'Importe', type: 'decimal', rub:'99'}
			];
			var options = [
				{btn:'danger', fa:'fa-trash-o', fn:'del'}
			];
			//Tablename, datafields, lista, opciones,autonumeracion,clases, 
			datatableLoad('tabItems',datafields,row.lst,options,false,{'tableSet':'table-sm text-nowrap'});
			//alert(JSON.stringify(row.lst));
			document.getElementById('tabItems').setAttribute('cpe',row['ser']+'-'+row['num']);
			tablePagination('tabItems',true,5);
    	}
    });
    $('#modalNota').modal('show');
});

$(document).on('click','#tabNotaPedido div table tbody tr td div button.del', function() { 
	let ser = (((this.parentNode).parentNode).parentNode).childNodes[2].innerHTML;
    let num = (((this.parentNode).parentNode).parentNode).childNodes[3].innerHTML;
	document.getElementById(confirmFormId).setAttribute('cpe',ser.trim()+'-'+num.trim());
    document.getElementById(confirmFormId).setAttribute('ope',0);
    document.querySelector(confirmFormMensaje).innerHTML = msgConfirmacion.eliminar;
    $(confirmForm).modal('show');
});
//Generar PDF 
$(document).on('click','#tabNotaPedido div table tbody tr td div button.pdf', function() {
	let ser = (((this.parentNode).parentNode).parentNode).childNodes[2].innerHTML;
    let num = (((this.parentNode).parentNode).parentNode).childNodes[3].innerHTML;
	document.getElementById(confirmFormId).setAttribute('cpe',ser.trim()+'-'+num.trim());
    document.getElementById(confirmFormId).setAttribute('ope','pdf');
    document.querySelector(confirmFormMensaje).innerHTML = msgConfirmacion.generaPdf;
    $(confirmForm).modal('show');
});

$(document).on('click','#tabItems div table tbody tr td div button.del', function() {
	(((this.parentNode).parentNode).parentNode).remove();
	var items = document.querySelectorAll('#tabItems div table tbody tr');
	var mtoTotal = 0;
	items.forEach(function(row) {
		mtoTotal += (parseInt(row.children[5].innerHTML*100))/100;
	});
	document.getElementById('txtTotal').value = mtoTotal.toFixed(2);
	tablePagination('tabItems',true,5);
});

//Functions
function listarNotasPedido(datatable,per='default'){
	per = per.trim();
	if(per.length==6){
		fetchGet(ruta +'listarNotasPedido?per='+per).then(json => {
			if(json.cod === 200){	
				cargarNotasPedido(json.res.lstNotas,datatable,per);
			}else if(json.cod == 401){
				$(expiredSession).modal('show');
			}else{
				snackAlert(json.msg,'warning');
			}
		});
	}else{
	  	snackAlert('Periodo Incorrecto','warning');
	}
}
function cargarNotasPedido(lstNotas,datatable,per='default'){
	lstNotas.forEach(function(row,x) {
		var list = [];
		row.det.forEach(function(detal) {
			detal.forEach(function(rubro) {
				if(rubro.rub=='01'){
					lstNotas[x].dir = rubro.des;
				}
				if(rubro.rub=='02'){
					lstNotas[x].obs = rubro.des;
				}
			});  
		});
		row.itm.forEach(function(item) {
			var i = {};
			item.forEach(function(rubro) {
			if(rubro.rub=='81'){
				i.cnt = rubro.val;
			}
			if(rubro.rub=='83'){
				i.cod = rubro.des;
			}
			if(rubro.rub=='84'){
				i.des = rubro.des;
			}
			if(rubro.rub=='85'){
				i.pun = rubro.val;
			}
			if(rubro.rub=='99'){
				i.imp = rubro.val;
			}
			});
			list.push(i);
		});
		lstNotas[x].lst = list;
	});
	localData.lstNotas = lstNotas;
	var totPeriodo = 0;
	var cntPeriodo = 0;
	lstNotas.forEach(function(row) {
		cntPeriodo ++;
		totPeriodo += (parseInt(row['tot']*100))/100;
	});
	document.getElementById('txtTotPeriodo').value = totPeriodo.toFixed(2);
	document.getElementById('txtCantidad').value = cntPeriodo;
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
	datatableLoad(datatable,datafields,lstNotas,options,true,{'tableSet':'text-nowrap'});
	document.getElementById(datatable).setAttribute('data',per);
	tablePagination(datatable,true,5);
}
