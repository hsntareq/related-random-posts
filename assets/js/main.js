var rrpToggler = document.querySelector('.rrp-toggler');
var rrpPosts = document.querySelector('.rrp-posts');
var rrpGrid = localStorage.getItem('rrpGrid');

rrpPosts.classList.toggle(rrpGrid === 'false' ? 'grid' : 'list');
rrpToggler.classList.toggle(rrpGrid === 'true' ? 'grid' : 'list');

rrpToggler.addEventListener('click', function (e) {
	e.preventDefault();
	localStorage.setItem('rrpGrid', rrpPosts.classList.contains('grid'));
	rrpPosts.classList.toggle('grid');
	rrpToggler.classList.toggle('grid');
});
