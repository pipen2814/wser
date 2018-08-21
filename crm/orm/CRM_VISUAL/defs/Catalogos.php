<?

namespace crm\orm\defs;

use php\orm\BaseORM;
/**
 *
 * @package crm
 * @subpackage orm
 * @subpackage defs
 */
class Catalogos extends BaseORM {

	protected $host 	= "organization";
	protected $tableName 	= "catalogos";

	protected $id 		= array(
					"id_catalogo");

	protected $fields 	= array(
					"id_catalogo" 	=> "idCatalogo",
					"catalogo" 	=> "catalogo",
					"desde"		=> "desde",
					"hasta" 	=> "hasta",
					"fecha_hora" 	=> "fechaHora");

}
?>
