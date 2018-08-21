<?

namespace crm\orm\defs;

use php\orm\BaseORM;
/**
 * Class Permisos
 *
 * ORM para la tabla permisos
 *
 * @package crm
 * @subpackage orm
 * @subpackage defs
 */
class Permisos extends BaseORM{

	protected $tableName = "permisos";
	protected $id = array("id_permiso");
	protected $fields = array(
		"id_permiso" => "idPermiso",
		"permiso" => "permiso"
	);
}
?>
