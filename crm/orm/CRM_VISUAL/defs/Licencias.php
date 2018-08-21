<?

namespace crm\orm\defs;

use php\orm\BaseORM;
/**
 * Class Licencias
 *
 * ORM para la tabla licencias
 *
 * @package crm
 * @subpackage orm
 * @subpackage defs
 */
class Licencias extends BaseORM{

	protected $tableName = "licencias";
	protected $id = array("id_licencia");
	protected $fields = array(
		"id_licencia" => "idLicencia",
		"licencia" => "licencia"
	);
}
?>
