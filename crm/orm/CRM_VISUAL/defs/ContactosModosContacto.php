<?

namespace crm\orm\defs;

use php\orm\BaseORM;
/**
 * Class ContactosModosContacto
 *
 * ORM para la tabla contactos_modos_contacto
 *
 * @package crm
 * @subpackage orm
 * @subpackage defs
 */
class ContactosModosContacto extends BaseORM{

	protected $host = "organization";
	protected $tableName = "contactos_modos_contacto";
	protected $id = array("id_contacto", "id_modo_contacto");
	protected $fields = array(
		"id_contacto" => "idContacto",
		"id_modo_contacto" => "idModoContacto",
		"id_tipo_contacto" => "idTipoContacto",
		"codigo_postal" => "codigoPostal",
		"valor" => "valor",
		"principal" => "principal",
		"fecha_creacion" => "fechaCreacion",
		"fecha_modificacion" => "fechaModificacion"
	);
}
?>
