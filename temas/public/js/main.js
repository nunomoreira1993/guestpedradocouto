AOS.init({
	duration: 800,
	easing: "slide",
});

(function ($) {
	"use strict";

	var isMobile = {
		Android: function () {
			return navigator.userAgent.match(/Android/i);
		},
		BlackBerry: function () {
			return navigator.userAgent.match(/BlackBerry/i);
		},
		iOS: function () {
			return navigator.userAgent.match(/iPhone|iPad|iPod/i);
		},
		Opera: function () {
			return navigator.userAgent.match(/Opera Mini/i);
		},
		Windows: function () {
			return navigator.userAgent.match(/IEMobile/i);
		},
		any: function () {
			return (
				isMobile.Android() ||
				isMobile.BlackBerry() ||
				isMobile.iOS() ||
				isMobile.Opera() ||
				isMobile.Windows()
			);
		},
	};

	$(window).stellar({
		responsive: true,
		parallaxBackgrounds: true,
		parallaxElements: true,
		horizontalScrolling: false,
		hideDistantElements: false,
		scrollProperty: "scroll",
	});

	var fullHeight = function () {
		$(".js-fullheight").css("height", $(window).height());
		$(window).resize(function () {
			$(".js-fullheight").css("height", $(window).height());
		});
	};
	fullHeight();

	// loader
	var loader = function () {
		setTimeout(function () {
			if ($("#ftco-loader").length > 0) {
				$("#ftco-loader").removeClass("show");
			}
		}, 1);
	};
	loader();

	// Scrollax
	$.Scrollax();

	var carousel = function () {
		$(".carousel-car").owlCarousel({
			center: true,
			loop: true,
			autoplay: true,
			items: 1,
			margin: 30,
			stagePadding: 0,
			nav: false,
			navText: [
				'<span class="ion-ios-arrow-back">',
				'<span class="ion-ios-arrow-forward">',
			],
			responsive: {
				0: {
					items: 1,
				},
				600: {
					items: 2,
				},
				1000: {
					items: 3,
				},
			},
		});
		$(".carousel-testimony").owlCarousel({
			center: true,
			loop: true,
			items: 1,
			margin: 30,
			stagePadding: 0,
			nav: false,
			navText: [
				'<span class="ion-ios-arrow-back">',
				'<span class="ion-ios-arrow-forward">',
			],
			responsive: {
				0: {
					items: 1,
				},
				600: {
					items: 2,
				},
				1000: {
					items: 3,
				},
			},
		});
	};
	carousel();

	$("nav .dropdown").hover(
		function () {
			var $this = $(this);
			// 	 timer;
			// clearTimeout(timer);
			$this.addClass("show");
			$this.find("> a").attr("aria-expanded", true);
			// $this.find('.dropdown-menu').addClass('animated-fast fadeInUp show');
			$this.find(".dropdown-menu").addClass("show");
		},
		function () {
			var $this = $(this);
			// timer;
			// timer = setTimeout(function(){
			$this.removeClass("show");
			$this.find("> a").attr("aria-expanded", false);
			// $this.find('.dropdown-menu').removeClass('animated-fast fadeInUp show');
			$this.find(".dropdown-menu").removeClass("show");
			// }, 100);
		}
	);

	$("#dropdown04").on("show.bs.dropdown", function () {
		console.log("show");
	});

	// scroll
	var scrollWindow = function () {
		$(window).scroll(function () {
			var $w = $(this),
				st = $w.scrollTop(),
				navbar = $(".ftco_navbar"),
				sd = $(".js-scroll-wrap");

			if (st > 150) {
				if (!navbar.hasClass("scrolled")) {
					navbar.addClass("scrolled");
				}
			}
			if (st < 150) {
				if (navbar.hasClass("scrolled")) {
					navbar.removeClass("scrolled sleep");
				}
			}
			if (st > 350) {
				if (!navbar.hasClass("awake")) {
					navbar.addClass("awake");
				}

				if (sd.length > 0) {
					sd.addClass("sleep");
				}
			}
			if (st < 350) {
				if (navbar.hasClass("awake")) {
					navbar.removeClass("awake");
					navbar.addClass("sleep");
				}
				if (sd.length > 0) {
					sd.removeClass("sleep");
				}
			}
		});
	};
	scrollWindow();

	var isMobile = {
		Android: function () {
			return navigator.userAgent.match(/Android/i);
		},
		BlackBerry: function () {
			return navigator.userAgent.match(/BlackBerry/i);
		},
		iOS: function () {
			return navigator.userAgent.match(/iPhone|iPad|iPod/i);
		},
		Opera: function () {
			return navigator.userAgent.match(/Opera Mini/i);
		},
		Windows: function () {
			return navigator.userAgent.match(/IEMobile/i);
		},
		any: function () {
			return (
				isMobile.Android() ||
				isMobile.BlackBerry() ||
				isMobile.iOS() ||
				isMobile.Opera() ||
				isMobile.Windows()
			);
		},
	};

	var counter = function () {
		$("#section-counter, .hero-wrap, .ftco-counter").waypoint(
			function (direction) {
				if (
					direction === "down" &&
					!$(this.element).hasClass("ftco-animated")
				) {
					var comma_separator_number_step =
						$.animateNumber.numberStepFactories.separator(",");
					$(".number").each(function () {
						var $this = $(this),
							num = $this.data("number");
						console.log(num);
						$this.animateNumber(
							{
								number: num,
								numberStep: comma_separator_number_step,
							},
							7000
						);
					});
				}
			},
			{ offset: "95%" }
		);
	};
	counter();

	var contentWayPoint = function () {
		var i = 0;
		$(".ftco-animate").waypoint(
			function (direction) {
				if (
					direction === "down" &&
					!$(this.element).hasClass("ftco-animated")
				) {
					i++;

					$(this.element).addClass("item-animate");
					setTimeout(function () {
						$("body .ftco-animate.item-animate").each(function (k) {
							var el = $(this);
							setTimeout(
								function () {
									var effect = el.data("animate-effect");
									if (effect === "fadeIn") {
										el.addClass("fadeIn ftco-animated");
									} else if (effect === "fadeInLeft") {
										el.addClass("fadeInLeft ftco-animated");
									} else if (effect === "fadeInRight") {
										el.addClass("fadeInRight ftco-animated");
									} else {
										el.addClass("fadeInUp ftco-animated");
									}
									el.removeClass("item-animate");
								},
								k * 50,
								"easeInOutExpo"
							);
						});
					}, 100);
				}
			},
			{ offset: "95%" }
		);
	};
	contentWayPoint();

	// navigation
	var OnePageNav = function () {
		$(".smoothscroll[href^='#'], #ftco-nav ul li a[href^='#']").on(
			"click",
			function (e) {
				e.preventDefault();

				var hash = this.hash,
					navToggler = $(".navbar-toggler");
				$("html, body").animate(
					{
						scrollTop: $(hash).offset().top,
					},
					700,
					"easeInOutExpo",
					function () {
						window.location.hash = hash;
					}
				);

				if (navToggler.is(":visible")) {
					navToggler.click();
				}
			}
		);
		$("body").on("activate.bs.scrollspy", function () {
			console.log("nice");
		});
	};
	OnePageNav();

	// magnific popup
	$(".image-popup").magnificPopup({
		type: "image",
		closeOnContentClick: true,
		closeBtnInside: false,
		fixedContentPos: true,
		mainClass: "mfp-no-margins mfp-with-zoom", // class to remove default margin from left and right side
		gallery: {
			enabled: true,
			navigateByImgClick: true,
			preload: [0, 1], // Will preload 0 - before current, and 1 after the current image
		},
		image: {
			verticalFit: true,
		},
		zoom: {
			enabled: true,
			duration: 300, // don't foget to change the duration also in CSS
		},
	});

	$(".popup-youtube, .popup-vimeo, .popup-gmaps").magnificPopup({
		disableOn: 700,
		type: "iframe",
		mainClass: "mfp-fade",
		removalDelay: 160,
		preloader: false,

		fixedContentPos: false,
	});

	$("#book_pick_date,#book_off_date").datepicker({
		format: "m/d/yyyy",
		autoclose: true,
	});
	$("#time_pick").timepicker();
})(jQuery);

isload = false;function validatePhone(hash, rp, evento, phone) {
    return new Promise((resolve, reject) => {
        if (isload == false) {
            isload = true;
            $("#ftco-loader").addClass("show");
            const input = document.querySelector("#telemovel_validation");
            const iti = window.intlTelInputGlobals.getInstance(input);
            if (iti.isValidNumber() == false) {
                isload = false;
                $("#ftco-loader").removeClass("show");
                reject("Número de telefone inválido");
            } else {
				
                const url = "/ajax/methods/phoneValidation.php";
                const data = new URLSearchParams();
                data.append("hash", hash);
                data.append("rp", rp);
                data.append("evento", evento);
                data.append("phone", phone);

                const xhr = new XMLHttpRequest();
                xhr.open("POST", url, true);

                xhr.onreadystatechange = function () {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        isload = false;
                        $("#ftco-loader").removeClass("show");
                        if (xhr.status === 200) {
                            resolve(xhr.responseText);
                        } else {
                            console.error("Erro na requisição:", xhr.statusText);
                            reject("Erro na requisição");
                        }
                    }
                };

                xhr.onerror = function () {
                    isload = false;
                    console.error("Erro na requisição:", xhr.statusText);
                    $("#ftco-loader").removeClass("show");
                    reject("Erro na requisição");
                };

                // Enviar a requisição
                xhr.send(data);
            }
        }
    });
}

document.addEventListener("DOMContentLoaded", function () {
	if ($(".request-form").length > 0) {
		var dataInput = document.getElementById("data_nascimento");

		dataInput.addEventListener("input", function (event) {
			var inputValue = event.target.value;
			var formattedValue = inputValue
				.replace(/\D/g, "")
				.replace(/(\d{2})(\d)/, "$1-$2")
				.replace(/(\d{2})(\d)/, "$1-$2");

			event.target.value = formattedValue;
		});

		const input2 = document.querySelector("#telemovel_validation");
		window.intlTelInput(input2, {
			utilsScript: "/temas/public/js/input-tel/utils.js",
			showSelectedDialCode: true,
			preferredCountries: ["PT"],
		});

		document
			.querySelector(".request-form")
			.addEventListener("submit", function (event) {
				const valid_phone = document.querySelector("input[name='valid_phone']");
				const valid_phone_value = valid_phone.value;
				const input = document.querySelector("#telemovel_validation");
				const iti = window.intlTelInputGlobals.getInstance(input);
				if(input.value != ""){
					iti.setNumber(input.value);
				}
				if(valid_phone_value != iti.getNumber() || iti.getNumber() == ""){
					if (iti.isValidNumber() == false) {
						isValid = false;
						document.getElementById("telemovel_validation").closest(".form-group").classList.add("error");
					} else {
						document.getElementById("telemovel_validation").closest(".form-group").classList.remove("error");
						var hash = document.getElementById("input-hash").value;
						var evento = document.getElementById("input-evento").value;
						var rp = document.getElementById("input-rp").value;


						validatePhone(hash, rp, evento, iti.getNumber())
						.then(response => {
							if(typeof(response) !== "undefined" && response != ""){
								response = JSON.parse(response);
							}
							if(typeof(response) != "undefined" && typeof(response.status) != "undefined" && response.status == "success") {
								valid_phone.value = iti.getNumber();
								document.getElementById("telemovel").value = iti.getNumber();
								document.querySelector(".column-search .request-form .step1").classList.add("d-none");
								document.querySelector(".column-search .request-form .step2").classList.remove("d-none");

								if(typeof(response.convite) != "undefined" && response.convite != null){
									document.getElementById("nome").value = response.convite.nome;
									document.getElementById("email").value = response.convite.email;
									document.getElementById("telemovel").value = response.convite.telemovel;
									document.getElementById("data_nascimento").value = response.convite.data_nascimento;
									if(response.convite.termos_condicoes == 1){
										document.getElementById("termos_condicoes").checked = true;
									}
									if(response.convite.marketing == 1){
										document.getElementById("marketing").checked = true;
									}
								}
							}
							else if(typeof(response) != "undefined" && typeof(response.status) != "undefined"){
								document.querySelector("#error-message-telemovel-validation").innerHTML = response.message;
								document.querySelector("#error-message-telemovel-validation").closest(".form-group").classList.add("error")
								document.querySelector(".column-search .request-form .step1").classList.remove("d-none");
								document.querySelector(".column-search .request-form .step2").classList.add("d-none");
							}
							else {
								alert("Ocorreu um problema no serviço, tente novamente mais tarde")
							}
						})
						.catch(error => {
							alert("Ocorreu um problema no serviço, tente novamente mais tarde")
							console.error(error); // Trata o erro aqui
						});
					}
					event.preventDefault();
					return false;
				}
				else {
					document.querySelector("#telemovel_validation").value = iti.getNumber();
					$("#ftco-loader").addClass("show");
					var nome = document.getElementById("nome").value;
					var email = document.getElementById("email").value;
					var telemovel = document.getElementById("telemovel").value;
					var data_nascimento = document.getElementById("data_nascimento").value;
					var termos_condicoes = document.getElementById("termos_condicoes").checked;

					var isValid = true;

					// Validar o campo Nome
					if (nome.trim() === "") {
						isValid = false;
						document
							.getElementById("nome")
							.closest(".form-group")
							.classList.add("error");
					} else {
						document
							.getElementById("nome")
							.closest(".form-group")
							.classList.remove("error");
					}

					// Validar o campo E-mail
					if (email.trim() !== "") {
						if (!isValidEmail(email)) {
							isValid = false;
							document
								.getElementById("email")
								.closest(".form-group")
								.classList.add("error");
						} else {
							document
								.getElementById("email")
								.closest(".form-group")
								.classList.remove("error");
						}
					}

					// Validar o campo Telémovel
					if (telemovel.trim() === "" || !isValidPhone(telemovel)) {
						isValid = false;
						document
							.getElementById("telemovel")
							.closest(".form-group")
							.classList.add("error");
					} else {
						document
							.getElementById("telemovel")
							.closest(".form-group")
							.classList.remove("error");
					}

					// Validar o campo Data de Nascimento
					if (
						data_nascimento.trim() === "" ||
						!isValidDateFormat(data_nascimento)
					) {
						isValid = false;
						document
							.getElementById("data_nascimento")
							.closest(".form-group")
							.classList.add("error");
					} else {
						document
							.getElementById("data_nascimento")
							.closest(".form-group")
							.classList.remove("error");
					}

					// Validar o campo Termos e Condições
					if (!termos_condicoes) {
						isValid = false;
						document
							.getElementById("termos_condicoes")
							.closest(".form-group")
							.classList.add("error");
					} else {
						document
							.getElementById("termos_condicoes")
							.closest(".form-group")
							.classList.remove("error");
					}

					if (!isValid) {
						$("#ftco-loader").removeClass("show");
						event.preventDefault(); // Impede o envio do formulário
					} else {
					}
				}
			});
	}
});

function isValidEmail(email) {
	// Utilizando uma expressão regular para validar o formato do e-mail
	var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
	return emailPattern.test(email);
}

function isValidPhone(phone) {
	var phonePattern = /^\+?\d{9,15}$/;
    return phonePattern.test(phone);
}
function isValidDateFormat(dateString) {
	var datePattern = /^(0[1-9]|[12][0-9]|3[01])-(0[1-9]|1[0-2])-\d{4}$/;
	return datePattern.test(dateString);
}
