<?php

namespace crm\i18n;

use php\i18n\BaseI18N;

/**
 * Class NotificationPriorityI18N
 *
 * I18N de prioridades de las notificaciones.
 *
 * @package crm
 * @subpackage i18n
 */
class NotificationPriorityI18N extends BaseI18N   {

	public static $key = 'notification_priority';

	const VERY_HIGH = 1;
	const HIGH = 2;
	const NORMAL = 3;
	const LOW = 4;
	const VERY_LOW = 5;

}