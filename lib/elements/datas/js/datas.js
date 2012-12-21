$(document).ready(function() {
	$.datas.init();
});

$.datas.init=function() {
	$('thead th').each(function() {
		
		$(this).click(function() {
			$.datas.reorderDatas($(this));
		});
	});
}

$.datas.reorderDatas=function(obj) {
	var el=$(obj).closest('.element');
	var orderByField=el.find('.orderBy');
	var orderSortField=el.find('.orderSort');
	if(orderByField.val()==$(obj).attr('rel')) {
		if(orderSortField.val()=='ASC') {
			orderSortField.val('DESC');
		}
		else {
			orderSortField.val('ASC');
		}
	}
	orderByField.val($(obj).attr('rel'));
	$.datas.post("/index.php?pageId="+$('#pageId').val(),{'jfunc':el.attr('rel')+'.display','limitStart':$('#limitStart').val(),'orderBy':orderByField.val(),'orderSort':orderSortField.val()},function(data) {
		el.html(data);	
		$.datas.init();
	});
	
}

$.datas.editContact=function(contactId) {
	alert(contactId);	
}

$.datas.jumpToPage=function(obj,l) {
	
	nPage=$(obj).closest('.nextback').find('.limitStart').val();
	$.datas.nextDatas(obj,0,nPage,l);
	
	
}

$.datas.nextDatas=function(obj,d,s,l) {
	var el=$(obj).closest('.element');
	var orderByField=el.find('.orderBy');
	var orderSortField=el.find('.orderSort');
	if(d>0) {
		ls=Number(s+l);
	}
	else if(d<0){
		ls=Number(s-l);	
	}
	else{
		ls=s*l-l;	
	}
	$.datas.post("/index.php?pageId="+$('#pageId').val(),{'jfunc':el.attr('rel')+'.display','limitStart':ls,'orderBy':orderByField.val(),'orderSort':orderSortField.val()},function(data) {
		el.html(data);	
		$.datas.init();
	});
}