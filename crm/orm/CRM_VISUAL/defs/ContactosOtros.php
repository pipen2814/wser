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
class ContactosOtros extends BaseORM{

	protected $host = "organization";
	protected $tableName = "contactos_otros";
	protected $id = array("id_contacto");
	protected $fields = array(
		"id_contacto" => "idContacto",
		"info_comercial" => "infoComercial"
	);
}
?>
