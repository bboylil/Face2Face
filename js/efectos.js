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
			$(".nroom").hide();
    		$(".rooms").hide();
    		$(".users").show();
    	} 
    )
    
    $("#ver_salas").click(
    	function(){
			$(".nroom").hide();
    		$(".users").hide();
    		$(".rooms").show();
    	} 
    )
    $(".nroom").hide();
    $("#newRoom").click(
    	function(){
			$(".nroom").show();
    		$(".users").hide();
    		$(".rooms").hide();
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

	$("#passchange").hide();
	$("#cambiarpass").click(
		function(){
			 $(this).show();
			 $("#panel").hide();
			 $("#passchange").slideDown(500);
		}
	)
	$("#cerrar").click(
		function(){
			 $("#passchange").slideUp();
		}
	)
	

}); 
