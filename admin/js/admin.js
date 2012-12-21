$(document).ready(function() {
	$('#fileInput').uploadify({
		'uploader'  : '/admin/js/uploadify.swf',
		'script'    : '/admin/js/uploadify.php',
		'cancelImg' : '/admin/css/cancel.png',
		'multi'          : true,
		'auto'      : true,
		'folder'    : '../photos/big',
		'scriptData' : {'item_id': $('#num').val()},
		'fileExt' : '*.jpg;*.jpeg;*.png;*.gif;*tif;*.tiff',
		'fileDesc' : 'images',
		'onComplete': function(event, queueID, fileObj, reposnse, data) {
			$('#thumbs').append("<li><img src='"+fileObj.filePath+"'/>"+fileObj.filePath+"</li>");
			 
		},
		'onAllComplete':function() {
			var ranks=Array();
			$('#thumbs').children().each(function() {
				var t=$(this).find('img').attr('src').replace('/photos/thumbs/','');
				t=t.replace('/photos/big/','');
				ranks.push($t);	
			});
			$('#ranks').val(ranks.join(','));
			document.theForm.submit();
		}
	});
	
	$('#thumbs').sortable({
		placeholder: 'ui-state-highlight',
		update: function(event, ui) { 
			var ranks=Array();
			$(this).children().each(function() {
				ranks.push($(this).find('img').attr('src').replace('/photos/thumbs/',''));	
			});
			$('#ranks').val(ranks.join(','));
			
		}
	
	});
	
	
	$('button').button();
});



function deleteUser(userId) {
	if(userId>0) {
		if(confirm("Etes-vous sur? Cette action ne pourra etre annulee.")) {
			document.location="index.php?kind=users&deleteId="+userId;
		}
	}
}