//active-Row Onclick
$(document).on('click','table tbody tr', function(event) {
    $(this).addClass('table-active').siblings().removeClass('table-active');
});
//rezise table on change screen
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

//cargar datatable
function datatableLoad(json,datatable,datafields,options,rowindex) {
    var divContainer = document.getElementById(datatable);
    divContainer.innerHTML="";
    var tableResponsive = document.createElement("div");
    tableResponsive.classList.add("table-responsive");
    var table = tableLoad(json,datatable,datafields,options,rowindex);
    tableResponsive.appendChild(table);
    var nav = document.createElement("nav");
    var ul = document.createElement("ul");
    ul.classList.add("pagination");
    ul.classList.add("justify-content-center");
    nav.appendChild(ul);
    divContainer.innerHTML = "";
    divContainer.appendChild(tableResponsive);
    divContainer.appendChild(nav);
}
//generate table
function tableLoad(json,datatable,datafields,options,rowindex) {
    var divContainer = document.getElementById(datatable);
    var table = document.createElement("table");
    table.classList.add("table");
    if($(window).width()<768){
        table.classList.add("table-sm");
    }
    var thead = document.createElement("thead");
    var tr = thead.insertRow(-1);
    if(rowindex == true){
        var th = document.createElement("th");
        th.setAttribute( "scope", "col" );
        th.innerHTML = "#";
        tr.appendChild(th);      
    }
    if(options.length > 0){
        var th = document.createElement("th");
        th.setAttribute( "scope", "col" );
        th.innerHTML = "Opciones";
        tr.appendChild(th);      
    }
    for (var i = 0; i < datafields.length; i++) {
        var th = document.createElement("th"); // TABLE HEADER.
        th.setAttribute( "scope", "col" );
        th.innerHTML = datafields[i]['name'];
        if(typeof datafields[i]['hidden'] !== 'undefined'){
            th.hidden = datafields[i]['hidden'];
        }
        tr.appendChild(th);
    }
    table.appendChild(thead);
    // ADD JSON DATA TO THE TABLE AS ROWS.
    var tbody = document.createElement("tbody");
    for (var i = 0; i < json.length; i++) {
        tr = tbody.insertRow(-1);
        if(rowindex == true){
            var th = document.createElement('th');
            th.innerHTML = i + 1;
            th.setAttribute( "scope", "row" );
            tr.appendChild(th);
        }
        if(options.length > 0){
            var td = document.createElement('td');
			var btng = document.createElement('div');
			btng.classList.add('btn-group');
			btng.setAttribute( "role", "group" );
            var ind = 0;
            options.forEach(function(opt) {
                var btn = document.createElement('button');
                btn.type = 'button';
                btn.classList.add('btn');
                btn.classList.add('btn-'+opt['btn']);
                btn.classList.add('btn-sm');
                btn.classList.add(opt['fn']);
                btn.classList.add('mb-1');
                if(ind > 0){
                    btn.classList.add('ml-1');
                }
                ind ++;
                var fa = document.createElement('i');
                fa.classList.add('fa');
                fa.classList.add(opt['fa']);
                btn.appendChild(fa);
                btng.appendChild(btn);
            });
			td.appendChild(btng);
            //td.innerHTML = i + 1;
            tr.appendChild(td);
        }
        for (var j = 0; j < datafields.length; j++) {    
            var td = tr.insertCell(-1);
            
            if(datafields[j]['type']=='string'){
                td.innerHTML = json[i][datafields[j]['key']];
            }else if (datafields[j]['type']=='decimal'){
                var decimal = parseFloat(json[i][datafields[j]['key']]).toFixed(2);
                td.innerHTML = decimal;
            }else if (datafields[j]['type']=='date'){
                //var decimal = parseFloat(json[i][datafields[j]['key']]).toFixed(2);
                var date = new Date(json[i][datafields[j]['key']].replace(/-/g,"/"));
                var year = date.getFullYear();
                var fecha = date.getDate()+'/'+(date.getMonth()+1)+'/'+date.getFullYear().toString().substr(2)+' '+date.getHours()+':'+date.getMinutes().toString().padStart(2,'0');
                //Saturday, June 9th, 2007, 5:46:21 PM      
                td.innerHTML = fecha;
            }else{
                td.innerHTML = json[i][datafields[j]['key']];
            }
            
            if(typeof datafields[j]['hidden'] !== 'undefined'){
                td.hidden = datafields[j]['hidden'];
            }
        }

    }
    table.appendChild(tbody);
    return table;
}
//Filtro  datatable
function tableFilter(table,buscar,maxrows) {
    buscar = buscar.trim();
    if(document.getElementById(table) && buscar!=='undefined' && Number.isInteger(maxrows)){
        var input,filter,tbody,tr,td,tdval,rows=0;
        filter = buscar.toUpperCase();
        table = document.getElementById(table);
        tbody = table.getElementsByTagName('tbody')[0];
        tr = tbody.getElementsByTagName('tr');
        for (var i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName('td')
            var ind = 0;
                for (var j = 0; j < td.length; j++) {
              tdval = td[j].textContent || td[j].innerText;
              if (tdval.toUpperCase().indexOf(filter) > -1) {
                  ind = 1;
              }
            }
            if (ind == 1) {
                if (rows < maxrows) {
                    tr[i].style.display = "";
                }else{
                    tr[i].style.display = "none";
                }
                rows++;
            } else {
              tr[i].style.display = "none";
            }
        }
    }else{
        console.log('Error: Error table filter');
    }
}
//paginar datatable
function tablePagination(datatable,indprevNext,npages) {
    $(document).ready(function(){
        $('#pagina').find('#'+datatable).each(function(){
            $(this).pageMe({showPrevNext:indprevNext,hidePageNumbers:false,perPage:npages});
        }); 
    }); 
}
//exporto to csv file
function tableToCsv(table, filename) {
    var csv = [];
    var rows = document.querySelectorAll('#'+table+" div table tr");
    for (var i = 0; i < rows.length; i++) {
        var row = [], cols = rows[i].querySelectorAll("td, th");
        for (var j = 0; j < cols.length; j++) 
            row.push(cols[j].innerText);
        csv.push(row.join(","));        
    }
    // Download CSV
    downloadCSV(csv.join("\n"), filename);
}
//download file
function downloadCSV(csv, filename) {
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
//Paginacion options
$.fn.pageMe = function(opts){
    var $this = this.find('tbody'),
        defaults = {
            perPage: 5,
            showPrevNext: false,
            hidePageNumbers: false
        },
        settings = $.extend(defaults, opts);
    var pagerSelector = this.find('.pagination');
    var listElement = $this;
    var perPage = settings.perPage; 
    var children = listElement.children();
    var pager;
    if (typeof settings.childSelector!="undefined") {
        children = listElement.find(settings.childSelector);
    }
    if (typeof pagerSelector!="undefined") {
        pager = pagerSelector;
    }
    var numItems = children.length;
    var numPages = Math.ceil(numItems/perPage);
    pager.data("curr",0);
    if (settings.showPrevNext){
        $('<li class="page-item"><a class="page-link prev-link" href="#"><i class="fa fa-arrow-left"></i></a></li>').appendTo(pager);
    }
    var curr = 0;
    while(numPages > curr && (settings.hidePageNumbers==false)){
        $('<li class="page-item"><a class="page-link num-link" href="#">'+(curr+1)+'</a></li>').appendTo(pager);
        curr++;
    }
    if (settings.showPrevNext){
        $('<li class="page-item"><a class="page-link next-link" href="#"><i class="fa fa-arrow-right"></i></a></li>').appendTo(pager);
    }
    pager.find('.page-link:first').addClass('active');
    pager.find('.prev-link').parent().addClass('disabled');
    if (numPages<=1) {
        pager.find('.next-link').parent().addClass('disabled');
    }
    pager.children().eq(1).addClass("active");
    children.hide();
    children.slice(0, perPage).show();
    
    pager.find('li .num-link').click(function(){
        var clickedPage = $(this).html().valueOf()-1;
        goTo(clickedPage,perPage);
        return false;
    });
    pager.find('li .prev-link').click(function(){
        previous();
        return false;
    });
    pager.find('li .next-link').click(function(){
        next();
        return false;
    });

    function previous(){
        var goToPage = parseInt(pager.data("curr")) - 1;
        goTo(goToPage);
    }
     
    function next(){
        goToPage = parseInt(pager.data("curr")) + 1;
        goTo(goToPage);
    }
    
    function goTo(page){
        var startAt = page * perPage,
            endOn = startAt + perPage;
        children.css('display','none').slice(startAt, endOn).show();
        if (page>=1) {
            pager.find('.prev-link').parent().removeClass('disabled');
        }
        else {
            pager.find('.prev-link').parent().addClass('disabled');
        }
        if (page<(numPages-1)) {
            pager.find('.next-link').parent().removeClass('disabled');
        }
        else {
            pager.find('.next-link').parent().addClass('disabled');
        }
        pager.data("curr",page);
        pager.children().removeClass("active");
        pager.children().eq(page+1).addClass("active");
    }
};
