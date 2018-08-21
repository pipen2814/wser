<?php

namespace crm\managers;

use php\Hashtable;
use php\sql\ConnectionFactory;
use php\util\Date;

/**
 * Class NotificationManager
 *
 * Manager encargado de gestionar las consultas sobre notificaciones.
 *
 * @package crm\managers
 */
class NotificationManager {

	/**
	 * Método que recuperará de base de datos todas las alertas a partir de los filtros pasados como
	 * parámetros.
	 *
	 * @param Hashtable $filters
	 */
	public function listAlertsByFilters( Hashtable $filters ){

		$conn = new ConnectionFactory();

		$date = new Date();

		$extraConds = '';
		$binds = array();

		if ($filters->has('idOrganizacion')){
			$extraConds .=' and ao.id_organizacion = :organizationId';
			$binds[':organizationId'] = $filters->idOrganizacion;
		}

		$db_master = MASTER_DATABASE_NAME;

		$query = "select a.* from $db_master.alertas a join $db_master.alertas_organizaciones ao 
			on (a.id_alerta = ao.id_alerta) where 1=1 $extraConds and :now between fecha_inicio and fecha_fin 
			and visible = '1'";

		$binds[':now'] = $date->formatDate(Date::FORMAT_SQL_DATETIME);

		$stmt = $conn->prepare($query);
		$stmt->binds($binds);

		$rs = $stmt->execute();

		return $rs;
	}

	/**
	 * Método que recuperará de base de datos todas las notificaciones a partir de los filtros pasados como
	 * parámetros
	 *
	 * @param Hashtable $filters
	 */
	public function listNotificationByFilters( Hashtable $filters ){

	}

}