/* ////////////////////////////////////////////////////////////////////////////// */
/* ///////////////////////////////////////////////////////////////// DOCUMENT /// */
/* ////////////////////////////////////////////////////////////////////////////// */

/* -------------------------------------------------------------------- READY --- */
jQuery(init);

/* --------------------------------------------------------------------- INIT --- */
function init() {
	// message
    message_init();
}


/* ////////////////////////////////////////////////////////////////////////////// */
/* ////////////////////////////////////////////////////////////////// MESSAGE /// */
/* ////////////////////////////////////////////////////////////////////////////// */

/* --------------------------------------------------------------------- INIT --- */
function message_init() {
    // listen
    message_listen();
}

/* ------------------------------------------------------------------- LISTEN --- */
function message_listen() {
	// listen for message
	jQuery(window).on('message', function(event) {
		// determine event object
		event = event.originalEvent || event;

		// bail when no callback defined
		if (typeof window[event.data.method] === 'undefined') return;

		// bail when method does not exist
		if (typeof window[event.data.method] !== 'function') {
			// warning
			return console.warn('Message callback `' + event.data.method + '` does not exist');
		}

		// execute method
		window[event.data.method](event);
    });
}

/* --------------------------------------------------------------------- POST --- */
function message_post(target, method, payload, callback, origin) {
	// format payload
	if (typeof payload !== 'undefined') payload = JSON.parse(JSON.stringify(payload));
    // format origin
    if (typeof origin === 'undefined') origin = '*';
	// post message
	target.postMessage({ method: method, payload: payload, callback: callback, }, origin);
}
