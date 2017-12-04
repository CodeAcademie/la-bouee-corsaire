$('.show-base-item').on('click', function(){
	if( ! $(this).hasClass('active') ) {
		$('.show-second-item').removeClass('active');
		$(this).addClass('active');
		$('#second-item').fadeOut('fast', function(){
			$('#base-item').fadeIn('fast');
		});
	}
});
$('.show-second-item').on('click', function(){
	if( ! $(this).hasClass('active') ) {
		$('.show-base-item').removeClass('active');
		$(this).addClass('active');
		$('#base-item').fadeOut('fast', function(){
			$('#second-item').fadeIn('fast');
		});
	}
});

var registerStep = 0;

$('.register-submit').on('click', function(event){
	$('#alert_form_registration').hide();
	var errors = [];
	if (registerStep == 0) {
		checkMail( $('#fos_user_registration_form_email').val(), errors);
		checkPassword($('#fos_user_registration_form_plainPassword_first').val(), $('#fos_user_registration_form_plainPassword_second').val(), errors);
		if (errors.length == 0){
			$('.step-0').fadeOut('fast', function(){
				$('.step-1').fadeIn('fast');
			});
		} else {
			$('#alert_form_registration').show().html(errors);
		}

	}
	if (registerStep == 1) {
		checkSurname($('#fos_user_registration_form_surname').val(), errors);
		checkName($('#fos_user_registration_form_name').val(), errors);
		if (errors.length == 0){
			$('.step-1').fadeOut('fast', function(){
				$('.step-2').fadeIn('fast');
			});
		} else {
			$('#alert_form_registration').show().html(errors);
		}
	}
	if (registerStep == 2) {
		checkPhone($('#fos_user_registration_form_phone').val(), errors);
		checkAdress($('#fos_user_registration_form_adress').val(), errors);
		checkRegion($('#fos_user_registration_form_region').val(), errors);
		if (errors.length == 0) $(".fos_user_registration_register").submit();

	}
	if (errors.length == 0) registerStep++;
	else {
		$('#alert_form_registration').show().html(errors);
	}
});
$('#fos_user_registration_form_adress').keyup(function() {
	var input = $('#fos_user_registration_form_adress').val();
	if (input.length > 3){
		zipCaller(input);
	}
});

function zipCaller(input) {
	$.ajax({

		type: 'GET',
			url: 'https://api-adresse.data.gouv.fr/search',
			datatype: 'json',
			data: {
				"q": input
			},
		success: function(res) {
			var datas = res.features;
			let adress = [];
			for (var i = 0; i < datas.length; i++) {
				adress.push({
					name: datas[i].properties.label,
					label: datas[i].properties.name,
					zipCode: datas[i].properties.postcode,
					city: datas[i].properties.city,
					area: datas[i].properties.context.split(',')[2],
					latitude: datas[i].geometry.coordinates[1],
					longitude: datas[i].geometry.coordinates[0]
				});
			}
			$('#fos_user_registration_form_adress').autocomplete({
				source: adress,

				select: function (event, ui){
					$('#fos_user_registration_form_adress').val(ui.item.label);
					$('#fos_user_registration_form_region').val(ui.item.area);
					$('#fos_user_registration_form_zip_code').val(ui.item.zipCode);
					$('#fos_user_registration_form_city').val(ui.item.city);
					$('#fos_user_registration_form_latitude').val(ui.item.latitude);
					$('#fos_user_registration_form_longitude').val(ui.item.longitude);
				}
			}).autocomplete( "instance" )._renderItem = function( ul, item ) {
				return $( "<li>" )
					.append( "<div>" + item.name + "</div>" )
					.appendTo( ul );
				};

		}
	});
}
function checkMail(input, erreur){
	var regex = /^[a-zA-Z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$/;
	if(!regex.test(input)){
		erreur.push('Votre email n\'est pas valide. ');
		return false;
	}
	else return true;
}
function checkPassword(input,checked, erreur){
	if (input.length <= 3 || input.length >= 255){
		erreur.push('Votre mot de passe est trop '+ ((input.length <= 3) ? 'court' : 'long')+'. ');
	}
	if (input != checked){
		erreur.push('Vos mots de passe ne sont pas identiques. ')
	}
}
function checkName(input, erreur){
	if (input.length < 3 || input.length >= 100){
		erreur.push('Votre Prénom est trop '+ ((input.length < 3) ? 'court' : 'long')+'. ');
	}

}
function checkSurname(input, erreur){
	if (input.length < 3 || input.length >= 100){
		erreur.push('Votre Nom est trop '+ ((input.length < 3) ? 'court' : 'long')+'. ');
	}
}
function checkPhone(input, erreur){
	if (input.length != 10 && input.length != 0){
		erreur.push('Votre numéro de téléphone est invalide. ');
	}
}
function checkAdress(input, erreur){
	if (input.length == 0){
		erreur.push('Veuillez saisir votre adresse. ');
	}
}
function checkRegion(input, erreur){
	if (input.length == 0){
		erreur.push('Vous n\'avez pas renseigné d\'adresse. ');
	}
}
