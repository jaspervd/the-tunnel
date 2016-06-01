<?php
require_once WWW_ROOT . 'dao/DAO.php';
class LikesDAO extends DAO {
	public function selectAll() {
		$sql = "SELECT * FROM `tt_likes";
		$qry = $this->pdo->prepare($sql);
		if($qry->execute()) {
			return $qry->fetchAll(pdo::FETCH_ASSOC);
		}
		return array();
	}

	public function selectById($id) {
		$sql = "SELECT * FROM `tt_likes` WHERE `id` = :id";
		$qry = $this->pdo->prepare($sql);
		$qry->bindValue(':id', $id);
		if($qry->execute()) {
			return $qry->fetch(pdo::FETCH_ASSOC);
		}
		return array();
	}

	public function insert($creation_id, $guest_ip, $user_id = '0') {
		$sql = "INSERT INTO `tt_likes` (`creation_id`, `guest_ip`, `user_id`) VALUES (:creation_id, :guest_ip, :user_id)";
		$qry = $this->pdo->prepare($sql);
		$qry->bindValue(':creation_id', $creation_id);
		$qry->bindValue(':guest_ip', $guest_ip);
		$qry->bindValue(':user_id', $user_id);
		if($qry->execute()) {
			return $this->selectById($this->pdo->lastInsertId());
		}
		return array();
	}

	public function delete($creation_id, $user_id) {
		$sql = "DELETE FROM `tt_likes` WHERE `creation_id` = :creation_id AND `user_id` = :user_id";
		$qry = $this->pdo->prepare($sql);
		$qry->bindValue(':creation_id', $creation_id);
		$qry->bindValue(':user_id', $user_id);
		return $qry->execute();
	}
}
