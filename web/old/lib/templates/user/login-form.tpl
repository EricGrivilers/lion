<div class="row-fluid">
	<table cellpadding="0" cellspacing="0" id="loginForm"  >

			{% if user %}
			<thead>
				<tr>
					<th class="title"><h2>{{user.firstname}} {{user.lastname}}</h2></th>
					<th class="newAccount">
						<a href="/register">{% if language=='en' %}Create a new account {% else %} Créer un nouveau compte{% endif %}</a>
					</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td class='newAccount'><a href="/utilisateur">{% if language=='en' %}My profile{% else %}Mon profil{% endif %}</a></td>
					<td class='newAccount'><a href='/logout'>{% if language=='en' %}Logout{% else %}Deconnexion{% endif %}</a></td>
				</tr>
			</tbody>
			{% else %}
			<thead>
				<tr>
					<th class="title"><h2>{% if language=='en' %}MY ACCOUNT{% else %}MON COMPTE{% endif %}</h2></th>
					<th class="newAccount">
						<a href="/register">{% if language=='en' %}Create a new account {% else %} Créer un nouveau compte{% endif %}</a>
					</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td colspan="2">
						<input type="text" name="email" value="email" onclick="resetField(this)" rel="email" placeholder="email" class="span5">
						<input type="text" name="password" value="mot de passe" onclick="resetField(this)" onfocus="resetField(this)" rel="mot de passe" placeholder="password" class="span5"  >

						<a href="#" onclick="login()"><img src="/medias/login_go.gif" /></a>
					</td>

				</tr>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="2">
						<a href="#" onclick="getPassword()">{% if language=='en' %}Password lost ?{% else %} Mot de passe oublié ?{% endif %}</a>
					</td>
				</tr>
			</tfoot>
			{% endif %}

	</table>


</div>
