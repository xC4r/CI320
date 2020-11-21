var ruta = "syst_user/main/";

//JS Events
page_load();

document.getElementById('txt_buscar').onkeyup = function(){
	table_filter('tabla1',this.value,5);
}

document.getElementById('btn_buscar').onclick = function(){
	txt = document.getElementById('txt_buscar').value;
	table_reload('tabla1',txt);
}

document.getElementById('btn_export').onclick = function(){
	table_tocsv('tabla1','export.csv');
}

document.getElementById('btn_reload').onclick = function(){
	txt = document.getElementById('tabla1').getAttribute('data');
 	table_reload('tabla1',txt);
}

document.getElementById('tab1_regi_usua_form').onsubmit = function(e){
	e.preventDefault();
}


//jQuery Events
$(document).on('click','#tabla1 div table tbody tr td button.edit', function() {
    var num = ((this.parentNode).parentNode).childNodes[0].innerHTML;
	var data = (JSON.parse(localStorage.getItem("local_data"))).lst_user;
    data.forEach(function(row) {
    	if(row['num'] == num){
    		document.getElementById('tab1_regi_usua_txt_usua').setAttribute('num',row['num']);
    		document.getElementById('tab1_regi_usua_txt_nomb').value = row['nom'];
    		document.getElementById('tab1_regi_usua_txt_docu').value = row['doc'];
    		document.getElementById('tab1_regi_usua_txt_corr').value = row['eml'];
    		document.getElementById('tab1_regi_usua_txt_empr').value = row['emp'];
    		document.getElementById('tab1_regi_usua_txt_usua').value = row['cod'];
    		document.getElementById('tab1_regi_usua_txt_clav').value = row['pas'];
    		document.getElementById('tab1_regi_usua_txt_role').value = row['rol'];
    		document.getElementById('tab1_regi_usua_txt_esta').value = row['est'];
    		$('#exampleModal').modal('show');
    	}
    });
});


//Bootstrap Events
$('#exampleModal').on('hidden.bs.modal', function () {
	document.getElementById("tab1_regi_usua_form").reset();
	document.getElementById("tab1_regi_usua_txt_empr").selectedIndex = '-1';
	document.getElementById("tab1_regi_usua_txt_role").selectedIndex = '-1';
	document.getElementById("tab1_regi_usua_txt_esta").selectedIndex = '-1';
	document.getElementById('tab1_regi_usua_txt_usua').removeAttribute('num');
});

$('#confirm').on('show.bs.modal', function () {
	document.getElementById("exampleModal").classList.remove("show");
});

$('#confirm').on('hide.bs.modal', function () {
	document.getElementById("exampleModal").classList.add("show");
});


//Functions
function page_load(){
	fetch(ruta +'syst_user_load',{
		method: 'GET',
		headers:{
		'Content-Type':'application/json'
		},
		cache:'no-cache'
	})
	.then(response => response.json())
	.then(json => {
	    if(json.cod == 200){
	    	var local_data = {};
	    	local_data = json.res;
	    	localStorage.setItem("local_data", JSON.stringify(local_data));
	    	table_load(json.res.lst_user,'tabla1');
	        select_load('tab1_regi_usua_txt_empr',json.res.lst_empresa,'num','des');
	        select_load('tab1_regi_usua_txt_role',json.res.lst_rol,'num','des');
	        select_load('tab1_regi_usua_txt_esta',json.res.lst_estado,'cod','des');
	    }else{
	        snack_alert(json.msg,'warning');
	    }})
	.catch(error => console.error('Error:', error));
}

function table_reload(datatable,txt='default'){
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
	    .catch(error => console.error('Error:', error));
	}else if(txt.length > 0){
	  	snack_alert('La descripción es muy corta','warning');
	}else{
	  	snack_alert('La descripción esta vacia','warning');
	}
}

function table_load(lst_user,datatable,txt='default'){
	var local_data;
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
	    {key: 'num', name: '#', type: 'number'},
	    {key: 'cod', name: 'Codigo Usuario', type: 'string'},
	    {key: 'eml', name: 'Correo Electronico', type: 'string'},
	    {key: 'nom', name: 'Nombres', type: 'string'},
	    {key: 'doc', name: 'Num Doc.', type: 'string'},
	    {key: 'des_emp', name: 'Empresa', type: 'string'},
	    {key: 'des_est', name: 'Estado', type: 'string'},
	    {key: 'des_rol', name: 'Rol', type: 'string'}
	];
	var options = [
	    {btn:'primary', fa:'fa-edit', fn:'edit'},
	    {btn:'danger', fa:'fa-trash-alt', fn:'del'}
	];
	datatable_load(lst_user,datatable,datafields,options,false);
	document.getElementById(datatable).setAttribute('data',txt);
	datatable_pagination(datatable,true,5);	
}

/*
function dataload_usuario(datatable='tabla1'){
	fetch(ruta +'syst_user_load',{
	  method: 'GET',
	  headers:{
	    'Content-Type':'application/json'
	  },
	  cache:'no-cache'
	})
	.then(response => response.json())
	.then(json => {
	    if(json.cod == 200){
	        var datafields = [
	            {key: 'num', name: '#', type: 'number'},
	            {key: 'cod', name: 'Codigo Usuario', type: 'string'},
	            {key: 'pas', name: 'Clave', type: 'string'},
	            {key: 'eml', name: 'Correo Electronico', type: 'string'},
	            {key: 'nom', name: 'Nombres', type: 'string'},
	            {key: 'doc', name: 'Nro Documento', type: 'string'},
	            {key: 'emp', name: 'Empresa', type: 'string'},
	            {key: 'rol', name: 'Rol', type: 'string'},
	            {key: 'est', name: 'Estado', type: 'string'},
	            {key: 'del', name: 'Indicador', type: 'string'},
	        ];
	        var options = [
	            {btn: 'primary', fa: 'fa-edit'},
	            {btn: 'danger', fa: 'fa-trash-alt'}
	        ];
	        datatable_load(json.res.lst_user,datatable,datafields,options,false);
	        document.getElementById(datatable).setAttribute('data','default');
	        datatable_pagination(datatable,true,5);
	        datalist_load(json.res.lst_empresa,datalist);
	    }else{
	        snack_alert(json.msg,'warning');
	    }})
	.catch(error => console.error('Error:', error));
}*/