 $(function(){ // <-- Runs when DOM is ready.
	$("input[id^='task_'").each(function(index, element) { // <- Re-inputs fetched phone numbers to their fields. 
		$(element).val(phones[index]);
	});
	$("input[id^='owner_'").each(function(index, element) { // <- Re-inputs fetched owners to their fields.
		$(element).val(owners[index]);
	});
	var client_id = getUrlParameter('id'); //Get client ID from url. Not good practice. In future we will fetch client_id in a hidden field without requiring JS.
	
	$('#delete_r').click(function() { //<-- Runs when delete button is clicked.
		var arr_id = [];
		arr_id.push(client_id);
		if (confirm("WARNING! THIS ACTION CANNOT BE UNDONE. Proceed?")) {
		$.ajax({ 
		url: '/classes/Delete.php',
		type: 'POST',
		data: {table: "0", ids_to_delete : arr_id, token: csrftoken},
		dataType : 'JSON',
		success: function(response) {
				alert("The following IDs have been completely removed."+"\n"+response.id);
				window.location.href = "/index.php";
			}
			
		});
		}
		//END OF DELETE FUNCTION
	});
		
	// END OF DOCUMENT READY FUNCTION
});