<?php
/**
* Application Error Handling Service Class
*
* @author Vinas de Andrade <vinas.andrade@gmail.com>
* @since 2016/10/19
* @version 1.16.1026
* @license SaSeed\license.txt
*/

namespace Application\Service;

use SaSeed\Handlers\Exceptions;

use Application\Model\ResponseModel;

class ResponseHandlerService
{
	public function handleCode($code)
	{
		$res = new ResponseModel();

		switch ($code) {
			case 200:
				$res->setCode($code);
				$res->setMessage($this->info('Everything went just fine!'));
				break;
			case 500:
				$res->setCode($code);
				$res->setMessage($this->error('It seems something went wrong... check back-end logs.'));
				break;
			default:
				$res->setCode(666);
				$res->setMessage($this->warning());
		}

		return $res;
	}

	public function handleErrorMessage($msg = false, $code = true)
	{
		$res = new ResponseModel();
		if ($code === true)
			$res->setCode(500);
		if ($code > 0)
			$res->setCode($code);
		$res->setMessage($this->error($msg));
		return $res;
	}

	public function handleInfoMessage($msg = false, $code = true)
	{
		$res = new ResponseModel();
		if ($code === true)
			$res->setCode(100);
		if ($code > 0)
			$res->setCode($code);
		$res->setMessage($this->info($msg));
		return $res;
	}

	public function handleWarningMessage($msg = false, $code = true)
	{
		$res = new ResponseModel();
		if ($code === true)
			$res->setCode(100);
		if ($code > 0)
			$res->setCode($code);
		$res->setMessage($this->warning($msg));
		return $res;
	}

	private function error($msg = false)
	{
		if ($msg) {
			return 'Error: '.$msg;
		}
		return 'Error: An unexpected error. not that we are expecting any...';
	}

	private function warning($msg = false)
	{
		if ($msg) {
			return 'Warning: '.$msg;
		}
		return 'Warning: Some uninterpreted odd behavior has occured. Fishy...';
	}

	private function info($msg = false)
	{
		if ($msg) {
			return 'Info: '.$msg;
		}
		return 'Info: You should take notice of somehing. We just happened to forget tell you what.';
	}
}
