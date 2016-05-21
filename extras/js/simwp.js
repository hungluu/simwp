!function($){
	var locale = $('body').attr('class').match(/locale-(\w{2})/);

	if(locale){
		locale = locale[1];
	}

	function removeNotice(){
		var _this = this;

		$.ajax({
			url : '.',
			data : {
				'---simwp-removed-notices' : this.id.replace('simwp-notice-', '')
			},
			method : 'POST',
			headers: {
	            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	        }
		});
	}

	function removeLine(){
		$(this).closest('tr').remove();
	}

	function addLine(){
		// test trim
		if(this.value.replace(/^\s+|\s+$/g, '') === '') {
			return;
		}

		var id = this.id.replace('simwp-input-lines-edit-', '');
		var currentRow = $(this).parent().parent();
		// create new row
		var row = $('<tr>');
		// add label
		row.html('<td><input class="hidden" name="' + id + '[]" value="' + this.value + '" type="text" readonly> <label> ' + this.value + ' </label></td>');
		// reset input
		this.value = '';
		// add delete button
		var button = $('<button>', {type : 'button' , class : 'delete' }).text('x').click(removeLine);
		$('<td>').append(button).appendTo(row);
		row.insertBefore(currentRow);
	}

	function addLineButton(){
		var id = this.id.replace('simwp-input-lines-button-', '');
		addLine.apply($('#simwp-input-lines-edit-' + id)[0]);
	}

	function openWpImageSelector(e){
		var _this = this;
		e.preventDefault();
		var image = wp.media({
				title: 'Upload Image',
				// mutiple: true if you want to upload multiple files at once
				multiple: false
			}).on('select', function(e){
				// This will return the selected image from the Media Uploader, the result is an object
				var uploaded_image = image.state().get('selection').first();
				// We convert uploaded_image to a JSON object to make accessing it easier
				// Output to the console uploaded_image
				// console.log(uploaded_image);
				var image_url = uploaded_image.toJSON().url;
				// Let's assign the url value to the input field
				var parent = $(_this).parent();
				parent.children('img').attr('src', image_url);
				parent.children('input').val(image_url);
			}).open();
	}

	function removeImage(){
		var img = $(this).parent().children('img');
		img.attr('src', '');
		img.attr('src', '//placehold.it/' + img.width() + 'x' + img.height() + '/ddd/fdfdfd');
		$(this).parent().children('input').val('');
	}

	$(function(){
		$('.notice.is-removable').click(removeNotice);
		$('.simwp-color-field').wpColorPicker();
		$('.simwp-tags').tagit();
		$('.tagit').children().children('input').focusin(function(){
			$(this).parent().parent().addClass('tagit-focus');
		}).focusout(function(){
			$(this).parent().parent().removeClass('tagit-focus');
		});

		if(locale){
			$('.simwp-date-field').datepicker({dateFormat : 'yy-mm-dd'}, $.datepicker.regional[locale]);
		}
		else{
			$('.simwp-date-field').datepicker({dateFormat : 'yy-mm-dd'});
		}

		$('#ui-datepicker-div').addClass('ll-skin-melon');

		$('.simwp-input-image').children('button.add').click(openWpImageSelector);
		$('.simwp-input-image').children('button.delete').click(removeImage);

		$('.simwp-input-lines').find('button.delete').click(removeLine);
		$('.simwp-input-lines-edit').keydown(function enter(e){
			var code = e.code || e.which;
			if(code === 13){
				e.preventDefault();
				addLine.apply(this);
			}
		});
		$('.simwp-input-lines-button').click(addLineButton);
	});
}(jQuery);
