<?php
require_once WWW_ROOT . 'dao/DAO.php';
class CreationsDAO extends DAO {
	public function selectAll() {
		$sql = "SELECT * FROM `tt_creations`";
		$qry = $this->pdo->prepare($sql);
		if($qry->execute()) {
			return $qry->fetchAll(pdo::FETCH_ASSOC);
		}
		return array();
	}

	public function selectByFeatured($featured) {
		$sql = "SELECT * FROM `tt_creations` WHERE `featured` = :featured";
		$qry = $this->pdo->prepare($sql);
		$qry->bindValue(':featured', $featured);
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

	public function selectByUserId($user_id) {
		$sql = "SELECT `tt_creations` WHERE `creator_id` = :user_id";
		$qry = $this->pdo->prepare($sql);
		$qry->bindValue(':user_id', $user_id);
		if($qry->execute()) {
			return $qry->fetchAll(pdo::FETCH_ASSOC);
		}
		return array();
	}

	public function selectGroupId($group_id) {
		$sql = "SELECT `tt_creations` WHERE `group_id` = :group_id";
		$qry = $this->pdo->prepare($sql);
		$qry->bindValue(':group_id', $group_id);
		if($qry->execute()) {
			return $qry->fetchAll(pdo::FETCH_ASSOC);
		}
		return array();
	}

	public function insert($data) {
		$sql = "INSERT INTO `tt_creations` (`user_id`, `title`, `info`, `image_url`, `group_id`) VALUES (:user_id, :title, :info, :image_url, :group_id)";
		$qry = $this->pdo->prepare($sql);
		$qry->bindValue(':user_id', $data['user_id']);
		$qry->bindValue(':title', $data['title']);
		$qry->bindValue(':info', $data['info']);
		$qry->bindValue(':image_url', $data['image_url']);
		$qry->bindValue(':group_id', $data['group_id']);
		if($qry->execute()) {
			return $this->selectById($this->pdo->lastInsertId());
		}
		return array();
	}

	public function update($data) {
		$sql = "UPDATE `tt_creations` SET `user_id` = :user_id, `title` = :title, `info` = :info, `image_url` = :image_url, `group_id` = :group_id WHERE `id` = :id";
		$qry = $this->pdo->prepare($sql);
		$qry->bindValue(':id', $id);
		$qry->bindValue(':user_id', $user_id);
		$qry->bindValue(':title', $title);
		$qry->bindValue(':info', $info);
		$qry->bindValue(':image_url', $image_url);
		$qry->bindValue(':group_id', $group_id);
		if($qry->execute()) {
			return $this->selectById($id);
		}
		return array();
	}

	public function setFeatured($id, $featured) {
		$sql = "UPDATE `tt_creations` SET `featured` = :featured WHERE `id` = :id";
		$qry = $this->pdo->prepare($sql);
		$qry->bindValue(':id', $id);
		$qry->bindValue(':featured', $featured);
		if($qry->execute()) {
			return $this->selectById($id);
		}
		return array();
	}

	public function setElected($id, $elected) {
		$sql = "UPDATE `tt_creations` SET `elected` = :elected WHERE `id` = :id";
		$qry = $this->pdo->prepare($sql);
		$qry->bindValue(':id', $id);
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
