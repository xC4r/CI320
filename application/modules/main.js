//Focus top
$('#sidebar-collapse').on('shown.bs.collapse', function (e) {
    $('html,body').animate({ scrollTop: $('#sidebar-collapse').offset().top -80}, 500);
}); 
//Load modulo
$('#side-menu').on('click','.item-menu', function(){
    getPageFetch('main/main_apli',$(this).attr('name'));
    $('#sidebar-collapse').collapse('hide');
    return false;
});

$(window).on('resize', function(e) {    
    var tables = document.getElementsByTagName('table');    
    if($(window).width()<768){
        Array.prototype.forEach.call(tables, function(elem) {
            elem.classList.add("table-sm");
        });
    }else{
        Array.prototype.forEach.call(tables, function(elem) {
            elem.classList.remove("table-sm");
        });
    }
});

fetch('main/main_menu',{
  method: 'GET',
  headers:{
    'Content-Type':'application/json'
  },
  cache: 'no-cache'})
.then(response => response.json())
.then(json => {
    if(json.mensaje == ""){
        var $menu = $("#side-menu");
        $.each(json.menu, function (i) {
            $menu.append(
                load_menu(this,(i+1)*100)
            );
        });
    }else{
        alert(json.mensaje);
    }})
.catch(error => console.error('Error:', error));

function getPageFetch(uri,modulo){
    fetch(uri,{
        method: 'POST',
        body: JSON.stringify({modu: modulo}),
        headers:{
            'Content-Type':'application/json'
        }
    })
    .then(function(response){
        return response.text()
    })
    .then(function(html) {
        document.getElementById('pagina').innerHTML = html;
        var scripts = document.getElementById("pagina").querySelectorAll("script");
        for (var i = 0; i < scripts.length; i++) {
            if (scripts[i].innerText) {
              eval(scripts[i].innerText);
            } else {
              fetch(scripts[i].src).then(function (data) {
                data.text().then(function (r) {
                    eval(r); //Try - Catch: Script Error
                })
              });
            }
        scripts[i].parentNode.removeChild(scripts[i]);
        }
    })
    .catch(error => console.error('Error:', error));
}
//load main menu
function load_menu(menu_item,hash) {
    var html = $('<li/>');
    html.addClass('list-group-item');
    html.append($('<a/>',{ href:'#'}));
    html.find(' a').append($('<t/>',{ text:' '+menu_item.nom}));
    html.find(' a').attr('name',menu_item.cod);
    html.find(' a').prepend($('<i/>',{ class: menu_item.ico}));
    if (menu_item.sub) {
        hash ++;
        html.find(' a').addClass('collapsed').attr('data-target','#sub'+hash.toString()).attr('data-toggle','collapse').attr('aria-expanded',false).attr('role','button');
        html.find(' a').append($('<span/>',{class:'fa arrow mr-3'}));
        var sub = $('<ul/>',{ id:'sub'+hash.toString()});
        //sub.addClass('list-group-item');
        sub.addClass('collapse');

        $.each(menu_item.sub, function () {
            sub.append(load_menu(this,hash));
        });
        html.append(sub);
    }else{
        html.find(' a').addClass('item-menu');
    }
    return html;  
}

function snack_alert(message,type) {
    var div = document.getElementById('toast');
    if(div !== null){
        var snack = document.createElement("div");
        var tipo = 'alert-'+type;
        //CREATE SNAKCBAR
        snack.classList.add("alert");
        snack.classList.add(tipo);
        snack.classList.add("alert-dismissible");
        snack.classList.add("shadow");
        snack.setAttribute('role','alert');
        //CREATE ICON
        var span = document.createElement("span");
        span.classList.add("ml-2");
        span.innerText = message;
        var icon = document.createElement("i");
        icon.classList.add("fa");
            if(type=='primary'){
                icon.classList.add("fa-comment");  
            }else if(type=='secondary'){
                icon.classList.add("fa-shield");  
            }else if(type=='success'){
                icon.classList.add("fa-check");  
            }else if(type=='danger'){
                icon.classList.add("fa-times");  
            }else if(type=='warning'){
                icon.classList.add("fa-exclamation");  
            }else if(type=='info'){
                icon.classList.add("fa-info");  
            }else if(type=='light'){
                icon.classList.add("fa-bookmark");  
            }else if(type=='dark'){
                icon.classList.add("fa-question");  
            }else{
                icon.classList.add("fa-flag"); 
            }

        snack.appendChild(icon);
        snack.appendChild(span);
        //CREATE BUTTON
        var btn = document.createElement("button");
        btn.setAttribute('type','button');
        btn.setAttribute('data-dismiss','alert');
        btn.setAttribute('aria-label','close');
        btn.classList.add('close');
        var dim = document.createElement("span");
        dim.setAttribute('aria-hidden','true');
        dim.innerHTML = '&times;';
        btn.appendChild(dim);
        snack.appendChild(btn);
        //LOAD TOAST
        div.appendChild(snack);
        div.classList.add("show");
        setTimeout(function(){ 
            div.classList.remove("show"); 
            snack.remove();
        }, 3000);
    }
}

function form_alert_show(form,message) {
    var div = document.getElementById(form).getElementsByClassName('invalid-feedback');
    if(div !== null &&  div.length!==0){
        div[0].innerHTML = message;
        div[0].classList.add("d-block");
    }  
}

function form_alert_remove(form) {
    var div = document.getElementById(form).getElementsByClassName('invalid-feedback');
    if(div !== null && div.length!==0){
        div[0].innerHTML = '';
        div[0].classList.remove("d-block");
    }  
}

function form_check_valid(form) {
    var div = document.getElementById(form).getElementsByClassName('form-check-input');
    if(div !== null &&  div.length!==0){
        return div[0].checked;
    }else{
        return true;
    }
}


