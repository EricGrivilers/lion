
<table border="0" width=100% cellspacing=0>
  <tr>
    <td  >Option </td>
    <td  ><input name='enoption' type='checkbox' {hasOption} /></td>
  </tr>
  <tr>
    <td  >Actif </td>
    <td  ><input type='checkbox' name='actif' {isActive} /></td>
  </tr>
  <tr>
    <td  >Prix: </td>
    <td  ><input name='prix' type='text' id="prix" value="{prix}"   />
      &euro;&nbsp;&nbsp;
      <input name='surdemande' type=checkbox id="surdemande" {surDemande}/>
      sur demande</td>
  </tr>
  <tr>
    <td  >Reference: </td>
    <td  ><input name='reference' type='text' id="reference" value="{reference}" /></td>
  </tr>
  <tr>
    <td  >Localité: </td>
    <td  ><select name='locfr'>
        <option value=''></option>
        
      
        
      
      
        
                    {locations}
                  
      
    
    
      
    
      </select></td>
  </tr>
  <tr>
    <td  >Type: </td>
    <td  ><select name='type'>
        <option value='1'>Maison</option>
        <option value='2'>Appartement</option>
        <option value='3'>Terrain</option>
      </select></td>
  </tr>
  <tr>
    <td  >Vente
      <input type=radio name=searchfor value='sale' {isVente} /></td>
    <td  >Location
      <input type=radio name=searchfor value='rent' {isLocation} /></td>
  </tr>
  <tr>
    <td>Rue et numéro&nbsp;</td>
    <td><input type="text" name="name" id="name" value="{name}" style="width: 200px;" /></td>
  </tr>
  <tr>
    <td>Quartier:&nbsp;</td>
    <td><select name='quartier_id' id='quartier_id' class='selectbox'  >
        <option selected></option>
        
      
        
      
        {quartiers}
      
    
      
    
      </select></td>
  </tr>
  <tr>
    <td  >Description:<br /></td>
    <td  ><textarea id="descrfr" name="descrfr" rows="10" cols="40">{descrfr}
	</textarea></td>
  </tr>
  <tr>
    <td  >Surface </td>
    <td  ><input type='text' size='4' name='area' />
      m²</td>
  </tr>
  <tr>
    <td  >Chambre(s) </td>
    <td  ><select name='rooms'>
      <option value='1'>1</option>
      <option value='2'>2</option>
      <option value='3'>3</option>
      <option value='4'>4</option>
      <option value='5'>5</option>
      <option value='6'>6</option>
      <option value='7'>7</option>
      <option value='8'>8</option>
      <option value='9'>9</option>
      <option value='10'>10</option>
      <option value='11'>11</option>
      <option value='12'>12</option>
      <option value='13'>13</option>
      <option value='14'>14</option>
    </select></td>
  </tr>
  <tr>
    <td  >salle(s) d'eau</td>
    <td  ><select name='bathrooms'>
        <option value='1'>1</option>
        <option value='2'>2</option>
        <option value='3'>3</option>
        <option value='4'>4</option>
        <option value='5'>5</option>
        <option value='6'>6</option>
        <option value='7'>7</option>
        <option value='8'>8</option>
        <option value='9'>9</option>
        <option value='10'>10</option>
        <option value='11'>11</option>
        <option value='12'>12</option>
        <option value='13'>13</option>
        <option value='14'>14</option>
      </select></td>
  </tr>
  <tr>
    <td  >garage(s)</td>
    <td  ><select name='garages'>
      <option value='0'>0</option>
      <option value='1'>1</option>
      <option value='2'>2</option>
      <option value='3'>3</option>
      <option value='4'>4</option>
      <option value='5'>5</option>
      <option value='6'>6</option>
      <option value='7'>7</option>
      <option value='8'>8</option>
      <option value='9'>9</option>
      <option value='10'>10</option>
      <option value='11'>11</option>
      <option value='12'>12</option>
      <option value='13'>13</option>
      <option value='14'>14</option>
    </select></td>
  </tr>
  <tr>
    <td  >Jardin</td>
    <td  ><select name='garden'>
        <option value=''>
        <option value='Jardin'>Jardin</option>
        <option value='Cour'>Cour</option>
        <option value='Terrasse'>Terrasse</option>
      </select></td>
  </tr>
  <tr>
    <td colspan="2" valign="bottom"><!--Lien Whyse: <input type='text' name='lienwhyse' value=""  size='40'/>--></td>
  </tr>
  <tr>
    <td class=price >Images&nbsp;</td>
    <td class=price >{images}</td>
  </tr>
</table>
