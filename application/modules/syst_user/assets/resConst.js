//console.log(Object.entries(dataContainer));

/*
var Person = function() {
	var Person = {};
	Person.name = 'valda';
	Person.greeting = function() {
	  this.name ='sinname';
	};
	return Person;
  }
*/
//var salva = new Person;
//console.log(Object.entries(salva));


var ipuser = {
	"ip": "116.12.250.1",
	"city": "Singapore",
	"region": "Central Singapore Community Development Council",
	"country": "SG",
	"country_name": "Singapore",
	"postal": null,
	"latitude": 1.2855,
	"longitude": 103.8565,
	"timezone": "Asia/Singapore"
};

// JS Objetcs  oficial  developer.mozilla.org 
/*
var o = {
	a: 7,
	get b() {
	  return this.a + 1;
	},
	set c(x) {
	  this.a = x / 2;
	}
};
*/

// JS clases oficial  developer.mozilla.org
class Rectangulo {
	constructor (alto, ancho) {
	  this.alto = alto;
	  this.ancho = ancho;
	}
	// Getter
	get area() {
	   return this.calcArea();
	 }
	// Método
	calcArea () {
	  return this.alto * this.ancho;
	}
  }
  
  //const cuadrado = new Rectangulo(10, 10);
  
  //console.log(cuadrado.area); // 10


// PseudoClases con variables unica compartida
var Foo = (function() {
    // "private" variables 
    var _bar;

    // constructor
    function Foo() {};

    // add the methods to the prototype so that all of the 
    // Foo instances can access the private static
    Foo.prototype.getBar = function() {
        return _bar;
    };
    Foo.prototype.setBar = function(bar) {
        _bar = bar;
    };

    return Foo;
})();

// var a = new Foo();
// var b = new Foo();
// a.setBar('a');
// b.setBar('b');
// alert(a.getBar()); // alerts 'b' :( 

// PseudoClases con variables privadas
var Foo = (function() {
	// constructor
	function Foo() {
		this._bar = "some value";
	};

	// add the methods to the prototype so that all of the 
	// Foo instances can access the private static
	Foo.prototype.getBar = function() {
		return this._bar;
	};
	Foo.prototype.setBar = function(bar) {
		this._bar = bar;
	};

	return Foo;
})();
/*
var a = new Foo();
var b = new Foo();
a.setBar('a');
b.setBar('b');
alert(a.getBar()); // alerts 'a' :) 
alert(b.getBar()); // alerts 'b' :) 

delete a._bar;
b._bar = null;
alert(a.getBar()); // alerts undefined :(
alert(b.getBar()); // alerts null :(
*/