const ruta = "alma_inve_prod/main/";
//JS Events
let localData;
let today = new Date();
let date = today.getFullYear() + '-' + (today.getMonth() + 1) + '-' + today.getDate();
let time = today.getHours() + ":" + today.getMinutes();
let todayDateTime = date + ' ' + time;
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
let dataFormulario = new DataContainer;

document.getElementById('txtBuscar').onkeyup = function(){
	tableFilter('tabInventario',this.value,5);
}
document.getElementById('btnExport').onclick = function(){
	tableToCsv('tabInventario','Inventario.csv');
}
document.getElementById('btnExpReporteMov').onclick = function(){
	tableToCsv('tabMovInventario','Reporte_Inventario.csv');
}
document.getElementById('btnReload').onclick = function(){

}
document.getElementById('formInventario').onsubmit = function(e) {
	e.preventDefault();
}
document.getElementById('formRegMovInventario').onsubmit = function(e) {
	e.preventDefault();
}

let lisdest = [
	{key: 'cod', dest: 'txtCodigo'},
	{key: 'des', dest: 'txtProducto',ind:true},
	{key: 'pun', dest: 'txtpcompra'},
	{key: 'pun', dest: 'txtpventa'}
];

$(function() {
    fetchGet(ruta + 'defaultLoad').then(json => {
        if (json.cod === 200) {
            localData = json.res;
            cargarProductosInve(json.res.lstProductosInve, 'tabInventario');
        } else if (json.cod == 401) {
            $(expiredSession).modal('show');
        } else {
            snackAlert(json.msg, 'warning');
        }
    });
});

$(document).on('click','#tabInventario div table tbody tr td div button.edit', function() {
    let cod = (((this.parentNode).parentNode).parentNode).childNodes[2].innerHTML;
    let inv = document.getElementById('selInventario').value;
	document.getElementById('formInventario').setAttribute('cod',cod.trim());
    document.getElementById('formInventario').setAttribute('inv',inv);
    document.getElementById('txtRegCodigo').readOnly = true;
    document.getElementById('txtRegStock').readOnly = true;
    document.getElementById('btnRegistrar').innerHTML='Actualizar';
	localData.lstProductosInve.forEach(function(row) { 
        if(row['cod'] == cod){
            document.getElementById('txtRegCodigo').value = row['cod'];
            document.getElementById('txtRegDescProd').value = row['des'];
            document.getElementById('txtRegStock').value = row['stk'];
            document.getElementById('selRegUnidad').value = row['und'];
            document.getElementById('txtRegPcompra').value = row['pco'];
            document.getElementById('txtRegPventa').value = row['pvt'];
            document.getElementById('selRegEstado').value = row['ces'];
        }
    });
    $('#modalInventario').modal('show');
});

$(document).on('click','#tabInventario div table tbody tr td div button.det', function() {
    let cod = (((this.parentNode).parentNode).parentNode).childNodes[2].innerHTML;
    let des = (((this.parentNode).parentNode).parentNode).childNodes[3].innerHTML;
    document.getElementById('txtMovCodigo').value = cod;
    document.getElementById('txtMovProducto').value = des;
    document.getElementById('txtMovCodigo').readOnly = true;
    document.getElementById('txtMovProducto').readOnly = true;
    let prod = (((this.parentNode).parentNode).parentNode).childNodes[2].innerHTML;
	if(prod.trim()!=='') {
        let datafields = [
            { key: 'num', name: '#', type: 'string' },
            { key: 'fec', name: 'Fecha', type: 'date' },
            { key: 'cnt', name: 'Cant', type: 'numeric' },
            { key: 'stk', name: 'Prev', type: 'numeric' },
            { key: 'stp', name: 'Post', type: 'numeric' },
            { key: 'mot', name: 'Motivo', type: 'string' },
            { key: 'tip', name: 'Operacion', type: 'string' },
            { key: 'cpe', name: 'Comprobante', type: 'string' },
            { key: 'cli', name: 'Cliente', type: 'string' }
        ];
		fetchGet(ruta +'listarMovimientosInve?prod='+prod).then(json => {
			if(json.cod === 200){
                datatableLoad('tabMovInventario', datafields, json.res.lstMovimientosInve,[], false, { 'tableSet': 'text-nowrap' });
                
            }else{
				snackAlert(json.msg,'warning');
			}
		});
	}
    console.log('1');
    $('#modalMovInventario').modal('show');
});

$(document).on('click','#tabInventario div table tbody tr td div button.add', function() {
    document.getElementById('formRegMovInventario').setAttribute('mov','add');
    document.getElementById('lblRegMovStock').innerHTML="Agregar";
    document.getElementById('btnRegMovInventario').innerHTML="Aumentar";
    let cod = (((this.parentNode).parentNode).parentNode).childNodes[2].innerHTML;
    let des = (((this.parentNode).parentNode).parentNode).childNodes[3].innerHTML;
    document.getElementById('txtRegMovCodigo').value = cod;
    document.getElementById('txtRegMovProducto').value = des;
     $('#modalRegMovInventario').modal('show');
 });

 $(document).on('click','#tabInventario div table tbody tr td div button.des', function() {
    document.getElementById('formRegMovInventario').setAttribute('mov','des');
    document.getElementById('lblRegMovStock').innerHTML="Descontar";
    document.getElementById('btnRegMovInventario').innerHTML="Descontar";
    let cod = (((this.parentNode).parentNode).parentNode).childNodes[2].innerHTML;
    let des = (((this.parentNode).parentNode).parentNode).childNodes[3].innerHTML;
    document.getElementById('txtRegMovCodigo').value = cod;
    document.getElementById('txtRegMovProducto').value = des;
     $('#modalRegMovInventario').modal('show');
 });

 $('#modalRegMovInventario').on('hidden.bs.modal', function () {
	document.getElementById("formRegMovInventario").reset();
	document.getElementById('formRegMovInventario').removeAttribute('mov');
	document.getElementById('txtRegMovCodigo').readOnly = true;
    document.getElementById('txtRegMovProducto').readOnly = true;
});


$(document).on('click','#tabInventario div table tbody tr td div button.del', function() { 
    let cod = (((this.parentNode).parentNode).parentNode).childNodes[2].innerHTML;
    console.log(cod);
    let inv = document.getElementById('selInventario').value;
	document.getElementById(confirmFormId).setAttribute('cod',cod.trim());
    document.getElementById('formInventario').setAttribute('inv',inv);
    document.getElementById(confirmFormId).setAttribute('ope',0);
    document.querySelector(confirmFormMensaje).innerHTML = msgConfirmacion.eliminar;
    $(confirmForm).modal('show');
});

$('#modalInventario').on('hidden.bs.modal', function () {
	document.getElementById("formInventario").reset();
	document.getElementById('formInventario').removeAttribute('prod');
    document.getElementById('formInventario').removeAttribute('inv');
	document.getElementById('txtRegCodigo').readOnly = false;
    document.getElementById('txtRegStock').readOnly = false;
    document.getElementById('btnRegistrar').innerHTML='Registrar';
});

$(confirmForm).on('hidden.bs.modal', function () {
	this.removeAttribute('ope');
    this.removeAttribute('ope');
	this.querySelector('div div div.modal-body').innerHTML = '';
});

$('#modalInventario').on('shown.bs.modal', function () {
    let inv = document.getElementById('selInventario').value;
    document.getElementById('formInventario').setAttribute('inv',inv);
});

document.getElementById('btnRegMovInventario').onclick = function() {

	if(document.getElementById('formRegMovInventario').checkValidity()){
        if(document.getElementById('formRegMovInventario').hasAttribute('mov')){
        	let formData = new FormData(document.getElementById('formRegMovInventario'));
            formData.set('mov',document.getElementById('formRegMovInventario').getAttribute('mov')); 
            fetchPost(ruta +'registrarMovimientoManual',formData).then(json => {
                if(json.cod == 200){
                    cargarProductosInve(json.res.lstProductosInve,'tabInventario');
                    snackAlert(json.msg,'success');
                }else{
                    snackAlert(json.msg,'danger');
                }
            });
        }else{alert('no valido');}
	}
    $('#modalRegMovInventario').modal('hide');
}

document.getElementById('btnRegistrar').onclick = function() {
	if(document.getElementById('formInventario').checkValidity()){
        if(!document.getElementById('formInventario').hasAttribute('cod')){
            document.querySelector(confirmFormMensaje).innerHTML = msgConfirmacion.guardar;
            document.getElementById(confirmFormId).setAttribute('ope',1);
        }else{
            document.querySelector(confirmFormMensaje).innerHTML = msgConfirmacion.modificar;
            document.getElementById(confirmFormId).setAttribute('ope',2);
        }
        $(confirmForm).modal('show');
	}//else{alert('no valido');}
}

document.querySelector(confirmFormAceptar).onclick = function() {
    $(progressForm).modal('show');
    let ope = document.getElementById(confirmFormId).getAttribute('ope');
    let inv = document.getElementById('formInventario').getAttribute('inv');
    $('#modalInventario').modal('hide');
    let msg;

    switch (ope) {
        case '1':
            msg = msgRespuesta.guardado;
            break;
        case '2':
            msg = msgRespuesta.modificado;
            break;
        case '0':
            msg = msgRespuesta.eliminado;
            break;
        default:
            snackAlert('No hay acción para ejecutar');
            return;
    }

    let formData = new FormData(document.getElementById('formInventario'));
    formData.set('ope', ope);
    formData.set('numInv', inv);

    if (ope === '0' || ope === '2') {
        let cod = document.getElementById(confirmFormId).getAttribute('cod');
        formData.set('codProd', cod);
    }
    
    let PostData = setPostData(dataFormulario, 'formulario', formData); 
    let count = formData.size;
    console.log(formData);

    if (count > 0) {
        if (ope !== 'pdf') {
            fetchPost(ruta + 'operacion', PostData).then(json => {
                if (json.cod == 200) {
                    cargarProductosInve(json.res.lstProductosInve, 'tabInventario');
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

function cargarProductosInve(lstProductosInve, datatable) {
    localData.lstProductosInve = lstProductosInve;

    let cntProductosInve = 0;
    lstProductosInve.forEach(function(row) {
        cntProductosInve++;

    });
    document.getElementById('txtCantProductos').value = cntProductosInve;
    let datafields = [
        { key: 'cod', name: 'Código', type: 'string' },
        { key: 'des', name: 'Descripción', type: 'string' },
        { key: 'stk', name: 'Stock', type: 'numeric' },
        { key: 'und', name: 'UM', type: 'string' },
        { key: 'pco', name: 'P. Compra', type: 'decimal' },
        { key: 'pvt', name: 'P. Venta', type: 'decimal' },
        { key: 'est', name: 'Estado', type: 'string' }
    ];
    let options = [
        { btn: 'primary', fa: 'fa-pencil', fn: 'edit' },
        { btn: 'info', fa: 'fa-list', fn: 'det' },
        { btn: 'success', fa: 'fa-plus', fn: 'add' },
        { btn: 'warning', fa: 'fa-minus', fn: 'des' },
        { btn: 'danger', fa: 'fa-trash-o', fn: 'del'}
    ];
    datatableLoad(datatable, datafields, lstProductosInve, options, true, { 'tableSet': 'text-nowrap' });
    //document.getElementById(datatable).setAttribute('data', per);
    tablePagination(datatable, true, 5);
}

