<?

namespace crm\orm;

use php\orm\BaseORM;
use php\sql\DB;
/**
 * Class Usuarios
 *
 * ORM para la tabla usuarios
 *
 * @package crm
 * @subpackage orm
 */
class AccountsUsers extends \crm\orm\defs\AccountsUsers {

	public static function getByAccountId($accountId){
		$db = DB::getInstance();
		$db->query("select * from cuentas_usuarios where id_cuenta = ".$accountId);
		$rs = $db->getRs();

		return $rs;
	
	}

	public static function deleteAllForAccount($accountId){
		$db = DB::getInstance();
		$db->query("delete from cuentas_usuarios where id_cuenta = ".$accountId);
	
		return true;
	}

}
