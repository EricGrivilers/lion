
	$('#main_menu nav li a[rel="search"]').click(function(e) {
		e.preventDefault();
		$('#search').toggle();
		//$('body').css('margin-top',$('header').height()+"px");
	});


	$(document).ready(function() {
		searchType=$("#caravane_bundle_estatebundle_search #form_location input");
		if(searchType.val()==0) {
				$("#caravane_bundle_estatebundle_search #form_prix input[value^=rent]").closest('.btn').hide();
				$("#caravane_bundle_estatebundle_search #form_prix input[value^=sale]").closest('.btn').show();
			}
			else {
				$("#caravane_bundle_estatebundle_search #form_prix input[value^=sale]").closest('.btn').hide();
				$("#caravane_bundle_estatebundle_search #form_prix input[value^=rent]").closest('.btn').show();
			}

		searchType.change(function() {
			$('#caravane_bundle_estatebundle_search #form_prix input').attr('checked',false);
			$('#caravane_bundle_estatebundle_search #form_prix label').removeClass('active');
			if($(this).val()==0) {
				$("#caravane_bundle_estatebundle_search #form_prix input[value^=rent]").closest('.btn').hide();
				$("#caravane_bundle_estatebundle_search #form_prix input[value^=sale]").closest('.btn').show();
			}
			else {
				$("#caravane_bundle_estatebundle_search #form_prix input[value^=sale]").closest('.btn').hide();
				$("#caravane_bundle_estatebundle_search #form_prix input[value^=rent]").closest('.btn').show();
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


	$('#nextEstate').click(function() {

		offset=$("form#caravane_bundle_estatebundle_search input#form_offset").val();
		limit=$("form#caravane_bundle_estatebundle_search input#form_limit").val();
		if(offset==undefined) {
			offset=0;
		}
		$("form#caravane_bundle_estatebundle_search input#form_offset").val(parseInt(offset)+parseInt(limit));
		var postData = $('form#caravane_bundle_estatebundle_search').serializeArray();
		var formURL = $('form#caravane_bundle_estatebundle_search').attr("action");

		$.ajax(
		    {
		        url : formURL,
		        type: "POST",
		        data : postData,
		        success:function(data, textStatus, jqXHR)
		        {
		            //data: return data from server
		            html=$(data).find('#list')
		            $('#list').append(html.html());
		        },
		        error: function(jqXHR, textStatus, errorThrown)
		        {
		            //if fails
		            console.log(data);
		        }
		    });
	});







