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
                });
            }scripts[i].parentNode.removeChild(scripts[i]);
        }
    });
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
    .then(response => response.json()) //verifyToken
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


