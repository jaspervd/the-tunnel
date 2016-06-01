<?php
require_once WWW_ROOT . 'dao/DAO.php';
class GroupsDAO extends DAO {
	public function selectAll() {
		$sql = "SELECT * FROM `tt_groups`";
		$qry = $this->pdo->prepare($sql);
		if($qry->execute()) {
			return $qry->fetchAll(pdo::FETCH_ASSOC);
		}
		return array();
	}

	public function selectById($id) {
		$sql = "SELECT * FROM `tt_groups` WHERE `id` = :id";
		$qry = $this->pdo->prepare($sql);
		$qry->bindValue(':id', $id);
		if($qry->execute()) {
			return $qry->fetch(pdo::FETCH_ASSOC);
		}
		return array();
	}

	public function selectGroupsByUserId($user_id) {
		$sql = "SELECT `tt_groups`.*, `tt_user_groups`.`user_id` FROM `tt_groups` LEFT JOIN `tt_user_groups` ON `tt_groups`.`id` = `tt_user_groups`.`group_id` WHERE `tt_user_groups`.`user_id` = :user_id";
		$qry = $this->pdo->prepare($sql);
		$qry->bindValue(':user_id', $user_id);
		if($qry->execute()) {
			return $qry->fetchAll(pdo::FETCH_ASSOC);
		}
		return array();
	}

	public function insert($title, $info, $creator_id) {
		$sql = "INSERT INTO `tt_groups` (`title`, `info`, `creator_id`) VALUES (:title, :info, :creator_id)";
		$qry = $this->pdo->prepare($sql);
		$qry->bindValue(':title', $title);
		$qry->bindValue(':info', $info);
		$qry->bindValue(':creator_id', $creator_id);
		if($qry->execute()) {
			return $this->selectById($this->pdo->lastInsertId());
		}
		return array();
	}

	public function update($id, $title, $creator_id, $approved) {
		$sql = "UPDATE `tt_groups` SET `title` = :title, `creator_id` = :creator_id, `approved` = :approved WHERE `id` = :id";
		$qry = $this->pdo->prepare($sql);
		$qry->bindValue(':id', $id);
		$qry->bindValue(':title', $title);
		$qry->bindValue(':creator_id', $creator_id);
		$qry->bindValue(':approved', $approved);
		if($qry->execute()) {
			return $this->selectById($id);
		}
		return array();
	}

	public function delete($id) {
		$sql = "DELETE FROM `tt_groups` WHERE `id` = :id";
		$qry = $this->pdo->prepare($sql);
		$qry->bindValue(':id', $id);
		if($qry->execute()) {
			$sql = "DELETE FROM `tt_user_groups` WHERE `group_id` = :id";
			$qry = $this->pdo->prepare($sql);
			$qry->bindValue(':id', $id);
			return $qry->execute();
		}
		return false;
	}
}
