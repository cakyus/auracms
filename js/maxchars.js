var maxChars = {
        // cross-browser event handling for IE5+, NS6 and Mozilla 
        // By Scott Andrew 
	addEvent: function(elm, evType, fn, useCapture) {
		if(elm.addEventListener) {
			elm.addEventListener(evType, fn, useCapture);
			return true;
		} else if(elm.attachEvent) {
			var r = elm.attachEvent('on' + evType, fn);
			return r;
		} else {
			elm['on' + evType] = fn;
		}
	},

	attVal: function(element, attName) {
	  return parseInt(element.getAttribute(attName));
	},

	init: function() {
		if(!document.getElementsByTagName || !document.getElementById) {
			return;
		}
		
		maxChars.form = document.getElementById('myform');
		maxChars.textarea = document.getElementById('message');
		maxChars.maxlength = maxChars.attVal(maxChars.textarea, 'maxlength');
		maxChars.limit_span = document.getElementById('limiter');
		maxChars.limit_span.innerHTML = '<strong>' + maxChars.maxlength + '</strong>' 
			+ ' characters remaining.';
		
		maxChars.addEvent(maxChars.textarea, 'keyup', maxChars.countlimit, false);
	},

	countlimit: function(e) {
		var placeholder;
		var lengthleft = maxChars.maxlength - maxChars.textarea.value.length;

		if(e && e.target) {
			placeholder = e.target;
		}

		if(window.event && window.event.srcElement) {
			placeholder = window.event.srcElement;
		}

		if(!placeholder) {
			return;
		} else if(lengthleft < 0) {
			maxChars.textarea.value = maxChars.textarea.value
				.substring(0, maxChars.maxlength);
		} else if(lengthleft > 1) {
			maxChars.limit_span.innerHTML = '<strong>' + lengthleft + '</strong>' 
				+ ' characters remaining.';
		} else {
			maxChars.limit_span.innerHTML = '<strong>' + lengthleft + '</strong>' 
				+ ' character remaining.';
		}
	}

}

maxChars.addEvent(window, 'load', maxChars.init, false);