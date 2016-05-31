var Simwp = (function($){
	var simwp = {};

	simwp.locale = 'en';
	// ajax options
	simwp.set = function(key, val){
		$.ajax({
			url : '.',
			data : {
				key : val
			},
			method : 'POST',
			headers: {
	            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	        }
		});
	};

	simwp.trigger = function(action, options){
		$(document).trigger(action, options);
	};

	simwp.bind = function(action, fn){
		$(document).on(action, fn);
	};

	simwp.view = {};

	simwp.view.noticeRemove = function(s){
		$(s).on('click', function removeNotice(){
			simwp.set('---simwp-removed-notices', this.id.replace('simwp-notice-', ''));
			siwmp.trigger('simwp_notice_removed', [this]);
		});
	};

	simwp.view.lineRemoveButton = function(s){
		$(s).on('click', function removeLine(){
			$(this).closest('tr').remove();
		});
	};

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
		var button = $('<button>', {type : 'button' , class : 'delete' }).text('x');

		simwp.view.lineRemoveButton(button);

		$('<td>').append(button).appendTo(row);
		row.insertBefore(currentRow);

		currentRow.closest('table');

		simwp.trigger('simwp_line_added', [row, this]);
	}

	simwp.view.lineAddInput = function(s){
		$(s).on('keydown', function enter(e){
			var code = e.code || e.which;
			if(code === 13){
				e.preventDefault();
				addLine.apply(this);
			}
		});
	};

	simwp.view.lineAddButton = function(s){
		$(s).click(function addLineButton(){
			var id = this.id.replace('simwp-input-lines-button-', '');
			addLine.apply($('#simwp-input-lines-edit-' + id)[0]);
		});
	};

	simwp.view.imagePicker = function(s){
		$(s).click(function pickImage(e){
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
				// src, target
				simwp.trigger('simwp_image_selected', [image_url, parent]);
			}).open();
		});
	};

	simwp.view.imageRemove = function(s){
		$(s).click(function removeImage(){
			var img = $(this).parent().children('img');
			img.attr('src', '');
			img.attr('src', '//placehold.it/' + img.width() + 'x' + img.height() + '/ddd/fdfdfd');
			$(this).parent().children('input').val('');
			// target
			simwp.trigger('simwp_image_removed', [parent]);
		});
	};

	simwp.view.tags = function(s){
		if($.fn.tagit){
			$(s).tagit();

			if($('.simwp-material-ui').length > 0){
				$('.tagit').children().children('input').on('focusin', function(){
					$(this).parent().parent().addClass('tagit-focus');
				}).on('focusout', function(){
					$(this).parent().parent().removeClass('tagit-focus');
				});
			}
		}
	};

	simwp.view.colorPicker = function(s){
		if($.fn.wpColorPicker){
			$(s).wpColorPicker();
		}
	};

	simwp.view.datePicker = function(s){
		if($.datepicker){
			$(s).datepicker({dateFormat : 'yy-mm-dd'}, $.datepicker.regional[simwp.locale]);
		}
	};

	// Install components
	$(function(){
		var bodyLocale = $('body').attr('class').match(/locale-(\w{2})/);

		if(bodyLocale){
			simwp.locale = bodyLocale[1];
		}

		simwp.view.noticeRemove('.notice.is-removable');

		simwp.view.colorPicker('.simwp-color-field');

		simwp.view.tags('.simwp-tags');

		simwp.view.datePicker('.simwp-date-field');

		$('#ui-datepicker-div').addClass('ll-skin-melon');

		var images = $('.simwp-input-image');

		simwp.view.imagePicker(images.children('button.add'));
		simwp.view.imageRemove(images.children('button.delete'));

		simwp.view.lineRemoveButton($('.simwp-input-lines').find('button.delete'));
		simwp.view.lineAddInput('.simwp-input-lines-edit');
		simwp.view.lineAddButton('.simwp-input-lines-button');
	});

	return simwp;
})(jQuery);
