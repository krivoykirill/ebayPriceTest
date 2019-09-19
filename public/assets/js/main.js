/*
	Industrious by TEMPLATED
	templated.co @templatedco
	Released for free under the Creative Commons Attribution 3.0 license (templated.co/license)
*/
(function($) {

	var	$window = $(window),
		$banner = $('#banner'),
		$body = $('body');

	// Breakpoints.
		breakpoints({
			default:   ['1681px',   null       ],
			xlarge:    ['1281px',   '1680px'   ],
			large:     ['981px',    '1280px'   ],
			medium:    ['737px',    '980px'    ],
			small:     ['481px',    '736px'    ],
			xsmall:    ['361px',    '480px'    ],
			xxsmall:   [null,       '360px'    ]
		});

	// Play initial animations on page load.
		$window.on('load', function() {
			window.setTimeout(function() {
				$body.removeClass('is-preload');
			}, 100);
		});

	// Menu.
		$('#menu')
			.append('<a href="#menu" class="close"></a>')
			.appendTo($body)
			.panel({
				target: $body,
				visibleClass: 'is-menu-visible',
				delay: 500,
				hideOnClick: true,
				hideOnSwipe: true,
				resetScroll: true,
				resetForms: true,
				side: 'right'
			});

})(jQuery);

var acc = document.getElementsByClassName("accordion");
var i;

for (i = 0; i < acc.length; i++) {
  acc[i].addEventListener("click", function() {
    this.classList.toggle("active");
    var panel = this.nextElementSibling;
    if (panel.style.maxHeight){
      panel.style.maxHeight = null;
    } else {
      panel.style.maxHeight = panel.scrollHeight + "px";
    } 
  });
}



var showForm = function (){
        var form = document.getElementById("form");
        form.style.display = "block";
};
var hideForm = function (){
        var form = document.getElementById("form");
        form.style.display = "none";
};

var classArr = document.getElementsByClassName("cta_button");
for (var i = 0; i < classArr.length; i++) {
    classArr[i].addEventListener('click', showForm, false);
    
}


var overlay = document.getElementById("form");

function isNumeric(n) {
	console.log(!isNaN(parseFloat(n)) && isFinite(n));
  return !isNaN(parseFloat(n)) && isFinite(n);
}

function validateForm() {
  var number = document.getElementById("num").value; 
  if ((!isNumeric(number)) || (number.length!=8)) {

    alert("Ievadiet pareizo numuru");
    return false;
  }
}
function ch1(){
	$('#companyCar').prop('checked', true);
}

function ch2(){
	$('#ownCar').prop('checked', true);
}