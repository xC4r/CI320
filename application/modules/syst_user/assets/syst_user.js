var ruta = "syst_user/main/";

table_reload('tabla1');

document.getElementById('txt_buscar').addEventListener('keyup',function(){
	table_filter('tabla1',this.value,5);
});

document.getElementById('btn_buscar').addEventListener('click',function(){
	txt = document.getElementById('txt_buscar').value;
	table_reload('tabla1',txt);
});

document.getElementById('btn_export').addEventListener('click',function(){
	table_tocsv('tabla1','export.csv');
});

document.getElementById('btn_reload').addEventListener('click',function(){
	txt = document.getElementById('tabla1').getAttribute('data');
 	table_reload('tabla1',txt);
});

//FUNCTIONS
function table_reload(datatable,txt='default'){
	txt = txt.trim();
	if(txt.length>3){
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
	            var datafields = [
	                {key: 'num', name: '#', type: 'number'},
	                {key: 'cod', name: 'Codigo Usuario', type: 'string'},
	                {key: 'pas', name: 'Clave', type: 'string'},
	                {key: 'eml', name: 'Correo Electronico', type: 'string'},
	                {key: 'nom', name: 'Nombres', type: 'string'},
	                {key: 'doc', name: 'Documento Identif', type: 'string'},
	                {key: 'est', name: 'Estado', type: 'string'},
	                {key: 'del', name: 'Indicador', type: 'string'},
	            ];
	            var options = [
	                {btn: 'primary', fa: 'fa-edit'},
	                {btn: 'danger', fa: 'fa-trash-alt'}
	            ];
	            datatable_load(json.res.lst_user,datatable,datafields,options,false);
	            document.getElementById(datatable).setAttribute('data',txt);
	            datatable_pagination(datatable,true,5);
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
}