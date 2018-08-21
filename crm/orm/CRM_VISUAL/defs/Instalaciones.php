<?

namespace crm\orm\defs;

use php\orm\BaseORM;
/**
 * Class Instalaciones
 *
 * ORM para la tabla instalaciones
 *
 * @package crm
 * @subpackage orm
 * @subpackage defs
 */
class Instalaciones extends BaseORM{

	protected $tableName = "instalaciones";
	protected $id = "id_instalacion";
	protected $fields = array(
		"id_instalacion" => "idInstalacion",
		"id_organizacion" => "idOrganizacion",
		"instalacion" => "instalacion",
		"fecha_alta" => "fechaAlta",
		"fecha_baja" => "fechaBaja",
		"id_provincia" => "idProvincia",
		"telefono" => "telefono",
		"direccion" => "direccion",
		"codigo_postal" => "codigoPostal"
	);
}
?>
