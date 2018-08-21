<?

namespace crm\orm\defs;

use php\orm\BaseORM;
/**
 * Class ModosContacto
 *
 * ORM para la tabla modos_contacto
 *
 * @package crm
 * @subpackage orm
 * @subpackage defs
 */
class ModosContacto extends BaseORM{

	protected $tableName = "modos_contacto";
	protected $id = array("id_modo_contacto");
	protected $fields = array(
		"id_modo_contacto" => "idModoContacto",
		"modo_contacto" => "modoContacto"
	);
}
?>
