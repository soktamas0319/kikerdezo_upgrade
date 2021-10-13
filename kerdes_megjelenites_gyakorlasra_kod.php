<?php 

include 'kerdes_megjelenites_gyakorlasra_fuggvenyei.php';

if (!isset($_SESSION["temanev"])){	//ha még nincs téma session  beállítva, beállítjuk
	init_SESSION();
}else if ($_SESSION["mehet_tovabb"]){ //akor mehet tovább, ha megjelöltünk egy választ, amúgy visszadobja az oldalt
	$_SESSION["kerdesn"]++;
}

kerdest_beolvas($_SESSION["idtema"], $_SESSION["kerdesn"], $_SESSION["id_tablanev"]);  //feltölti a megfelelő $_SESSION változókat 
//$celoldal="gyakorlas_keret.php";// Ha nincs megjelölve egy kerdes sem, maradunk az oldalon

//print "Súgás: A helyes válasz: ". $helyes."<br>";
//print "Helyes válaszok száma: ".$jo_valaszdb."<br>";
//print "Cél: "."$celoldal";
?>


	<h2> <?php print $_SESSION["temanev"] ?></h2>

	<form action="<?php print "checked_ellenorzes.php" ?> " method="POST"> <!--elküldjük ellenőrzésre a választ-->
						
		<!--Kérdés számláló megjelenítése  -->
		<h3>Kérdés:<?php print $_SESSION["kerdesn"]."/".$_SESSION["kerdesDb"];?></h3>
		
		<table border="0" width="90%" height="100" align="center" cellspacing="3" cellpadding="10">
			<!--A kérdés megjelenítése  -->
			<?php kerdest_megjelenit($_SESSION["kerdes"])?>
			
			<!--Az 1. és 2. válasz megjelenítése  -->
			<?php 	
				valaszt_megjelenit($_SESSION["valasz1"], 1);
				valaszt_megjelenit($_SESSION["valasz2"], 2);
			?>
			
			<!--A 3. válasz megjelenítése, ha van  -->
			<?php 
				if (!($_SESSION["valasz3"]=='')){    //Ha nem üres string a 3. válasz, kiíratjuk a 3. sort is
					valaszt_megjelenit($_SESSION["valasz3"], 3);
				}
			?>
		</table>
		<br>
		<center><input type="image" src="tovabb_gomb5.gif" name="elkuld" > </center>
	</form> 
		


