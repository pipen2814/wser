<?php

namespace crm\controllers;

use crm\CRMController;
use php\Hashtable;
use php\Session;
use crm\managers\UserManager;
use php\XMLModel;
use crm\factories\Managers;

/**
 * Pagina de logueo
 *
 * @package crm
 * @subpackage controllers
 */
class LoginController extends CRMController {

	/**
	 * @app
	 *
	 * @param $appNode
	 *
	 * @return string
	 */
	public function defaultAction(XMLModel $appNode, Hashtable $args){
		/*if ($this->isAPI( $args )){
			$userRS = UserManager::getUserByLogin($args->user, $args->pass);
			if ($userRS->next()) {
				$this->generateJWT($appNode, $userRS);
			}
		}*/
		if(!is_null(Session::getSessionVar('idUsuario'))){
			return "main";
		}else{
			$userRS = UserManager::getUserByLogin($args->usuario, $args->password);
			if($userRS->next()){
				$this->generateJWT($appNode, $userRS);
				Session::setUserSession($userRS->id_usuario);
				Session::setSessionVar('nombreUsuario', $userRS->dni);
				Session::setSessionVar('idOrganizacion', $userRS->id_organizacion);
				Session::setSessionVar('hostnameOrganization', $userRS->host);
				UserManager::setDBUserSession($userRS->id_usuario, Session::getSessionId());
				header("Location: /app/index");
			}
		}
	}

	/**
	 * @app
	 * Método para hacer logout.
	 *
	 * @return string Vista.
	 */
	public function logoutAction(){
		//Codigo real del metodo logoutAction
		UserManager::destroyDBUserSession(Session::getSessionId());
		Session::destroy();

		return "logout";
	}

	/**
	 * @api
	 * Metodo para hacer logout.
	 *
	 * @param $model
	 * @param $args
	 *
	 */
	public function loginAPIAction(\stdClass $model, $args){
		$newToken = false;
		//$response = "";
		$model->status = "OK";
		try{
			if(!is_null($args->usuario) && !is_null($args->clave)){
				list($userData,$newToken) = Managers::UserManager()->loginAPI($args->usuario, $args->clave);
			}else{
				//$response = '{"status": "KO", "message": "No se han recibido parametros de login"}';
				throw new \Exception("No se han recibido parametros de login");
				//$this->parseResponse($response);
			}
			if($newToken){
				//$response =  '{"status": "OK", "apiTK": "'.$newToken.'"}';
				$model->apiTK = $newToken;
				$model->userData= $userData;
			}else{
				//$response = '{"status": "KO", "message": "No se pudo crear token, login incorrecto"}';
				throw new \Exception("No se pudo crear token, login incorrecto");
			}
		}catch(\Exception $e){
			$model->status = "KO";
			$model->message = "ERROR: ".$e->getMessage();
		}

		//echo $this->parseResponse($response);
		//return $this->parseResponse($response);
	}

	/**
	 * @api
	 * Metodo para chequear la validez de un token y renovarlo.
	 *
	 * @param $model
	 * @param $args
	 *
	 */
	public function checkConnectionAction(\stdClass $model, $args){
		$newToken = false;
		//$response = "";
		$model->status = "OK";
		try{
			list($userData,$newToken) = Managers::UserManager()->checkAPIToken($args->apiTK);
			if($newToken){
				$model->apiTK = $newToken;
				$model->userData= $userData;
			}else{
				throw new \Exception("No se pudo crear token, login incorrecto");
			}
		}catch(\Exception $e){
			$model->status = "KO";
			$model->message = "ERROR: ".$e->getMessage();
		}
	}


	/**
	 * @api
	 * Metodo para hacer pruebas con el token, hay que eliminarlo.
	 *
	 * @return string Vista.
	 */
	public function checkAPITokenAction(\stdClass $model, $args){
		$tk = Managers::Tokenizer();
		var_dump('<pre>', $args, $_REQUEST['apiTK']);
		echo "<br><br><br>";
		var_dump('<pre>',  $tk->checkValidToken($_REQUEST['apiTK']));
		
	}

	/**
	 * @api
	 * Metodo para renovar el token de conexion mientras haya actividad.
	 *
	 * @param $model
	 * @param $args
	 *
	 */
	public function renovateTokenAction(\stdClass $model, $args){
		$model->status = "OK";
		try{
			list($userInformation, $apiTK) = $this->renoveToken($args->apiTK);
			if($apiTK){
				$model->apiTK = $apiTK;
			}else{
				throw new \Exception("No se pudo crear token");
			}
		}catch(\Exception $e){
			$model->status = "KO";
			$model->message = "ERROR: ".$e->getMessage();
		}
	}
}
