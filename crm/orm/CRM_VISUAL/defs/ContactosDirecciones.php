<?

namespace crm\orm\defs;

use php\orm\BaseORM;
/**
 * Class ContactosDirecciones
 *
 * ORM para la tabla contactos_direcciones
 *
 * @package crm
 * @subpackage orm
 * @subpackage defs
 */
class ContactosDirecciones extends BaseORM{

	protected $host = "organization";
	protected $tableName = "contactos_direcciones";
	protected $id = array("id_contacto", "id_direccion");
	protected $fields = array(
		"id_contacto" => "idContacto",
		"id_direccion" => "idDireccion",
		"id_tipo_via" => "idTipoVia",
		"direccion" => "direccion",
		"codigo_postal" => "codigoPostal",
		"id_pais" => "idPais",
		"id_provincia" => "idProvincia",
		"id_localidad" => "idLocalidad",
		"principal" => "principal",
		"fecha_creacion" => "fechaCreacion",
		"fecha_modificacion" => "fechaModificacion"
	);
}
?>
