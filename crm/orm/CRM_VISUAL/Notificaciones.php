<?

namespace crm\orm;

use php\orm\BaseORM;
/**
 *
 * @package crm
 * @subpackage orm
 */
class Notificaciones extends \crm\orm\defs\Notificaciones  {

	/**
	 * Método que devuelve un ResultSet con todas las notificaciones ordenadas por fecha de inicio descendente
	 * para dejar las antiguas al final.
	 *
	 * @return MySQLResultSet
	 */
	public function getAll(){
		$query = "select * from $this->tableName order by fecha_inicio desc";

		$stmt = $this->conn->prepare($query);
		$rs = $stmt->execute();

		return $rs;
	}

	/**
	 * Método encargado de gestionar las búsquedas del buscador.
	 *
	 * @param $search
	 *
	 * @return MySQLResultSet
	 */
	public function searchLike($search) {
		$search = "%$search%";

		$query = sprintf("select * from %s where nombre like :search or descripcion like :search order by fecha_inicio desc",$this->tableName);

		$stmt = $this->conn->prepare($query);
		$stmt->bind(':search', $search);

		return $stmt->execute();
	}

}
?>
