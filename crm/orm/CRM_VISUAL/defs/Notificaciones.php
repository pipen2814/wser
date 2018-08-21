<?

namespace crm\orm\defs;

use php\orm\BaseORM;
/**
 *
 * @package crm
 * @subpackage orm
 * @subpackage defs
 */
class Notificaciones extends BaseORM {

	protected $host = "organization";

	protected $tableName = "notificaciones";

	protected $id = array("id_notificacion");

	protected $fields 	= array(
		"id_notificacion" 	=> "idNotificacion",
		"nombre"			=> "nombre",
		"descripcion"		=> "descripcion",
		"prioridad"			=> "prioridad",
		"fecha_inicio"		=> "fechaInicio",
		"fecha_fin"			=> "fechaFin",
		"visible"			=> "visible"
	);
}
?>
