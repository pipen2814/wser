<?

namespace crm\orm;

use php\orm\BaseORM;
/**
 *
 * @package crm
 * @subpackage orm
 */
class AlertasOrganizaciones extends \crm\orm\defs\AlertasOrganizaciones  {
	/**
	 * Método encargado de borrar todas las relaciones entre alertas y organizaciones a partir
	 * de un id de alerta.
	 *
	 * @param $alertId
	 */
	public function deleteByAlertId( $alertId ){

		$query = "delete from $this->tableName where id_alerta = :alertId";

		$stmt = $this->conn->prepare($query);
		$stmt->bind(':alertId', $alertId);

		$stmt->execute();
	}

	/**
	 * Método que recupera todas las relaciones entre alertas y organizaciones a partir
	 * de un id de alerta.
	 *
	 * @param $alertId
	 */
	public function getByAlertId( $alertId ){
		$query = "select * from $this->tableName where id_alerta = :alertId";

		$stmt = $this->conn->prepare($query);
		$stmt->bind(':alertId', $alertId);

		$rs = $stmt->execute();

		return $rs;
	}

}
?>
