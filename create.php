<?php
### autoload ###
  require_once __DIR__ . '/vendor/autoload.php';
  ini_set('display_errors',1);
  error_reporting(E_ALL | E_STRICT);
# SQL VARIABLES

  $where = "localhost"; # DATABASE LOCATION
  $user = "root"; # DATABASE USER
  $password = ""; # DATABASE PASSWORD

# POZYSKANIE ZMIENNYCH
  if(isset($_POST['stworz'])){
    $miejscewystawienia = $_POST['miejscewystawienia'];
    $datawystawienia = $_POST['datawystawienia'];
    $datasprzedazy = $_POST['datasprzedazy'];
    $fakturanr = $_POST['fakturanr'];
    $sprzedawca = $_POST['sprzedawca'];
    if(!empty($_POST['nip'])){
    $nip = "NIP: ".$_POST['nip'];
  }
  $ulica = $_POST['ulica'];
  $miastoikod = $_POST['miastoikod'];
  $nabywca = $_POST['nabywca'];
  if(!empty($_POST['nipnb'])){
    $nipnb = "NIP: ".$_POST['nipnb'];
  }
  $ulicanb = $_POST['ulicanb'];
  $miastoikodnb = $_POST['miastoikodnb'];
  if(!empty($_POST['tel'])){
    $tel = "Tel. ".$_POST['tel'];
  }
  if(!empty($_POST['konto'])){
    $konto = "Konto: ".$_POST['konto'];
  }
  if(!empty($_POST['swift'])){
    $swift = "SWIFT: ".$_POST['swift'];
  }
  if(!empty($_POST['adnotacje'])){
    $adnotacje = "Adnotacje. ".$_POST['adnotacje'];
  }

# SQL INSERT
  try {
    $db = new PDO("mysql:host={$where};charset=utf8;dbname=pdf", $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    $sql = $db->prepare("INSERT INTO faktury (miejsce_wystawienia, data_wystawienia, data_sprzedazy, faktura_nr, sprzedawca, nip_sprzedawcy, ulica_sprzedawcy, miasto_i_kod_sprzedawcy, telefon_sprzedawcy, konto_sprzedawcy, swift_sprzedawcy, nabywca, ulica_nabywcy, miasto_i_kod_nabywcy, nip_nabywcy, adnotacje, sposob_zaplaty, termin_zaplaty) VALUES (:miejsce_wystawienia, :data_wystawienia, :data_sprzedazy, :faktura_nr, :sprzedawca, :nip_sprzedawcy, :ulica_sprzedawcy, :miasto_i_kod_sprzedawcy, :telefon_sprzedawcy, :konto_sprzedawcy, :swift_sprzedawcy, :nabywca, :ulica_nabywcy, :miasto_i_kod_nabywcy, :nip_nabywcy, :adnotacje, :sposob_zaplaty, :termin_zaplaty)");
    $sql->bindParam(':miejsce_wystawienia', $miejscewystawienia);
    $sql->bindParam(':data_wystawienia', $datawystawienia);
    $sql->bindParam(':data_sprzedazy', $datasprzedazy);
    $sql->bindParam(':faktura_nr', $fakturanr);
    $sql->bindParam(':sprzedawca', $sprzedawca);
    $sql->bindParam(':nip_sprzedawcy', $nip);
    $sql->bindParam(':ulica_sprzedawcy', $ulica);
    $sql->bindParam(':miasto_i_kod_sprzedawcy', $miastoikod);
    $sql->bindParam(':telefon_sprzedawcy', $tel);
    $sql->bindParam(':konto_sprzedawcy', $konto);
    $sql->bindParam(':swift_sprzedawcy', $swift);
    $sql->bindParam(':nabywca', $nabywca);
    $sql->bindParam(':ulica_nabywcy', $ulicanb);
    $sql->bindParam(':miasto_i_kod_nabywcy', $miastoikodnb);
    $sql->bindParam(':nip_nabywcy', $nipnb);
    $sql->bindParam(':adnotacje', $adnotacje);
    $sql->bindParam(':sposob_zaplaty', $sposobzaplaty);
    $sql->bindParam(':termin_zaplaty', $terminzaplaty);
    $sql->execute();
    echo "Zapisano do DB<br />";
    $db = null;
  }
  catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
  }

# USTAWIENIA TCPDF
  $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
  $pdf->SetCreator(PDF_CREATOR);
  $pdf->SetFont('helvetica', '', 11, '', false);
  $pdf->SetTitle('Faktura');
  $pdf->SetSubject('Faktura');
  $pdf->setPrintHeader(false);
  $pdf->setPrintFooter(false);
  $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
  $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
  $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
  $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
  $pdf->AddPage();

# ODCZYTYWANIE LICZBY WIERSZY

  $wiersz = "";
  $kwotanettorazem = 0;
  $kwotabruttorazem = 0;
  $tmp = 0;
  $vatrazem = 0;

  for($i=1;$i<=INF;$i++){
    echo "start<br />";
 if(is_null($_POST['nazwatowaru' . $i])){
      echo "break<br />";
      break;
 }
    echo "echopoifiezbreak<br />";
    $nazwatowaru  = $_POST['nazwatowaru' . $i];
    $ilosc  = $_POST['ilosc' . $i];
    $jm  = $_POST['jm' . $i];
    $cenanetto  = $_POST['cenanetto' . $i];
    $vat  = $_POST['vat' . $i];
    if($vat !="zw"){
      $vat  = $vat ."%";
    }
    $kwotanettophp  = $cenanetto  * $ilosc ;
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
  $kwotanettorazem = $kwotanettorazem + ($cenanetto * $ilosc );
  $tmp = $kwotanettophp  + ($kwotanettophp  * ($vat  / 100 ));
  $kwotabruttorazem += $tmp;
  $vatrazem = $vatrazem + ($kwotanettophp *($vat / 100));

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
    echo "Zapisano do DB<br />";
    $db = null;
  }
  catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
  }
  try{
    $db = new PDO("mysql:host={$where};charset=utf8;dbname=pdf", $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    $q=$db->prepare("SELECT id_produktu from produkty ORDER BY id_produktu DESC LIMIT 1");
    $q->execute();
    $result = $q->fetch(PDO::FETCH_ASSOC);
    echo $result['id_produktu'].'<br />';
    $dbh = null;
}
  catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
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
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
  }
# END OF SQL INSERT

  }
  echo "koniecif<br />";
  $kwotabruttorazem = round($kwotabruttorazem, 2);
  $vatrazem = round($vatrazem, 2);
  $sposobzaplaty = $_POST['sposobzaplaty'];
  $terminzaplaty = $_POST['terminzaplaty'];


# SQL INSERT
  try {
    $db = new PDO("mysql:host={$where};charset=utf8;dbname=pdf", $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    $sql = $db->prepare("UPDATE faktury SET kwota_netto_razem=:kwota_netto_razem, kwota_brutto_razem=:kwota_brutto_razem, vat_razem=:vat_razem");
    $sql->bindParam(':kwota_netto_razem', $kwotanettorazem);
    $sql->bindParam(':kwota_brutto_razem', $kwotabruttorazem);
    $sql->bindParam(':vat_razem', $vatrazem);
    $sql->execute();
    echo "Zapisano do DB<br />";
    $db = null;
  }
  catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
  }
# END OF SQL INSERT



# STWORZENIE PDF'a
  $txt = <<<EOF
  <html>
  <head>
  <meta charset="utf-8">
  </head>
  <style>
    body{
      margin-left: auto;
      margin-right: auto;
      letter-spacing: 1px;
      font-size: 11px;
      font-style: normal;
      font-family: freeserif;
    }
    right{
     float: right;
    }
    .border{
      border-bottom: 1px solid black;
      background-color: #e9f8ff;
    }
    .center{
      text-align: center;
    }
    .long{
    width: 200px;
    }
    .ultrashort{
    width: 20px;
    }
    .short{
    width: 40px;
    }
  </style>

  <body>
  <table>
  <tbody>
  <tr>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><b>Miejsce wystawienia:</b></td>
  <td>{$miejscewystawienia}</td>
  </tr>
  <tr>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><b>Data wystawienia:</b></td>
  <td>{$datawystawienia}</td>
  </tr>
  <tr>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><b>Data sprzedaży:</b></td>
  <td>{$datasprzedazy}</td>
  </tr>
  </tbody>
  </table>
  <br /><br />
  <table>
  <tbody>
  <tr>
  <td style="text-align: center;"><span style="font-size: 18px;"><b>Faktura NR {$fakturanr}</b></span></td>
  </tr>
  </tbody>
  </table>
  <br /><br />
  <table>
  <tbody>
  <tr>
  <td>Sprzedawca</td>
  <td>Nabywca</td>
  </tr>
  <br />
  <tr>
  <td>{$sprzedawca}</td>
  <td>{$nabywca}</td>
  </tr>
  <tr>
  <td>Ulica: {$ulica}</td>
  <td>Ulica: {$ulicanb}</td>
  </tr>
  <tr>
  <td>Miasto: {$miastoikod}</td>
  <td>Miasto: {$miastoikodnb}</td>
  </tr>
  <tr>
  <td>{$nip}</td>
  <td>{$nipnb}</td>
  </tr>
  <tr>
  <td>{$tel}</td>
  <td></td>
  </tr>
  <tr>
  <td>{$konto}</td>
  <td></td>
  </tr>
  <tr>
  <td>{$swift}</td>
  <td></td>
  </tr>
  </tbody>
  </table>
  <br /><br />
  <table>
  <tbody>
  <tr>
  <td class="border center long"><b>Nazwa towaru/usługi</b></td>
  <td class="border center short"><b>Ilość</b></td>
  <td class="border center" style="width: 50px;"><b>Jm.</b></td>
  <td class="border center"><b>Cena netto</b></td>
  <td class="border center short"><b>VAT</b></td>
  <td class="border center"><b>Kwota netto</b></td>
  <td class="border center"><b>Kwota VAT</b></td>
  <td class="border center"><b>Kwota brutto</b></td>
  </tr>
  {$wiersz}
  <br /><br />
  <tr>
  <td colspan="2" class="center" style="font-size: 14px;">Do zapłaty: <b>{$kwotabruttorazem} PLN</b></td>
  <td colspan="2" class="center">VAT: {$vatrazem} PLN</td>
  <td colspan="2" class="center">Netto: {$kwotanettorazem} PLN</td>
  <td colspan="2" class="center">Brutto: {$kwotabruttorazem} PLN</td>
  </tr>
  </tbody>
  </table>
  <table>
  <br /><br />
  <tbody>
  <tr>
  <td>{$adnotacje}</td>
  </tr>
  </tbody>
  </table>
  <table>
  <tbody>
  <br /><br /><br />
  <tr>
  <td colspan="4"></td>
  <td>Sposób zapłaty: {$sposobzaplaty}</td>
  </tr>
  <tr>
  <td colspan="4"></td>
  <td>Termin zapłaty: {$terminzaplaty}</td>
  </tr>
  </tbody>
  </table>
  <br /><br />
  <table>
  <tbody>
  <tr>
  <td class="center">Podpis odbierającego<br /><br /><br />...........................</td>
  <td class="center">Podpis wystawiającego<br /><br /><br />...........................</td>
  </tr>
  </tbody>
  </table>
  </body></html>
EOF;


  echo $wiersz;
  $pdf->writeHTML($txt, true, false, true, false, '');
  ob_end_clean();
  $date = date("/m/Y");
  $zapis = $fakturanr.$date."XXX".rand(0,50000);
  $pdf->Output('faktura'.$zapis.'.pdf', 'I');
}
