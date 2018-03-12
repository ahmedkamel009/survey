var surveyUl = ".survey-builder";
$(document).ready(function(){
    $(surveyUl).sortable({
        placeholder: "ui-state-highlight",
        handle: '.options-buttons .handle',
        update : function(){
		}
    });
	select2_init()
})
function select2_init(){
	$('.select2-tags').select2({
		tags: true,
		placeholder: "Enter Your inputs",
		tokenSeparators: [","]
	}).on('change', function() {
		var textBox = $(this).parent().find('[name="q_type_fields[]"]');
		var selected = $(this).find('option:selected')
		var selectedArr = [];    
		selected.each(function(k, v) {
			selectedArr.push($(v).text());
		});
		textBox.val(selectedArr.join(','))
	})

}
function addField(el){
	$.get('templates/question-wrapper.html', function(data) {
		if(el){
			$(data).insertAfter($(el).closest('li.row'));
		}else{
			$(surveyUl).append(data);
		}
		select2_init()
	});
}

function fieldTypeChange(el){
	var fieldTab = $(el).closest("li").find(".field-type-tab");
	$.get('templates/'+$(el).val()+'.html', function(data) {
		fieldTab.html(data);
	});
}

function questionTypeChange(el){
	var qTypeFields = $(el).parent();
	if($(el).val() == "open-text" || $(el).val() == "short-text" ){
		qTypeFields.find(".q_type_fields input").val("");
		qTypeFields.find(".q_type_fields").hide();
		qTypeFields.next().find('option[value="pie-chart"], option[value="bar-chart"]').addClass('hide');
		qTypeFields.next().find('select option:first-child').prop('selected', true)
	}else{
		qTypeFields.find(".q_type_fields").show();
		qTypeFields.next().find('option[value="pie-chart"], option[value="bar-chart"]').removeClass('hide');
	}
}

function summarizeCheckbox(el){
	if ($(el).is(':checked')) {
		$(el).parent().find("[name='summarize[]']").val('1');
		$(el).closest('div.field-type-tab').find(".chart-options select").show();
		questionTypeChange($(el).closest('div.field-type-tab').find('select[name="q_type[]"]'));
	}else{
		$(el).parent().find("[name='summarize[]']").val('0');
		$(el).closest('div.field-type-tab').find(".chart-options select").hide()
	}
}

function deleteField(el){
	$(el).closest("li").remove()
}
