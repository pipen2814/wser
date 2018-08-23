<?php

namespace crm\factories;

use php\BaseObject;
use php\exceptions\Exception;

/**
 * Class Factory
 *
 * Clase genérica para crear Factories.
 *
 * @todo Implementar Singleton
 *
 * @package crm
 * @subpackage factories
 */
class Factory extends BaseObject {

	/**
	 * @var string $namespace Namespace genérico del Factory a instanciar.
	 */
	protected static $namespace = null;

	/**
	 * @var array $instances Lista de instancias.
	 */
	protected static $instances = array();

	/**
	 * @param string $name Nombre del Objeto a instanciar.
	 * @param array $arguments No se utilizan de momento.
	 *
	 * @return mixed Instancia de una clase mediante Factory.
	 */
	public static function __callStatic( $name, $arguments ){

		if ( array_key_exists($name, self::$instances) )
			return self::$instances[$name];

		return self::getFullNamespace($name, $arguments[0]['extraNS']);

	}

	/**
	 * Método que comprueba si existe el Objeto en el namespace y lo instanciamos
	 *
	 * @todo CRM-19 Namespaces dinámicos
	 *
	 * @param string $class Nombre de la clase.
	 *
	 * @return mixed Nueva instancia.
	 */
	private static function getFullNamespace( $class, $extraNS ){
		$ns[] = "custom";
		$ns[] = "crm";
		$ns[] = "php";
	

		foreach ( $ns as $n ){
			$testNS = "\\" . $n . "\\" . static::$namespace . "\\" . (($extraNS) ? ($extraNS . "\\") : "") ;

			if (self::checkInNamespace( $testNS, $class )){
				$fullClass = $testNS . $class;
				self::$instances[$class] = new $fullClass;

				return self::$instances[$class];
			}
		}

		throw new Exception("Class '$class' not Found in " . $testNS);
	}

	/**
	 * Método que comprueba si está en el Namespace el objeto
	 *
	 * @param string $namespace Namespace de donde miramos si existe el objeto.
	 * @param string $class Nombre del objeto que vamos a buscar.
	 *
	 * @return bool True si existe // false en caso contrario.
	 */
	private static function checkInNamespace( $namespace, $class ){
		try{
			if (class_exists($namespace . $class ))
				return true;
			else
				return false;
		}catch(Exception $e){
			return false;
		}
	}
}
