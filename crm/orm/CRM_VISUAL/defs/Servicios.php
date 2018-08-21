<?

namespace crm\orm\defs;

use php\orm\BaseORM;
/**
 *
 * @package crm
 * @subpackage orm
 * @subpackage defs
 */
class Servicios extends BaseORM {

	protected $host 	= "organization";
	protected $tableName 	= "servicios";

	protected $id 		= array("id_servicio");

	protected $fields 	= array(
					"id_servicio" 	=> "idServicio",
					"id_catalogo" 	=> "idCatalogo",
					"servicio" 	=> "servicio",
					"detalle" 	=> "detalle",
					"fecha_hora" 	=> "fechaHora");
}
?>
