<?php
/**
* Users Controller Class
*
* @author Vinas de Andrade <vinas.andrade@gmail.com>
* @since 2015/10/26
* @version 1.16.1026
* @license SaSeed\license.txt
*/

namespace Application\Controller;

use SaSeed\Output\RestView;

use Application\Service\ResponseHandlerService;
use Application\Service\CampoService;

/*
use SaSeed\Handlers\Exceptions;
use SaSeed\Handlers\Mapper;

use Application\Model\UserModel;
*/

class CamposController
{

	private $service;
	private $responseHandler;
	private $params;

	public function __construct($params) {
		$this->responseHandler = new ResponseHandlerService();
		$this->service = new CampoService();
		$this->params = $params;
	}

	public function index() {
		$res = $this->responseHandler->handleCode(200);
		RestView::render('index', $res);
	}

	public function getCampo() {
		RestView::render(
			$this->service->getCampoByNome($this->params[0]),
			$this->responseHandler->handleCode(200)
		);
	}
}
