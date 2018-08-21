<?

namespace crm\orm\defs;

use php\orm\BaseORM;
/**
 *
 * @package crm
 * @subpackage orm
 * @subpackage defs
 */
class NotificacionesRelaciones extends BaseORM {

	protected $host = "organization";

	protected $tableName = "notificaciones_relaciones";

	protected $id = array("id_notificacion","relacion","id_relacion");

	protected $fields 	= array(
		"id_notificacion" 	=> "idAlerta",
		"relacion"			=> "relacion",
		"id_relacion"		=> "idRelacion",
	);

}
?>
