function snackAlert(message, type) {
	let div = document.getElementById("toast");
	if (div !== null) {
		let snack = document.createElement("div");
		let tipo = "alert-" + type;
		//CREATE SNAKCBAR
		snack.classList.add("alert");
		snack.classList.add(tipo);
		snack.classList.add("alert-dismissible");
		snack.classList.add("shadow");
		snack.setAttribute("role", "alert");
		//CREATE ICON
		let span = document.createElement("span");
		span.classList.add("ml-2");
		span.innerText = message;
		let icon = document.createElement("i");
		icon.classList.add("fa");
		if (type == "primary") {
			icon.classList.add("fa-comment");
		} else if (type == "secondary") {
			icon.classList.add("fa-shield");
		} else if (type == "success") {
			icon.classList.add("fa-check");
		} else if (type == "danger") {
			icon.classList.add("fa-times");
		} else if (type == "warning") {
			icon.classList.add("fa-exclamation");
		} else if (type == "info") {
			icon.classList.add("fa-info");
		} else if (type == "light") {
			icon.classList.add("fa-bookmark");
		} else if (type == "dark") {
			icon.classList.add("fa-question");
		} else {
			icon.classList.add("fa-flag");
		}

		snack.appendChild(icon);
		snack.appendChild(span);
		//CREATE BUTTON
		let btn = document.createElement("button");
		btn.setAttribute("type", "button");
		btn.setAttribute("data-dismiss", "alert");
		btn.setAttribute("aria-label", "close");
		btn.classList.add("close");
		let dim = document.createElement("span");
		dim.setAttribute("aria-hidden", "true");
		dim.innerHTML = "&times;";
		btn.appendChild(dim);
		snack.appendChild(btn);
		//LOAD TOAST
		div.appendChild(snack);
		div.classList.add("show");
		setTimeout(function () {
			div.classList.remove("show");
			snack.remove();
		}, 3000);
	}
}

function formAlertShow(form, message) {
	let div = document
		.getElementById(form)
		.getElementsByClassName("invalid-feedback");
	if (div !== null && div.length !== 0) {
		div[0].innerHTML = message;
		div[0].classList.add("d-block");
	}
}

function formAlertRemove(form) {
	let div = document
		.getElementById(form)
		.getElementsByClassName("invalid-feedback");
	if (div !== null && div.length !== 0) {
		div[0].innerHTML = "";
		div[0].classList.remove("d-block");
	}
}

function formCheckValid(form) {
	let div = document
		.getElementById(form)
		.getElementsByClassName("form-check-input");
	if (div !== null && div.length !== 0) {
		return div[0].checked;
	} else {
		return true;
	}
}

function formSelectLoad(id, list, value, html) {
	let selectContainer = document.getElementById(id);
	selectContainer.innerHTML = "";
	list.forEach(function (lst) {
		let opt = document.createElement("option");
		opt.value = lst[value];
		opt.innerHTML = lst[html];
		selectContainer.appendChild(opt);
	});
	selectContainer.selectedIndex = "-1";
}
//autocomplete
function autocomplete(valor, lst, arr, dest) {
	let input = document.getElementById(valor);
	let optionsVal = document.getElementById(lst);
	RegExp.escape = function (s) {
		return s.replace(/[-\/\\^$*+?.()|[\]{}]/g, "\\$&");
	};
	optionsVal.innerHTML = "";
	if (input.value) {
		let textCountry = input.value;
		for (let i = 0; i < arr.length; i++) {
			let testableRegExp = new RegExp(RegExp.escape(textCountry), "i");
			if (arr[i]["des"].match(testableRegExp)) {
				addValue(arr[i], dest, lst);
			}
		}
		if (optionsVal.children.length > 0) $("#" + valor).dropdown("show");
	} else {
		$("#" + valor).dropdown("hide");
	}

	$("#" + lst).on("click", "a.dropdown-item", function () {
		let sel = this;
		dest.forEach(function (row) {
			if (sel.getAttribute(row["key"])) {
				document.getElementById(row["dest"]).value = sel.getAttribute(
					row["key"]
				);
			}
		});
		$("#" + valor).dropdown("hide");
	});

	function addValue(item, dst, lst) {
		let createOptions = document.createElement("a");
		let listaAuto = document.getElementById(lst);
		createOptions.classList.add("dropdown-item");
		createOptions.href = "#";
		dst.forEach(function (row) {
			if (row["ind"]) {
				createOptions.text = item[row["key"]];
				createOptions.setAttribute(row["key"], item[row["key"]]);
			}
			createOptions.setAttribute(row["key"], item[row["key"]]);
		});
		listaAuto.appendChild(createOptions);
	}

	function setAutocomplete(valor, inpt) {
		console.log("entre set value");
		let input = document.getElementById(inpt);
		input.value = valor;
	}
}
