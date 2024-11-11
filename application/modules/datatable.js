//active-Row Onclick
$(document).on('click','table tbody tr', function(event) {
    $(this).addClass('table-active').siblings().removeClass('table-active');
});
//rezise table on change screen
$(window).on('resize', function(e) {    
    let tables = document.getElementsByTagName('table');
    Array.prototype.forEach.call(tables, function(elem) {
		if(!elem.classList.contains('table-sm')){
			elem.classList.add("nts");
		}
    });
    if($(window).width()<992){
        Array.prototype.forEach.call(tables, function(elem) {
        	if(elem.classList.contains('nts')){
        		elem.classList.add("table-sm");
        	}
        });
    }else{
        Array.prototype.forEach.call(tables, function(elem) {
        	if(elem.classList.contains('nts')){
        		elem.classList.remove("table-sm");
        	}
        });
    }
});

//cargar datatable
function datatableLoad(datatable='',datafields=[],json=[],options=[],rowindex=false,config={}) {
    let divContainer = document.getElementById(datatable);
    let tableResponsive = document.createElement("div");
    tableResponsive.classList.add("table-responsive");
	if(config.tabResp){
		let lista = config.tabResp.split(' ');
		lista.forEach(function(opt) {
			tableResponsive.classList.add(opt);
		});
	}
    let table = tableLoad(datafields,json,options,rowindex,config);
    tableResponsive.appendChild(table);
    let nav = document.createElement("nav");
    let ul = document.createElement("ul");
    ul.classList.add("pagination");
    ul.classList.add("justify-content-center");
    ul.classList.add("mb-0");
    nav.appendChild(ul);
    divContainer.innerHTML = "";
    divContainer.appendChild(tableResponsive);
    divContainer.appendChild(nav);
}
//generate table
function tableLoad(datafields,json=[],options=[],rowindex,config={}) {
    let table = document.createElement("table");
    table.classList.add("table");
    table.classList.add("mb-0");
	if(config.tableSet){
		let lista = config.tableSet.split(' ');
		lista.forEach(function(opt) {
			table.classList.add(opt);
		});
	}
    if($(window).width()<992){
        table.classList.add("table-sm");
        if(config.tableSet){
            if(!config.tableSet.includes('table-sm')){
                table.classList.add('nts');
            }
        }
    }
    let caption = document.createElement("caption");
    caption.innerHTML = "Datatable";
    table.appendChild(caption);
    let thead = document.createElement("thead");
	if(config.theadSet){
		let lista = config.theadSet.split(' ');
		lista.forEach(function(opt) {
			thead.classList.add(opt);
		});
	}
    let tr = thead.insertRow(-1);
    if(rowindex == true){
        let th = document.createElement("th");
        th.setAttribute( "scope", "col" );
        th.innerHTML = "#";
        tr.appendChild(th);      
    }
    if(options.length > 0){
        let th = document.createElement("th");
        th.setAttribute( "scope", "col" );
        th.innerHTML = "Opción";
        tr.appendChild(th);      
    }/* else{
        var th = document.createElement("th");
        th.setAttribute( "scope", "col" );
        th.innerHTML = "Opción";
        tr.appendChild(th);     
    }*/ 
    for (let i = 0; i < datafields.length; i++) {
        let th = document.createElement("th"); // TABLE HEADER.
        th.setAttribute( "scope", "col" );
        th.innerHTML = datafields[i]['name'];
        if(typeof datafields[i]['type']!=='undefined' && datafields[i]['type']=='decimal'&& datafields[i]['type']=='numeric'){
            th.classList.add('text-right');
        }
        if(typeof datafields[i]['key'] !== 'undefined'){
            th.setAttribute( "cod", datafields[i]['key'] );
        }
        if(typeof datafields[i]['hidden'] !== 'undefined'){
            th.hidden = datafields[i]['hidden'];
        }
        tr.appendChild(th);
    }
    table.appendChild(thead);
	
    // ADD JSON DATA TO THE TABLE AS ROWS.
    let tbody = document.createElement("tbody");
	
	if(!json.length > 0){
        /*
		for (var i = 0; i < 5; i++) {
			tr = tbody.insertRow(-1);
			for (var j = 0; j < datafields.length+1; j++) {    
				var td = tr.insertCell(-1);
				if(j == 0){
					td.innerHTML = '-';
				}else{
					td.innerHTML = '';
				}
			}
		}*/
	}else{
		for (let i = 0; i < json.length; i++) {
			tr = tbody.insertRow(-1);
			if(rowindex == true){
				let th = document.createElement('th');
				th.innerHTML = i + 1;
				th.setAttribute( "scope", "row" );
				tr.appendChild(th);
			}
			if(options.length > 0){
				let td = document.createElement('td');
				let btng = document.createElement('div');
				btng.classList.add('btn-group');
				btng.setAttribute( "role", "group" );
				let ind = 0;
				options.forEach(function(opt) {
					let btn = document.createElement('button');
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
					let fa = document.createElement('i');
					fa.classList.add('fa');
					fa.classList.add(opt['fa']);
					btn.appendChild(fa);
					btng.appendChild(btn);
				});
				td.appendChild(btng);
				tr.appendChild(td);
			}

			for (let j = 0; j < datafields.length; j++) {    
				let td = tr.insertCell(-1);

				if(datafields[j]['type']=='string'){
					//td.innerHTML = json[i][datafields[j]['key']];
                    (typeof json[i][datafields[j]['key']] !== 'undefined') ? td.innerHTML = json[i][datafields[j]['key']]:td.innerHTML ='';
				}else if (datafields[j]['type']=='numeric'){
                    //td.classList.add('text-right');
                    let numerico = parseInt(json[i][datafields[j]['key']]).toFixed(0);
					//td.innerHTML = json[i][datafields[j]['key']];
                    td.innerHTML = numerico;
				}else if (datafields[j]['type']=='decimal'){
                    td.classList.add('text-right');
					let decimal = parseFloat(json[i][datafields[j]['key']]).toFixed(2);
					td.innerHTML = decimal;
				}else if (datafields[j]['type']=='date'){
					let date = new Date(json[i][datafields[j]['key']].replace(/-/g,"/"));
					let year = date.getFullYear();
					let fecha = date.getDate()+'/'+(date.getMonth()+1)+'/'+date.getFullYear().toString().substr(2);
					//Saturday, June 9th, 2007, 5:46:21 PM      
					td.innerHTML = fecha;
				}else if (datafields[j]['type']=='datetime'){
					let date = new Date(json[i][datafields[j]['key']].replace(/-/g,"/"));
					let year = date.getFullYear();
					let fecha = date.getDate()+'/'+(date.getMonth()+1)+'/'+date.getFullYear().toString().substr(2)+' '+date.getHours()+':'+date.getMinutes().toString().padStart(2,'0');
					//Saturday, June 9th, 2007, 5:46:21 PM      
					td.innerHTML = fecha;
				}else{
					td.innerHTML = json[i][datafields[j]['key']];
				}
				
				if(typeof datafields[j]['hidden'] !== 'undefined'){
					td.hidden = datafields[j]['hidden'];
				}
                if(typeof datafields[j]['ind'] !== 'undefined'){
					tr.setAttribute('ind',datafields[j]['ind']);
				}

                //console.log(json[i][datafields[j]['key']]);
			}		
		}
    }
	
	table.appendChild(tbody);
    return table;
}

function tableAddRow(datatable='',datafields=[],json=[],options=[],rowindex=false){
	let tr = document.createElement("tr");
	if(rowindex == true){
		let th = document.createElement('th');
		th.innerHTML = 0;
		th.setAttribute( "scope", "row" );
		tr.appendChild(th);
	}
	if(options.length > 0){
		let td = document.createElement('td');
		let btng = document.createElement('div');
		btng.classList.add('btn-group');
		btng.setAttribute( "role", "group" );
		let ind = 0;
		options.forEach(function(opt) {
			let btn = document.createElement('button');
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
			let fa = document.createElement('i');
			fa.classList.add('fa');
			fa.classList.add(opt['fa']);
			btn.appendChild(fa);
			btng.appendChild(btn);
		});
		td.appendChild(btng);
		//td.innerHTML = i + 1;
		tr.appendChild(td);
	}
	for (let j = 0; j < datafields.length; j++) {    
		let td = tr.insertCell(-1);
		if(datafields[j]['type']=='string'){
            (typeof json[datafields[j]['key']] !== 'undefined') ? td.innerHTML = json[datafields[j]['key']]:td.innerHTML ='';
		}else if (datafields[j]['type']=='decimal'){
            td.classList.add('text-right');
			let decimal = parseFloat(json[datafields[j]['key']]).toFixed(2);
			td.innerHTML = decimal;
		}else if (datafields[j]['type']=='date'){
			let date = new Date(json[datafields[j]['key']].replace(/-/g,"/"));
			let year = date.getFullYear();
			let fecha = date.getDate()+'/'+(date.getMonth()+1)+'/'+date.getFullYear().toString().substr(2);
			//Saturday, June 9th, 2007      
			td.innerHTML = fecha;
		}else if (datafields[j]['type']=='datetime'){
			let date = new Date(json[datafields[j]['key']].replace(/-/g,"/"));
			let year = date.getFullYear();
			let fecha = date.getDate()+'/'+(date.getMonth()+1)+'/'+date.getFullYear().toString().substr(2)+' '+date.getHours()+':'+date.getMinutes().toString().padStart(2,'0');
			//Saturday, June 9th, 2007, 5:46:21 PM      
			td.innerHTML = fecha;
		}else{
			td.innerHTML = json[datafields[j]['key']];
		}
		if(typeof datafields[j]['hidden'] !== 'undefined'){
			td.hidden = datafields[j]['hidden'];
		}
	}
	document.querySelector('#'+datatable+' div table tbody').appendChild(tr);

}
//Filtro  datatable
function tableFilter(table,buscar,maxrows) {
    buscar = buscar.trim();
    if(document.getElementById(table) && buscar!=='undefined' && Number.isInteger(maxrows)){
        let input,filter,tbody,tr,td,tdval,rows=0;
        filter = buscar.toUpperCase();
        table = document.getElementById(table);
        tbody = table.getElementsByTagName('tbody')[0];
        tr = tbody.getElementsByTagName('tr');
        for (let i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName('td')
                let ind = 0;
                for (let j = 0; j < td.length; j++) {
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
			$(this).find('nav > ul').each(function(){$(this).html('');});
            $(this).pageMe({showPrevNext:indprevNext,hidePageNumbers:false,perPage:npages});
        }); 
    }); 
}
//exporto to csv file
function tableToCsv(table, filename) {
    let csv = [];
    let rows = document.querySelectorAll('#'+table+" div table tr");
    for (let i = 0; i < rows.length; i++) {
        let row = [], cols = rows[i].querySelectorAll("td, th");
        for (let j = 0; j < cols.length; j++) 
            row.push(cols[j].innerText);
        csv.push(row.join(","));        
    }
    // Download CSV
    downloadCSV(csv.join("\n"), filename);
}
//download file
function downloadCSV(csv, filename) {
    let csvFile;
    let downloadLink;
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
    let $this = this.find('tbody'),
        defaults = {
            perPage: 5,
            showPrevNext: false,
            hidePageNumbers: false
        },
        settings = $.extend(defaults, opts);
    let pagerSelector = this.find('.pagination');
    let listElement = $this;
    let perPage = settings.perPage; 
    let children = listElement.children();
    let pager;
    if (typeof settings.childSelector!="undefined") {
        children = listElement.find(settings.childSelector);
    }
    if (typeof pagerSelector!="undefined") {
        pager = pagerSelector;
    }
    let numItems = children.length;
    let numPages = Math.ceil(numItems/perPage);

    if(numPages == 0){ numPages=1;}

    pager.data("curr",0);
    if (settings.showPrevNext){
        $('<li class="page-item"><a class="page-link prev-link" href="#"><i class="fa fa-arrow-left"></i></a></li>').appendTo(pager);
    }
    let curr = 0;
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
        let clickedPage = $(this).html().valueOf()-1;
        goTo(clickedPage);
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
        let goToPage = parseInt(pager.data("curr")) - 1;
        goTo(goToPage);
    }
     
    function next(){
        let goToPage = parseInt(pager.data("curr")) + 1;
        goTo(goToPage);
    }
    
    function goTo(page){
        let startAt = page * perPage,
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

function tableToJson(tableName){
    let myRows = [];
    let $headers = $("#"+tableName+" div table thead th");
    $("#"+tableName+" div table tbody tr").each(function(index) {
        let $cells = $(this).find("td");
        myRows[index] = {};
        $cells.each(function(cellIndex) {
            if($($headers[cellIndex]).attr("cod")){
                //console.log("cod : "+ $($headers[cellIndex]).attr("cod"));
                myRows[index][$($headers[cellIndex]).attr("cod")] = $(this).html();
            }
        });
        if($(this).attr('ind') && $(this).attr('ind')=='1'){
            myRows[index]['ind'] = $(this).attr('ind'); 
        }
    });
    return myRows;
}
