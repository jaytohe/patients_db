 $(function() {
	//On Submit Function. --Checks Validity of data input.
		$("#leform").submit(function( event ) {
		console.log("Processing Submit...");
		// Declare Variables
		var regex_date = /^([1-2][0-9]|(3)[0-1]|[1-9]|(0)[1-9])(\/)(((0)[1-9])|((1)[0-2])|[0-9])(\/)\d{4}$/;
		var date = $("#dt4").val(); //get date value
		var regex_phone = /^([(+]*[0-9]+[()+. -]*)$/; //same regex we use in search.php
		
		
		//Check if date is in correct format (DD/MM/YYYY) with regex.
		if(regex_date.test(date) == false) {
			console.log(date);
			alert("Invalid date. Correct format : DD/MM/YYYY"+"\nExample:  09/06/2019 or 9/6/2019");
			event.preventDefault();
		} else {console.log("Date is good.");}
		
		//Check if phone numbers match regex.
		$("[id^=task_]").each(function(index, elem) { //we use jquery's each() to iterate through multiple phone numbers.
			var phone = $(elem).val();
			console.log(phone);
			if((phone.length < 4) || !(phone).match(regex_phone)) {
				alert("Phone number "+(index+1)+" is not correct."+"\n"+"Please check the format or the length of the number.");
				event.preventDefault();
			} else {console.log("Phone number is good.");}
		});
 
 
	});
 
 });