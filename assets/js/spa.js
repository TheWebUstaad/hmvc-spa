(function () {
	'use strict';

	var body = document.body;
	var containerId = body.getAttribute('data-spa-container') || 'spa-content';
	var container = document.getElementById(containerId);
	var siteUrl = body.getAttribute('data-site-url') || '/';
	var baseUrl = body.getAttribute('data-base-url') || '/';

	if (!container || !window.history || !window.fetch) {
		return;
	}

	function normalizePath(url) {
		try {
			var u = new URL(url, window.location.origin);
			var path = u.pathname.replace(/\/+$/, '') || '/';
			return path + u.search;
		} catch (e) {
			return url;
		}
	}

	function isInternal(anchor) {
		if (!anchor || anchor.target === '_blank' || anchor.hasAttribute('download')) {
			return false;
		}
		if (anchor.getAttribute('href') && anchor.getAttribute('href').charAt(0) === '#') {
			return false;
		}
		try {
			var u = new URL(anchor.href, window.location.origin);
			return u.origin === window.location.origin;
		} catch (e) {
			return false;
		}
	}

	function setActiveNav(pathname) {
		var links = document.querySelectorAll('.spa-nav .spa-link');
		Array.prototype.forEach.call(links, function (link) {
			var linkPath = normalizePath(link.href).replace(/\/index\.php/i, '');
			var current = pathname.replace(/\/index\.php/i, '');
			var module = link.getAttribute('data-module');
			var active = current === linkPath || (module && current.indexOf('/' + module) !== -1) ||
				(module && (current.endsWith('/' + module) || current.endsWith('/' + module + '/')));
			link.classList.toggle('is-active', !!active);
		});
	}

	function buildFetchUrl(url) {
		var u = new URL(url, window.location.origin);
		u.searchParams.set('spa', '1');
		return u.toString();
	}

	function navigate(url, push) {
		container.classList.add('is-loading');

		return fetch(buildFetchUrl(url), {
			headers: {
				'X-SPA-Request': '1',
				'X-Requested-With': 'XMLHttpRequest',
				'Accept': 'application/json'
			},
			credentials: 'same-origin'
		})
			.then(function (res) {
				if (!res.ok) {
					throw new Error('SPA request failed: ' + res.status);
				}
				return res.json();
			})
			.then(function (data) {
				container.innerHTML = data.html || '';
				if (data.title) {
					document.title = data.title;
				}
				if (push !== false) {
					history.pushState({ spa: true, url: url }, data.title || '', url);
				}
				setActiveNav(normalizePath(url));
			})
			.catch(function () {
				window.location.href = url;
			})
			.then(function () {
				container.classList.remove('is-loading');
			});
	}

	document.addEventListener('click', function (e) {
		var anchor = e.target.closest ? e.target.closest('a.spa-link, a[data-spa]') : null;
		if (!anchor) {
			anchor = e.target.closest ? e.target.closest('a') : null;
			if (!anchor || !anchor.classList.contains('spa-link')) {
				// Allow in-content internal links without full reload
				if (!anchor || !isInternal(anchor)) {
					return;
				}
			}
		}

		if (!isInternal(anchor)) {
			return;
		}

		e.preventDefault();
		if (normalizePath(anchor.href) === normalizePath(window.location.href)) {
			return;
		}
		navigate(anchor.href, true);
	});

	window.addEventListener('popstate', function () {
		navigate(window.location.href, false);
	});

	setActiveNav(normalizePath(window.location.href));
})();
