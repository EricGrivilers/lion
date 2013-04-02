// JavaScript Document
//jBGallery.Loading();

$(document).ready(function() {

   $("#types").buttonset();
	$("#areas").buttonset();
	$("#cities").buttonset();
	$(".sale").buttonset();
	$(".rent").buttonset();
	$("#radio").buttonset();;
	$('.pictThumb').each(function() {
		//$(this).css('margin-top',(138-$(this).height())/2);
	});

	$('.itemThumb').mouseover(function() {
		$(this).find('.more').show();
		$(this).find('.descro').hide();
	});
	$('.itemThumb').mouseout(function() {
		$(this).find('.more').hide();
		$(this).find('.descro').show();
	});
	$('.itemThumb').click(function() {
		$('#ref').val($(this).attr('rel'));
		$('#searchForm').submit();
	});

	$('.moreLink').click(function() {
		$('#ref').val($(this).closest('.itemThumb').attr('rel'));
		$('#searchForm').submit();
	});


	/*
	$('#map_tab').hide();

	$('#tabs ul li a').click(function() {
			var div=$(this).attr('href');
			$('.tabDiv').hide();
			$('#tabs ul li').removeClass('current');
			$(div).show();
			$(this).closest('li').addClass('current');
			return false;
	});
	*/

	$("input[name$='orderBy']").click(function() {
		$('#orderBy').val($(this).val());
		searchItem();
	});

	$("select[name$='orderBy']").change(function() {
		$('#orderBy').val($(this).val());
		searchItem();
	});



	$("select[name$='limitBy']").change(function() {
		$('#limitBy').val($(this).val());
		searchItem();
	});



	$("label.ui-state-active").each(function() {
		 $(this).closest('td').addClass('active');
	});


	$("#searchBlock table").delegate("label", "click", function(){
	  if($(this).hasClass('ui-state-active')) {
		  $(this).closest('td').addClass('active');
	  }
	  else {
		  $(this).closest('td').removeClass('active');
	  }

	});

	$('ul.nav-tabs a').click(function() {
		$('.nav-tabs li').removeClass('active');
		$('.tab-pane').removeClass('active');
		$($(this).attr('href')).addClass('active');
		$(this).closest('li').addClass('active');
		if($('#generalSearch').hasClass('active')) {
			$('#address').val('');
			$('#quartiers').val('');
			$('#searchForm').removeClass('geographic');
		}
		else {
			$('#areas input').attr('checked','');
			$('#cities input').attr('checked','');
			$('#searchForm').addClass('geographic');
		}
	});



	$('a.button').button();









});



function googleTranslateElementInit() {
  new google.translate.TranslateElement({
    pageLanguage: 'fr',
    includedLanguages: 'de,en,es,it,nl,ru',
    autoDisplay: false,
    multilanguagePage: true,
    gaTrack: true,
    layout: google.translate.TranslateElement.InlineLayout.SIMPLE
  }, 'header');
}



function changeSearchType() {

	$('.sale').toggle();
	$('.rent').toggle();
	var i=$('#radio label[aria-pressed=true]').attr('for');
	//alert(i);
	$('#radio input').removeAttr('checked');
	$('#'+i).attr('checked','checked');
	// alert($('#'+i).val());
	$('#searchType').val($('#'+i).val());
	/*
	//$(".collapsible").show();
	if($('#searchTypeSale').attr('checked')) {
		$('#searchTypeRent').attr('checked',false);
		$('.rent :input').attr('checked',false);
		$('#searchTypeRent').removeAttr('checked');
		$('.rent label').removeClass('ui-state-active');
		$('.rent td').removeClass('active');
	}
	else {
		$('#searchTypeSale').attr('checked',false);
		$('.sale :input').attr('checked',false);
		$('#searchTypeSale').removeAttr('checked');
		$('.sale label').removeClass('ui-state-active');
		$('.sale td').removeClass('active');
	}
	*/

}

function login() {
	datas=postDatas($('#loginForm :input'));
	var el=$('#loginForm').closest('.element');
	$.post("/index.php?pageId="+$('#pageId').val(),{"jfunc":"user.loginForm","datas":datas},function(data) {
		el.html(data);
	});
}


function searchItem() {
	var t=$("input[name$='searchType']:checked").attr('value');

	if(t=='sale') {
		t='vente';
	}
	else {
		t='location';
	}
	$('#searchForm').attr('action',"/"+t);
	$('#ref').val($('#ref').val().replace('030/',''));


	if($('#ref').val()==$('#ref').attr('rel')) {$('#ref').remove()}
	if($('#ref').val()>0) {

		//document.location="http://www.immo-lelion.be/"+$('#ref').val();
		document.location="/"+$('#ref').val();

	}
	else {
	$('#searchForm').submit();
	}

}


function goTo(target) {
	if(target=='prev') {
		$('#searchStart').val(Number($('#listStart').val()-$('#limitBy').val())+1);
	}
	if(target=='first') {
		$('#searchStart').val(0);
	}
	if(target=='next') {
		$('#searchStart').val(Number($('#listStart').val())+Number($('#limitBy').val())+1);
	}
	if(target=='last') {
		$('#searchStart').val(Number($('#maxItems').val())-Number($('#limitBy').val())+1);
	}
	if(target>=-20) {
		$('#searchStart').val(Number(target));
	}
	$('#ref').val('');
	 searchItem();

}

function initRefSearch(o) {
	if($(o).val()==$(o).attr('rel')) {


		//$(o).val('030/');
		$(o).val('');
		setTimeout(function(){$(o).val('030/');}, 100);

	}
}

function resetField(o) {
	if($(o).val()==$(o).attr('rel')) {
		if($(o).attr('name')=='password') {
			//$(o).attr('type','password');

			$(o).after("<input type='password' name='password' id='password' class='span5'/>");
			$(o).remove();
			$('#password').focus();
			//document.getElementByid('ipassword').setAttribute('type','password');
		}
		$(o).val('');

	}
}


function contactMe(ref) {
	window.location="/contact?ref="+ref;
}

function submitContactForm() {
	//alert('ret');
	datas=postDatas($('#contactForm :input'));
	var el=$('#contactForm').closest('.element');
	$.post("/index.php?pageId="+$('#pageId').val(),{"jfunc":"contact.contactForm","datas":datas},function(data) {
		el.html(data);
	});
}


function submitEstimationForm() {
	//alert('ret');
	datas=postDatas($('#contactForm :input'));
	var el=$('#contactForm').closest('.element');
	$.post("/index.php?pageId="+$('#pageId').val(),{"jfunc":"contact.contactForm","estimation":"true","datas":datas},function(data) {
		el.html(data);
	});
}



function submitRegisterForm() {
	//alert('ret');
	datas=postDatas($('#registerForm :input'));
	var el=$('#registerForm').closest('.element');
	$.post("/index.php?pageId="+$('#pageId').val(),{"jfunc":"user.register","datas":datas},function(data) {
		el.html(data);
	});
}

function postDatas(fields) {
	var datas={};
	fields.each(function() {
		datas[this.name] = $(this).val();
		if($(this).attr('type')=='checkbox') {
			if($(this).attr('checked')) {
				datas[this.name] = 1;
			}
			else {
				datas[this.name] = 0;
			}

		}

	});
	return datas;
}



function saveMe() {
	var itemId=$('#num').val();
	$.post("/index.php?pageId="+$('#pageId').val(),{"jfunc":"item.saveItem","itemId":itemId},function(data) {
			alert(data);
	});
}

function removeFromUser(itemId) {
	$.post("/index.php?pageId="+$('#pageId').val(),{"jfunc":"item.removeFromUser","itemId":itemId},function(data) {
			alert(data);
			$('#i'+itemId).remove();
	});
}

function translate(language) {
	//alert(language);
	var text=$("#origLang").val();
	text=text.substr(0,1200);
    google.language.detect(text, function(result) {
        if (!result.error && result.language) {
          google.language.translate(text, result.language, language, function(result) {
            if (result.translation) {
				$("#text").html(result.translation);
            }
         });
       }
   });

}
