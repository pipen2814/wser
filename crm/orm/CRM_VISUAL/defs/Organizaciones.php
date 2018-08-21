<?

namespace crm\orm\defs;

use php\orm\BaseORM;
/**
 * Class Organizaciones
 *
 * ORM para la tabla organizaciones
 *
 * @package crm
 * @subpackage orm
 * @subpackage defs
 */
class Organizaciones extends BaseORM{

	protected $tableName = "organizaciones";

	protected $id = array("id_organizacion");
	
	protected $fields = array(
		"id_organizacion"	=> "idOrganizacion",
		"organizacion" 		=> "organizacion",
		"fecha_alta"		=> "fechaAlta",
		"fecha_baja"		=> "fechaBaja",
		"id_provincia"		=> "idProvincia",
		"telefono"			=> "telefono",
		"direccion"			=> "direccion",
		"codigo_postal"		=> "codigoPostal",
		"id_licencia"		=> "idLicencia",
	);

}
?>
