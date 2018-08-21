<?

namespace crm\orm\defs;

use php\orm\BaseORM;
/**
 * Class Perfiles
 *
 * ORM para la tabla perfiles
 *
 * @package crm
 * @subpackage orm
 * @subpackage defs
 */
class Perfiles extends BaseORM{

	protected $tableName = "perfiles";
	protected $id = array("id_perfil");
	protected $fields = array(
		"id_perfil" => "idPerfil",
		"perfil" => "perfil"
	);
}
?>
