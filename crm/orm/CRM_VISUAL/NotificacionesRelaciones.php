<?

namespace crm\orm;

use php\orm\BaseORM;
/**
 *
 * @package crm
 * @subpackage orm
 */
class NotificacionesRelaciones extends \crm\orm\defs\NotificacionesRelaciones  {

	/**
	 * Método encargado de borrar todas las relaciones entre Notificaciones a partir de un idNotificacion
	 *
	 * @param notificationId
	 */
	public function deleteByNotificationId( $notificationId ){

		$query = "delete from $this->tableName where id_notificacion = :notificationId";

		$stmt = $this->conn->prepare($query);
		$stmt->bind(':notificationId', $notificationId);

		$stmt->execute();
	}

	/**
	 * Método que recupera todas las relaciones de una notificacion.
	 *
	 * @param notificationId
	 */
	public function getByNotificationId( $notificationId ){
		$query = "select * from $this->tableName where id_notificacion = :notificationId";

		$stmt = $this->conn->prepare($query);
		$stmt->bind(':notificationId', $notificationId);

		$rs = $stmt->execute();

		return $rs;
	}

}
?>
