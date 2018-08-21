<?

namespace crm\controllers\admin;

use crm\CRMController;
use crm\factories\Business;
use crm\factories\I18N;
use crm\factories\ORM;
use crm\i18n\AgendaViewI18N;
use php\Hashtable;
use php\Session;
use php\util\Request;
use php\XMLModel;

/**
 * Class ConfigurationController
 *
 * Controller encargado de agrupar todos los datos de configuración.
 *
 * @package crm
 * @subpackage controllers
 * @subpackage admin
 */
class ConfigurationController extends CRMController  {

	/**
	 * Muestra la configuración de la aplicación
	 *
	 * @app
	 *
	 * @param XMLModel $appNode Modelo
	 * @param Hastable $args Parametros 
	 * @return string Vista del controlador
	 */
	public function defaultAction(XMLModel $appNode, Hashtable $args) {
		$this->personalInfoAction($appNode, $args);
	}

	/**
	 * Acceso a Información personal del admin.
	 *
	 * @app
	 *
	 * @param XMLModel $appNode
	 * @param Hashtable $args
	 *
	 */
	public function personalInfoAction(XMLModel $appNode, Hashtable $args){
		$appNode['menu'] = 'personalInfo';
	}

	/**
	 * Acceso a configuración de datos del concesionario.
	 *
	 * @app
	 *
	 * @param XMLModel $appNode
	 * @param Hashtable $args
	 */
	public function crmConfigurationAction(XMLModel $appNode, Hashtable $args){
		$appNode['menu'] = 'crmConfiguration';

		$userId = Session::getSessionVar('idUsuario');
		$userConfiguration = ORM::UsuariosConfiguracion()->getById($userId);


		$agendaView = $appNode->addChild('agendaView');
		$agendaView['agendaView'] = $userConfiguration->agendaView;

		$agendaViewValues = I18N::AgendaViewI18N()->getAll();

		foreach($agendaViewValues as $key => $val){
			$agendaViewValueModel = $agendaView->addChild('agendaViewValue');
			$agendaViewValueModel['id'] = $key;
			$agendaViewValueModel['name'] = $val;
		}



	}

	/**
	 * Acción mediante la que guardaremos todos los datos del usuario.
	 *
	 * @app
	 *
	 * @param XMLModel $appNode modelo
	 * @param Hashtable $args parámetros.
	 */
	public function saveAction(XMLModel $appNode, Hashtable $args){

		Business::UserConfigurationBusiness(array('extraNS' => 'admin'))->saveUserData($args);

		Request::redirect('/app/admin/configuration.'.$args->crmConfiguration);
	}

}
?>
