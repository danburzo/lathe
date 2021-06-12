parcelRequire = (function (e, r, t, n) {
	var i,
		o = 'function' == typeof parcelRequire && parcelRequire,
		u = 'function' == typeof require && require;
	function f(t, n) {
		if (!r[t]) {
			if (!e[t]) {
				var i = 'function' == typeof parcelRequire && parcelRequire;
				if (!n && i) return i(t, !0);
				if (o) return o(t, !0);
				if (u && 'string' == typeof t) return u(t);
				var c = new Error("Cannot find module '" + t + "'");
				throw ((c.code = 'MODULE_NOT_FOUND'), c);
			}
			(p.resolve = function (r) {
				return e[t][1][r] || r;
			}),
				(p.cache = {});
			var l = (r[t] = new f.Module(t));
			e[t][0].call(l.exports, p, l, l.exports, this);
		}
		return r[t].exports;
		function p(e) {
			return f(p.resolve(e));
		}
	}
	(f.isParcelRequire = !0),
		(f.Module = function (e) {
			(this.id = e), (this.bundle = f), (this.exports = {});
		}),
		(f.modules = e),
		(f.cache = r),
		(f.parent = o),
		(f.register = function (r, t) {
			e[r] = [
				function (e, r) {
					r.exports = t;
				},
				{}
			];
		});
	for (var c = 0; c < t.length; c++)
		try {
			f(t[c]);
		} catch (e) {
			i || (i = e);
		}
	if (t.length) {
		var l = f(t[t.length - 1]);
		'object' == typeof exports && 'undefined' != typeof module
			? (module.exports = l)
			: 'function' == typeof define && define.amd
			? define(function () {
					return l;
			  })
			: n && (this[n] = l);
	}
	if (((parcelRequire = f), i)) throw i;
	return f;
})(
	{
		upHy: [
			function (require, module, exports) {
				function e() {
					document
						.querySelectorAll('.wp-ajax-action-form')
						.forEach(function (e) {
							e.addEventListener('submit', function (n) {
								n.preventDefault(),
									t(e).then(function () {
										return window.location.reload();
									});
							});
						}),
						document
							.querySelectorAll('.wp-admin-bar-ajax-menu-item')
							.forEach(function (e) {
								var n = e.querySelector(':scope > a'),
									o = e.querySelector(':scope > form');
								n &&
									o &&
									n.addEventListener('click', function (e) {
										e.preventDefault(),
											t(o).then(function () {
												return window.location.reload();
											});
									});
							});
				}
				function t(e) {
					return fetch(e.getAttribute('action'), {
						method: e.method,
						body: new FormData(e)
					});
				}
				document.addEventListener('DOMContentLoaded', e);
			},
			{}
		]
	},
	{},
	['upHy'],
	null
);
