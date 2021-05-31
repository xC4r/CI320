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
                }).catch(error => {console.log('Error:'+ error)});
            }scripts[i].parentNode.removeChild(scripts[i]);
        }
    }).catch(error => {console.log('Error:'+ error)});
}

function fetchGet(url){
    return fetch(url,{
        method: 'GET',
        headers:{'token':localStorage.getItem("token")},
    })
    .then(response => response.json())
    .catch(error => {console.log('Error:'+ error)});
}

function fetchPost(url,data){
    return fetch(url,{
        method: 'POST',
        body: data,
        headers:{'token':localStorage.getItem("token")},
    })
    .then(response => response.json())
    .catch(error => {console.log('Error:'+ error)});
}

function setMenu(menuItem,hash) {
    var html = $('<li/>');
    html.addClass('list-group-item');
    html.append($('<a/>',{ href:'#'}));
    html.find(' a').append($('<t/>',{ text:' '+menuItem.nom}));
    html.find(' a').attr('name',menuItem.cod);
    html.find(' a').prepend($('<i/>',{ class: menuItem.ico}));
    if (menuItem.sub) {
        hash ++;
        html.find(' a').addClass('collapsed').attr('data-target','#sub'+hash.toString()).attr('data-toggle','collapse').attr('aria-expanded',false).attr('role','button');
        html.find(' a').append($('<span/>',{class:'fa arrow mr-3'}));
        var sub = $('<ul/>',{ id:'sub'+hash.toString()});
        //sub.addClass('list-group-item');
        sub.addClass('collapse');
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
    //console.log(obj);
    return dataForm;
}
