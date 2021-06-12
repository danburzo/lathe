function onload() {
	/*
		When an AJAX Action form is submitted
		perform a JavaScript `fetch()` call instead, 
		then refresh the page.

		See: `templates/admin/ajax-action-form.twig`
	 */
	document.querySelectorAll('.wp-ajax-action-form').forEach(form => {
		form.addEventListener('submit', e => {
			e.preventDefault();
			submitAjaxAction(form).then(() => window.location.reload());
		});
	});

	/*
		For the `Theme Caches` menu items we've registered
		to the Admin Bar, perform the attached AJAX actions
		by submitting their adjacent forms.
	 */
	document.querySelectorAll('.wp-admin-bar-ajax-menu-item').forEach(item => {
		let btn = item.querySelector(':scope > a');
		let form = item.querySelector(':scope > form');
		if (btn && form) {
			btn.addEventListener('click', e => {
				e.preventDefault();
				submitAjaxAction(form).then(() => window.location.reload());
			});
		}
	});
}

/*
	Submit an AJAX Action form with the JavaScript fetch() API.
	See: `templates/admin/ajax-action-form.twig`
 */
function submitAjaxAction(form) {
	return fetch(
		/*
			The <form> element contains an <input> named `action`,
			therefore `form.action` points to the input rather than
			the submission URL.
		 */
		form.getAttribute('action'),
		{
			method: form.method,
			body: new FormData(form)
		}
	);
}

document.addEventListener('DOMContentLoaded', onload);
