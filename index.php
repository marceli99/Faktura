  <!DOCTYPE html>
  <html>
    <head>
      <meta charset="utf-8">
      <title>Faktura</title>
	    <link rel="Stylesheet" type="text/css" href="style.css" />
      <script src="file.js" type="text/javascript"></script>
    </head>
    <body>
      <section>
        <br />
        <span style="text-align: center;"><h1>Faktura</h1></span>
        <form method="post" action="create.php">
        <input type="text" name="fakturanr" value="<?php nr(); ?>" placeholder="Numer faktury" style="width: 985px; font-size: 24px;"><br />
        <hr size="2px" style="background-color: black;"><br /><br />
          <header>
      <b>Miejsce wystawienia: </b><input type="text" name="miejscewystawienia" placeholder="Miejsce wystawienia"><br />
      <b>Data wystawienia: </b><input type="text" name="datawystawienia" placeholder="Data wystawienia"><br />
      <b>Data sprzedaży: </b><input type="text" name="datasprzedazy" placeholder="Data sprzedaży"><br />
    </header>
        <nav>
          <fieldset style="float: left; width: 250px;">
            <legend>Sprzedawca</legend>
            <input type="text" name="sprzedawca" placeholder="Sprzedawca"><br>
            <input type="text" name="ulica" placeholder="Ulica"><br />
            <input type="text" name="miastoikod" placeholder="Miasto i kod"><br />
            <input type="text" name="nip" placeholder="NIP"><br>
            <input type="text" name="tel" placeholder="Telefon"><br />
            <input type="text" name="konto" placeholder="Numer konta"><br />
            <input type="text" name="swift" placeholder="SWIFT"><br />

          </fieldset>
          <fieldset style="float: right; width: 250px;">
            <legend>Nabywca</legend>
            <input type="text" name="nabywca" placeholder="Nabywca"><br>
            <input type="text" name="ulicanb" placeholder="Ulica"><br />
            <input type="text" name="miastoikodnb" placeholder="Miasto i kod"><br />
            <input type="text" name="nipnb" placeholder="NIP / PESEL"><br>
          </fieldset>
        </nav>
        <article id="wiecej">

          <table style="width: 100%; text-align: center;">
  <tbody>
  <tr>
  <td>Nazwa towaru/usługi</td>
  <td>Ilość</td>
  <td>Jm.</td>
  <td>Cena netto</td>
  <td>VAT</td>

  </tr>
  <tr>
  <td><input type="text" name="nazwatowaru1" placeholder="Nazwa" style="width: 550px;"></td>
  <td><input type="number" name="ilosc1" placeholder="Ilość" style="width: 50px;"></td>
  <td><input type="text" name="jm1" placeholder="Jednostka miary" style="width: 100px;"></td>
  <td><input type="text" name="cenanetto1" placeholder="Cena netto" style="width: 100px;"></td>
  <td><select name="vat1">
  <option value="23">23%</option>
  <option value="8">8%</option>
  <option value="5">5%</option>
  <option value="0">0%</option>
  <option value="zw">zw.</option>
  </select></td>
  </tr>
  </tbody>
  </table>
        </article>
        <footer>
          <table>
            <tbody>
              <tr>
                <td><input type="text" name="adnotacje" placeholder="Adnotacje" style="width: 950px; height: 100px;"></textarea></td>
              </tr>
            </tbody>
          </table>
          <br />
          <input type="text" name="sposobzaplaty" placeholder="Sposób zapłaty"><br />
          <input type="text" name="terminzaplaty" placeholder="Termin zapłaty">
        </footer><br /><br /><br /><br /><br /><br /><br />
        <div style="text-align: center">
        <input type="submit" name="stworz" value="Stwórz" style="margin-left: auto; margin-right: auto; width: 400px;">
        <input type="button" value="Więcej wierszy" onclick="wiecej();" style="margin-left: auto; margin-right: auto; width: 400px;">
      </div>
    </form>
    </section>
    </body>
  </html>
  <?php
    function nr(){
      echo date("/m/Y");
    }
  ?>
