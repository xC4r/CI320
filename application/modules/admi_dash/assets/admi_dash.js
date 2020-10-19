var admi_dash_boar_ctrl = "admi_dash_boar/main/";
/* SECCION XXXXX */

//admin_dash_boar_print();

/* FUNTIONS SECTION  */
$("#expXLS").click(function (){
    export_table_to_csv('exportTable', "excel_data.csv");
});


/* FUNTIONS SECTION  */
function admin_dash_boar_print(){
    $.ajax({
        type: 'POST',
        url: admi_dash_boar_ctrl+'admi_prog_list',
        data: {
            //"command": "on"
        },
        dataType: 'json',
        success: function(data){
            if(data.mensaje == ""){
                $("#contenido").html('Hello World');
            }else{
                alert('Sucess: device control succeeded');
            }
        },
        error: function(){
            alert('Error: Device control failed');
        }
    });

    $.ajax({
        type: 'POST',
        url: admi_dash_boar_ctrl + 'menu_list_apli',
        data: {
            //"command": "on"
        },
        dataType: 'json',
        success: function(data){
            if(data.mensaje == ""){
                var $menu = $("#side-menu");
                $.each(data.menu, function (i) {
                    $menu.append(
                        load_menu(this,(i+1)*100)
                    );
                });
            }else{
                alert('Sucess: device control succeeded');
            }
        },
        error: function(){
            alert('Error: Device control failed');
        }
    });

}

function admi_apro_prog_tabl_load(){
    jQuery("#admi_apro_prog_lbl_mens").html("");
    jQuery.ajaxSetup({async: true});
    jQuery.post(
        admi_apro_prog_nomb_cont + "admi_prog_list", {
            cache: Math.random()
        }, function (html) {
            if (html.mensaje == "") {
                var sour_codi_prog = {
                    datatype: "json",
                    datafields: [
                        {name: 'cons_prog', type: 'string'},
                        {name: 'codi_prog', type: 'string'}
                    ],
                    localdata: html.data
                };
                var data_codi_prog = new jQuery.jqx.dataAdapter(sour_codi_prog);
                 jQuery("#admi_apro_prog_txt_prog").jqxInput({
                    source: data_codi_prog
                });
            } else {
                jQuery("#admi_apro_prog_lbl_mens").html(html.mensaje);
            }
        }, "json")
    .fail(function (xhr, textStatus, errorThrown) {
        main_erro("admi_apro_prog_prog()", admi_apro_prog_nomb_cont + "admi_prog_list", xhr.responseText, jQuery("#codi_usua").html())
        jQuery("#admi_apro_prog_lbl_mens").html('* SU MENSAJE DE ERROR A SIDO ENVIADO A SISTEMAS.');
    });
}
