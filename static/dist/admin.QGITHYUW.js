(() => {
	// static/admin.js
	function onload() {
		document.querySelectorAll('.wp-ajax-action-form').forEach(form => {
			form.addEventListener('submit', e => {
				e.preventDefault();
				submitAjaxAction(form).then(() => window.location.reload());
			});
		});
		document
			.querySelectorAll('.wp-admin-bar-ajax-menu-item')
			.forEach(item => {
				let btn = item.querySelector(':scope > a');
				let form = item.querySelector(':scope > form');
				if (btn && form) {
					btn.addEventListener('click', e => {
						e.preventDefault();
						submitAjaxAction(form).then(() =>
							window.location.reload()
						);
					});
				}
			});
	}
	function submitAjaxAction(form) {
		return fetch(form.getAttribute('action'), {
			method: form.method,
			body: new FormData(form)
		});
	}
	document.addEventListener('DOMContentLoaded', onload);
})();
