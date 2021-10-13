<?php
include 'beallitas.php';

function sql_lekerdezes($param_sql_parancs, $hibauzenet){
	$ossz = mysqli_connect(DB_HOSZT, DB_FELH_NEV, DB_JELSZO, DB_NEV);
	mysqli_query($ossz, "SET NAMES utf8"); //utf-8 beállítás
	$result = mysqli_query($ossz, $param_sql_parancs ) 	
		or die( "<b><br>$hibauzenet<br>Hiba a \"$param_sql_parancs\"</b>  lekérdezés futtatásában: " . mysqli_error($ossz));
	return $result;
}
?>