<?php

namespace crm\i18n;

use php\i18n\BaseI18N;

/**
 * Class AgendaViewI18N
 *
 * I18N de opciones de visualización de la agenda.
 *
 * @package crm
 * @subpackage i18n
 */
class AgendaViewI18N extends BaseI18N   {

	public static $key = 'agendaView';

	const MONTH_VIEW = 1;
	const WEEK_VIEW = 2;
	const DAY_VIEW = 3;

	/**
	 * Método con el que devolvemos el valor que espera fullCalendar para cambiar de vista.
	 *
	 * @param int $key Nuestra clave.
	 *
	 * @return string Valor esperado por FullCalendar para cambiar la vista.
	 */
	public function getFullcalendarNameByKey( $key ){
		switch ($key){

			case self::WEEK_VIEW:
				return 'agendaWeek';
				break;
			case self::DAY_VIEW:
				return 'agendaDay';
				break;

			default:
				return 'month';
				break;
		}
	}
}