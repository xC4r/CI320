//Focus top
$('#sidebar-collapse').on('shown.bs.collapse', function (e) {
    $('html,body').animate({ scrollTop: $('#sidebar-collapse').offset().top -80}, 500);
}); 
//Load modulo
$('#side-menu').on('click','.item-menu', function(){
    fetchGetHTML('main/modulo?mod='+$(this).attr('name'));
    $('#sidebar-collapse').collapse('hide');
    return false;
});
$(document).on('click','.fa-eye-slash',function(){
    var input = ((($(this).parent()).parent()).parent()).find('input');
    if(input.hasClass('password')){
        input.removeClass('password');
    }else{
        input.addClass('password');
    }
});

$(document).on('show.bs.modal', '.modal', function (event) {
	var zIndex = 1040 + (10 * $('.modal:visible').length);
	$(this).css('z-index', zIndex);
	setTimeout(function() {
		$('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
	},0);
});

$(document).on('hidden.bs.modal', function (event) {
  if ($('.modal:visible').length) {
    $('body').addClass('modal-open');
  }
});

fetchGet('main/menu').then(json => {
    if(json.mensaje == ""){
        var $menu = $("#side-menu");
        $.each(json.menu, function (i) {
            $menu.append(
                setMenu(this,(i+1)*100)
            );
        });
    }else{
        alert(json.mensaje);
    }
});

document.getElementById('btnThemeChange').onclick = function(){
    var theme = document.getElementById('wrapper').getAttribute('class');
    var navs = document.getElementsByClassName('navbar');
    for(var i = 0; i < navs.length; i++){    
        if(theme == 'light'){
            navs[i].classList.replace("navbar-light","navbar-dark");
            navs[i].classList.replace("bg-light","bg-dark");
        }else if (theme == 'dark'){
            navs[i].classList.replace("navbar-dark","navbar-light");
            navs[i].classList.replace("bg-dark","bg-light");
        }
    }
    
    var side = document.getElementsByClassName('sidebar');
    for(var i = 0; i < side.length; i++){
        if(theme == 'light'){
            side[i].classList.replace("bg-white","bg-dark");
        }else if (theme == 'dark'){
            side[i].classList.replace("bg-dark","bg-white");
        }
        var li = side[i].getElementsByTagName('li');
        for(var l = 0; l < li.length; l++){
            if(theme == 'light'){
                li[l].classList.replace("bc-light","bc-dark");
            }else if (theme == 'dark'){
                li[l].classList.replace("bc-dark","bc-light");
            }
        }
        var as = side[i].getElementsByTagName('a');
        for(var j = 0; j < as.length; j++){
            if(theme == 'light'){
                as[j].classList.replace("text-dark","text-white");
            }else if (theme == 'dark'){
                as[j].classList.replace("text-white","text-dark");
            }
            var span = as[j].getElementsByTagName('span');
            for(var k = 0; k < span.length; k++){
                if(theme == 'light'){
                    span[k].classList.replace("border-dark","border-white");
                }else if (theme == 'dark'){
                    span[k].classList.replace("border-white","border-dark");
                }
            }
        }
    }
    
    var icons = document.querySelectorAll('#btnThemeChange i');
    if(theme == 'light'){
        icons[0].classList.replace("fa-moon-o","fa-sun-o");
        document.getElementById('btnThemeChange').classList.replace("btn-light","btn-dark");
        document.getElementById('btnUserDrop').classList.replace("btn-light","btn-dark");
        document.getElementById('toast').classList.replace("bg-light","bg-dark");
        document.getElementById('pagina').classList.replace("bg-white","bg-black");
        
        document.getElementById('wrapper').setAttribute('class','dark');
    }else if (theme == 'dark'){
        icons[0].classList.replace("fa-sun-o","fa-moon-o");
        document.getElementById('btnThemeChange').classList.replace("btn-dark","btn-light");
        document.getElementById('btnUserDrop').classList.replace("btn-dark","btn-light");
        document.getElementById('toast').classList.replace("bg-dark","bg-light");
        document.getElementById('pagina').classList.replace("bg-black","bg-white");

        document.getElementById('wrapper').setAttribute('class','light');
    }
}

function fetchGetHTML(url){
    fetch(url,{method:'GET'})
    .then(function(data) {return data.text();})
    .then(function(html) {
        document.getElementById('pagina').innerHTML = html;
        var scripts = document.getElementById("pagina").querySelectorAll("script");
        for (var i = 0; i < scripts.length; i++) {
            if (scripts[i].innerText) {
                eval(scripts[i].innerText);
            } else {
                fetch(scripts[i].src).then(function(data) {
                    data.text().then(function(r) {
                        eval(r);
                    })
                }).catch(error => console.log('Error:'+ error));
            }scripts[i].parentNode.removeChild(scripts[i]);
        }
    }).catch(error => console.log('Error:'+ error));
}

function fetchGet(url){
    return fetch(url,{
        method: 'GET',
        headers:{'token':localStorage.getItem("token")},
    })
    .then(response => response.json())
    .catch(error => console.log('Error:'+ error));
}

function fetchPost(url,data){
    return fetch(url,{
        method: 'POST',
        body: data,
        headers:{'token':localStorage.getItem("token")},
    })
    .then(response => response.json())
    .catch(error => console.log('Error:'+ error));
}

function setMenu(menuItem,hash) {
    var html = $('<li/>');
    html.addClass('list-group-item');
    html.addClass('bg-transparent');
    html.addClass('bc-light');
    html.append($('<a/>',{ href:'#',class:'text-dark'}));
    html.find(' a').append($('<t/>',{ text:' '+menuItem.nom}));
    html.find(' a').attr('name',menuItem.cod);
    html.find(' a').prepend($('<i/>',{ class: menuItem.ico}));
    if (menuItem.sub) {
        hash ++;
        html.find(' a').addClass('collapsed').attr('data-target','#sub'+hash.toString()).attr('data-toggle','collapse').attr('aria-expanded',false).attr('role','button');
        html.find(' a').append($('<span/>',{class:'fa arrow border-dark mr-3'}));
        var sub = $('<ul/>',{ id:'sub'+hash.toString()});
        //sub.addClass('list-group-item');
        sub.addClass('collapse');
        sub.addClass('list-group-flush');
        $.each(menuItem.sub, function () {
            sub.append(setMenu(this,hash));
        });
        html.append(sub);
    }else{
        html.find(' a').addClass('item-menu');
    }
    return html;  
}

function IdGen(length) {
    var result           = [];
    var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    var charactersLength = characters.length;
    for ( var i = 0; i < length; i++ ) {
      result.push(characters.charAt(Math.floor(Math.random() * 
 charactersLength)));
   }
   return result.join('');
}
function getIpClient() {
    $.getJSON('https://ipapi.co/json/', function(data) {
        return JSON.stringify(data, null, 2);
    });
}

function dateToday() {
	let now = new Date();
	let m = (now.getMonth()+1).toLocaleString('en-US',{minimumIntegerDigits: 2});
	let d = (now.getDate()).toLocaleString('en-US',{minimumIntegerDigits: 2});
	let date = now.getFullYear()+'-'+m+'-'+d;
	//let date = now.getFullYear()+'-'+(now.getMonth()+1)+'-'+now.getDate();
	return date;
}

function dateSubtractYear(str,num) {
	let input = str.split('-');
	let fecha = new Date(input[0],input[1]-1,input[2]);
	fecha.setYear(fecha.getFullYear() - num );
	let m = (fecha.getMonth()+1).toLocaleString('en-US',{minimumIntegerDigits: 2});
	let d = (fecha.getDate()).toLocaleString('en-US',{minimumIntegerDigits: 2});
	let date = fecha.getFullYear()+'-'+m+'-'+d;
	return date;
}

function dateAddYear(str,num) {
	let input = str.split('-');
	var fecha = new Date(input[0],input[1]-1,input[2]);
	fecha.setYear(fecha.getFullYear() + num );
	let m = (fecha.getMonth()+1).toLocaleString('en-US',{minimumIntegerDigits: 2});
	let d = (fecha.getDate()).toLocaleString('en-US',{minimumIntegerDigits: 2});
	let date = fecha.getFullYear()+'-'+m+'-'+d;
	return date;
}

function setPostData(objectContainer,index,formData){
    var dataForm = new FormData();
    for ( var key in objectContainer ) {
        if(typeof objectContainer[key] === 'object' && objectContainer[key] !== null && !(objectContainer[key] instanceof Date)){
            dataForm.append(key, (JSON.stringify(objectContainer[key])).replace(/"/g, '"\\\\'));
        }else if(objectContainer[key] instanceof Function){
            // excluye
        }else{
            dataForm.append(key, objectContainer[key]);
        }
    }
    var obj = {};
    formData.forEach(function(value, key){
        obj[key] = value;
    });
    var str=JSON.stringify(obj);
    obj = str.replace(/"/g, '"\\\\');
    dataForm.set(index,obj); 
    return dataForm;
}
