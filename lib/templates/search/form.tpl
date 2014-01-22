<div class="row-fluid">
	<form name='searchForm' id='searchForm' method='get'  action="/vente" class='general'>

		<input type='hidden' name='searchStart' id='searchStart' value='{{get.searchStart}}' />
		<input type='hidden' name='orderBy' id='orderBy' value='{{get.orderBy}}' />
		<input type='hidden' name='limitBy' id='limitBy' value='{% if get.limitBy=='' %}12{% else %}{{get.limitBy}}{% endif %}' />
		<div class="row-fluid" >
			<ul class="nav nav-tabs">
			  <li class="{% if tab=='generale' or tab=='' %} active {% endif %}"><a href="#generalSearch" data-toggle="tab" style="background:#6D6D6D">{% if language=='en' %}Global search {% else %}Recherche globale{% endif %}</a></li>
			  <li class="{% if tab=='geographique' %} active {% endif %}"><a href="#mapSearch" data-toggle="tab" style="background:#655237">{% if language=='en' %}Geographic search {% else %}Recherche géographique{% endif %}</a></li>

			</ul>
		</div>
		<div class="row-fluid">

				<div class='searchHeader'>
					<div class='main'>
						{% if language=='en' %} SEARCH {% else %} RECHERCHE {% endif %}:
						<input type='hidden' name='searchType' id='searchType' value='{{get.searchType}}' />
						<div id='radio'>
							<a class="switchType" rel="sale"><div class="checkbox  {% if get.searchType=='sale' or get.searchType=='' %}active{% endif %}" rel="sale"></div>
							{% if language=='en' %} Sale {% else %} Vente {% endif %}</a>
							<a class="switchType" rel="rent"><div class="checkbox  {% if get.searchType=='rent' %} active {% endif %}" rel="rent"></div>
							{% if language=='en' %} Rent {% else %} Location {% endif %}</a>
						</div>
					</div>
					<!--<div class='infos'>{% if language=='en' %}
						Specify your criterias (multiple choices)
						{% else %}
						Précisez votre recherche (choix multiples)
						{% endif %}</div>-->
						<input type='text' name='keywords' id='keywords' value="{{get.keywords}}" class='span12' placeholder="{% if language=='en' %}Search. Ex.: piscine, brugmann by keyword {% else %}Recherche. Ex.: piscine, brugmann{% endif %}" />
						{% if language=='en' %}Or precise your search criterias: {% else %} Ou précisez vos critères de recherche:{% endif %}
				</div>
		</div>
		<div class="row-fluid">
			<table class="row-fluid">

				<tr id="types">
					<td>
						<input type='checkbox' id='type_1' name='type[1]' value="{{ types[0]['label']}}" {% if get.type[1] %} checked {% endif %}/><label for='type_1'>{{types[0]['label']}}</label>
					</td>


					<td>
						<input type='checkbox' id='type_2' name='type[2]' value="{{ types[1]['label']}}" {% if get.type[2] %} checked {% endif %} /><label for='type_2'>{{types[1]['label']}}</label>
					</td>
					<td>
						<input type='checkbox' id='type_3' name='type[3]' value="{{ types[2]['label']}}" {% if get.type[3] %} checked {% endif %} /><label for='type_3'>{{types[2]['label']}}</label>
					</td>
				</tr>

				<tr class='sale' {% if get.searchType=='rent' %} style="display:none" {% endif %}>
					<td>
						<input type='checkbox' id='sale_1' name='sale[1]' value="<{{prices['sale'][0]}}" {% if get.sale[1] %} checked {% endif %} />
						<label for='sale_1'>{% if language=='en' %}
							less than
							{% else %}
							- de
							{% endif %}<br/>{{ prices['sale'][0]|number_format(0, ',', '.') }} &euro;</label>
					</td>
					<td>
						<input type='checkbox' id='sale_2' name='sale[2]' value="BETWEEN {{ prices['sale'][0]}} AND {{ prices['sale'][1]}}" {% if get.sale[2] %} checked {% endif %} />
						<label for='sale_2'>
							{% if language=='en' %} from {% else %} de {% endif %}
							 {{ prices['sale'][0]|number_format(0, ',', '.')}}
							<br/>
							{% if language=='en' %} to {% else %} à {% endif %}
							{{ prices['sale'][1]|number_format(0, ',', '.')}} &euro;</label>
					</td>
					<td>
						<input type='checkbox' id='sale_3' name='sale[3]' value=">{{ prices['sale'][1]}}" {% if get.sale[3] %} checked {% endif %} />
						<label for='sale_3'>
							{% if language=='en' %}
							more than
							{% else %}
							+ de
							{% endif %}
							<br/>
							{{ prices['sale'][1]|number_format(0, ',', '.')}} &euro;</label>
					</td>
				</tr>

				<tr class='rent' {% if get.searchType!='rent' %} style="display:none" {% endif %}>
					<td>
						<input type='checkbox' id='rent_1' name='rent[1]' value="<{{prices['rent'][0]}}" {% if get.rent[1] %} checked {% endif %} />
						<label for='rent_1'>{% if language=='en' %}
							less than
							{% else %}
							- de
							{% endif %}<br/>{{ prices['rent'][0]|number_format(0, ',', '.') }} &euro;</label>
					</td>
					<td>
						<input type='checkbox' id='rent_2' name='rent[2]' value="BETWEEN {{ prices['rent'][0]}} AND {{ prices['rent'][1]}}" {% if get.rent[2] %} checked {% endif %} />
						<label for='rent_2'>
							{% if language=='en' %} from {% else %} de {% endif %}
							 {{ prices['rent'][0]|number_format(0, ',', '.')}}
							<br/>
							{% if language=='en' %} to {% else %} à {% endif %}
							{{ prices['rent'][1]|number_format(0, ',', '.')}} &euro;</label>
					</td>
					<td>
						<input type='checkbox' id='rent_3' name='rent[3]' value=">{{ prices['rent'][1]}}" {% if get.rent[3] %} checked {% endif %} />
						<label for='rent_3'>
							{% if language=='en' %}
							more than
							{% else %}
							+ de
							{% endif %}
							<br/>
							{{ prices['rent'][1]|number_format(0, ',', '.')}} &euro;</label>
					</td>
				</tr>

			</table>
			<div class="tab-content">
				<div class="tab-pane {% if tab=='generale' or tab=='' %} active {% endif %}" id="generalSearch">
						<table class="row-fluid">
							<tr id="areas">
								<td >
									<input type='checkbox' id='area_1' name='area[1]' value="1" {% if get.area[1] %} checked {% endif %} />
									<label for='area_1'>{{ areas[0] }}</label>
								</td>
								<td >
									<input type='checkbox' id='area_2' name='area[2]' value="2" {% if get.area[2] %} checked {% endif %} />
									<label for='area_2'>{{ areas[1] }}</label>
								</td>

							</tr>
							<tr id="cities">
								<td >
									<input type='checkbox' id='area_3' name='area[3]' value="3" {% if get.area[3] %} checked {% endif %} />
									<label for='area_3'>{{ areas[2] }}</label>
								</td>
								<td >
									<input type='checkbox' id='area_4' name='area[4]' value="4" {% if get.area[4] %} checked {% endif %} />
									<label for='area_4'>{{ areas[3] }}</label>
								</td>


							</tr>
						</table>

				</div>
  				<div class="tab-pane {% if tab=='geographique'%} active {% endif %}" id="mapSearch">

				  	<table class="row-fluid">

				  		<tr>
				  		<td><input type='text' class="span11"name='address' id="address" value='{{get.address}}' placeholder= "{% if language=='en' %}Find an address{% else %}Rechercher une adresse{% endif %}" data-default="{% if language=='en' %}Find an address{% else %}Rechercher une adresse{% endif %}"/></td></tr>


				  	<tr><td>


				  <select name="quartier" id="quartiers" class="span11"><option value=''>{% if language=='en' %}Neighborhood{% else %}Quartier{% endif %}</option>
				  			{% for quartier in quartiers %}
				  			<option value='{{quartier.id}}' {% if get.quartier==quartier.id %}selected{% endif %}>{{quartier.nom_quartier}}</option>
				  			{% endfor %}
				  		</select></td></tr>

				  		<tr><td>


				  	<select name="rayon" class="span11"><option value=''>{% if language=='en' %}Distance range{% else %}Rayon{% endif %}</option>
				  			<option value="1" {% if get.rayon==1 %} selected {% endif %} >1km</option>
				  			<option value="5" {% if get.rayon==5 %} selected {% endif %} >5 km</option>
				  			<option value="10" {% if get.rayon==10 %} selected {% endif %} >10 km</option>
				  			<option value="20" {% if get.rayon==20 %} selected {% endif %} >20 km</option>
				  			<option value="50" {% if get.rayon==50 %} selected {% endif %} >50 km</option>
				  		</select></td></tr>
				  </table>



				  </div>
			</div>

			<table class="row-fluid submit" style='background:none'>

				<tr>
					<td style='border-right:0;' class="ref" >
						<input  type='text' name='ref' id='ref' class="span11" placeholder="{% if language=='en' %}Or fill in a reference{% else %}Ou entrez ici une référence{% endif %}" value="{% if get.ref %}030/{{get.ref}} {% else %}{% if language=='en' %}Or fill in a reference{% else %}Ou entrez ici une référence{% endif %}{% endif %}" rel='{% if language=='en' %}Or fill in a reference{% else %}Ou entrez ici une référence{% endif %}' onclick="initRefSearch(this)"  />
					</td>
					<td >
						<a href='#' onclick="$('#searchStart').val(0);searchItem()" class='ref'>
							{% if language=='en' %}GO{% else %}LANCER{% endif %}
							 <i class="icon  icon-caret-right" style="font-size:16px"></i>
						</a>
					</td>
				</tr>

			</table>
		</div>
	</form>
</div>
