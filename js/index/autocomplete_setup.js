$(document).ready(function() {
		$('input:radio[name=table_choice][value=1]').prop("checked", true);
		console.log("DOM is ready.");
		var first_name = '';
		var last_name = '';
		var phone = '';
		var diagosis ='';
		var options = {
		url: function (phrase) {
			phrase = encodeURIComponent(phrase);
			console.log(phrase);
			return '/search.php?query=' + phrase;
			
		},
		getValue: function (element) { first_name = $(element).prop("first_name"); last_name=$(element).prop("last_name"); phone=$(element).prop("phone"); return first_name+" "+last_name;},
		requestDelay: 500,
		list: {
			maxNumberOfElements: 20,
			showAnimation: {
				type: "normal",
				time: 500,
				callback: function() {}
			},
			hideAnimation: {
				type: "fade",
				time: 400,
				callback: function() {}
			},
			onClickEvent: function() {
				var val = $("#data-remote").getSelectedItemData().client_id;
				window.location.href = 'info.php?id='+val;
			}
		},
		template: {
			type: "custom",
			method: function(value,item) {
				return "<p>Full Name:"+first_name+" "+last_name+"</p><p>Phone :"+" "+phone+"</p>";
			}
		}
		};
		$('#data-remote').easyAutocomplete(options);
		$('input:radio[name=table_choice]').change(function() {
		
        if (this.value == '2') {
		console.log("hey");
        var modeoptions = {
		url: function (phrase) {
			phrase = encodeURIComponent(phrase);
			return '/search.php?query=' + phrase + '&mode=1';
			
		},
		getValue: function (element) { first_name=$(element).prop("first_name"); last_name=$(element).prop("last_name"); diagnosis=$(element).prop("diagnosis"); return first_name+" "+last_name;},
		requestDelay: 500,
		list: {
			maxNumberOfElements: 20,
			showAnimation: {
				type: "normal",
				time: 500,
				callback: function() {}
			},
			hideAnimation: {
				type: "fade",
				time: 400,
				callback: function() {}
			},
			onClickEvent: function() {
				var val = $("#data-remote").getSelectedItemData().visit_id;
				window.location.href = 'visit.php?id='+val;
			}
		},
		template: {
			type: "custom",
			method: function(value,item) {
				return "<p>Full Name :"+ " " +first_name+ " " +last_name+"</p><p>Diagnosis :"+" "+diagnosis+"</p><p>Date :"+" "+item.date+"</p>";
			}
		}
		}
		} else {
		modeoptions = options;
		}
		$('#data-remote').easyAutocomplete(modeoptions);
    });
});