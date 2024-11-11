const admi_dash_boar_ctrl = "admi_dash_boar/main/";
/* SECCION XXXXX */

/* FUNTIONS SECTION  */
$("#expXLS").click(function (){
    export_table_to_csv('exportTable', "excel_data.csv");
});


/* FUNTIONS SECTION  */
function admin_dash_boar_print(){
    $.ajax({
			type: "POST",
			url: admi_dash_boar_ctrl + "admi_prog_list",
			data: {
				//"command": "on"
			},
			dataType: "json",
			success: function (data) {
				if (data.mensaje == "") {
					$("#contenido").html("Hello World");
				} else {
					alert("Sucess: device control succeeded");
				}
			},
			error: function () {
				alert("Error: Device control failed");
			},
		});

    $.ajax({
			type: "POST",
			url: admi_dash_boar_ctrl + "menu_list_apli",
			data: {
				//"command": "on"
			},
			dataType: "json",
			success: function (data) {
				if (data.mensaje == "") {
					let $menu = $("#side-menu");
					$.each(data.menu, function (i) {
						$menu.append(load_menu(this, (i + 1) * 100));
					});
				} else {
					alert("Sucess: device control succeeded");
				}
			},
			error: function () {
				alert("Error: Device control failed");
			},
		});

}

