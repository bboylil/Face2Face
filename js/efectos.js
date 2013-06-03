jQuery(document).ready(function(){ 
    $("#olvido").click(
    	function(){
    		$("#form1").hide();
    		$("#form2").fadeIn(1500);
    	} 
    )
    $(".users").hide();

    $("#ver_usuarios").click(
    	function(){
    		$(".rooms").hide();
    		$(".users").show();
    	} 
    )
    
    $("#ver_salas").click(
    	function(){
    		$(".users").hide();
    		$(".rooms").show();
    	} 
    )
    
    
    //Control de esconder y mostrar div oculto de informacion del usuario
	$("#panel").hide();
	$(".pnick").click(
		function(){
			 $("#panel").slideDown(500);
		}
	)
	$(".main").click(
		function(){
			 $("#panel").hide();
		}
	)

	$(".passchange").hide();
	$("#cambiarpass").click(function(){
		$("#panel").hide();
		$(".passchange").slideDown(500);
	});

	// Cambiamos password
	$("#aceptar").click(function(event){
		// Recogemos datos
		var oldPass = $('#oldpass').val(),
			newPass1 = $('#newpass1').val(),
			newPass2 = $('#newpass2').val();

		if(oldPass == "" || newPass1 == "" || newPass2 == "")
			return;

		event.preventDefault();
		$('#errores').html("");

		var datos = {
			"oldpass":oldPass,
			"npass1": newPass1,
			"npass2": newPass2
		};

		// Ajax
		$.ajax({
			type: "post",
			dataType: "json",
			url: "/connect/cambiopass.php",
			data: datos,
			success: function(data){
				// Todo Ok
				var color = "white";
				$('#errores').css('color',color).html(data.msg);

				if(data.code == 0){
					setTimeout(function() { $('#passchange').fadeOut(2000); }, 1000);
				}
			}
		});
	});

	$(".cerrar").click(
		function(){
			 $(".passchange").slideUp();
		}
	)
}); 
