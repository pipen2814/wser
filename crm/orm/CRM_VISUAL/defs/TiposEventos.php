<?

namespace crm\orm\defs;

use php\orm\BaseORM;
/**
 * Class TiposEventos
 *
 * ORM para la tabla tipos_eventos
 *
 * @package crm
 * @subpackage orm
 * @subpackage defs
 */
class TiposEventos extends BaseORM{

	protected $tableName = "tipos_eventos";

	protected $id = array("id_tipo_evento");
	
	protected $fields = array(
		"id_tipo_evento"	=> "idTipoEvento",
		"tipo_evento" 		=> "tipoEvento",
		"color_rgb"			=> "colorRgb"
	);
}
?>
