<?

namespace crm\orm\defs;

use php\orm\BaseORM;
/**
 * Class EventosUsuarios
 *
 * ORM para la tabla eventos_usuarios
 *
 * @package crm
 * @subpackage orm
 * @subpackage defs
 */
class EventosUsuarios extends BaseORM{

	protected $host = "organization";

	protected $tableName = "eventos_usuarios";

	protected $id = array("id_evento", "id_usuario");
	
	protected $fields = array(
		"id_evento" => "idEvento",
		"id_usuario" => "idUsuario"
	);
}
?>
