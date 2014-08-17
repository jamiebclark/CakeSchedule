(function($) {
	$.fn.schedulerTasksListItem = function() {
		return this.each(function(i) {
			var $this = $(this),
				$input = $(':input', $this),
				$list = $this.closest('.scheduler-tasks-list'),
				$checkbox = $('[type="checkbox"]', $this).first(),
				$text = $('.scheduler-tasks-list-item-input', $this),
				$options = $('.scheduler-tasks-list-item-options', $this).schedulerTasksListItemOptions(),
				textResizeTimeout;

			$(':input', $this).not('.scheduler-tasks-list-item-input').attr('tabindex', '-1');

			function clone (pos, insertText) {
				var $new = $this.clone(),
					match = /^(data\[[^\]]+\]\[)([\d]*)(.*)/,
					listCount = $list.children().length;

				$(':input', $new).each(function(i) {
					var newName = $(this).attr('name').replace(match, "$1" + $list.children().length + "$3");
					$(this).attr('name', newName);

					if (newName.match(/\[id\]/) || $(this).attr('type') != "hidden") {
						if ($(this).attr('type') == 'checkbox' || $(this).attr('type') == 'radio' || $(this).attr('type') == 'select') {
							$(this).prop('checked', false);
						} else {
							$(this).val("");
						}
					}
				});

				if (typeof pos == "undefined") {
					if (listCount) {
						pos = $list.children().length;
					}					
				}

				if (typeof pos != "undefined") {
					$new.insertAfter($this.parent().children().eq(pos));
				} else {
					$new.appendTo($list);
				}

				$new.schedulerTasksListItem();

				if (typeof insertText != "undefined") {
					$new.trigger('setValue', [insertText]);
				}

				$('.scheduler-tasks-list-item-input', $new).focus();
			}

			function autoresize(timeout) {
				if (typeof timeout == "undefined") {
					timeout = 1;
				}
				clearTimeout(textResizeTimeout);
				setTimeout(function() {
					$text[0].style.height = "0px";
					$text[0].style.height = ($text[0].scrollHeight + 10 ) + "px";
				});
			}

			$this
				.click(function(e) {
					$this.trigger('setFocus');
				})
				.on('setFocus', function() {
					$this.siblings().trigger('setBlur');
				
					if (!$this.hasClass('active')) {
						$this.addClass('active');
						if (!$text.is(':focus')) {
							$text.focus();
						}
					}
				})
				.on('setBlur', function() {
					$this.removeClass('active');
					$text.blur();
				})
				.on('setValue', function(e, text) {
					$text.val(text);
				});


			$text
				.focus(function() {
					$this.trigger('setFocus');
					// $this.addClass('active').siblings().removeClass('active');
				})
				.blur(function() {
					// $this.removeClass('active');
				})
				.keypress(function(e) {
					if (!e.shiftKey && e.which == 13) {		// Enter
						e.preventDefault();
						clone($this.index());
					} else if (e.which == 38) {	// Up
						$this.prev().focus();
					}
					autoresize();
				})
				.keyup(function(e) {
					//autoresize();
				})
				.on('change', function() {
					autoresize();
				})
				.on('paste', function() {
					var $el = $(this);
					setTimeout(function() {
						var lines = $el.val().replace(/\t/g,"").split(/\n/);
						var ct = lines.length;
						if (ct > 0) {
							$text.val(lines[0]);
							if (ct > 1) {
								var pos = $this.index();
								for (var i = 1; i < ct; i++) {
									lines[i] = lines[i].trim();
									if (lines[i] != '') {
										clone(pos, lines[i]);
										pos++;
									}
								}
							}
						}
					}, 0);
				});

			$checkbox.click(function(e) {
				$text.focus();
				if ($(this).is(':checked')) {
					$this.addClass('completed');
				} else {
					$this.removeClass('completed');
				}
			});

			if (!$this.data('scheduler-tasks-init')) {
				$optionsControl = $('<ul class="scheduler-tasks-list-item-options-control"></ul>');
				$this.data('scheduler-tasks-init', true);
			}
			return $this;
		});
	};

	var optionControlCount = 0;
	$.fn.schedulerTasksListItemOptions = function() {
		var $options = $(this),
			$controls = $options.prev('.scheduler-tasks-list-item-options-control');

		if (!$controls.length) {
			$controls = $('<div class="scheduler-tasks-list-item-options-control btn-group"></div>');

			$options.wrap($('<div class="scheduler-tasks-list-item-options-wrap"></div>'));

			$controls.insertBefore($options);

			$('li', $options).each(function() {
				var elId = 'scheduler-task-option-' + (optionControlCount++);
				$(this).attr('id', elId).hide();
				console.log('hiding');
				$title = $('h5', $(this)).first().remove();
				$control = $('<a href="#"></a>')
					.addClass('btn btn-small btn-default')
					.data('scheduler-task-option-target', '#' + elId)
					.append($title.html());
				$controls.append($control);
			});
		}

		$('a', $controls).click(function(e) {
			e.preventDefault();
			$(this).addClass('active').siblings().removeClass('active');
			var $target = $($(this).data('scheduler-task-option-target'));
			$target.show().siblings().hide();
		});
	};
})(jQuery);


$(document).ready(function() {
	$('.scheduler-tasks-list-item').schedulerTasksListItem();
	$('form.scheduler-tasks').submit(function(e) {
		e.preventDefault();

		var $form = $(this).addClass('loading');

		console.log($form.attr('action') + ".json");
		console.log($form.serialize());
		$.ajax({
			type: "POST",
			url: $form.attr('action') + ".json",
			data: $form.serialize(),
			success: function(data) {
				$form.removeClass('loading');
				if (data.message) {
					var $alert = $('<div class="form-alert alert"></div>');
					$alert.text(data.message).appendTo($form);
					if (data.success) {
						$alert.addClass(data.success ? 'alert-success' : 'alert-error')
					}
					setTimeout(function() {
						$alert.fadeOut();
					}, 1000);
				}
			},
			error: function(xhr, status, error) {
				console.log([status, error]);
			}
		});
	});
});
