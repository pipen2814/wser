<?

namespace crm\orm\defs;

use php\orm\BaseORM;
/**
 * Class Contactos
 *
 * ORM para la tabla contactos
 *
 * @package crm
 * @subpackage orm
 * @subpackage defs
 */
class Contactos extends BaseORM{

	protected $host = "organization";

	protected $tableName = "contactos";

	protected $id = array("id_contacto");

	protected $fields = array(
		"id_contacto" => "idContacto",
		"nombre" => "nombre",
		"apellidos" => "apellidos",
		"id_estado_negociacion" => "idEstadoNegociacion",
		"dni" => "dni",
		"fecha_nacimiento" => "fechaNacimiento",
		"fecha_creacion" => "fechaCreacion",
		"fecha_modificacion" => "fechaModificacion",
		"fecha_baja" => "fechaBaja"
	);
}
?>
