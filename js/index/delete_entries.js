var already_clicked =0;
if (set_mode == 0) {
	var search_arg = "a";
} else {
	var search_arg = "input[name='visit_id']";
}
function OnDelete(elem) {
	if (already_clicked ==0) {
	elem.classList.replace('is-info', 'is-warning');
	elem.innerHTML = 'Delete Mode: On';
	alert("You can now remove entries from the database. Proceed with caution!");
	jQuery('#patients_tb td:nth-child(1) '+search_arg).each(function(key, val) {
		jQuery(val).css('display', 'inline-block');
		var id="chickfilla_";
		if (set_mode ==0) {
			id += jQuery(val).text();
		} else {
			id += jQuery(val).val();
		}
		jQuery(val).after("<input type='checkbox' class='chickfilla' style='position: relative; display: inline-block; left: 10px;' id="+id+">");
	});
	jQuery('#SendServBtn').removeAttr("style");
	already_clicked = 1;
	} else {
	jQuery('.chickfilla').remove();
	elem.classList.replace('is-warning', 'is-info');
	elem.innerHTML = 'Delete Mode: Off';
	jQuery('#patients_tb td:nth-child(1) a').each(function(key,val) {
		jQuery(val).css('display', 'block');
	});
	jQuery('#SendServBtn').css("display", "none");
	already_clicked = 0;
	}
}

$(document).ready(function() {
	//Commit function.
	$('#SendServBtn').click(function() {
		var idee = '';
		var arr_ids = [];
		$('.chickfilla').each(function() { 
			if ($(this).is(':checked') === true) {
				idee = $(this).attr('id');
				idee = idee.replace("chickfilla_", "");
				arr_ids.push(idee);
			}
		});
		if (Array.isArray(arr_ids) && arr_ids.length) {
			if(confirm("Warning! DANGEROUS ACTION! Are you sure you want to delete IDs: "+arr_ids.toString()+" ?")) {
			$.ajax({ 
				url: '/classes/Delete.php',
				type: 'POST',
				data: {table: choosetable, ids_to_delete : arr_ids, token: csrftoken},
				dataType : 'JSON',
				success: function(response) {
					alert("The following IDs have been completely removed."+"\n"+response.id);
					window.location.href = window.location.href;
				}
			
			});
			}
		}
		
	});

});

