 $(function(){
	 //Repeat phone number fields -- Repeatable.js script
	 $(".phone_nums .repeatable").repeatable({
	 addTrigger: ".phone_nums .add",
	 deleteTrigger: ".phone_nums .delete",
	 template: "#phone_nums",
	 min: min_phone,
	 max: 10
	});
});