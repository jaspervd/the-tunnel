<?php
require_once WWW_ROOT . 'dao/DAO.php';
class LikesDAO extends DAO {
	public function selectAll() {
		$sql = "SELECT * FROM `tt_likes`";
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

  public function selectByUserId($user_id) {
    $sql = "SELECT * FROM `tt_likes` WHERE `user_id` = :user_id";
    $qry = $this->pdo->prepare($sql);
    $qry->bindValue(':user_id', $user_id);
    if($qry->execute()) {
      return $qry->fetchAll(pdo::FETCH_ASSOC);
    }
    return array();
  }

	public function selectByCreationId($creation_id) {
		$sql = "SELECT * FROM `tt_likes` WHERE `creation_id` = :creation_id";
		$qry = $this->pdo->prepare($sql);
		$qry->bindValue(':creation_id', $creation_id);
		if($qry->execute()) {
			return $qry->fetchAll(pdo::FETCH_ASSOC);
		}
		return array();
	}

	public function countByCreationId($creation_id) {
		$sql = "SELECT COUNT(*) FROM `tt_likes` WHERE `creation_id` = :creation_id";
		$qry = $this->pdo->prepare($sql);
		$qry->bindValue(':creation_id', $creation_id);
		if($qry->execute()) {
			return $qry->fetchColumn();
		}
		return array();
	}

	public function selectByInputAndCreationId($input, $creation_id) {
		$sql = "SELECT * FROM `tt_likes` WHERE (`guest_ip` = :input OR `user_id` = :input) AND `creation_id` = :creation_id";
		$qry = $this->pdo->prepare($sql);
		$qry->bindValue(':input', $input);
		$qry->bindValue(':input', $input);
		$qry->bindValue(':creation_id', $creation_id);
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

	public function delete($id) {
		$sql = "DELETE FROM `tt_likes` WHERE `id` = :id";
		$qry = $this->pdo->prepare($sql);
		$qry->bindValue(':id', $id);
		return $qry->execute();
	}
}
