///* FUNTIONS SECTION  */
function load_menu(menu_item,hash) {
    var html = $('<li/>');
    html.addClass('list-group-item');
    html.append($('<a/>',{ href:'#'}));
    html.find(' a').append($('<t/>',{ text:' '+menu_item.nomb_apli}));
    html.find(' a').attr('name',menu_item.codi_apli);
    html.find(' a').prepend($('<i/>',{ class: menu_item.icon_apli}));
    if (menu_item.subm_apli) {
        hash ++;
        html.find(' a').addClass('collapsed').attr('data-target','#sub'+hash.toString()).attr('data-toggle','collapse').attr('aria-expanded',false).attr('role','button');
        html.find(' a').append($('<span/>',{class:'fa arrow mr-3'}));
        var sub = $('<ul/>',{ id:'sub'+hash.toString()});
        //sub.addClass('list-group-item');
        sub.addClass('collapse');

        $.each(menu_item.subm_apli, function () {
            sub.append(load_menu(this,hash));
        });
        html.append(sub);
    }else{
        html.find(' a').addClass('item-menu');
    }
    return html;  
}

function load_table(json,datatable,datafields,rowindex) {
    var divContainer = document.getElementById(datatable);
    divContainer.innerHTML="";
    var spin = document.createElement("div");
    spin.classList.add("spinner-border");
    spin.setAttribute( "role", "status");
    var sr = document.createElement("span");
    sr.classList.add("sr-only");
    sr.innerHTML ='Loading...';
    spin.appendChild(sr);
    setTimeout(function(){ 
        divContainer.appendChild(spin);
    },50);
    var col = [];
    for (var i = 0; i < json.data.length; i++) {
        for (var key in json.data[i]) {
            if (col.indexOf(key) === -1) {
                col.push(key);
            }
        }
    }

    var table = document.createElement("table");
    table.classList.add("table");
    table.classList.add("table-hover");
    table.classList.add("table-bordered");
    table.classList.add("table-sm");
    var thead = document.createElement("thead");
    thead.classList.add("thead-dark");
    var tr = thead.insertRow(-1);
    if(rowindex == true){
        var th = document.createElement("th");
        th.setAttribute( "scope", "col" );
        th.innerHTML = "#";
        tr.appendChild(th);      
    }


    for (var i = 0; i < datafields.length; i++) {
        var th = document.createElement("th");      // TABLE HEADER.
        th.setAttribute( "scope", "col" );
        th.innerHTML = datafields[i]['name'];
        tr.appendChild(th);
    }
    table.appendChild(thead);
    // ADD JSON DATA TO THE TABLE AS ROWS.
    var tbody = document.createElement("tbody");
    for (var i = 0; i < json.data.length; i++) {
        tr = tbody.insertRow(-1);
        if(rowindex == true){
            var th = document.createElement('th');
            th.innerHTML = i + 1;
            th.setAttribute( "scope", "row" );
            tr.appendChild(th);
        }
        for (var j = 0; j < datafields.length; j++) {    
            var td = tr.insertCell(-1);
            
            if(datafields[j]['type']=='string'){
                td.innerHTML = json.data[i][datafields[j]['key']];
            }else if (datafields[j]['type']=='decimal'){
                var decimal = parseFloat(json.data[i][datafields[j]['key']]).toFixed(2);
                td.innerHTML = decimal;
            }else if (datafields[j]['type']=='date'){
                //var decimal = parseFloat(json.data[i][datafields[j]['key']]).toFixed(2);
                var date = new Date(json.data[i][datafields[j]['key']].replace(/-/g,"/"));
                var year = date.getFullYear();
                var fecha = date.getDate()+'/'+(date.getMonth()+1)+'/'+date.getFullYear().toString().substr(2)+' '+date.getHours()+':'+date.getMinutes().toString().padStart(2,'0');
                //Saturday, June 9th, 2007, 5:46:21 PM      
                td.innerHTML = fecha;
            }else{
                td.innerHTML = json.data[i][datafields[j]['key']];
            }

        }
    }
    table.appendChild(tbody);
    // FINALLY ADD THE NEWLY CREATED TABLE WITH JSON DATA TO A CONTAINER.
    setTimeout(function(){ 
        divContainer.innerHTML = "";
        divContainer.appendChild(table);
    },300);
}

function load_select(json,dataselect,datafields) {
    var divContainer = document.getElementById(dataselect);
    divContainer.innerHTML="";
    for (var i = 0; i < json.data.length; i++) {
        var option = document.createElement("option");
        option.value = json.data[i][datafields['value']];
        option.innerHTML = json.data[i][datafields['text']];
        divContainer.appendChild(option);
    }
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

function export_table_to_csv(table, filename) {
    var csv = [];
    var rows = document.querySelectorAll('#'+table+" table tr");
    for (var i = 0; i < rows.length; i++) {
        var row = [], cols = rows[i].querySelectorAll("td, th");
        for (var j = 0; j < cols.length; j++) 
            row.push(cols[j].innerText);
        csv.push(row.join(","));        
    }
    // Download CSV
    download_csv(csv.join("\n"), filename);
}

function download_csv(csv, filename) {
    var csvFile;
    var downloadLink;
    // CSV FILE
    csvFile = new Blob([csv], {type: "text/csv"});
    // Download link
    downloadLink = document.createElement("a");
    // File name
    downloadLink.download = filename;
    // We have to create a link to the file
    downloadLink.href = window.URL.createObjectURL(csvFile);
    // Make sure that the link is not displayed
    downloadLink.style.display = "none";
    // Add the link to your DOM
    document.body.appendChild(downloadLink);
    // Lanzamos
    downloadLink.click();
}