<?php
require_once WWW_ROOT . 'dao/DAO.php';
class ScoresDAO extends DAO {
	public function selectAll() {
		$sql = "SELECT * FROM `tt_scores";
		$qry = $this->pdo->prepare($sql);
		if($qry->execute()) {
			return $qry->fetchAll(pdo::FETCH_ASSOC);
		}
		return array();
	}

	public function selectByCreationId($creation_id) {
		$sql = "SELECT * FROM `tt_scores` WHERE `creation_id` = :creation_id";
		$qry = $this->pdo->prepare($sql);
		$qry->bindValue(':creation_id', $creation_id);
		if($qry->execute()) {
			return $qry->fetch(pdo::FETCH_ASSOC);
		}
		return array();
	}

	public function selectByUserId($user_id) {
		$sql = "SELECT * FROM `tt_scores` WHERE `user_id` = :user_id";
		$qry = $this->pdo->prepare($sql);
		$qry->bindValue(':user_id', $user_id);
		if($qry->execute()) {
			return $qry->fetchAll(pdo::FETCH_ASSOC);
		}
		return array();
	}

	public function insert($user_id, $creation_id, $score) {
		$sql = "INSERT INTO `tt_scores` (`user_id`, `creation_id`, `score`) VALUES (:user_id, :creation_id, :score)";
		$qry = $this->pdo->prepare($sql);
		$qry->bindValue(':user_id', $user_id);
		$qry->bindValue(':creation_id', $creation_id);
		$qry->bindValue(':score', $score);
		if($qry->execute()) {
			return $this->selectById($this->pdo->lastInsertId());
		}
		return array();
	}

	public function delete($creation_id, $user_id) {
		$sql = "DELETE FROM `tt_scores` WHERE `creation_id` = :creation_id AND `user_id` = :user_id";
		$qry = $this->pdo->prepare($sql);
		$qry->bindValue(':creation_id', $creation_id);
		$qry->bindValue(':user_id', $user_id);
		return $qry->execute();
	}
}
