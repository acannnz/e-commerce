"use strict";
    
var timeout = {
		settings: {time: (1000*60*45)},
		session: false,
		init: function(){
			var self = this;
			
			document.onmousemove = function(){
				clearTimeout(self.session);
				self.session = setTimeout(function(){
					document.location.href = "/users/lock";
				}, self.settings.time);
			}
		}
	};
    
$(function(){
		timeout.init();
	});      