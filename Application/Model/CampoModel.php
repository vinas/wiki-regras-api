<?php

namespace Application\Model;

class CampoModel implements \JsonSerializable
{

	private $id;
	private $nome;
	private $descricao;
	private $formato;
	private $obrigatorio;

	public function setId($id = false) {
		$this->id = $id;
	}
	public function getId() {
		return $this->id;
	}

	public function setNome($nome = false) {
		$this->nome = $nome;
	}
	public function getNome() {
		return $this->nome;
	}

	public function setDescricao($descricao = false) {
		$this->descricao = $descricao;
	}
	public function getDescricao() {
		return $this->descricao;
	}

	public function setFormato($formato = false) {
		$this->formato = $formato;
	}
	public function getFormato() {
		return $this->formato;
	}

	public function setObrigatorio($obrigatorio = false) {
		$this->obrigatorio = $obrigatorio;
	}
	public function getObrigatorio() {
		return $this->obrigatorio;
	}

	public function listProperties() {
		return array_keys(get_object_vars($this));
	}

	public function JsonSerialize()
	{
		return get_object_vars($this);
	}
}
