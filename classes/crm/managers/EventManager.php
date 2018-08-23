<?php

namespace crm\managers;

use php\Hashtable;
use php\managers\BaseManager;
use php\sql\ConnectionFactory;
use php\sql\DB;
use php\session;

/**
 * Class EventManager
 *
 * Manager de Eventos
 *
 * @package crm
 * @subpackage managers
 */
class EventManager extends BaseManager
{

	/**
	 * Método que recupera todos los eventos visibles.
	 *
	 * @param \php\Hashtable $filters
	 *
	 * @return \php\sql\stmt\MySQLResultSet
	 */
	public static function getVisibleEvents(Hashtable $filters)
	{
		$conn = new ConnectionFactory();

		$extraConds = "";
		$binds = array();

		if ($filters->has('userId')){
			$extraConds .= ' and id_usuario = :id_usuario';
			$binds[':id_usuario'] = $filters->userId;
		}

		if ($filters->has('startDate')){
			$extraConds .= " and fecha_inicio >= :fecha_inicio";
			$binds[':fecha_inicio'] = $filters->startDate;
		}

		if ($filters->has('endDate')){
			$extraConds .= " and fecha_fin < :fecha_fin";
			$binds[':fecha_fin'] = $filters->endDate;
		}

		$db_master = MASTER_DATABASE_NAME;

		$sql = "select * from eventos e 
						join eventos_usuarios eu using (id_evento)
						join $db_master.tipos_eventos te using (id_tipo_evento)
			where 1=1 $extraConds";

		$stmt = $conn->prepare($sql);
		$stmt->binds($binds);

		$rs = $stmt->execute();

		return $rs;
	}

}
?>
