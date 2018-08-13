var i = 2;
function wiecej(){
  var parent = document.getElementById('wiecej');
  var newChild =`<table style="width: 100%;">
    <tbody>
    <tr>
    <td><input type="text" name="nazwatowaru`+i+`" placeholder="Nazwa" style="width: 550px;"></td>
    <td><input type="number" name="ilosc`+i+`" placeholder="Ilość" style="width: 50px;"></td>
    <td><input type="text" name="jm`+i+`" placeholder="Jednostka miary" style="width: 100px;"></td>
    <td><input type="text" name="cenanetto`+i+`" placeholder="Cena netto" style="width: 100px;"></td>
    <td><select name="vat`+i+`">
    <option value="23">23%</option>
    <option value="8">8%</option>
    <option value="5">5%</option>
    <option value="0">0%</option>
    <option value="zw">zw.</option>
    </select></td>
    </tr>
    </tbody>
    </table>
    `;
    parent.insertAdjacentHTML('beforeend', newChild);
    i++;
}
