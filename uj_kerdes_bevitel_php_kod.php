<?php 

include 'beallitas.php';


function szovegboxokat_torol(){      //alapbeállítás
global $k, $v1, $v2, $v3, $helyes, $szink, $szinv1, $szinv2;
$k="";
$v1="";
$v2="";
$v3="";
$helyes=1;
$szink="#000000";
$szinv1="#000000";
$szinv2="#000000";
}



function ujtema($Temanev){
  global $Temanev;
  $ossz=mysql_connect(DB_HOSZT, DB_FELH_NEV, DB_JELSZO);
  mysql_select_db(DB_NEV, $ossz);
  $sql="select * from temak where Tema_nev='$Temanev'";
  $eredmeny=mysql_query($sql, $ossz) or die ("Hiba a téma ellenőrzésében:" .mysql_error());
  if (mysql_num_rows($eredmeny)>0){
    return false;
  } 
  else {
    return true;
  }
}

//Az ideiglenes tábla adatbázisba írása
function kerdeseket_rogzit(){
  global $Temanev, $tulajdonos, $id_tablanev;
  $ossz=mysql_connect(DB_HOSZT, DB_FELH_NEV, DB_JELSZO);
  mysql_select_db(DB_NEV, $ossz);
  if (ujtema($Temanev)){  //ha új téma, hozzáadjuk a témát és a tulajdonost
    $tema_sql="insert into temak (Tema_nev, tulajdonos) values ('$Temanev', '$tulajdonos')";
    $eredmeny=mysql_query($tema_sql, $ossz) or die("Hiba a téma hozzáadásában: ".mysql_error());
    $idTema=mysql_insert_id();  //az új téma id-je
  }
  else{                   //ha nem új téma cseréljük az újra (szerkesztésnél)
    $tema_sql="select idTema from temak where Tema_nev='$Temanev'";
    $eredmeny=mysql_query($tema_sql, $ossz) or die ("Hiba a téma lekérdezésében: ".mysql_error());
    $adatok=mysql_fetch_array($eredmeny);
    $idTema=$adatok[idTema]; //megkeressük a témához tartozó id-t
    $sql_torles="delete from kerd_val where idTema='$idTema'";      //az addigi kérdéseket töröljük
    $eredmeny1=mysql_query($sql_torles, $ossz) or die ("Hiba a törléskor: ".mysql_error());
  }
  //lekérdezzük az összes adatot az ideiglenes táblából  	
  $sql="select * from $id_tablanev";
  $eredmeny2=mysql_query($sql, $ossz) or die ("Hiba az ideiglenes tábla lekérdezésben: ".mysql_error());
  //kiírjuk ideiglenes tartalmát a kerd_val táblába
  while($adatok=mysql_fetch_array($eredmeny2)){
    $KerdesN=$adatok[KerdesN];
    $kerdes=$adatok[Kerdes];
    $v1=$adatok[valasz1];
    $v2=$adatok[valasz2];
    $v3=$adatok[valasz3];
    $helyes=$adatok[helyes];
    $sql2="insert into kerd_val values ($idTema, $KerdesN, '$kerdes', '$v1', '$v2', '$v3', $helyes)";
    $eredmeny3=mysql_query($sql2, $ossz) or die("Hiba az adatbázisba íráskor ".$idTema.mysql_error());
  }
}

//Létrehozunk a felhasználónak egy ideiglenes táblát
function ideiglenes_tabla_letrehozas($id_tablanev){
global $id_tablanev;
  $ossz=mysql_connect(DB_HOSZT, DB_FELH_NEV, DB_JELSZO);
  mysql_select_db(DB_NEV, $ossz);
  $id_tablanev=$_POST[felh_nev_mezo]."_user_table";
  $sql="create table $id_tablanev (
                                           KerdesN integer, 
                                           Kerdes varchar(250),
                                           valasz1 varchar(250), 
                                           valasz2 varchar(250), 
                                           valasz3 varchar(250), 
                                           helyes integer)";
$eredmeny=mysql_query($sql, $ossz) or die ("Hiba az ideiglenes tábla létrehozásában:" .mysql_error());
}

//feltöltjük az ideiglenes táblát a kerd_val táblából
function ideiglenes_tablat_feltolt($id_tablanev, $Temanev){
  $ossz=mysql_connect(DB_HOSZT, DB_FELH_NEV, DB_JELSZO);
  $db=mysql_select_db(DB_NEV, $ossz);	
//Lekérdezzük a téma ID-t
  $sql_idTema="select idTema from temak where '$Temanev'=Tema_nev";
  $eredmeny1=mysql_query($sql_idTema) or die("Hiba a témanév lekérdezésében ".mysql_error());
  $mezo=mysql_fetch_array($eredmeny1);
  $idTema=$mezo[idTema];
  //Lekérdezzük a témához tartozó kérdéseket
  $sql="select * from kerd_val where '$idTema'=idTema";             
  $eredmeny2=mysql_query($sql, $ossz) or die("A témához tartozó kérdések nem jeleníthetők meg ".mysql_error());
  $N=1;
  //  takaritas("$id_tablanev"); //kitöröljük az ideiglenes tábla tartalmát
  while ($Mezo=mysql_fetch_array($eredmeny2)){                 //újra feltöltjük az ideiglenes táblát
    $sql="insert into $id_tablanev values('$N',                 
                                     '$Mezo[kerdes]',
                                     '$Mezo[valasz1]',
                                     '$Mezo[valasz2]', 
                                     '$Mezo[valasz3]', 
                                     '$Mezo[helyes]')" ;
    $Ok=mysql_query($sql, $ossz) or die("Ideiglenes feltöltési hiba ". mysql_error());
    $N++;
  }
}


function van_tabla($id_tablanev){
  $ossz=mysql_connect(DB_HOSZT, DB_FELH_NEV, DB_JELSZO);
  $db=mysql_select_db(DB_NEV, $ossz);	
  $eredmeny = mysql_list_tables (DB_NEV);
  $i = 0;
  $vanilyen=false;
  while ($i < mysql_num_rows ($eredmeny)) {
    $tb_nevek[$i] = mysql_tablename ($eredmeny, $i);
    if ($tb_nevek[$i]==$id_tablanev){
      $vanilyen=true;
    }
    $i++;
  }
  if ($vanilyen){
    return true;
  }else{
    return false;
  }
}


//
//
//Megvizsgáljuk, hogy most léptünk-e be kérdés-szerkesztőbe és beállítjuk a szükséges változókat
function kezdes(){
  global $Temanev, $KerdesN, $max, $tulajdonos, $id_tablanev, $felh_nev, $jelszo;
  //beállítjuk a változókat, amelyekre később szükségünk lesz
  $tulajdonos= "$_POST[felh_nev_mezo]";
  $felh_nev="$_POST[felh_nev_mezo]";  //felh_nevet, jelszót tovább kell vinni
  $jelszo="$_POST[jelszo]";
  $id_tablanev=$_POST[felh_nev_mezo]."_user_table";

  if (isset ($_POST[Kerdesek_szama_mezo])){
    $max=$_POST[Kerdesek_szama_mezo];    //megkapjuk az összes kérdés számát
  }
  else{
    $max=1;
  };

  if (isset ($_POST[Kerdes_szama_mezo])){
    $KerdesN=$_POST[Kerdes_szama_mezo];    //megkapjuk az elküldött kérdés számát
  }
  else{
    $KerdesN=1;
  }

  if (isset($_POST[Tema_neve_mezo])) 
	{        //Már egyszer elküldtük a téma nevét a Tema_neve_mezo-vel
          $Temanev=$_POST[Tema_neve_mezo];
        }
  else	{       //Mikor először lépünk a kérdés-szerkesztőbe
          $Temanev=$_POST[Tema_neve_box];    //Téma neve box, nem pedig mezo
          if (van_tabla($id_tablanev)){                            //Ha van ideiglenes tábla
            tablat_torol($id_tablanev);                 //először kitöröljük, hogy ne legyen gond
            ideiglenes_tabla_letrehozas($id_tablanev);  //majd létrehozzuk
          }else{
            ideiglenes_tabla_letrehozas($id_tablanev);  //létrehozzuk az ideiglenes táblát
          }
          //Ha szerkeszteni szeretnénk a témát, fel is töltjük az ideiglenes táblát
          //a meglévő adatokkal
          if ($_POST[szerkesztes_e_mezo]){
            ideiglenes_tablat_feltolt($id_tablanev, $Temanev);
            $KerdesN=1;
            $max=maxkerdes($id_tablanev);
            kerdest_kiir($KerdesN);
          }
	}
}

function kerdest_rogzit_dbbe($KerdesN){
global $id_tablanev;
$ossz=mysql_connect(DB_HOSZT, DB_FELH_NEV, DB_JELSZO);
$db=mysql_select_db(DB_NEV, $ossz);	
$sql="insert into $id_tablanev values('$KerdesN', 
                                     '$_POST[Kerdes_box]',
                                     '$_POST[Valasz1_box]',
                                     '$_POST[Valasz2_box]', 
                                     '$_POST[Valasz3_box]', 
                                     '$_POST[Helyes]')" ;
$Ok=mysql_query($sql, $ossz) or die("Kérdés rögzítés hiba ". mysql_error());
}


function kerdest_torol($KerdesN){
global $id_tablanev;
$ossz=mysql_connect(DB_HOSZT, DB_FELH_NEV, DB_JELSZO);
$db=mysql_select_db(DB_NEV, $ossz);	
$sql="delete from $id_tablanev 
             where KerdesN=$KerdesN";
$ok=mysql_query($sql, $ossz) or die("Hiba a törlés közben: " .mysql_error());
ujraszamoz();
}


function kerdest_modosit($KerdesN){
global $id_tablanev;
$ossz=mysql_connect(DB_HOSZT, DB_FELH_NEV, DB_JELSZO);
$db=mysql_select_db(DB_NEV, $ossz);	
$sql="update $id_tablanev set KerdesN='$KerdesN',
                            Kerdes='$_POST[Kerdes_box]',
                            valasz1='$_POST[Valasz1_box]',
                            valasz2='$_POST[Valasz2_box]',
                            valasz3='$_POST[Valasz3_box]', 
                            helyes='$_POST[Helyes]'
       where KerdesN=$KerdesN" ;
$Ok=mysql_query($sql, $ossz) or die("Hiba a módósításban: " .mysql_error());
}

function ujkerdes($KerdesN){
global $id_tablanev;
$ossz=mysql_connect(DB_HOSZT, DB_FELH_NEV, DB_JELSZO);
$db=mysql_select_db(DB_NEV, $ossz);	
$sql="select * from $id_tablanev
      where ($KerdesN=KerdesN)" ;
$eredmeny=mysql_query($sql, $ossz) or die("Újkérdés hiba".mysql_error());
  if (mysql_num_rows($eredmeny)<1){
  return true;
  }else{ 
  return false;
  }
}

function kerdest_kiir($KerdesN){
global $k, $v1, $v2, $v3, $helyes, $id_tablanev;
$ossz=mysql_connect(DB_HOSZT, DB_FELH_NEV, DB_JELSZO);
$db=mysql_select_db(DB_NEV, $ossz);	
$sql="select * from $id_tablanev
      where KerdesN='$KerdesN'";
$eredmeny=mysql_query($sql, $ossz) or die(mysql_error());
$adatok=mysql_fetch_array($eredmeny);
$k=$adatok[Kerdes];
$v1=$adatok[valasz1];
$v2=$adatok[valasz2];
$v3=$adatok[valasz3];
$helyes=$adatok[helyes];
}


function takaritas($tabla){
$ossz=mysql_connect(DB_HOSZT, DB_FELH_NEV, DB_JELSZO);
$db=mysql_select_db(DB_NEV, $ossz);	
$sql="delete from $tabla" ;
$Torles_eredmeny=mysql_query($sql, $ossz) or die("Törlés nem sikerült ".mysql_error());
}

function ujraszamoz(){
global $id_tablanev;
  $ossz=mysql_connect(DB_HOSZT, DB_FELH_NEV, DB_JELSZO);
  $db=mysql_select_db(DB_NEV, $ossz);	
  $sql="select * from $id_tablanev";
  $eredmeny=mysql_query($sql, $ossz) or die(mysql_error());
  $N=1;
  takaritas("$id_tablanev"); //kitöröljük az ideiglenes tábla tartalmát
  while ($Mezo=mysql_fetch_array($eredmeny)){                 //újra feltöltjük az ideiglenes táblát
    $sql="insert into $id_tablanev values('$N',                 
                                     '$Mezo[Kerdes]',
                                     '$Mezo[valasz1]',
                                     '$Mezo[valasz2]', 
                                     '$Mezo[valasz3]', 
                                     '$Mezo[helyes]')" ;
    $Ok=mysql_query($sql, $ossz) or die("Kérdés újraszámozás hiba ". mysql_error());
    $N++;
  }
}

function maxkerdes($tabla){
  $ossz=mysql_connect(DB_HOSZT, DB_FELH_NEV, DB_JELSZO);
  $db=mysql_select_db(DB_NEV, $ossz);	
  $sql="select max(KerdesN) as MaxN from $tabla";
  $max_eredmeny=mysql_query($sql, $ossz) or die(mysql_error());
  $mezo=mysql_fetch_array($max_eredmeny);
  $max= $mezo[MaxN];
  return $max;
} 


//ellenőrizzük, hogy ki van-e töltve minden
function ellenorzes_ok()
{
  if ( ($_POST[Kerdes_box]=="") or ($_POST[Kerdes_box]=="Nincs kérdés beírva!") or
      ($_POST[Valasz1_box]=="") or ($_POST[Valasz1_box]=="Nincs válasz beírva!") or
      ($_POST[Valasz2_box]=="") or ($_POST[Valasz2_box]=="Nincs válasz beírva!") )
  {
    return false;
  } else 
  {
   return true;
  }
}


function hibat_kiir()
{ global $k, $v1, $v2, $v3, $helyes, $szink, $szinv1, $szinv2;
 if ( ($_POST[Kerdes_box]=="") or ($_POST[Kerdes_box]=="Nincs kérdés beírva!") )
 { $k="Nincs kérdés beírva!";
   $szink="#FF3333";
   $v1=$_POST[Valasz1_box];
   $v2=$_POST[Valasz2_box];
   $v3=$_POST[Valasz3_box];
   $helyes=$_POST[Helyes];
 } else {
           if ( ($_POST[Valasz1_box]=="") or ($_POST[Valasz1_box]=="Nincs válasz beírva!") )
           { $v1="Nincs válasz beírva!";
             $szinv1="#FF3333";
             $k=$_POST[Kerdes_box];
             $v2=$_POST[Valasz2_box];
             $v3=$_POST[Valasz3_box];
             $helyes=$_POST[Helyes];
           } else {
                   if ( ($_POST[Valasz2_box]=="") or ($_POST[Valasz2_box]=="Nincs válasz beírva!") )
                    { $v2="Nincs válasz beírva!";
                      $szinv2="#FF3333";
                      $k=$_POST[Kerdes_box];
                      $v1=$_POST[Valasz1_box];
                      $v3=$_POST[Valasz3_box];
                      $helyes=$_POST[Helyes];
                    } 
                  }
        }  
}
 

function tablat_torol($id_tablanev){
  $ossz=mysql_connect(DB_HOSZT, DB_FELH_NEV, DB_JELSZO);
  $db=mysql_select_db(DB_NEV, $ossz);	
  $sql_torles="drop table $id_tablanev";
  $torles_eredmeny=mysql_query($sql_torles, $ossz) or die("Hiba az ideiglenes tábla törlésekor ".mysql_error());
}



//FŐPROGRAM
$vege=false;                         
szovegboxokat_torol();
kezdes();
//szovegboxokat_torol();
$mozgas=$_POST[mozgas];    //kell, hogy megjegyezze az előző mozgást
switch  ($_POST[mozgas]) {
case "Tovább":	 if (ellenorzes_ok())
                  { 
                   if (ujkerdes($KerdesN))
                    { 
                     Kerdest_rogzit_dbbe($KerdesN);    //Ha új kérdés: rögzítjük
                     $KerdesN++; 
                    } 
                   else
                    {                 
                     Kerdest_modosit($KerdesN);    //Ha nem új, betöltjük, módosítjuk
                     $KerdesN++;                          
                     kerdest_kiir($KerdesN);     //Kiírjuk a következőt tkp a változókat állítjuk be
                     if (!isset($helyes))         //Ha nincs semmi beállítva legyen az első a helyes
                       {$helyes=1;}       
                    }
                   if ($KerdesN>$max)           //új kérdésnél kell, hogy megfelelő legyen az összes kérdés kiírása
                     {$max=$KerdesN;} 
                  }
                  else
                  {hibat_kiir();}                    	
                break;
case "Vissza": if (ellenorzes_ok())
                 {
                   if (ujkerdes($KerdesN))
                     {
                     Kerdest_rogzit_dbbe($KerdesN);
                     }
                   else
                     {
                     Kerdest_modosit($KerdesN);  //amit beírt rögzítjük
                     }
                   if ($KerdesN>1)
                    {$KerdesN--;}
                  kerdest_kiir($KerdesN);
                 }  	//kiírjuk az előzőt
                else
                 {hibat_kiir();}                    	                                
                break;
case "Kérdés_törlése":      
                Kerdest_torol($KerdesN);
                if ($KerdesN>1){                                  
                $KerdesN--;
                $max=maxkerdes($id_tablanev);
                }
                kerdest_kiir($KerdesN);  	//kiírjuk az előzőt                
                break;
case "Elejére":if (ellenorzes_ok())                        	
                {
                 if (ujkerdes($KerdesN))
                  {
                  Kerdest_rogzit_dbbe($KerdesN);
                  }
                 else
                  {
                  Kerdest_modosit($KerdesN);  //amit beírt rögzítjük
                  }
                 if ($KerdesN>1)
                  { 
                  $KerdesN=1;     
                  }
                 kerdest_kiir($KerdesN);
                }  	//kiírjuk az elsőt
                else
                 {hibat_kiir();}                    	                                                               
                break;
case "Végére":	if (ellenorzes_ok())                        	
                    {
                  if (ujkerdes($KerdesN))
                    {
                    Kerdest_rogzit_dbbe($KerdesN);
                    }
                  else
                    {
                    Kerdest_modosit($KerdesN);  //amit beírt rögzítjük
                    }
                  $max=maxkerdes($id_tablanev);
                  if ($KerdesN<$max)
                    { 
                    $KerdesN=$max;     
                    }
                  kerdest_kiir($KerdesN);}  	//kiírjuk az utolsót                
                else
                 {hibat_kiir();}                    	                                                                 
                break;
case "Kész":	if (ellenorzes_ok())                        	
                  {
                    if (ujkerdes($KerdesN))
                    {
                      Kerdest_rogzit_dbbe($KerdesN);
                    }
                    else
                    {
                      Kerdest_modosit($KerdesN);  //amit beírt rögzítjük
                    }
                  kerdeseket_rogzit($KerdesN);  //kiírjuk az ideiglenesből a kérdéseket a kerd_val-ba
                  kerdest_kiir($KerdesN);
                  tablat_torol($id_tablanev);
                  //header("Location:kezdo_oldal.php");
                  $vege=true;
                  }  	                
                else
                 {hibat_kiir();}                    	                                                                 
                break;
default: print "";           
} 

if (!$vege){
  $celoldal="$_Server[PHP_SELF]";
}else{
  //$celoldal="kezdo_oldal.php";
  $celoldal="user_ell_keret.php";
}
