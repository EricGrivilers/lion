<div id="registerForm">
<input type='hidden' name='updating' value='true' /><table class="registerForm" border="0" >
					<tr>
							
							<td class="formlab" >Email</td>
							<td align="left"><b>{email}</b></td>
							
							

						</tr>
                       
                        
                      </table>  
                       <table class="registerForm" border="0" >
                        <tr>
							<td class="formlab">Salutation</td>
							<td align="left"><select name="salutation" class="gender" {isMme}><option value="Mme" >Mme</option><option value="M" {isM} >M</option></select></td>
							<td class="formlab">Langue</td>
							<td align="left"><input type="radio" name="language" {isFr} value='fr' />fr <input type="radio" name="language" {isEn} value='en'  />en </td>

						</tr>
                        
                        
						<tr>
							<td class="formlab">Nom*</td>
							<td><input type="text" name="lastname"  value="{lastname}"/></td>
							<td class="formlab">Prénom*</td>
							<td><input type="text" name="firstname"   value="{firstname}"/></td>
						</tr>
						<tr>

							<td class="formlab"></td>
							<td></td>
							<td class="formlab">Téléphone*</td>
							<td><input type="text" name="tel"   value="{tel}"/></td>
						</tr>
						<tr>
							<td class="formlab">Rue</td>

							<td><input type="text" name="street"   value="{street}"/></td>
							<td class="formlab">Numéro</td>
							<td><input type="text" name="number"   value="{number}"/></td>
						</tr>
						<tr>
							<td class="formlab">Code postal </td>
							<td><input type="text" name="zip"   value="{zip}"/></td>
							<td class="formlab">Ville</td>

							<td><input type="text" name="city"   value="{city}"/></td>
						</tr>
						<tr>
							<td class="formlab">Pays</td>
							<td><input type="text" name="country"   value="{country}"/></td>
							<td class="formlab">Fax</td>
							<td><input type="text" name="fax"   value="{fax}"/></td>
						</tr>
                        

						
						<tr>
                                <td colspan="4" class="detailb" style="text-align: center;"><a href='#' onclick='submitRegisterForm()' >Enregistrer</a></td>
						</tr>
					</table>
</div>