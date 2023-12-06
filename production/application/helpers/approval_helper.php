<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

final class Approval_helper
{
	private static $table = 'VW_ListApprove_BO';
	private static $approve_function = 'Approve_Function';
	private static $username = 'Approve_User';
	private static $password = 'Approve_Pswd';

	public static function approve( $approve_function, $username, $password )
    {
		self::$table = self::ci()->db->initial == 'FO' 
						? self::$table : 'ListApprove'; //sementara
		
		return (boolean)
			self::ci()->db->where(array(
								self::$approve_function => $approve_function,
								self::$username => $username,
								self::$password => $password,
							))
							->count_all_results( self::$table );
	}
	
	private static function & ci()
	{
		return get_instance();
	}
}