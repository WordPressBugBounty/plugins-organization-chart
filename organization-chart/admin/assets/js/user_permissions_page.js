function submitButton(value) {
	const form = document.getElementById('adminForm');
	if (!form) return;
	form.setAttribute('action', form.getAttribute('action') + '&task=' + value);
	document.querySelectorAll('[disabled]').forEach(el => el.removeAttribute('disabled'));
	form.submit();
}