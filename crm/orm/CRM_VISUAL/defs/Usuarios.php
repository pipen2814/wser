<?

namespace crm\orm\defs;

use php\orm\BaseORM;
/**
 * Class Usuarios
 *
 * ORM para la tabla usuarios
 *
 * @package crm
 * @subpackage orm
 * @subpackage defs
 */
class Usuarios extends BaseORM{

	protected $tableName = "usuarios";
	protected $id = array("id_usuario");
	protected $fields = array(
		"id_usuario" => "idUsuario",
		"id_perfil" => "idPerfil",
		"dni" => "dni",
		"password" => "password",
		"id_organizacion" => "idOrganizacion",
		"nombre" => "nombre",
		"apellidos" => "apellidos",
		"fecha_alta" => "fechaAlta",
		"fecha_baja" => "fechaBaja"
	);
}
?>
