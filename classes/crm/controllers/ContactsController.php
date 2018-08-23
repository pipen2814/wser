<?

namespace crm\controllers;

use php\Hashtable;
use crm\CRMController;
use crm\orm\Contactos;
use crm\orm\ContactosModosContacto;
use crm\orm\ContactosDirecciones;
use crm\orm\ContactosOtros;
use crm\i18n\ContactStatusI18N;
use crm\i18n\ContactModesI18N;
use crm\i18n\StreetTypesI18N;
use crm\managers\ContactManager;
use php\XMLModel;

/**
 * Gestion de contactos
 *
 * @package crm
 * @subpackage controllers
 */
class ContactsController extends CRMController{


	/**
	 * Acción por defecto del listado de contactos.
	 *
	 * @app
	 *
	 * @param XMLModel $appNode
	 * @param Hashtable $args
	 */
	public function defaultAction(XMLModel $appNode, Hashtable $args){
		$args->view = "complete";
		$this->createRegisters($appNode, $args);
	}

	/**
	 * Método encargado de crear los registros de contactos en el nodo.
	 *
	 * @param \php\XMLModel $appNode
	 * @param \php\Hashtable $filters
	 */
	protected function createRegisters(XMLModel $appNode, Hashtable $filters){

		$contactsNode = $appNode->addChild( 'contacts' );

		$orm = new Contactos();

		if(is_null($filters->pag)){
			$contactsFrom = 0;
			$actualPage = 1;
		}else{
			$contactsFrom = (int)DEFAULT_LIST_SIZE *($filters->pag -1 ) ;
			$actualPage = $filters->pag;
		}
		switch($filters->view){
			case "search":
				$contactsList = $orm->getFoundContacts(utf8_decode($filters->search), $contactsFrom);
				$totalCount = $orm->getTotalFoundContactsCount(utf8_decode($filters->search));
				break;
			case "complete":
			default:
				$contactsList = $orm->getAllContacts($contactsFrom);
				$totalCount = $orm->getTotalContactsCount();
				break;
		}

		$pagesNumber = ((int)$totalCount / (int)DEFAULT_LIST_SIZE);
		if($pagesNumber == 0) $pagesNumber = 1;
		$pagesNumber = (floor($pagesNumber)!= $pagesNumber?floor($pagesNumber)+1:$pagesNumber);

		$paginatorNode = $appNode->addChild("paginator");
		$paginatorNode['pagesNumber'] = $pagesNumber;
		$paginatorNode['contactsPage'] = DEFAULT_LIST_SIZE;
		$paginatorNode["actualPage"] = $actualPage;
		
		$count = 0;

		while($contact = $contactsList->next()){

			$count++;
			$contactNode = $contactsNode->addChild("contact");
			$contactNode['id_contacto'] = $contact->id_contacto;
			$contactNode['count'] = $count;
			$contactNode['estado'] = contactStatusI18N::getTrad($contact->id_estado_negociacion);
			$contactNode['nombre'] = utf8_encode($contact->nombre);
			$contactNode['apellidos'] = utf8_encode($contact->apellidos);
			$contactNode['dni'] = utf8_encode($contact->dni);
			$contactNode['contacto'] = utf8_encode($contact->contacto);
		}
		$contactsNode['count'] = $count;
		$contactsNode['totalCount'] = $totalCount;
		
	}

	/**
	 * Método que muestra un contacto.
	 *
	 * @app
	 *
	 * @param \php\XMLModel $appNode
	 * @param \php\Hashtable $args
	 *
	 * @return string
	 */
	public function contactDisplayAction(XMLModel $appNode, Hashtable $args){

		$contactNode = $appNode->addChild("contact");
		$orm = new Contactos();
		$contact = $orm->getByPK($args->contactId);

		if(!is_null($contact)){
			$contactNode["contactId"] = $contact->idContacto;
			$contactNode["firstName"] = utf8_encode($contact->nombre);
			$contactNode["lastName"] = utf8_encode($contact->apellidos);
			$contactNode['dni'] = utf8_encode($contact->dni);
			$contactNode['birthdate'] = $this->defaultDateFormat($contact->fechaNacimiento);
			$contactNode['contactStatus'] = strtolower(contactStatusI18N::getTrad($contact->idEstadoNegociacion)); 

			$ormOthers = new ContactosOtros();
			$contactOthers = $ormOthers->getByPK($args->contactId);

			$contactNode['LOPD'] = $contactOthers->infoComercial;

			
			$ormContactModes = new contactosModosContacto();
			$contactModes = $ormContactModes->getAllByContactId($args->contactId);

			$contactModesNode = $contactNode->addChild("contactModes");
			$count = 0;
			while($contactMode = $contactModes->next()){
				$count++;
				$item = $contactModesNode->addChild("item");
				$item["index"] = $contactMode->id_modo_contacto;
				$item["value"] = utf8_encode($contactMode->valor);
				$item["main"] = $contactMode->principal;
				$item["contactModeName"] = utf8_encode(contactModesI18N::getTrad($contactMode->id_tipo_contacto));
			}
			$contactModesNode["totalContactModes"] =$count;

			//TODO: Falta por agregar la base de datos de comun para poner nombre a localidades y provincias.
			//Para las aficiones crearemos tablas nuevas especificas de marketing.

			$ormContactDirections = new ContactosDirecciones();
			$contactDirections = $ormContactDirections->getAllByContactId($args->contactId);

			$contactDirectionsNode = $contactNode->addChild("contactDirections");
			$count = 0;
			while($contactDirection = $contactDirections->next()){
				$count++;
				$item = $contactDirectionsNode->addChild("item");
				$item["index"] = $contactDirection->id_direccion;
				$item["direction"] = utf8_encode($contactDirection->direccion);
				$item["zipCode"] = utf8_encode($contactDirection->codigo_postal);
				$item["main"] = $contactDirection->principal;
				$item["streetTypeId"] = $contactDirection->id_tipo_via;
				$item["streetType"] = utf8_encode(streetTypesI18N::getTrad($contactDirection->id_tipo_via));
				$item["localityId"] = $contactDirection->id_localidad;
				$item["provinceId"] = $contactDirection->id_provincia;
				$item["locality"] = "Madrid";
				$item["province"] = "Madrid";
			}
			$contactDirectionsNode["totalContactDirections"] = $count;
		}

		if(is_null($args->newContact)){
			return "contact_display";
		}else{
			return "contactEdit";
		}
	}

	/**
	 * Método para buscar contactos.
	 *
	 * @app
	 *
	 * @param \php\XMLModel $appNode
	 * @param \php\Hashtable $args
	 *
	 * @return string
	 */
	public function searchAction(XMLModel $appNode, Hashtable $args){
		$filters = new Hashtable();
		$filters->view = "search";
		$filters->search = $args->string;
		$this->createRegisters($appNode, $filters);
		return "contacts";
	}

	/**
	 * Método para guardar contactos
	 *
	 * @app
	 * @api
	 *
	 * @param \php\XMLModel $appNode
	 * @param \php\Hashtable $args
	 */
	public function saveContactChangesAction(XMLModel $appNode, Hashtable $args){
		header('Content-Type: application/json');

		ContactManager::modifyContactData($args);

		$postData = array();
		foreach($args as $index => $arg){
			$postData[$index] = $arg;
		}
		$postData = json_encode($postData, JSON_FORCE_OBJECT);
		echo $postData; 
		exit;

	}
}
?>
