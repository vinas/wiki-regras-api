<?php
/**
* User Service Class
*
* @author Vinas de Andrade <vinas.andrade@gmail.com>
* @since 2015/10/26
* @version 1.16.1026
* @license SaSeed\license.txt
*/

namespace Application\Service;

use SaSeed\Handlers\Exceptions;

use Application\Factory\CampoFactory;

class CampoService {

	private $factory;

	public function __construct() {
		$this->factory = new CampoFactory();
	}

	public function getCampoByNome($nome = false) {
		$campo = false;
		try {
			if ($nome)
				$campo = $this->factory->getByNome($nome);
			return $campo;
		} catch (Exception $e) {
			Exceptions::throwing(__CLASS__, __FUNCTION__, $e);
		}
	}

}
