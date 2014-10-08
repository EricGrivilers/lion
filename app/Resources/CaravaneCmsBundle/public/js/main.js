
	$('#main_menu nav li a[rel="search"]').click(function(e) {
		e.preventDefault();
		$('#search').toggle();
		//$('body').css('margin-top',$('header').height()+"px");
	});


	/*$('#main_menu').on('affix-top.bs.affix', function() {
		console.log($('header').height());
		$('body').css({
			paddingTop: $('header').height()
		});
	});*/