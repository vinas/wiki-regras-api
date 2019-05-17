<?php

namespace Application\Model;

class RegraModel implements \JsonSerializable
{

	private $id;
	private $idCampo;
	private $descricao;

	public function setId($id = false) {
		$this->id = $id;
	}
	public function getId() {
		return $this->id;
	}

	public function setIdCampo($idCampo = false) {
		$this->idCampo = $idCampo;
	}
	public function getIdCampo() {
		return $this->idCampo;
	}

	public function setDescricao($descricao = false) {
		$this->descricao = $descricao;
	}
	public function getDescricao() {
		return $this->descricao;
	}

	public function listProperties() {
		return array_keys(get_object_vars($this));
	}

	public function JsonSerialize()
	{
		return get_object_vars($this);
	}
}
