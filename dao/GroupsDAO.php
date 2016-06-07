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

	public function selectByApproved($approved) {
		$sql = "SELECT * FROM `tt_groups` WHERE `approved` = :approved";
		$qry = $this->pdo->prepare($sql);
		$qry->bindValue(':approved', $approved);
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

	public function selectByUserId($user_id) {
		$sql = "SELECT `tt_groups`.*, `tt_user_groups`.`user_id` FROM `tt_groups` LEFT JOIN `tt_user_groups` ON `tt_groups`.`id` = `tt_user_groups`.`group_id` WHERE `tt_user_groups`.`user_id` = :user_id AND `tt_groups`.`approved` = :approved";
		$qry = $this->pdo->prepare($sql);
		$qry->bindValue(':user_id', $user_id);
		$qry->bindValue(':approved', 1);
		if($qry->execute()) {
			return $qry->fetchAll(pdo::FETCH_ASSOC);
		}
		return array();
	}

	public function insert($data) {
		$sql = "INSERT INTO `tt_groups` (`title`, `info`, `image_url`, `creator_id`) VALUES (:title, :info, :image_url, :creator_id)";
		$qry = $this->pdo->prepare($sql);
		$qry->bindValue(':title', $data['title']);
		$qry->bindValue(':info', $data['info']);
    $qry->bindValue(':image_url', $data['image_url']);
		$qry->bindValue(':creator_id', $data['creator_id']);
		if($qry->execute()) {
			return $this->selectById($this->pdo->lastInsertId());
		}
		return array();
	}

	public function update($data) {
		$sql = "UPDATE `tt_groups` SET `title` = :title, `info` = :info, `creator_id` = :creator_id WHERE `id` = :id";
		$qry = $this->pdo->prepare($sql);
		$qry->bindValue(':id', $data['id']);
		$qry->bindValue(':title', $data['title']);
		$qry->bindValue(':info', $data['info']);
		$qry->bindValue(':creator_id', $data['creator_id']);
		if($qry->execute()) {
			return $this->selectById($id);
		}
		return array();
	}

	public function setApproved($id, $approved) {
		$sql = "UPDATE `tt_groups` SET `approved` = :approved WHERE `id` = :id";
		$qry = $this->pdo->prepare($sql);
		$qry->bindValue(':id', $id);
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

	public function validate($data) {
		$errors = array();
		if(empty($data['title'])) {
			$errors['title'] = 'Gelieve een titel op te geven';
		}
		if(empty($data['info'])) {
			$errors['info'] = 'Gelieve een korte descriptie te geven';
		}
		return $errors;
	}
}
