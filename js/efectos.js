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
			 $("#panel").slideDown(1500);
		}
	)
	$(".main").click(
		function(){
			 $("#panel").hide();
			 $(".formpasschange").hide();
		}
	)
	$(".formpasschange").hide();
	$("#cambiarpass").click(
		function(){
			 $("#panel").hide();
			 $(".formpasschange").slideDown(1500);
		}
	)
	
}); 
