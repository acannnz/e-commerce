"use strict";

(function( $ ){
		var __dev_viewport = {
			init: function(){                
					var self = this;
					
					if(window.innerWidth > 992){
						self.set_height();            
						$(".dev-viewport-center").mCustomScrollbar({axis:"y", autoHideScrollbar: true, scrollInertia: 200, advanced: {autoScrollOnFocus: false}});        
					}else{            
						$(".dev-viewport-center").mCustomScrollbar("disable",true);
					}
				},
			update: function(){        
					this.set_height();        
					$(".dev-viewport-center").mCustomScrollbar("update");
				},
			set_height: function(){        
					if(window.innerWidth > 992){
						var header_height = dev_layout_alpha_settings.headerHeight;
						var footer_height = $(".dev-page-footer").hasClass("dev-page-footer-collapsed") ? 0 : dev_layout_alpha_settings.footerHeight ;
						var new_height = window.innerHeight - footer_height - header_height;
						$(".dev-viewport-navigation, .dev-viewport-center").height(new_height);
					}else{
						$(".dev-viewport-navigation, .dev-viewport-center").removeAttr("style");
					}
				},
		};
		
		$( document ).ready(function(e) {
				$( ".dev-page" ).addClass( "dev-page-sidebar-minimized" );
				
				__dev_viewport.init();
				
				$(".dev-page-footer-collapse").click(function(){
						setTimeout(function(){
							__dev_viewport.init();
						},200);        
					});
					
				$(window).resize(function(){
						__dev_viewport.update();
					});	
        	});
	})( jQuery )