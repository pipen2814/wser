<?

namespace crm\orm\defs;

use php\orm\BaseORM;
/**
 * Class Eventos
 *
 * ORM para la tabla eventos
 *
 * @package crm
 * @subpackage orm
 * @subpackage defs
 */
class Eventos extends BaseORM{

	protected $host = "organization";

	protected $tableName = "eventos";

	protected $id = array("id_evento");
	
	protected $fields = array(
		"id_evento"			=> "idEvento",
		"id_tipo_evento"	=> "idTipoEvento",
		"evento" 			=> "evento",
		"comentarios"		=> "comentarios",
		"fecha_inicio"		=> "fechaInicio",
		"fecha_fin"			=> "fechaFin"
	);
}
?>
