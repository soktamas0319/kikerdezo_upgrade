<?php session_start();
	
	if ($_POST["valasztas"] == null){
		$_SESSION["mehet_tovabb"]=false;
		echo 	"<script>
					alert('Nincs megjelölve válasz!');
					location.replace('gyakorlas_keret.php');
				</script>";		
	}else{
		$_SESSION["valasztas"] = $_POST["valasztas"];
		$_SESSION["mehet_tovabb"]=true;
		echo 	"<script>
					location.replace('helyes_valaszt_megmutat_keret.php');
				</script>";	
	}
