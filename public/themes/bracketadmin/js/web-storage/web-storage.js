"use strict";

var _typeof = "_typeof";

var webStorage = {    
	init: function(){
		if (typeof(Storage) === "undefined") {
			alert('Maaf! Web Storage tidak support untuk browser Anda. Silahkan update browser Anda atau gunakan browser yang kompatibel!');
		}
		
	},
	_stringify_type: function( operand ){
					
		var _type = typeof operand;
		switch ( _type ){
			case "number":
				operand = String( operand );
			break;
			case "boolean":
				operand = String( operand );
			break;
			case "object":
				operand = JSON.stringify( operand ); 
			break;
			case "string":
				operand = operand;
			break;
		}
		
		return operand;

	},
	_origin_type : function( operand, _type ){
		
		switch ( _type ){
			case "number":
				operand = Number( operand );
			break;
			case "boolean":
				switch(operand.toLowerCase()) 
				{
					case "false": case "no": case "0": case "": operand = false; 
					break;
					default: operand = true;
				}
			break;
			case "object":
				operand = JSON.parse( operand ); 
			break;
			case "string":
				operand = String(operand);
			break;
		}
		
		return operand;
	},
	_get_type: function( _key, _StorageType ){
		
		var _type;
		switch ( _StorageType )
		{
			case 'local':
				_type = localStorage.getItem( _key );
			break;
			case 'session':
				_type = sessionStorage.getItem( _key );
			break;
			default:
				_type = 'string';
			break;
		}
		
		return  _type;

	},
	_store: function( _key, _value, _StorageType ){
		
		
		var _value_type = typeof _value;
		var _value = webStorage._stringify_type( _value );
		
		switch ( _StorageType )
		{
			case 'local':
				localStorage.setItem( _key, _value );
				localStorage.setItem( _key +"-"+ _typeof, _value_type );
			break
			case 'session':
				sessionStorage.setItem( _key, _value );
				sessionStorage.setItem( _key + _typeof , _value_type );
			break
		}
	},
	_retrieve: function( _key, _StorageType ){
		
		switch ( _StorageType )
		{
			case 'local':
				var _value = localStorage.getItem( _key );
			break
			case 'session':
				var _value = sessionStorage.getItem( _key );
			break
		}
		
		var _value_type = webStorage._get_type( _key + _typeof, _StorageType );
		
		return webStorage._origin_type( _value, _value_type );
	
	},
	localSetItem: function( key, value ){		
		webStorage._store( key, value, 'local' );
	},
	localGetItem: function( key ){		
		return webStorage._retrieve( key, 'local' );
	},
	localDTSetItem: function( key, _datatable ){		
		
		var value = _datatable.DataTable().rows().data();
				
		webStorage._store( key, value, 'local' );
	},
	localDTGetItem: function( key, _datatable ){		
		
		_datatable.DataTable().clear().draw();
		
		var data = webStorage._retrieve( key, 'local' );
		_datatable.DataTable().rows.data( data );		
	},
	sessionSetItem: function( key, value ){		
		webStorage._store( key, value, 'session' );
	},
	sessionGetItem: function( key ){		
		return webStorage._retrieve( key, 'session' );
	},
	sessionDTSetItem: function( key, _datatable ){		
		
		var value = _datatable.DataTable().rows().data();
				
		webStorage._store( key, value, 'session' );
	},
	sessionDTGetItem: function( key, _datatable ){		
		
		_datatable.DataTable().clear().draw();
		
		var data = webStorage._retrieve( key, 'session' );
		_datatable.DataTable().rows.data( data );		
	},
}

$(function(){
	
   	webStorage.init();
	
});