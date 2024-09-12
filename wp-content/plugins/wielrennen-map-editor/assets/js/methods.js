/* ////////////////////////////////////////////////////////////////////////////// */

/* /////////////////////////////////////////////////////////////////// METHOD /// */

/* ////////////////////////////////////////////////////////////////////////////// */



/* ----------------------------------------------------------- ASYNC LISTENER --- */



document.domain = "mapeditor.arhamsoft.info";



function async_listener(o, event) {

    return new Promise(function(resolve,reject) {

        o.on(event, function() { 

        

        	

            o.off(event);

            resolve();

            reject();

        });

    });

}







/* ----------------------------------------------------------------- DEBOUNCE --- */

function debounce(fn, delay) {

	var timeout;

	return function() {

		var context = this, args = arguments;

		var later = function() {

			timeout = null;

			fn.apply(context, args);

		};

		clearTimeout(timeout);

		timeout = setTimeout(later, delay);

		if (!timeout) fn.apply(context, args);

	}

}



/* -------------------------------------------------------------------- EMPTY --- */

function empty(value) {

	var empty = [undefined, null, false, 0, '', '0',];

	for (var a = 0; a < empty.length; a++) {

		if (value === empty[a]) {

			return true;

		}

	}

	if (typeof value === 'object') {

		for (key in value) {

			if (value.hasOwnProperty(key)) {

				return false;

			}

		}

		return true;

	}

	return false;

}



/* ------------------------------------------------------------ FORMAT SECOND --- */

function format_second(s) {

	var ms = (s * 1000);

	var d = moment.duration(ms);

	return [Math.floor(d.asHours()), moment.utc(ms).format('mm:ss'),].join(':');

}



/* --------------------------------------------------------------------- GUID --- */

function guid() {

	return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(x) {

		var rand = (Math.random() * 16) | 0;

		var value = x === 'x' ? rand : (rand & 0x3 | 0x8);

		return value.toString(16);

	});

}



/* -------------------------------------------------------------------- SLEEP --- */

function sleep(ms) {

	return new Promise(function(resolve) { setTimeout(resolve, ms); });

}





/* ////////////////////////////////////////////////////////////////////////////// */

/* //////////////////////////////////////////////////////////////// EXTENSION /// */

/* ////////////////////////////////////////////////////////////////////////////// */



/* ------------------------------------------------------ REMOVE CLASS PREFIX --- */

jQuery.fn.removeClassPrefix = function(prefix) {

	var $this = jQuery(this);

	jQuery.each(prefix.split(' '), function(_, value) {

		$this.removeClass(function(_, name) {

			return (name.match(new RegExp(['(^|\\s)', value, '\\S+',].join(''), 'g')) || []).join(' ');

		});

	});

};



