<?php

namespace crm\util\notifications;

use crm\orm\Alertas;

/**
 * Class Alert
 *
 * Objeto de Alertas.
 *
 * @package crm
 * @subpackage util
 * @subpackage notifications
 */
class Alert extends BaseNotification  {


	protected $type = self::ALERT;

	/**
	 * Alert constructor.
	 *
	 * @param \crm\orm\Alertas $alerta
	 */
	public function __construct(Alertas $alerta = null) {
		if (is_null($alerta)){
			parent::__construct();
		}else {
			parent::__construct($alerta->idAlerta, $alerta->prioridad, $alerta->nombre, $alerta->descripcion,
				$alerta->fechaInicio, $alerta->fechaFin);
		}
	}
}