<?

namespace crm\i18n;

use php\i18n\BaseI18N;

/**
 * Class ContactModesI18N
 *
 * Clase ContactMode, declaramos los modos de contacto
 *
 * @package crm
 * @subpackage i18n
 */
class ContactModesI18N extends BaseI18N {

	/**
	 * @var string $key contactModes
	 */
	public static $key = "contactModes";
	
	const LANDLINE = 1;
	const MOBILE = 2;
	const EMAIL = 3;
	const FAX = 4;

}
?>
