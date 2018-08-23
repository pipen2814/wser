<?
namespace crm\controllers\agenda;

use crm\CRMController;
use crm\factories\I18N;
use crm\factories\ORM;
use crm\managers\EventManager;
use crm\managers\UserManager;
use php\exceptions\Exception;
use php\Hashtable;
use php\Session;
use php\util\Date;
use php\util\Request;
use php\XMLModel;

/**
 * Class AgendaController
 *
 * Controlador de Agenda.
 *
 * @package crm
 * @subpackage controllers
 * @subpackage agenda
 */
class AgendaController extends CRMController {

	/**
	 * Acción por defecto del AgendaController.
	 *
	 * @app
	 *
	 * @param XMLModel $appNode Nodo base del modelo.
	 * @param Hashtable $hashtable Argumentos pasados por parámetro.
	 */
	public function defaultAction(XMLModel $appNode, Hashtable $args) {
		if ($args->has('mainFrame')) {
			$appNode['mainFrame'] = 1;
		}

		$calendar = $appNode->addChild('calendar');

		$agendaView = ORM::UsuariosConfiguracion()->getById(Session::getSessionVar('idUsuario'));

		// TODO: WTF?? donde !is_null no me deja poner un isset?!!??!?
		if (isset($agendaView) && !is_null($agendaView->agendaView)) $agendaView = $agendaView->agendaView;
		else $agendaView = 1;

		$calendar['defaultView'] = I18N::AgendaViewI18N()->getFullcalendarNameByKey($agendaView);
	}

	/**
	 * Acción de la ventana de Nuevo Evento.
	 *
	 * @app
	 *
	 * @param XMLModel $appNode Nodo base del modelo.
	 * @param Hashtable $args Argumentos pasados por parámetro.
	 *
	 * @return string Nombre de la vista que buscaremos.
	 */
	public function newEventAction(XMLModel $appNode, Hashtable $args) {
		$eventTypes = $appNode->addChild('tipos_eventos');

		$tiposEventos = ORM::TiposEventos()->getAll();
		foreach ($tiposEventos as $te) {
			$item = $eventTypes->addChild('item');
			$item['id'] = $te->id_tipo_evento;
			$item['tipo'] = utf8_encode($te->tipo_evento);
		}

		return 'new_event';
	}

	/**
	 * Método para editar un evento.
	 *
	 * @app
	 *
	 * @param XMLModel $appNode
	 * @param Hashtable $args
	 *
	 * @return string
	 */
	public function editAction(XMLModel $appNode, Hashtable $args) {
		if (!$args->has('id')){
			throw new Exception('Required param \'id\' not found.');
		}

		$event = ORM::Eventos()->getByPK($args->id);

		$fechaInicio = new Date($event->fechaInicio);
		$fechaFin = new Date($event->fechaFin);


		$eventModel = $appNode->addChild('event');
		$eventModel->appendORMAsAttributes($event);

		$dateFromModel = $eventModel->addChild('fecha_inicio');
		$fechaInicio->insertIntoModel($dateFromModel);

		$dateToModel = $eventModel->addChild('fecha_fin');
		$fechaFin->insertIntoModel($dateToModel);

		$eventTypes = $appNode->addChild('tipos_eventos');

		$tiposEventos = ORM::TiposEventos()->getAll();
		foreach ($tiposEventos as $te) {
			$item = $eventTypes->addChild('item');
			$item['id'] = $te->id_tipo_evento;
			$item['tipo'] = utf8_encode($te->tipo_evento);
		}

		return 'new_event';
	}

	/**
	 * Acción Encargada de crear un evento a través de la API.
	 *
	 * @api
	 *
	 * @param XMLModel $appNode
	 * @param Hashtable $args
	 */
	public function addEventAction(XMLModel $appNode, Hashtable $args) {

		// Primero recuperamos el idUsuario de la sesión.
		$userId = Session::getSessionVar('idUsuario');

		// Creamos el evento con los datos pasados por post.
		if ($args->has('updateAction') && $args->updateAction == 1){
			if ($args->has('eventId')){
				$evento = ORM::Eventos()->getByPK($args->eventId);
			}else{
				$evento = ORM::Eventos();
			}
		}else{
			$evento = ORM::Eventos();
		}


		$evento->idTipoEvento = $args->eventType;
		$evento->evento = $args->eventName;
		$evento->comentarios = $args->eventComment;
		$evento->fechaInicio = $args->eventStartDate;
		$evento->fechaFin = $args->eventFinishDate;

		$evento->save();


		if (!($args->has('updateAction') && $args->updateAction == 1)) {
			// Creamos el evento para ese usuario.
			$eventoUsuario = ORM::EventosUsuarios();

			$eventoUsuario->idEvento = $evento->idEvento;
			$eventoUsuario->idUsuario = $userId;

			$eventoUsuario->save();
		}
	}

	/**
	 * Método de la api para traerse todos los eventos del calendario en curso.
	 *
	 * @api
	 *
	 * @param XMLModel $model
	 * @param Hashtable $args
	 */
	public function getAllEventsAction(XMLModel $appNode, Hashtable $args){
		$filters = new Hashtable();
		$filters->userId = Session::getSessionVar('idUsuario');

		if ($args->has('startDate')){
			$filters->startDate = $args->startDate;
		}

		if ($args->has('endDate')){
			$filters->endDate = $args->endDate;
		}

		$rs = EventManager::getVisibleEvents($filters);

		$eventsModel = $appNode->addChild('events');
		$eventsModel->addRS($rs);
	}

}

?>
