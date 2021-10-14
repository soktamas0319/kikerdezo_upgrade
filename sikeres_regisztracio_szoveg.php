<?php

$nev=$_POST[nev];
$jelszo=$_POST[jelszo];
$rendes_nev=$_POST[rendes_nev];
$email=$_POST[email];

$uzenet="Név: $rendes_nev, E-mail: $email, Felhasználónév: $nev, Jelszó: $jelszo";
$oke=mail("soktreg@yahoo.com", "ZSPSZ tesztek regisztráció", $uzenet, "X-FW-MailID: 3e66b11434");

if ($oke){
  print "A regisztráció elküldve a soktreg@yahoo.com címre";
}else{
  print "A regisztráció nem sikerült";
}
