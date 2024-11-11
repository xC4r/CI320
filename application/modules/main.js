//Focus top
$("#sidebar-collapse").on("shown.bs.collapse", function (e) {
	$("html,body").animate(
		{ scrollTop: $("#sidebar-collapse").offset().top - 80 },
		500
	);
});
//Load modulo
$("#side-menu").on("click", ".item-menu", function () {
	fetchGetHTML("main/modulo?mod=" + $(this).attr("name"));
	$("#sidebar-collapse").collapse("hide");
	return false;
});
$(document).on("click", ".fa-eye-slash", function () {
	let input = $(this).parent().parent().parent().find("input");
	if (input.hasClass("password")) {
		input.removeClass("password");
	} else {
		input.addClass("password");
	}
});

$(document).on("show.bs.modal", ".modal", function (event) {
	let zIndex = 1040 + 10 * $(".modal:visible").length;
	$(this).css("z-index", zIndex);
	setTimeout(function () {
		$(".modal-backdrop")
			.not(".modal-stack")
			.css("z-index", zIndex - 1)
			.addClass("modal-stack");
	}, 0);
});

$(document).on("hidden.bs.modal", function (event) {
	if ($(".modal:visible").length) {
		$("body").addClass("modal-open");
	}
});

fetchGet("main/menu").then((json) => {
	if (json.mensaje == "") {
		let $menu = $("#side-menu");
		$.each(json.menu, function (i) {
			$menu.append(setMenu(this, (i + 1) * 100));
		});
	} else {
		alert(json.mensaje);
	}
});

document.getElementById("btnThemeChange").onclick = function () {
	let theme = document.body.getAttribute("class");
	let nav = document.getElementsByClassName("navbar")[0];
	let ico = document.querySelectorAll("#btnThemeChange i")[0];
	if (theme == "dark") {
		nav.classList.replace("navbar-dark", "navbar-light");
		ico.classList.replace("fa-sun-o", "fa-moon-o");
		document.body.classList.remove("dark");
	} else {
		nav.classList.replace("navbar-light", "navbar-dark");
		ico.classList.replace("fa-moon-o", "fa-sun-o");
		document.body.classList.add("dark");
	}
};

function fetchGetHTML(url) {
	fetch(url, { method: "GET" })
		.then(function (data) {
			return data.text();
		})
		.then(function (html) {
			document.getElementById("pagina").innerHTML = html;
			let scripts = document
				.getElementById("pagina")
				.querySelectorAll("script");
			for (let i = 0; i < scripts.length; i++) {
				if (scripts[i].innerText) {
					let a = scripts[i].innerText;

					eval(a);
				} else {
					fetch(scripts[i].src)
						.then(function (data) {
							data.text().then(function (r) {
								eval(r);
							});
						})
						.catch((error) => console.log("Error:" + error));
				}
				scripts[i].parentNode.removeChild(scripts[i]);
			}
		})
		.catch((error) => console.log("Error:" + error));
}

function fetchGet(url) {
	return fetch(url, {
		method: "GET",
		headers: { token: localStorage.getItem("token") },
	})
		.then((response) => response.json())
		.catch((error) => console.log("Error:" + error));
}

function fetchPost(url, data) {
	return fetch(url, {
		method: "POST",
		body: data,
		headers: { token: localStorage.getItem("token") },
	})
		.then((response) => response.json())
		.catch((error) => console.log("Error:" + error));
}

function fetchPostPdf(url, data) {
	return fetch(url, {
		method: "POST",
		body: data,
		headers: { token: localStorage.getItem("token") },
	})
		.then((response) => response.blob())
		.catch((error) => console.log("Error:" + error));
}

function setMenu(menuItem, hash) {
	let html = $("<li/>");
	html.addClass("list-group-item");
	html.append($("<a/>", { href: "#" }));
	html.find(" a").append($("<t/>", { text: " " + menuItem.nom }));
	html.find(" a").attr("name", menuItem.cod);
	html.find(" a").prepend($("<i/>", { class: menuItem.ico }));
	if (menuItem.sub) {
		hash++;
		html
			.find(" a")
			.addClass("collapsed")
			.attr("data-target", "#sub" + hash.toString())
			.attr("data-toggle", "collapse")
			.attr("aria-expanded", false)
			.attr("role", "button");
		html
			.find(" a")
			.append($("<span/>", { class: "fa arrow border-dark mr-3" }));
		let sub = $("<ul/>", { id: "sub" + hash.toString() });
		sub.addClass("collapse");
		sub.addClass("list-group-flush");
		$.each(menuItem.sub, function () {
			sub.append(setMenu(this, hash));
		});
		html.append(sub);
	} else {
		html.find(" a").addClass("item-menu");
	}
	return html;
}

function IdGen(length) {
	let result = [];
	let characters =
		"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
	let charactersLength = characters.length;
	for (let i = 0; i < length; i++) {
		result.push(
			characters.charAt(Math.floor(Math.random() * charactersLength))
		);
	}
	return result.join("");
}
function getIpClient() {
	$.getJSON("https://ipapi.co/json/", function (data) {
		return JSON.stringify(data, null, 2);
	});
}

function dateToday() {
	let now = new Date();
	let m = (now.getMonth() + 1).toLocaleString("en-US", {
		minimumIntegerDigits: 2,
	});
	let d = now.getDate().toLocaleString("en-US", { minimumIntegerDigits: 2 });
	let date = now.getFullYear() + "-" + m + "-" + d;

	return date;
}

function dateSubtractYear(str, num) {
	let input = str.split("-");
	let fecha = new Date(input[0], input[1] - 1, input[2]);
	fecha.setYear(fecha.getFullYear() - num);
	let m = (fecha.getMonth() + 1).toLocaleString("en-US", {
		minimumIntegerDigits: 2,
	});
	let d = fecha.getDate().toLocaleString("en-US", { minimumIntegerDigits: 2 });
	let date = fecha.getFullYear() + "-" + m + "-" + d;
	return date;
}

function dateAddYear(str, num) {
	let input = str.split("-");
	let fecha = new Date(input[0], input[1] - 1, input[2]);
	fecha.setYear(fecha.getFullYear() + num);
	let m = (fecha.getMonth() + 1).toLocaleString("en-US", {
		minimumIntegerDigits: 2,
	});
	let d = fecha.getDate().toLocaleString("en-US", { minimumIntegerDigits: 2 });
	let date = fecha.getFullYear() + "-" + m + "-" + d;
	return date;
}

function setPostData(objectContainer, index, formData) {
	let dataForm = new FormData();
	for (let key in objectContainer) {
		if (
			typeof objectContainer[key] === "object" &&
			objectContainer[key] !== null &&
			!(objectContainer[key] instanceof Date)
		) {
			dataForm.append(
				key,
				JSON.stringify(objectContainer[key]).replace(/"/g, '"\\\\')
			);
		} else if (objectContainer[key] instanceof Function) {
			// excluye
		} else {
			dataForm.append(key, objectContainer[key]);
		}
	}
	let obj = {};
	formData.forEach(function (value, key) {
		obj[key] = value;
	});
	let str = JSON.stringify(obj);
	obj = str.replace(/"/g, '"\\\\');
	dataForm.set(index, obj);
	return dataForm;
}
