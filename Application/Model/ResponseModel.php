<?php
/**
* Response User Model
*
* @author Vinas de Andrade <vinas.andrade@gmail.com>
* @since 2016/10/26
* @version 1.16.1026
* @license SaSeed\license.txt
*/ 

namespace Application\Model;

class ResponseModel implements \JsonSerializable
{

	private $code;
	private $message;
	private $content;

	public function setCode($code)
	{
		$this->code = $code;
	}
	public function getCode()
	{
		return $this->code;
	}

	public function setMessage($message)
	{
		$this->message = $message;
	}
	public function getMessage()
	{
		return $this->message;
	}

	public function setContent($content = false) {
		$this->content = $content;
	}
	public function getContent() {
		return $this->content;
	}

	public function listProperties() {
		return array_keys(get_object_vars($this));
	}

	public function JsonSerialize()
	{
		return get_object_vars($this);
	}
}
