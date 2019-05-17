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
use SaSeed\Handlers\Exceptions;
use SaSeed\Handlers\Mapper;

use Application\Model\UserModel;
use Application\Service\ResponseHandlerService;
use Application\Service\UserService;

class UsersController
{

	private $service;
	private $responseHandler;
	private $params;

	public function __construct($params) {
		$this->service = new UserService();
		$this->responseHandler = new ResponseHandlerService();
		$this->params = $params;
	}

	public function teste() {
		echo 'bunda';
	}

	public function listUsers() {
		try {
			$users = $this->service->listUsers();
			$res = $this->responseHandler->handleCode(200);
		} catch (Exception $e) {
			Exceptions::throwing(__CLASS__, __FUNCTION__, $e);
			$res = $this->responseHandler->handleErrorMessage($e->getMessage());
		} finally {
			RestView::render($users, $res);
		}
	}

	public function getUser()
	{
		$user = false;
		try {
			$user = $this->service->getUserById($this->params[0]);
			if ($user) {
				$res = $this->responseHandler->handleCode(200);
			} else {
				$res = $this->responseHandler->handleWarningMessage('No user found.', 200);
			}
		} catch (Exception $e) {
			Exceptions::throwing(__CLASS__, __FUNCTION__, $e);
			$res = $this->responseHandler->handleErrorMessage($e->getMessage());
		} finally {
			RestView::render($user, $res);
		}
	}

	public function save()
	{
		try {
			$user = Mapper::populate(new UserModel(), $this->params);
			$user = $this->service->save($user);
			$res = $this->responseHandler->handleCode(200);
		} catch (Exception $e) {
			Exceptions::throwing(__CLASS__, __FUNCTION__, $e);
			$user = false;
			$res = $this->responseHandler->handleErrorMessage($e->getMessage());
		} finally {
			RestView::render($user, $res);
		}
	}

	public function delete()
	{
		try {
			$this->service->delete($this->params[0]);
			$res = $this->responseHandler->handleInfoMessage('User deleted.', 200);
		} catch (Exception $e) {
			Exceptions::throwing(__CLASS__, __FUNCTION__, $e);
			$res = $this->responseHandler->handleErrorMessage($e->getMessage());
		} finally {
			RestView::render(false, $res);
		}
	}
}
