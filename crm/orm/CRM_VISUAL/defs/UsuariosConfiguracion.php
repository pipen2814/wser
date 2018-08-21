<?

namespace crm\orm\defs;

use php\orm\BaseORM;
/**
 * Class UsuariosConfiguracion
 *
 * ORM para la tabla usuarios_configuracion
 *
 * @package crm
 * @subpackage orm
 * @subpackage defs
 */
class UsuariosConfiguracion extends BaseORM{

	protected $tableName = "usuarios_configuracion";
	protected $id = array("id_usuario");
	protected $fields = array(
		"id_usuario" => "idUsuario",
		"agenda_view" => "agendaView",
	);
}
?>
