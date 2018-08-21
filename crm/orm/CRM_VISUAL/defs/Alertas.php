<?

namespace crm\orm\defs;

use php\orm\BaseORM;
/**
 *
 * @package crm
 * @subpackage orm
 * @subpackage defs
 */
class Alertas extends BaseORM {

	protected $tableName = "alertas";

	protected $id = array("id_alerta");

	protected $fields 	= array(
		"id_alerta" 	=> "idAlerta",
		"nombre"		=> "nombre",
		"descripcion"	=> "descripcion",
		"prioridad"		=> "prioridad",
		"fecha_inicio"	=> "fechaInicio",
		"fecha_fin"		=> "fechaFin",
		"visible"		=> "visible"
	);
}
?>
