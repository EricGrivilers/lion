<div class="row-fluid">


nav
</div>
<div class="row-fluid">



<div id="myCarousel" class="carousel slide">
  <!-- Carousel items -->
  <div class="carousel-inner">
  	{% for pict in picts %}
  	
  
  	
<div class="item {% if loop.index==1 %} active {% endif %}"><img src="http://www.immo-lelion.be/photos/big/{{pict['photos']}}" height="453"></div>
  	{% endfor %}

  </div>
  <!-- Carousel nav -->
  <a class="carousel-control left" href="#myCarousel" data-slide="prev">&lsaquo;</a>
  <a class="carousel-control right" href="#myCarousel" data-slide="next">&rsaquo;</a>
</div>


</div>
<div id="thumbs" class="row-fluid">
		{% for pict in picts %}
  	
  
  	
<div class="thumb {% if loop.index==1 %} active {% endif %}"><img src="http://www.immo-lelion.be/photos/thumbs/{{pict['photos']}}" height="453"></div>
  	{% endfor %}
</div>
<div class="row-fluid">
	<div class="span6">
		

		<div class="leftBlock"><li class="yellow">
			{% if item.surdemande=='Y' %}
			Prix sur demande
			{% else %}
			Prix: {{item.prix|number_format(0, ',', '.')}} &euro;
			{% endif %}
			
			{% if item.enoption=='Y' %}
			OPTION - Prix: {{item.prix|number_format(0, ',', '.')}} &euro;
			{% endif %}
			
			{% if item.vendu=='Y' %}
			VENDU

			{% endif %}
			Prix: 350.000 €</li>
			<li class="yellow">Référence du bien : {{item.reference}}</li>


			<p>
				{% if item.area %}
					Superficie: {{item.area}} m²<br>
				{% endif %}

				{% if item.rooms %}
					Chambre(s): {{item.rooms}} m²<br>
				{% endif %}

				{% if item.bathrooms %}
					Salle(s) d'eau: {{item.bathrooms}} m²<br>
				{% endif %}

				{% if item.garages %}
					Garage(s): {{item.garages}} m²<br>
				{% endif %}

				{% if item.garden %}
					Jardin: {{item.garden}} m²<br>
				{% endif %}



				



	</div>
</div>
	<div  class="span6">
<div id="rightBlock">
		<h3>{{item['locfr']}}</h3>
		
		<div id='text'>
		{{item['descrfr']}}
		</div>


		<script>
      function translateText(response) {
        document.getElementById("text").innerHTML += "<br>" + response.data.translations[0].translatedText;
      }
    </script>
    <script>
      var newScript = document.createElement('script');
      newScript.type = 'text/javascript';
      var sourceText = escape(document.getElementById("sourceText").innerHTML);
      // WARNING: be aware that YOUR-API-KEY inside html is viewable by all your users.
      // Restrict your key to designated domains or use a proxy to hide your key
      // to avoid misuage by other party.
      var source = 'https://www.googleapis.com/language/translate/v2?key=YOUR-API-KEY&source=en&target=de&callback=translateText&q=' + sourceText;
      newScript.src = source;

      // When we add this script to the head, the request is sent off.
      document.getElementsByTagName('head')[0].appendChild(newScript);
    </script>



		<div id='translation'>Translate in <select name='translateLanguageIso'  onchange='translate(this.value)'><option value=''>select language</option><option value='en'>ENGLISH</option><option value='nl'>DUTCH</option><option value='es'>SPANISH</option><option value='de'>GERMAN</option><option value='it'>ITALIAN</option><option value='ru'>RUSSIAN</option><option value=''>---</option><option value='af'>AFRIKAANS</option><option value='sq'>ALBANIAN</option><option value='am'>AMHARIC</option><option value='ar'>ARABIC</option><option value='hy'>ARMENIAN</option><option value='az'>AZERBAIJANI</option><option value='eu'>BASQUE</option><option value='be'>BELARUSIAN</option><option value='bn'>BENGALI</option><option value='bh'>BIHARI</option><option value='bg'>BULGARIAN</option><option value='my'>BURMESE</option><option value='ca'>CATALAN</option><option value='chr'>CHEROKEE</option><option value='zh'>CHINESE</option><option value='zh-CN'>CHINESE_SIMPLIFIED</option><option value='zh-TW'>CHINESE_TRADITIONAL</option><option value='hr'>CROATIAN</option><option value='cs'>CZECH</option><option value='da'>DANISH</option><option value='dv'>DHIVEHI</option><option value='eo'>ESPERANTO</option><option value='et'>ESTONIAN</option><option value='tl'>FILIPINO</option><option value='fi'>FINNISH</option><option value='fr'>FRENCH</option><option value='gl'>GALICIAN</option><option value='ka'>GEORGIAN</option><option value='el'>GREEK</option><option value='gn'>GUARANI</option><option value='gu'>GUJARATI</option><option value='iw'>HEBREW</option><option value='hi'>HINDI</option><option value='hu'>HUNGARIAN</option><option value='is'>ICELANDIC</option><option value='id'>INDONESIAN</option><option value='iu'>INUKTITUT</option><option value='ga'>IRISH</option><option value='ja'>JAPANESE</option><option value='kn'>KANNADA</option><option value='kk'>KAZAKH</option><option value='km'>KHMER</option><option value='ko'>KOREAN</option><option value='ku'>KURDISH</option><option value='ky'>KYRGYZ</option><option value='lo'>LAOTHIAN</option><option value='lv'>LATVIAN</option><option value='lt'>LITHUANIAN</option><option value='mk'>MACEDONIAN</option><option value='ms'>MALAY</option><option value='ml'>MALAYALAM</option><option value='mt'>MALTESE</option><option value='mr'>MARATHI</option><option value='mn'>MONGOLIAN</option><option value='ne'>NEPALI</option><option value='no'>NORWEGIAN</option><option value='or'>ORIYA</option><option value='ps'>PASHTO</option><option value='fa'>PERSIAN</option><option value='pl'>POLISH</option><option value='pt-PT'>PORTUGUESE</option><option value='pa'>PUNJABI</option><option value='ro'>ROMANIAN</option><option value='sa'>SANSKRIT</option><option value='sr'>SERBIAN</option><option value='sd'>SINDHI</option><option value='si'>SINHALESE</option><option value='sk'>SLOVAK</option><option value='sl'>SLOVENIAN</option><option value='sw'>SWAHILI</option><option value='sv'>SWEDISH</option><option value='tg'>TAJIK</option><option value='ta'>TAMIL</option><option value='tl'>TAGALOG</option><option value='te'>TELUGU</option><option value='th'>THAI</option><option value='bo'>TIBETAN</option><option value='tr'>TURKISH</option><option value='uk'>UKRAINIAN</option><option value='ur'>URDU</option><option value='uz'>UZBEK</option><option value='ug'>UIGHUR</option><option value='vi'>VIETNAMESE</option><option value='cy'>WELSH</option><option value='yi'>YIDDISH</option><option value=''>UNKNOWN</option></select></div><br/>
		<textarea id='origLang' style='display:none'>{{item['descrfr']}}</textarea>
	</div>
</div>
</div>