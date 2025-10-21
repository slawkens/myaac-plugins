(function ($) {
	let settings;
	$.fn.zxcvbnProgress = function (options) {
		settings = $.extend({
			ratings: ["1 - Very weak", "2 - weak", "3 - OK", "4 - Strong", "5 - Very strong"],
			passwordInput: '#password',
			userInputs: ['#account_input', '#email', '#character_name'],
			progressClasses: ['error', 'warning', 'note', 'success', 'success'],
		}, options);
		let $passwordInput = $(settings.passwordInput),
			$progress = this;
		if (!settings.passwordInput) throw new TypeError('Please enter password input');
		$passwordInput.on('keyup', throttle(function() {
			updateProgress($passwordInput, $progress);
		}, 2000));

		updateProgress($passwordInput, $progress);
	};

	function updateProgress($passwordInput, $progress) {
		let passwordValue = $passwordInput.val();
		if (passwordValue) {
			let userInputs = [];
			settings.userInputs.forEach(function(value) {
				userInputs.push($(value).val());
			});

			$.ajax({
				type: "POST",
				dataType: "json",
				url: "/plugins/password-strength/ajax.php",
				data: { uid: Math.random(), password: passwordValue, userInputs: userInputs },
				success: function(data) {
					if(!data.success) {
						console.log('password-strength error: ' + data.message);
						return;
					}

					let score = data.score;
					$progress.removeClass(settings.progressClasses.join(' ')).addClass(settings.progressClasses[score]).text("Score: " + settings.ratings[score]);

					let warning = data.warning ?? '';
					let suggestions = data.suggestions ?? '';

					let $passwordWarning = $("#password-warning");
					if (warning && warning.length) {
						$passwordWarning.html('Warning: <br/>' + warning);
						$passwordWarning.show();
					} else {
						$passwordWarning.hide();
					}

					let $passwordSuggestions = $("#password-suggestions");
					if (suggestions && suggestions.length) {
						$passwordSuggestions.html('Suggestions: <br/>' + suggestions.join('<br/>'));
						$passwordSuggestions.show();
					} else {
						$passwordSuggestions.hide();
					}
				},
				error: function (xhr, ajaxOptions, thrownError){
					console.log(thrownError);
				}
			});
		} else {
			$progress.removeClass(settings.progressClasses.join(' ')).text('');
		}
	}
})(jQuery);

/**
 * throttle function
 * taken from: https://stackoverflow.com/questions/5031501/how-to-rate-limit-ajax-requests
 */
function throttle(func, wait) {
	let timeout;
	return function() {
		let context = this, args = arguments;
		if (!timeout) {
			// the first time the event fires, we setup a timer, which
			// is used as a guard to block subsequent calls; once the
			// timer's handler fires, we reset it and create a new one
			timeout = setTimeout(function() {
				timeout = null;
				func.apply(context, args);
			}, wait);
		}
	}
}
