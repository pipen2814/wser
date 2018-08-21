<?php

namespace crm\controllers;
use crm\CRMController;
use crm\factories\Business;
use php\Hashtable;
use php\Session;
use php\XMLModel;

/**
 * Class NotificationsController
 *
 * Controlador de Notificaciones y Alertas del sistema (que también aparecerán como notificaciones).
 *
 * @package crm
 * @subpackage controllers
 */
class NotificationsController extends CRMController{

	/**
	 * Método desde el que recuperamos todas las notificaciones para el usuario.
	 *
	 * @api
	 *
	 * @param XMLModel $model
	 * @param Hashtable $args
	 */
	public function getAllAction(XMLModel $model, Hashtable $args){

		$params = new Hashtable();
		$params->idUsuario = Session::getSessionVar('idUsuario');
		$params->idOrganizacion = Session::getSessionVar('idOrganizacion');
		$params->idPerfil = ""; // TODO: Pendiente de los permisos/perfiles de @amora


		$data = Business::NotificationBusiness()->getNotifications($params);


		$notificationsModel = $model->addChild('notifications');
		if (is_array($data)){
			foreach ( $data as $arr ){
				$arrValues = $arr->toArray();
				$itemModel = $notificationsModel->addChild('item');
				$itemModel->addArray($arrValues);
			}
		}
	}
}