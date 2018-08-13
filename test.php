<?php
ini_set('display_errors',1);
error_reporting(E_ALL | E_STRICT);
$where = "localhost"; # DATABASE LOCATION
$user = "root"; # DATABASE USER
$password = ""; # DATABASE PASSWORD

for($i=1;$i<=50;$i++){
  echo "start";

if(!isset($nazwatowaru )){
  echo "start1";
  $nazwatowaru  = $_POST['nazwatowaru' . $i];
  $ilosc  = $_POST['ilosc' . $i];
  $jm  = $_POST['jm' . $i];
  $cenanetto  = $_POST['cenanetto' . $i];
  $vat  = $_POST['vat' . $i];
  if($vat !="zw"){
    $vat  = $vat ."%";
    echo "start2";
  }
  $kwotanettophp  = $cenanetto  * $ilosc;
  $kwotavatphp  = $kwotanettophp  * ($vat  / 100 );
  $kwotabruttophp  = $kwotanettophp  + ($kwotanettophp  * ($vat  / 100 ));
  $kwotanettophp  = round($kwotanettophp , 2);
  $kwotavatphp  = round($kwotavatphp , 2);
  $kwotabruttophp  = round($kwotabruttophp , 2);
  $wiersz .="<tr>
  <td>" . $nazwatowaru  . "</td>
  <td >" . $ilosc  . "</td>
  <td>" . $jm  . "</td>
  <td>" . $cenanetto  . "</td>
  <td>" . $vat  . "</td>
  <td>" . $kwotanettophp  . "</td>
  <td>" . $kwotavatphp  . "</td>
  <td>" . $kwotabruttophp  . "</td>
  </tr>";
  if($vat == "zw"){
    $vat = 0;
  }
  echo "start3";
$kwotanettorazem = $kwotanettorazem + ($cenanetto * $ilosc );
$tmp = $kwotanettophp  + ($kwotanettophp  * ($vat  / 100 ));
$kwotabruttorazem += $tmp;
$vatrazem = $vatrazem + ($kwotanettophp *($vat / 100));
}
# SQL INSERT
try {
  $db = new PDO("mysql:host={$where};charset=utf8;dbname=pdf", $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
  $sql = $db->prepare("INSERT INTO produkty (nazwa_towaru,jednostka_miary,cena_netto,vat,ilosc,kwota_netto,kwota_vat,kwota_brutto) VALUES (:nazwatowaru,:jm,:cenanetto,:vat,:ilosc,:kwotanettophp,
  :kwotavatphp,:kwotabruttophp)");
  $sql->bindParam(':nazwatowaru', $nazwatowaru);
  $sql->bindParam(':jm', $jm);
  $sql->bindParam(':cenanetto', $cenanetto);
  $sql->bindParam(':vat', $vat);
  $sql->bindParam(':ilosc', $ilosc);
  $sql->bindParam(':kwotanettophp', $kwotanettophp);
  $sql->bindParam(':kwotavatphp', $kwotavatphp);
  $sql->bindParam(':kwotabruttophp', $kwotabruttophp);
  $sql->execute();
  echo "Zapisano do DB";
  $db = null;
}
catch (PDOException $e) {
  die("Error!: " . $e->getMessage() . "<br/>");
}
try{
  $db = new PDO("mysql:host={$where};charset=utf8;dbname=pdf", $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
  $q=$db->prepare("SELECT id_produktu from produkty ORDER BY id_produktu DESC LIMIT 1");
  $q->execute();
  $result = $q->fetch(PDO::FETCH_ASSOC);
  echo $result['id_produktu'];
  $dbh = null;
}
catch (PDOException $e) {
  die("Error!: " . $e->getMessage() . "<br/>");

}
try {
$db = new PDO("mysql:host={$where};charset=utf8;dbname=pdf", $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
$sql = $db->prepare("INSERT INTO relacje (produkt_id,faktura_nr) VALUES (:id_produktu,:faktura_nr)");
$sql->bindParam(':faktura_nr', $fakturanr );
$sql->bindParam(':id_produktu', $result['id_produktu']);
$sql->execute();
$db = null;
}
catch (PDOException $e) {
  die("Error!: " . $e->getMessage() . "<br/>");

}
# END OF SQL INSERT

}
