<?

namespace crm\orm\defs;

use php\orm\BaseORM;
/**
 * Class UsuariosSesiones
 *
 * ORM para la tabla usuarios_sesiones
 *
 * @package crm
 * @subpackage orm
 * @subpackage defs
 */
class UsuariosSesiones extends BaseORM{

	protected $tableName = "usuarios_sesiones";
	protected $id = array("id_usuario");
	protected $fields = array(
		"id_usuario" => "idUsuario",
		"sesion" => "sesion"
	);
}
?>
