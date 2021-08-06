(() => {
	function r() {
		document.querySelectorAll('.wp-ajax-action-form').forEach(e => {
			e.addEventListener('submit', t => {
				t.preventDefault(), o(e).then(() => window.location.reload());
			});
		}),
			document
				.querySelectorAll('.wp-admin-bar-ajax-menu-item')
				.forEach(e => {
					let t = e.querySelector(':scope > a'),
						n = e.querySelector(':scope > form');
					t &&
						n &&
						t.addEventListener('click', a => {
							a.preventDefault(),
								o(n).then(() => window.location.reload());
						});
				});
	}
	function o(e) {
		return fetch(e.getAttribute('action'), {
			method: e.method,
			body: new FormData(e)
		});
	}
	document.addEventListener('DOMContentLoaded', r);
})();
