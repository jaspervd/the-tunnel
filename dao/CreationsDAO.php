<?php
require_once WWW_ROOT . 'dao/DAO.php';
class CreationsDAO extends DAO {
	public function selectAll() {
		$sql = "SELECT * FROM `tt_creations";
		$qry = $this->pdo->prepare($sql);
		if($qry->execute()) {
			return $qry->fetchAll(pdo::FETCH_ASSOC);
		}
		return array();
	}

	public function selectById($id) {
		$sql = "SELECT * FROM `tt_creations` WHERE `id` = :id";
		$qry = $this->pdo->prepare($sql);
		$qry->bindValue(':id', $id);
		if($qry->execute()) {
			return $qry->fetch(pdo::FETCH_ASSOC);
		}
		return array();
	}

	public function selectCreationsByUserId($user_id) {
		$sql = "SELECT `tt_creations` WHERE `creator_id` = :user_id";
		$qry = $this->pdo->prepare($sql);
		$qry->bindValue(':user_id', $user_id);
		if($qry->execute()) {
			return $qry->fetchAll(pdo::FETCH_ASSOC);
		}
		return array();
	}

	public function selectCreationsByGroupId($group_id) {
		$sql = "SELECT `tt_creations` WHERE `group_id` = :group_id";
		$qry = $this->pdo->prepare($sql);
		$qry->bindValue(':group_id', $group_id);
		if($qry->execute()) {
			return $qry->fetchAll(pdo::FETCH_ASSOC);
		}
		return array();
	}

	public function insert($user_id, $title, $info, $image_url, $group_id = '0') {
		$sql = "INSERT INTO `tt_creations` (`user_id`, `title`, `info`, `image_url`, `group_id`) VALUES (:user_id, :title, :info, :image_url, :group_id)";
		$qry = $this->pdo->prepare($sql);
		$qry->bindValue(':user_id', $user_id);
		$qry->bindValue(':title', $title);
		$qry->bindValue(':info', $info);
		$qry->bindValue(':image_url', $image_url);
		$qry->bindValue(':group_id', $group_id);
		if($qry->execute()) {
			return $this->selectById($this->pdo->lastInsertId());
		}
		return array();
	}

	public function update($id, $user_id, $title, $info, $image_url, $group_id, $featured, $elected) {
		$sql = "UPDATE `tt_creations` SET `user_id` = :user_id, `title` = :title, `info` = :info, `image_url` = :image_url, `group_id` = :group_id, `featured` = :featured, `elected` = :elected WHERE `id` = :id";
		$qry = $this->pdo->prepare($sql);
		$qry->bindValue(':id', $id);
		$qry->bindValue(':user_id', $user_id);
		$qry->bindValue(':title', $title);
		$qry->bindValue(':info', $info);
		$qry->bindValue(':image_url', $image_url);
		$qry->bindValue(':group_id', $group_id);
		$qry->bindValue(':featured', $featured);
		$qry->bindValue(':elected', $elected);
		if($qry->execute()) {
			return $this->selectById($id);
		}
		return array();
	}

	public function delete($id) {
		$sql = "DELETE FROM `tt_creations` WHERE `id` = :id";
		$qry = $this->pdo->prepare($sql);
		$qry->bindValue(':id', $id);
		return $qry->execute();
	}
}
