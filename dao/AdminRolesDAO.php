<?php
require_once WWW_ROOT . 'dao/DAO.php';
class AdminRolesDAO extends DAO {
	public function selectAll() {
		$sql = "SELECT * FROM `tt_admin_roles`";
		$qry = $this->pdo->prepare($sql);
		if($qry->execute()) {
			return $qry->fetchAll(pdo::FETCH_ASSOC);
		}
		return array();
	}

	public function selectById($id) {
		$sql = "SELECT * FROM `tt_admin_roles` WHERE `id` = :id";
		$qry = $this->pdo->prepare($sql);
		$qry->bindValue(':id', $id);
		if($qry->execute()) {
			return $qry->fetch(pdo::FETCH_ASSOC);
		}
		return array();
	}

	public function selectByIdAndPrivilige($id, $privilige) {
		$sql = "SELECT * FROM `tt_admin_roles` WHERE `id` = :id AND `". $privilige ."` = :value";
		$qry = $this->pdo->prepare($sql);
		$qry->bindValue(':id', $id);
		$qry->bindValue(':value', 1);
		if($qry->execute()) {
			return $qry->fetch(pdo::FETCH_ASSOC);
		}
		return array();
	}

	public function insert($data) {
		$sql = "INSERT INTO `tt_admin_roles` (`title`, `can_edit_users`, `can_approve_groups`, `can_edit_creations`, `can_delete_creations`, `can_feature_creations`, `can_judge_creations`)
		VALUES (:title, :can_edit_users, :can_delete_users, :can_edit_roles, :can_approve_groups, :can_edit_creations, :can_delete_creations, :can_feature_creations, :can_judge_creations)";
		$qry = $this->pdo->prepare($sql);
		$qry->bindValue(':title', $data['title']);
		$qry->bindValue(':can_edit_users', $data['can_edit_users']);
		$qry->bindValue(':can_delete_users', $data['can_delete_users']);
		$qry->bindValue(':can_edit_roles', $data['can_edit_roles']);
		$qry->bindValue(':can_approve_groups', $data['can_approve_groups']);
		$qry->bindValue(':can_edit_creations', $data['can_edit_creations']);
		$qry->bindValue(':can_delete_creations', $data['can_delete_creations']);
		$qry->bindValue(':can_feature_creations', $data['can_feature_creations']);
		$qry->bindValue(':can_judge_creations', $data['can_judge_creations']);
		if($qry->execute()) {
			return $this->selectById($this->pdo->lastInsertId());
		}
		return array();
	}

	public function update($data) {
		$sql = "UPDATE `tt_admin_roles` SET `title` = :title, `can_edit_users` = :can_edit_users, `can_delete_users` = :can_delete_users, `can_edit_roles` = :can_edit_roles, `can_approve_groups` = :can_approve_groups, `can_edit_creations` = :can_edit_creations,
		`can_delete_creations` = :can_delete_creations, `can_feature_creations` = :can_feature_creations, `can_judge_creations` = :can_judge_creations WHERE `id` = :id";
		$qry = $this->pdo->prepare($sql);
		$qry->bindValue(':id', $data['id']);
		$qry->bindValue(':title', $data['title']);
		$qry->bindValue(':can_edit_users', $data['can_edit_users']);
		$qry->bindValue(':can_delete_users', $data['can_delete_users']);
		$qry->bindValue(':can_edit_roles', $data['can_edit_roles']);
		$qry->bindValue(':can_approve_groups', $data['can_approve_groups']);
		$qry->bindValue(':can_edit_creations', $data['can_edit_creations']);
		$qry->bindValue(':can_delete_creations', $data['can_delete_creations']);
		$qry->bindValue(':can_feature_creations', $data['can_feature_creations']);
		$qry->bindValue(':can_judge_creations', $data['can_judge_creations']);
		if($qry->execute()) {
			return $this->selectById($id);
		}
		return array();
	}

	public function delete($id) {
		$sql = "DELETE FROM `tt_admin_roles` WHERE `id` = :id";
		$qry = $this->pdo->prepare($sql);
		$qry->bindValue(':id', $id);
		return $qry->execute();
	}
}
