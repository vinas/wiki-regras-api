<?php

namespace Application\Factory;

use SaSeed\Handlers\Mapper;
use SaSeed\Handlers\Exceptions;

use Application\Model\CampoModel;

class CampoFactory extends \SaSeed\Database\DAO {

	private $db;
	private $queryBuilder;
	private $table = 'campos';

	public function __construct() {
		$this->db = parent::setDatabase('localhost');
		$this->queryBuilder = parent::setQueryBuilder();
	}

	public function getByNome($nome = false) {
		$campo = new CampoModel();
		try {
			$this->queryBuilder->from($this->table);
			$this->queryBuilder->where([
					'nome',
					'=',
					$nome,
					$this->queryBuilder->getMainTableAlias()
				]);
			$campo = Mapper::populate(
					$campo,
					$this->db->getRow($this->queryBuilder->getQuery())
				);
		} catch (Exception $e) {
			Exceptions::throwing(__CLASS__, __FUNCTION__, $e);
		} finally {
			return $campo;
		}
	}

}
