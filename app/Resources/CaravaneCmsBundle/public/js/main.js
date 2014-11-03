var markers;
var type;

function initializeSearchMap() {
		    var mapOptions = {
		      center: new google.maps.LatLng(50.833555,4.39552),
		      zoom: 13
		    };
		    var map = new google.maps.Map(document.getElementById("search_map"), mapOptions);
		   i=0;
		   $.each(markers,function(a,b) {

				/*marker = new google.maps.Marker({
			        position: new google.maps.LatLng(b.lat, b.lng),
			        map: map
			      });
*/
			if(b.num>0) {
				var marker = new MarkerWithLabel({
				       position: new google.maps.LatLng(b.lat, b.lng),
				       map: map,
				        labelContent: b.num,
				       labelAnchor: new google.maps.Point(20, 40),
				       labelClass: "marker_label"
				});



				 google.maps.event.addListener(marker, 'click', (function(marker, i) {
					return function() {
						$('#form_container form')[0].reset();
						$('#form_container form #search_form_area').val(markers[i].id);
						if(type=='new') {
							$('#form_container form #search_form_isNewBuilding').attr('checked',true);
						}
						$('#form_container form')[0].submit();
						//route=Routing.generate('caravane_estate_frontend_estate_search_by_area',{'type':type,'id': markers[i].id});
						//document.location=route;
					}
				}) (marker, i));
			}

			i++;


			});

	}



	$('#main_menu nav li a.search').click(function(e) {
		e.preventDefault();
		e.stopPropagation();
		type=$(this).attr('rel');
		isLocation=(type=='sale'?0:1);

		if($('#search_form_location').val()==isLocation) {
			$('#search').show();
			$('.navbar-collapse').hide();
			route2=Routing.generate('caravane_estate_frontend_estate_search_count_by_area',{'type':type});
				$.get(route2, function(data) {
					markers=data;
					initializeSearchMap();
					google.maps.event.addDomListener(window, 'load', initializeSearchMap);

				})
		}
		else {
			$('#main_menu nav li ').removeClass('active');
			$('#main_menu nav li a.search[rel="'+type+'"]').closest('li').addClass('active');





			route=Routing.generate('caravane_estate_frontend_estate_search_from',{'type':type});

			$.get(route, function(data) {
				$('#form_container').html(data);
				$('#search').show();
				$('.navbar-collapse').hide();
				route2=Routing.generate('caravane_estate_frontend_estate_search_count_by_area',{'type':type});
				$.get(route2, function(data) {
					markers=data;
					initializeSearchMap();
					google.maps.event.addDomListener(window, 'load', initializeSearchMap);

				})
			})


		}

		/*if($(this).hasClass('rent')) {
			$("#caravane_bundle_estatebundle_search #form_location input#form_location_0").attr('checked','');
			$("#caravane_bundle_estatebundle_search #form_location label:eq(0)").removeClass('active');
			$("#caravane_bundle_estatebundle_search #form_location input#form_location_1").attr('checked','checked');
			$("#caravane_bundle_estatebundle_search #form_location label:eq(1)").addClass('active');
			$("#caravane_bundle_estatebundle_search #form_prix input[value^=sale]").closest('.btn').hide();
				$("#caravane_bundle_estatebundle_search #form_prix input[value^=rent]").closest('.btn').show();
		}
	*/

	});




	$(document).ready(function() {
			$(".ellipsis").dotdotdot({
			//	configuration goes here
			});
		/*searchType=$("#caravane_bundle_estatebundle_search #form_location input");
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
		});
	*/
		prepareLinks();
		$(".carousel").swiperight(function() {
    		$(this).carousel('prev');
	    });
		$(".carousel").swipeleft(function() {
		    $(this).carousel('next');
		});
		$('.maps').click(function () {
		    $('.maps iframe').css("pointer-events", "auto");
		});


	});

	$('#estate_last_updated').on('slid.bs.carousel', function () {
	    $(".ellipsis").dotdotdot();
	});



	$('#form_sort').change(function() {
		$("form#caravane_bundle_estatebundle_search input#search_form_offset").val(0);
	});

	$('#nextEstate').click(function(e) {
		e.stopPropagation();
		e.preventDefault();
		offset=$("form#caravane_bundle_estatebundle_search input#search_form_offset").val();
		limit=$("form#caravane_bundle_estatebundle_search input#search_form_limit").val();
		if(offset==undefined) {
			offset=0;
		}
		$("form#caravane_bundle_estatebundle_search input#search_form_offset").val(parseInt(offset)+parseInt(limit));
		var postData = $('form#caravane_bundle_estatebundle_search').serializeArray();
		var formURL = $('form#caravane_bundle_estatebundle_search').attr("action");

		$.ajax(
		    {
		        url : formURL,
		        type: "POST",
		        data : postData,
		        success:function(data, textStatus, jqXHR)
		        {
		        	if(data=="end") {
		        		$('#nextEstate').hide();
		        	}
		            //data: return data from server
		            html=$(data).find('.list:last')
		            $('.list:last').append(html.html());
		            prepareLinks();
		        },
		        error: function(jqXHR, textStatus, errorThrown)
		        {
		            //if fails
		            console.log(data);
		        }
		    });
	});


	$('#nextEstate').appear();
	$(document.body).on('appear', '#nextEstate', function(e, $affected) {
	    $("#nextEstate").click();
	  });


	function prepareLinks() {


		$('.list div.estate a.estate').bind();
		$('.list div.estate a.estate').click(function(e) {


			$('.row.detail').remove();
			e.preventDefault();
			e.stopPropagation();
			list=$(this).closest('.list');
			element=$(this).closest('div.estate');
			list=element.closest('.list');
			containerWidth=list.width();
			elementWidth=element.width();
			itemPerRow=parseInt(containerWidth/elementWidth);
			index=element.index();
			row=((index - (index)%itemPerRow)/itemPerRow)+1;
			newIndex=(row*itemPerRow)-1;
			lastElement=list.find("div.estate:eq('"+newIndex+"')");
			if(lastElement.length==0) {
				//console.log('no end');
				while(list.find("div.estate:eq('"+newIndex+"')").length==0) {
					newIndex--;
				}
				lastElement=list.find("div.estate:eq('"+newIndex+"')");
			}
			document.location.hash=$(this).attr('href');
			$.ajax(
		    		{
		        url : $(this).attr('href'),
		        type: "GET",
		        success:function(data, textStatus, jqXHR)
		        {
		        	if(list.find('#detail').size()==0) {
		        		list.append("<div id='detail' class='col-md-12'></div>");
		        	}
		        	detail=list.find('div#detail');
		        	//detail.hide();

		            lastElement.after(detail);
		            detail.html(data);
		            $('a.addToFavorite').click(function(e) {
		            	favBut=$(this);
					e.preventDefault();
					e.stopPropagation();
					$.get($(this).attr('href'),function(data) {
						if(data=='success') {
							if( element.find('span.favorite').length<=0 ) {
								element.prepend('<span class="favorite"><span class="fa fa-star"></span></span>');
							}
							element.find('span.favorite').toggle();
							element.find('a.estate').click();

						}
						else if(data=="no user") {
							alert("Vous devez être enregistré pour utiliser cette option");
						}
					});
				});
		            $('.maps').click(function () {
				    $('.maps iframe').css("pointer-events", "auto");
				});
		          // $('body').scrollTo(element);
		            //detail.slideDown();
		        },
		        error: function(jqXHR, textStatus, errorThrown)
		        {
		            //if fails
		            console.log(textStatus);
		        }
		    });
		});
	}



$.fn.scrollTo = function( target, options, callback ){
  if(typeof options == 'function' && arguments.length == 2){ callback = options; options = target; }
  var settings = $.extend({
    scrollTarget  : target,
    offsetTop     : 50,
    duration      : 500,
    easing        : 'swing'
  }, options);
  return this.each(function(){
    var scrollPane = $(this);
    var scrollTarget = (typeof settings.scrollTarget == "number") ? settings.scrollTarget : $(settings.scrollTarget);
    var scrollY = (typeof scrollTarget == "number") ? scrollTarget : scrollTarget.offset().top + scrollPane.scrollTop() - parseInt(settings.offsetTop);
    scrollPane.animate({scrollTop : scrollY }, parseInt(settings.duration), settings.easing, function(){
      if (typeof callback == 'function') { callback.call(this); }
    });
  });
}

