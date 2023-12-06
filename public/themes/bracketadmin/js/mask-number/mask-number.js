"use strict";

var mask_number = {    
		init: function(){
			if( $(".mask-number").size() > 0 )
			{
				var $this =	$(".mask-number");
				
				$this.each(function( index, element){
					var val = $(this).val();
					
					if ( val == 0 || val == '')
					{
						$(this).val("");
						return;
					}
					
					$(this).val( mask_number.currency_add( val ) );
				});
					
				$this.on("focus",function(){
						var val = $(this).val();
					
						if ( val == 0 || val == '')
						{
							$(this).val("");
							return;
						}
						
						$(this).val( mask_number.currency_remove( val ) );
					});
						
				$this.on("blur",function(){
						var val = $(this).val();
						if ( val > 0)
						{
							$(this).val( mask_number.currency_add(val) );
						} else {
							$(this).val( "0.00" );
						}
					});
				}
			},
		currency_add: function( _operand ){
				_operand = String( _operand );

				return parseFloat( _operand ).toFixed(2).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
		
			},
		currency_remove: function( _operand ){
				_operand = String( _operand );

				return parseFloat( _operand.replace(/[^0-9\.-]+/g,"") );			
			},
		currency_ceil( _operand, _increment = 500 ){
				return Number( Math.ceil( parseFloat(_operand) / _increment) * _increment );
			}
	}
$(function(){
	
   	mask_number.init();
	
});