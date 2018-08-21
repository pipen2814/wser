<?

namespace crm\orm\defs;

use php\orm\BaseORM;
/**
 *
 * @package crm
 * @subpackage orm
 * @subpackage defs
 */
class AlertasOrganizaciones extends BaseORM {

	protected $tableName = "alertas_organizaciones";

	protected $id = array("id_alerta","id_organizacion");

	protected $fields 	= array(
		"id_alerta" 		=> "idAlerta",
		"id_organizacion"	=> "idOrganizacion",
	);
}
?>
