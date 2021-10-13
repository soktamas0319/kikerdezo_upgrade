<?php

include 'alap_fuggvenyek.php';

function init_SESSION(){
	$_SESSION["temanev"]=$_POST['Tema'];	//elmentjük a téma nevét egy session globális változóba, amit kaptunk a POST-ból
	$idtema=tema_id_meghat($_SESSION["temanev"]); 	//téma azonosító lekérdezése	
	$_SESSION["idtema"] = $idtema;		//elmentjük a téma id-jét egy session változóba
	$kerdesDb=kerdesek_szama($idtema);	//az összes kérdés számának beállítása
	$_SESSION["kerdesDb"] = kerdesek_szama($idtema); 
	$_SESSION["jo_valaszdb"] = 0; 	//	jó válaszok száma
	//létrehozunk egy ideiglenes táblát, neve: $id_tablanev
	//$id_tablanev="felhasznalo_".session_id();
	$_SESSION["id_tablanev"]= "felhasznalo_".session_id(); 	//elmentjük az ideiglenes táblanevet
	if (van_ilyen_tabla($_SESSION["id_tablanev"])){                            //Ha van ideiglenes tábla
      tablat_torol($_SESSION["id_tablanev"]);                 //előbb kitöröljük, hogy ne legyen gond
      veletlen_sorsz_tablat_letrehoz($kerdesDb, $_SESSION["id_tablanev"]);
	}else{
      veletlen_sorsz_tablat_letrehoz($kerdesDb, $_SESSION["id_tablanev"]);
	}              
	$_SESSION["kerdesn"]=1;		//kezdéskor $kerdesn=1
}


// A téma neve alapján meghatározzuk a téma azonosítóját, az idTema-t 
function tema_id_meghat($temanev){
  $eredmeny=sql_lekerdezes("SELECT idTema FROM temak WHERE Tema_nev=\"$temanev\"", "Hiba a téma kiválasztásánál.");
  $tomb=mysqli_fetch_array($eredmeny, MYSQLI_ASSOC);
  return $tomb['idTema'];
}


//Feltölti a $kerdes és a $valasz1... stb változókat
function kerdest_beolvas($idtema, $kerdesn, $id_tablanev){  
	$result=sql_lekerdezes("SELECT kerdes_n FROM $id_tablanev WHERE n=$kerdesn ", "Hiba a kérdés kiválasztásánál az ideiglenes táblázatban"); 
	$mezok=mysqli_fetch_array($result, MYSQLI_ASSOC);
	$kerdes_n=$mezok['kerdes_n'];   //egy véletlen sorszámot kapunk
	//kiválasztjuk a kérdéshez tartozó sort az adatbázisból
	$result=sql_lekerdezes("SELECT * FROM kerd_val WHERE (idTema=$idtema) AND (KerdesN=$kerdesn)", "Hiba a kérdés lekérdezésében:");
	$result=mysqli_fetch_array($result, MYSQLI_ASSOC);
	$_SESSION["kerdes"]=$result['kerdes'];
	$_SESSION["valasz1"]=$result['valasz1'];
	$_SESSION["valasz2"]=$result['valasz2'];
	$_SESSION["valasz3"]=$result['valasz3'];
	$_SESSION["helyes"]=$result['helyes']; 
}


//Megszámolja, hány kérdés van a témában
function kerdesek_szama($idtema){
  $result=sql_lekerdezes("SELECT * FROM kerd_val WHERE idTema=$idtema", "Hiba a kérdések számának a megállapításában.");
  $kerdesDb=mysqli_num_rows($result);
  return $kerdesDb;
}


//feltettük-e már az összes kérdést, vagy sem?
function vege($kerdesn, $kerdesDb){
  return  $kerdesn>=$kerdesDb;
}


//létrehoz egy táblát amiben megkeveri a kérdések sorrendjét
function veletlen_sorsz_tablat_letrehoz($kerdesDb, $id_tablanev){
	$result=sql_lekerdezes("CREATE TABLE $id_tablanev (n int, kerdes_n int)", "Hiba a véletlen sorszámok tábla létrehozásában: ");
	$vel_kerdes_sorszamok = range (1,$kerdesDb);  //létrehoz egy tömböt a kérdések számával    
	shuffle ($vel_kerdes_sorszamok); //megkeveri a kérdések sorszámát
	//kiírja egy táblába a kérdésszámot, és a hozzá tartozó véletlen sorszámot
	for ($i=0; $i<$kerdesDb; $i++){
		$kerdes_sorszam=$i+1; // mert a tömbindex egyel kisebb, mint a kérdésszám	
		$result=sql_lekerdezes("INSERT INTO $id_tablanev VALUES ($kerdes_sorszam , $vel_kerdes_sorszamok[$i])", "Hiba a sorszámok tábla feltöltésében:");
	}
}


//Ellenőrzi, hogy van-e ilyen tábla
function van_ilyen_tabla($id_tablanev){
	$result=sql_lekerdezes("SHOW TABLES LIKE '$id_tablanev'", "Hiba a táblák lekérdezésében");  
	return (mysqli_num_rows($result) > 0);
}

  
function tablat_torol($id_tablanev){
	$result=sql_lekerdezes("DROP TABLE $id_tablanev", "Hiba az ideiglenes tábla törlésekor");
} 


function kerdest_megjelenit($kerdes){
	echo		'<tr> ' ;
	echo			' <td bgcolor="#D9DAD3" height="50" colspan="2">' ;
	echo					' <font face="Arial" size="3" >';
	echo						'<b>';
									echo $kerdes;
	echo						'</b>';
	echo					'</font>';
	echo			'</td>';
	echo		'</tr>';
	
}

function valaszt_megjelenit($valasz, $value){
	echo 	'<tr>';
	echo		'<td bgcolor="#FBEF99" width="1%">';
	$s = '<input type="radio" name="valasztas" value="'.
			$value.
			'" id="'.
			$value.
			'" >';
	echo $s;		
	//echo			'<input type="radio" name="valasztas" value="'.$value.'">';
	echo		'</td>';
	echo		'<td bgcolor="#FBEF99">';
	echo			 $valasz;
	echo		'</td>';
	echo	'</tr>';

}
?>