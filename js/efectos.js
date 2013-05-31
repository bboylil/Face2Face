
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
}); 
