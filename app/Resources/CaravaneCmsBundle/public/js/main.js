
	$('#main_menu nav li a.search').click(function(e) {
		e.preventDefault();
		e.stopPropagation();
		type=$(this).attr('rel');
		$('#main_menu nav li ').removeClass('active');
		$('#main_menu nav li a.search[rel="'+type+'"]').closest('li').addClass('active');
		route=Routing.generate('caravane_estate_frontend_estate_search_from',{'type':type});

		$.get(route, function(data) {
			$('#form_container').html(data);
			$('#search').show();
		$('.navbar-collapse').hide();
		})
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


	$('#form_sort').change(function() {
		$("form#caravane_bundle_estatebundle_search input#form_offset").val(0);
	});

	$('#nextEstate').click(function(e) {
		e.stopPropagation();
		e.preventDefault();
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
		        	if(data=="end") {
		        		$('#nextEstate').hide();
		        	}
		            //data: return data from server
		            html=$(data).find('#list')
		            $('#list').append(html.html());
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


		$('#list div.estate a.estate').bind();
		$('#list div.estate a.estate').click(function(e) {


			$('.row.detail').remove();
			e.preventDefault();
			e.stopPropagation();
			list=$(this).closest('#list');
			element=$(this).closest('div.estate');
			containerWidth=list.width();
			elementWidth=element.width();
			itemPerRow=parseInt(containerWidth/elementWidth);
			index=element.index();
			row=((index - (index)%itemPerRow)/itemPerRow)+1;
			newIndex=(row*itemPerRow)-1;
			lastElement=$("#list div.estate:eq('"+newIndex+"')");
			if(lastElement.length==0) {
				console.log('no end');
				while($("#list div.estate:eq('"+newIndex+"')").length==0) {
					newIndex--;
				}
				lastElement=$("#list div.estate:eq('"+newIndex+"')");
			}

			$.ajax(
		    {
		        url : $(this).attr('href'),
		        type: "GET",
		        success:function(data, textStatus, jqXHR)
		        {
		        	if($('#list #detail').size()==0) {
		        		$('#list').append("<div id='detail' class='col-md-12'></div>");
		        	}
		        	detail=$('#list div#detail');
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
		            console.log(data);
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

