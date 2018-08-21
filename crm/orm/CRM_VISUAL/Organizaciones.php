<?

namespace crm\orm;

use php\orm\BaseORM;
/**
 * Class Organizaciones
 *
 * ORM para la tabla organizaciones
 *
 * @package crm
 * @subpackage orm
 */
class Organizaciones extends \crm\orm\defs\Organizaciones {

	/**
	 * Metodo que obtiene todas las organizaciones disponibles
	 *
	 * @return $rs Todas las organizaciones disponibles
	 */
	public function getAll(){
		$query = "select * from $this->tableName";

		$stmt = $this->conn->prepare($query);
		$rs = $stmt->execute();

		return $rs;
	}

}
?>
