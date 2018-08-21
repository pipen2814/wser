<?php

namespace crm\util\notifications;

use php\BaseObject;

/**
 * Class BaseNotification
 *
 * Objeto genérico de notificaciones. De este objeto extenderán los objetos de
 * alertas, notificaciones, y mensajes del sistema.
 *
 * @package crm
 * @subpackage util
 * @subpackage notifications
 */
class BaseNotification extends BaseObject {

	const ALERT = "1";
	const NOTIFICATION = "2";
	const SYSTEM_MESSAGE = "3";

	/**
	 * @var null Identificador de la notificación.
	 */
	protected $id = null;

	/**
	 * @var null Prioridad de la Notificación.
	 */
	protected $priority = null;

	/**
	 * @var null Título de la Notificación.
	 */
	protected $name = null;

	/**
	 * @var null Descripción de la Notificación.
	 */
	protected $description = null;

	/**
	 * @var null FechaHora inicio de la Notificación.
	 */
	protected $startDate = null;

	/**
	 * @var null FechaHora fin de la Notificación.
	 */
	protected $endDate = null;

	/**
	 * @var null Visibilidad de la Notificación.
	 */
	protected $visible = null;

	/**
	 * @var null Tipo de la notificacion.
	 */
	protected $type = null;

	/**
	 * BaseNotification constructor.
	 */
	public function __construct($id = null, $priority = null, $name = null, $description = null,
								$startDate = null, $endDate = null) {
		$this->id = $id;
		$this->priority = $priority;
		$this->name = $name;
		$this->description = $description;
		$this->startDate = $startDate;
		$this->endDate = $endDate;
	}

	/**
	 * Getter de Prioridad
	 *
	 * @return null
	 */
	public function getPriority() {
		return $this->priority;
	}

	/**
	 * Setter de Prioridad
	 *
	 * @param null $priority
	 */
	public function setPriority($priority) {
		$this->priority = $priority;
	}

	/**
	 * Getter de Nombre
	 *
	 * @return null
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Setter de Nombre
	 *
	 * @param null $name
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * Getter de Descripción
	 *
	 * @return null
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * Setter de Descripción
	 *
	 * @param null $description
	 */
	public function setDescription($description) {
		$this->description = $description;
	}

	/**
	 * Getter de StartDate
	 *
	 * @return null
	 */
	public function getStartDate() {
		return $this->startDate;
	}

	/**
	 * Setter de StartDate
	 *
	 * @param null $startDate
	 */
	public function setStartDate($startDate) {
		$this->startDate = $startDate;
	}

	/**
	 * Getter de EndDate
	 *
	 * @return null
	 */
	public function getEndDate() {
		return $this->endDate;
	}

	/**
	 * Setter de EndDate
	 *
	 * @param null $endDate
	 */
	public function setEndDate($endDate) {
		$this->endDate = $endDate;
	}

	/**
	 * Getter de Visibilidad
	 *
	 * @return null
	 */
	public function getVisible() {
		return $this->visible;
	}

	/**
	 * Setter de Visibilidad
	 *
	 * @param null $visible
	 */
	public function setVisible($visible) {
		$this->visible = $visible;
	}

	/**
	 * Método que devuelve un array a partir de un objeto.
	 *
	 * @return array
	 */
	public function toArray(){
		return array(
			"id" => $this->id,
			"prioridad" => $this->priority,
			"nombre" => $this->name,
			"descripcion" => $this->description,
			"fechaInicio" => $this->startDate,
			"fechaFin" => $this->endDate
		);
	}
}