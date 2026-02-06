function submitButton(value) {
	const form = document.getElementById("adminForm");
	if (!form) return;

	const action = form.getAttribute("action") || "";
	form.setAttribute("action", action + "&task=" + encodeURIComponent(value));

	jQuery(form).find('[disabled]').removeAttr('disabled');
	form.submit();
}