const ruta = "cont_comp_nped/main/";
//JS Events
let localData;
let today = new Date();
let date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();
let time = today.getHours() + ":" + today.getMinutes();
let todayDateTime = date+' '+time;
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

let dataFormulario= new DataContainer;

let lisdest = [
	{key: 'cod', dest: 'txtCodigo'},
	{key: 'des', dest: 'txtProducto',ind:true},
	{key: 'und', dest: 'txtUnidad'},
	{key: 'pun', dest: 'txtPrecio'}
];

$(function () {
	fetchGet(ruta+'defaultLoad').then(json => {
		if(json.cod === 200){	
			localData = json.res;
			cargarNotasPedido(json.res.lstNotas,'tabNotaPedido');
			formSelectLoad('selSerieCP',json.res.lstSeries,'ser','ser');
			document.getElementById('selSerieCP').selectedIndex = 0;
		}else if(json.cod == 401){
			$(expiredSession).modal('show');
		}else{
			snackAlert(json.msg,'warning');
		}
	});
});

document.getElementById('txtProducto').onkeyup = function(){autocomplete('txtProducto','autoProducto',localData.lstProdserv,lisdest);}

// Por dfecto Eventos
document.getElementById('txtFecha').value = dateToday();
document.getElementById('txtFecha').setAttribute('min',dateSubtractYear(dateToday(),1)); //No working Safari
document.getElementById('txtFecha').setAttribute('max',dateToday());//No working Safari

$('#selPeriodo').datepicker({
    autoclose: true,
	startView: 1,
    minViewMode: 1,
    format: 'yyyymm',
	startDate: '-1y',
	endDate: '+0m',
    maxViewMode: 2,
    language: "es",
    orientation: "bottom auto"
}).datepicker('setDate', 'today').on('changeDate', function(e) {  

	listarNotasPedido('tabNotaPedido',e.format());
});

document.getElementById('txtBuscar').onkeyup = function(){
	tableFilter('tabNotaPedido',this.value,5);
}

document.getElementById('btnReload').onclick = function(){
	let per = document.getElementById('tabNotaPedido').getAttribute('data');
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
			// Validar stock items

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
	if (document.getElementById('formAddProducto').checkValidity()) {
			formAlertRemove('formAddProducto');
			let coditem = document.getElementById('txtCodigo').value;
			let rows = document.querySelectorAll('#tabItems div table tbody tr');
			let itemExist = 'ok';
			let json = {
					cod: document.getElementById('txtCodigo').value,
					des: document.getElementById('txtProducto').value,
					und: document.getElementById('txtUnidad').value,
					cnt: document.getElementById('txtCantidad').value,
					pun: document.getElementById('txtPrecio').value,
					imp: Math.round(document.getElementById('txtCantidad').value * document.getElementById('txtPrecio').value * 100) / 100
			};
			let datafields = [
					{ key: 'cod', name: 'Código', type: 'string' },
					{ key: 'des', name: 'Descripción', type: 'string' },
					{ key: 'und', name: 'Und', type: 'string' },
					{ key: 'cnt', name: 'Cant', type: 'decimal' },
					{ key: 'pun', name: 'P.Unit', type: 'decimal' },
					{ key: 'imp', name: 'Importe', type: 'decimal' }
			];
			let options = [
					{ btn: 'danger', fa: 'fa-trash-o', fn: 'del' }
			];
			if (coditem.trim() !== '') {
					rows.forEach(function(row) {
							if ((row.children[1].innerHTML).trim() == coditem.trim()) {
									if (row.getAttribute('ind') == '1') {
											itemExist = 'ind';
									} else {
											itemExist = 'duplicado';
									}
							}
					});
					if (itemExist == 'duplicado') {
							formAlertShow('formAddProducto', 'Ya tiene el producto en la lista');
					} else if (itemExist == 'ind') {
							let tr_hiden = document.querySelector('#tabItems div table tbody tr[hidden]');
							tr_hiden.children[1].innerHTML = document.getElementById('txtCodigo').value;
							tr_hiden.children[2].innerHTML = document.getElementById('txtProducto').value;
							tr_hiden.children[3].innerHTML = document.getElementById('txtUnidad').value;
							tr_hiden.children[4].innerHTML = document.getElementById('txtCantidad').value;
							tr_hiden.children[5].innerHTML = document.getElementById('txtPrecio').value;
							tr_hiden.children[6].innerHTML = Math.round(document.getElementById('txtCantidad').value * document.getElementById('txtPrecio').value * 100) / 100;
							tr_hiden.setAttribute('ind', '0');
							tr_hiden.hidden = false;

							let items = document.querySelectorAll('#tabItems div table tbody tr:not([hidden])');
							if (items.length > 5) {
									tablePagination('tabItems', true, 5);
							}

							$('#modalProducto').modal('hide');
							let mtoTotal = 0;
							items.forEach(function(row) {
									mtoTotal += parseFloat(row.children[6].innerHTML);
							});
							document.getElementById('txtTotal').value = mtoTotal.toFixed(2);
					} else if (itemExist == 'ok') {
							tableAddRow('tabItems', datafields, json, options, false);
							let items = document.querySelectorAll('#tabItems div table tbody tr:not([hidden])');
							if (items.length > 5) {
									tablePagination('tabItems', true, 5);
							}
							$('#modalProducto').modal('hide');
							let mtoTotal = 0;
							items.forEach(function(row) {
									mtoTotal += parseFloat(row.children[6].innerHTML);
							});
							document.getElementById('txtTotal').value = mtoTotal.toFixed(2);
					}
			} else {
					tableAddRow('tabItems', datafields, json, options, false);
					let items = document.querySelectorAll('#tabItems div table tbody tr:not([hidden])');
					if (items.length > 5) {
							tablePagination('tabItems', true, 5);
					}
					$('#modalProducto').modal('hide');
					let mtoTotal = 0;
					items.forEach(function(row) {
							mtoTotal += parseFloat(row.children[6].innerHTML);
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
	document.getElementById('txtFecha').readOnly = true;
	document.getElementById('txtNumeroCP').readOnly = true;
	document.getElementById('chkNumeroCP').disabled = false;
	formSelectLoad('selSerieCP',localData.lstSeries,'ser','ser');  // Object.entries(localData.lstSeries)
	document.getElementById('selSerieCP').selectedIndex = 0;
	let datafields = [
		{key: 'cod', name: 'Código', type: 'string'},
		{key: 'des', name: 'Descripción', type: 'string'},
		{key: 'und', name: 'Und', type: 'string'},
		{key: 'cnt', name: 'Cant', type: 'decimal'},
		{key: 'pun', name: 'P.Unit', type: 'decimal'},
		{key: 'imp', name: 'Importe', type: 'decimal'}
	];
	let options = [
		{btn:'danger', fa:'fa-trash-o', fn:'del'}
	];
	datatableLoad('tabItems',datafields,'',options,false,{'tableSet':'table-sm text-nowrap'});
	tablePagination('tabItems',true,5);
});

$('#modalNota').on('shown.bs.modal', function () {
	if(!document.getElementById('formNotaPedido').hasAttribute('cpe')){
		obtenerNumCp(document.getElementById('selSerieCP').value);
	}
});

document.getElementById('selSerieCP').addEventListener('change', (event) => {
	obtenerNumCp(event.target.value);
});

function obtenerNumCp(numSerie){
	fetchGet(ruta +'obtenerNumCp?codCpe=00&numSerie='+numSerie).then(json => {
		if(json.cod === 200){
			document.getElementById('txtNumeroCP').value = json.res.numCp;
		}else if(json.cod == 401){
			$(expiredSession).modal('show');
		}else{
			snackAlert(json.msg,'warning');
		}
	});
}

document.getElementById('txtNumeroCP').addEventListener('keydown', function(e){
	if (!(e.keyCode >= 48 && e.keyCode <= 57 || e.keyCode==8 || e.keyCode==9)){
		if (!(e.keyCode >= 96 && e.keyCode <= 105 || e.keyCode==8 || e.keyCode==9)){
	  		e.preventDefault();
	  	}
	}
});

document.getElementById('txtNumeroCP').onblur = function(){
	let numSerie = document.getElementById('selSerieCP').value;
	let numCp = Number(document.getElementById('txtNumeroCP').value);
	if(Number.isInteger(numCp)&& numCp>0) {
		fetchGet(ruta +'validarNumCp?numSerie='+numSerie+'&numCp='+numCp).then(json => {
			if(json.cod === 200){
				// No hacer nada
			}else if(json.cod == 201){
				document.getElementById('txtNumeroCP').value = json.res.numCp;
				snackAlert(json.msg,'warning');
			}else if(json.cod == 401){
				$(expiredSession).modal('show');
			}else{
				snackAlert(json.msg,'warning');
				document.getElementById('txtNumeroCP').value = '';
			}
		});
	}
};

document.getElementById('chkNumeroCP').addEventListener('change', (e) => {
	if (e.currentTarget.checked) {
		document.getElementById('txtNumeroCP').readOnly = false;
	} else {
		document.getElementById('txtNumeroCP').readOnly = true;
	}
}); 
document.getElementById('chkFecha').addEventListener('change', (e) => {
	if (e.currentTarget.checked) {
		document.getElementById('txtFecha').readOnly = false; 
	} else {
		document.getElementById('txtFecha').readOnly = true; 
	}
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
	let ope = document.getElementById(confirmFormId).getAttribute('ope');
	let per = document.getElementById('tabNotaPedido').getAttribute('data');
	let itm = JSON.stringify(tableToJson('tabItems'));
	$('#modalNota').modal('hide');
	let msg;
	switch (ope) {
			case 1:
					msg = msgRespuesta.guardado;
					break;
			case 2:
					msg = msgRespuesta.modificado;
					break;
			case 0:
					msg = msgRespuesta.eliminado;
					break;
			case 'pdf':
					msg = msgRespuesta.generadoPdf;
					break;
			default:
					snackAlert('No hay accion para ejecutar');
					return;
	}
	let formData = new FormData(document.getElementById('formNotaPedido'));
	formData.append('itm', itm);
	formData.append('ope', ope);
	formData.append('per', per);
	if (ope == 0 || ope == 'pdf') {
			let cpeid = document.getElementById(confirmFormId).getAttribute('cpe').split('-');
			formData.set('selSerieCP', cpeid[0]);
			formData.set('txtNumeroCP', cpeid[1]);
	}
	if (ope == 2) {
			let cpeid = document.getElementById('formNotaPedido').getAttribute('cpe').split('-');
			formData.set('selSerieCP', cpeid[0]);
			formData.set('txtNumeroCP', cpeid[1]);
	}
	console.log(formData);
	let PostData = setPostData(dataFormulario, 'formulario', formData); // params: object container, object formData;
	let count = formData.size;
	if (count > 0) {
			if (ope !== 'pdf') {
					fetchPost(ruta + 'operacion', PostData).then(json => {
							if (json.cod == 200) {
									cargarNotasPedido(json.res.lstNotas, 'tabNotaPedido', per);
									snackAlert(msg, 'success');
							} else {
									snackAlert(json.msg, 'danger');
							}
					});
			} else {
					fetchPostPdf(ruta + 'operacion', PostData).then(blob => {
						  let file = new Blob([blob], { type: "application/pdf" });
						  let fileURL = window.URL.createObjectURL(file);
							window.open(fileURL, "_blank");
					});
			}
	} else {
			snackAlert('No hay datos para registrar', 'warning');
	}
	$(confirmForm).modal('hide');
	setTimeout(function() { $(progressForm).modal('hide'); }, 500);
}

//Eventos opciones datatable
$(document).on('click','#tabNotaPedido div table tbody tr td div button.edit', function() {
    let ser = (((this.parentNode).parentNode).parentNode).childNodes[2].innerHTML;
    let num = (((this.parentNode).parentNode).parentNode).childNodes[3].innerHTML;
	document.getElementById('formNotaPedido').setAttribute('cpe',ser.trim()+'-'+num.trim());
    document.getElementById('chkNumeroCP').disabled = true;
	localData.lstNotas.forEach(function(row) { 
    	if(row['ser'] == ser && row['num'] == num){
    		formSelectLoad('selSerieCP',[{'ser':row['ser']}],'ser','ser');
    		document.getElementById('selSerieCP').value = row['ser'];
    		document.getElementById('txtNumeroCP').value = row['num'];
    		document.getElementById('txtFecha').value = row['fec'];
    		document.getElementById('txtNombre').value = row['des'];
    		document.getElementById('txtDocumento').value = row['rec'];
			document.getElementById('txtTotal').value = row['tot'];
			if(row['dir']) document.getElementById('txtDireccion').value = row['dir'];
    	document.getElementById('txtObservacion').value = (row['obs']) ? row['obs']:'';
			let datafields = [
				{key: 'cod', name: 'Código', type: 'string', ind:'0'},
				{key: 'des', name: 'Descripción', type: 'string'},
				{key: 'und', name: 'Und', type: 'string'},
				{key: 'cnt', name: 'Cant', type: 'decimal'},
				{key: 'pun', name: 'P.Unit', type: 'decimal'},
				{key: 'imp', name: 'Importe', type: 'decimal'}
			];
			let options = [
				{btn:'danger', fa:'fa-trash-o', fn:'del'}
			];
			datatableLoad('tabItems',datafields,row.itm,options,false,{'tableSet':'table-sm text-nowrap'});
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
	if((((this.parentNode).parentNode).parentNode).hasAttribute('ind')){
		(((this.parentNode).parentNode).parentNode).hidden = true;
		(((this.parentNode).parentNode).parentNode).setAttribute('ind','1');
	} else {
		(((this.parentNode).parentNode).parentNode).remove();
	}
	let items = document.querySelectorAll('#tabItems div table tbody tr:not([hidden])');
	let mtoTotal = 0;
	items.forEach(function(row) {
		mtoTotal += (parseInt(row.children[6].innerHTML*100))/100;
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
	localData.lstNotas = lstNotas;

	let totPeriodo = 0;
	let cntPeriodo = 0;
	lstNotas.forEach(function(row) {
		cntPeriodo ++;
		totPeriodo += (parseInt(row['tot']*100))/100;
	});
	document.getElementById('txtTotPeriodo').value = totPeriodo.toFixed(2);
	document.getElementById('txtCantCpe').value = cntPeriodo;
	let datafields = [
	    {key: 'ser', name: 'Serie', type: 'string'},
	    {key: 'num', name: 'Número', type: 'numeric'},
	    {key: 'fec', name: 'Fecha Emisión', type: 'date'},
	    {key: 'rec', name: 'Documento', type: 'string'},
	    {key: 'des', name: 'Nombre / Razon Social', type: 'string'},
	    {key: 'tot', name: 'Total', type: 'decimal'}
	];
	let options = [
	    {btn:'primary', fa:'fa-pencil', fn:'edit'},
			{btn:'secondary', fa:'fa-file-pdf-o', fn:'pdf'},
	    {btn:'danger', fa:'fa-trash-o', fn:'del'}
	];
	datatableLoad(datatable,datafields,lstNotas,options,true,{'tableSet':'text-nowrap'});
	document.getElementById(datatable).setAttribute('data',per);
	tablePagination(datatable,true,5);
}
