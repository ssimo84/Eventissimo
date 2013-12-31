jQuery(document).ready(function() {
	jQuery('#data_inizio,#data_fine,#untilRepeat').datepicker({
		closeText: objectL10n.closeText,
		currentText: objectL10n.currentText,
		monthNames: objectL10n.monthNames,
		monthNamesShort: objectL10n.monthNamesShort,
		dayNames: objectL10n.dayNames,
		dayNamesShort: objectL10n.dayNamesShort,
		dayNamesMin: objectL10n.dayNamesMin,
		dateFormat: objectL10n.dateFormat,
		isRTL: objectL10n.isRTL,
		altFormat: "yy-mm-dd",
		
		beforeShow: function(input, inst) {
			var dataInizio = jQuery("#data_inizio").datepicker('getDate');
			var dataFine = jQuery("#data_fine").datepicker('getDate');
			if ((jQuery(this).attr("id")=="data_fine") && (dataFine<dataInizio))
       			jQuery(this).datepicker("setDate", dataInizio);
		},
		onSelect: function(dateText, inst){
			var t = jQuery(this).datepicker('getDate');
			var id = jQuery(this).attr("name") + "_yy-mm-dd";
			jQuery("#" + id).val(jQuery.datepicker.formatDate('yy-mm-dd', t));
			viewRepeatForm();
		}
	});
	
	jQuery('#ora_inizio,#ora_fine').timepicker({
		interval: 30,
		timeFormat: objectL10n.timeFormat,
		scrollbar:true
	});
	
});