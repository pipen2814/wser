<?

namespace crm\orm\defs;

use php\orm\BaseORM;

/**
 * @todo hacerlo multiidioma
 *
 * @package crm
 * @subpackage orm
 * @subpackage defs
 */
class Trads extends BaseORM {

	protected $host 	= "crmmaster";
	protected $tableName 	= "trads_ES";

	protected $id		= array(
					"dominio",
					"clave");

	protected $fields 	= array(
					"dominio" 	=> "dominio",
					"clave" 	=> "clave",
					"txt" 		=> "txt",
					"fecha_hora" 	=> "fechaHora");
}
?>
