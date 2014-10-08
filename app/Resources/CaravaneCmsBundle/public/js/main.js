
	$('#main_menu nav li a[rel="search"]').click(function(e) {
		e.preventDefault();
		$('#search').toggle();
		//$('body').css('margin-top',$('header').height()+"px");
	});


	$(document).ready(function() {
		searchType=$("div#caravane_bundle_estatebundle_search_location input");
		if(searchType.val()==0) {
				$("#caravane_bundle_estatebundle_search_prix input[value^=rent]").closest('.btn').hide();
				$("#caravane_bundle_estatebundle_search_prix input[value^=sale]").closest('.btn').show();
			}
			else {
				$("#caravane_bundle_estatebundle_search_prix input[value^=sale]").closest('.btn').hide();
				$("#caravane_bundle_estatebundle_search_prix input[value^=rent]").closest('.btn').show();
			}
				
		searchType.change(function() {
			if($(this).val()==0) {
				$("#caravane_bundle_estatebundle_search_prix input[value^=rent]").closest('.btn').hide();
				$("#caravane_bundle_estatebundle_search_prix input[value^=sale]").closest('.btn').show();
			}
			else {
				$("#caravane_bundle_estatebundle_search_prix input[value^=sale]").closest('.btn').hide();
				$("#caravane_bundle_estatebundle_search_prix input[value^=rent]").closest('.btn').show();
			}
			
			//alert($("input[name='caravane_bundle_estatebundle_search[location][]']").val())
		});

	});

	/*$('#main_menu').on('affix-top.bs.affix', function() {
		console.log($('header').height());
		$('body').css({
			paddingTop: $('header').height()
		});
	});*/