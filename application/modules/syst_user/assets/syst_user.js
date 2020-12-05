var ruta = "syst_user/main/";
//JS Events
$(function () {
	json = fetchGet(ruta+'syst_user_load');
	alert(JSON.stringify(json));
	if(json.cod == 200){
		var local_data = {};
		local_data = json.res;
		localStorage.setItem("local_data", JSON.stringify(local_data));
		table_load(json.res.lst_user,'tablaUsuario');
	    select_load('txtEmpresa',json.res.lst_empresa,'num','des');
	    select_load('txtRol',json.res.lst_rol,'num','des');
	    select_load('txtEstado',json.res.lst_estado,'cod','des');
	}else if(json.cod == 401){
		$(expiredSession).modal('show');
	}else{
	    snack_alert(json.msg,'warning');
	}
});
	//var form =document.getElementById('formRegistro');
	//var data = new FormData(form);




document.getElementById('txtBuscar').onkeyup = function(){
	table_filter('tablaUsuario',this.value,5);
}

document.getElementById('btnBuscar').onclick = function(){
	txt = document.getElementById('txtBuscar').value;
	table_listar('tablaUsuario',txt);
}

document.getElementById('btnExport').onclick = function(){
	table_tocsv('tablaUsuario','export.csv');
}

document.getElementById('btnReload').onclick = function(){
	txt = document.getElementById('tablaUsuario').getAttribute('data');
 	table_listar('tablaUsuario',txt);
}

// Modal Registro Eventos
document.getElementById('formRegistro').onsubmit = function(e){
	e.preventDefault();
}

document.getElementById('btnRegistrar').onclick = function(){
	if(document.getElementById('formRegistro').checkValidity()){
		if(document.getElementById('txtUsuario').getAttribute('num') === null ){
			document.querySelector(confirmFormMensaje).innerHTML = confirm.guardar;
			document.getElementById(confirmForm).setAttribute('accion','registrar');
		}else{
			document.querySelector(confirmFormMensaje).innerHTML = confirm.modificar;
			document.getElementById(confirmForm).setAttribute('accion','modificar');
		}
		fetchPost();
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


//document.querySelector(expiredSesionAceptar).onclick = function(){

//}

document.querySelector(confirmFormAceptar).onclick = function(){
	$(progressForm).modal('show');
	var accion = document.getElementById(confirmForm).getAttribute('accion');
	var txt = document.getElementById('tablaUsuario').getAttribute('data');
	if(accion =='registrar'||accion == 'modificar'){  //Accion registrar - modificar
		$('#addModal').modal('hide');
		var json = {
			'num': document.getElementById('txtUsuario').getAttribute('num'),
			'nom': document.getElementById('txtNombres').value,
			'doc': document.getElementById('txtDocumento').value,
			'eml': document.getElementById('txtCorreo').value,
			'cod': document.getElementById('txtUsuario').value,
			'pas': document.getElementById('txtPassword').value,
			'emp': document.getElementById('txtEmpresa').value,
			'rol': document.getElementById('txtRol').value,
			'est': document.getElementById('txtEstado').value
		}
		if(Object.keys(json).length>0){
			fetch(ruta +'syst_user_regi',{
		        method: 'POST',
		        body: JSON.stringify({'json':json,'txt':txt}),
		        headers:{
		            'Content-Type':'application/json'
		        },
		        cache:'no-cache'
		    })
		    .then(response => response.json())
		    .then(json => {
		        if(json.cod == 200){
		        	if(accion == 'registrar'){
		        		snack_alert('Registro guardado satisfactoriamente','success');
		        	}else{
		        		snack_alert('Registro modificado satisfactoriamente','warning');
		        	}
		        	table_load(json.res.lst_user,'tablaUsuario',txt);
		        }else{
		            snack_alert(json.msg,'danger');
		        }
		    })
		    .catch(error => {console.log('Error:'+ error)});
		}else{
		  	snack_alert('No hay datos para registrar','warning');
		}
	}else if(accion == 'eliminar'){  //Accion eliminar
		var del = document.getElementById(confirmForm).getAttribute('del');
		var doc = document.getElementById(confirmForm).getAttribute('doc');
		if(del !== null && doc !== null){
			fetch(ruta +'syst_user_dele',{
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
		        	snack_alert('Registro eliminado satisfactoriamente','danger');
		        	table_load(json.res.lst_user,'tablaUsuario',txt);
		        }else{
		            snack_alert(json.msg,'danger');
		        }
		    })
		    .catch(error => {console.log('Error:'+ error)});
		}else{
		  	snack_alert('No hay registro para eliminar','warning');
		}
	}
	$($confirmForm).modal('hide');
	setTimeout(function(){ $(progressForm).modal('hide'); }, 500);
}

//jQuery Events
$(document).on('click','#tablaUsuario div table tbody tr td button.edit', function() {
    var num = ((this.parentNode).parentNode).childNodes[1].innerHTML;
	var data = (JSON.parse(localStorage.getItem("local_data"))).lst_user;
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

$(document).on('click','#tablaUsuario div table tbody tr td button.del', function() { 
    num = ((this.parentNode).parentNode).childNodes[1].innerHTML;
    doc = ((this.parentNode).parentNode).childNodes[4].innerHTML;
    document.getElementById(confirmForm).setAttribute('accion','eliminar');
    document.getElementById(confirmForm).setAttribute('del',num);
    document.getElementById(confirmForm).setAttribute('doc',doc);
    document.querySelector(confirmFormMensaje).innerHTML = confirm.eliminar;
    $($confirmForm).modal('show');
});

//Functions
function table_listar(datatable,txt='default'){
	txt = txt.trim();
	if(txt.length>=3){
		fetch(ruta +'syst_user_list',{
	        method: 'POST',
	        body: JSON.stringify({data:txt}),
	        headers:{
	            'Content-Type':'application/json'
	        },
	        cache:'no-cache'
	    })
	    .then(response => response.json())
	    .then(json => {
	        if(json.cod == 200){
	        	table_load(json.res.lst_user,datatable,txt);
	        }else{
	            snack_alert(json.msg,'warning');
	        }})
	    .catch(error => {console.log('Error:'+ error)});
	}else if(txt.length > 0){
	  	snack_alert('La descripción es muy corta','warning');
	}else{
	  	snack_alert('La descripción esta vacia','warning');
	}
}

function table_load(lst_user,datatable,txt='default'){
	local_data = JSON.parse(localStorage.getItem('local_data'));
	local_data['lst_user'] = lst_user;
	localStorage.setItem("local_data", JSON.stringify(local_data));
	for (var i = 0; i < lst_user.length; i++) {
		lst_user[i]['des_emp'] = '';
		lst_user[i]['des_est'] = '';
		lst_user[i]['des_rol'] = '';
		local_data.lst_empresa.forEach(function(emp){
			if(lst_user[i]['emp'] == emp['num']){
				lst_user[i]['des_emp'] = emp['des'];
			}
		});
		local_data.lst_estado.forEach(function(est){
			if(lst_user[i]['est'] == est['cod']){
				lst_user[i]['des_est'] = est['des'];
			}
		});
		local_data.lst_rol.forEach(function(rol){
			if(lst_user[i]['rol'] == rol['num']){
				lst_user[i]['des_rol'] = rol['des'];
			}
		});
	}
	var datafields = [
	    {key: 'num', name: '#', type: 'number', hidden: true },
	    {key: 'nom', name: 'Nombres', type: 'string'},
	    {key: 'eml', name: 'Correo Electronico', type: 'string'},
	    {key: 'doc', name: 'Documento Identificación', type: 'string'},
	    {key: 'des_emp', name: 'Empresa', type: 'string'},
	    {key: 'des_est', name: 'Estado', type: 'string'},
	    {key: 'des_rol', name: 'Rol', type: 'string'}
	];
	var options = [
	    {btn:'primary', fa:'fa-edit', fn:'edit'},
	    {btn:'danger', fa:'fa-trash-alt', fn:'del'}
	];
	datatable_load(lst_user,datatable,datafields,options,true);
	document.getElementById(datatable).setAttribute('data',txt);
	datatable_pagination(datatable,true,5);	//
}
