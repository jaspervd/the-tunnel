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

	public function insert($title, $can_create_admin, $can_approve_groups, $can_edit_creations, $can_delete_creations, $can_feature_creations, $can_judge_creations) {
		$sql = "INSERT INTO `tt_admin_roles` (`title`, `can_create_admin`, `can_approve_groups`, `can_edit_creations`, `can_delete_creations`, `can_feature_creations`, `can_judge_creations`) VALUES (:title, :can_create_admin, :can_approve_groups, :can_edit_creations, :can_delete_creations, :can_feature_creations, :can_judge_creations)";
		$qry = $this->pdo->prepare($sql);
		$qry->bindValue(':title', $title);
		$qry->bindValue(':can_create_admin', $can_create_admin);
		$qry->bindValue(':can_approve_groups', $can_approve_groups);
		$qry->bindValue(':can_edit_creations', $can_edit_creations);
		$qry->bindValue(':can_delete_creations', $can_delete_creations);
		$qry->bindValue(':can_feature_creations', $can_feature_creations);
		$qry->bindValue(':can_judge_creations', $can_judge_creations);
		if($qry->execute()) {
			return $this->selectById($this->pdo->lastInsertId());
		}
		return array();
	}

	public function update($id, $title, $can_create_admin, $can_approve_groups, $can_edit_creations, $can_delete_creations, $can_feature_creations, $can_judge_creations) {
		$sql = "UPDATE `tt_admin_roles` SET `title` = :title, `can_create_admin` = :can_create_admin, `can_approve_groups` = :can_approve_groups, `can_edit_creations` = :can_edit_creations, `can_delete_creations` = :can_delete_creations, `can_feature_creations` = :can_feature_creations, `can_judge_creations` = :can_judge_creations WHERE `id` = :id";
		$qry = $this->pdo->prepare($sql);
		$qry->bindValue(':id', $id);
		$qry->bindValue(':title', $title);
		$qry->bindValue(':can_create_admin', $can_create_admin);
		$qry->bindValue(':can_approve_groups', $can_approve_groups);
		$qry->bindValue(':can_edit_creations', $can_edit_creations);
		$qry->bindValue(':can_delete_creations', $can_delete_creations);
		$qry->bindValue(':can_feature_creations', $can_feature_creations);
		$qry->bindValue(':can_judge_creations', $can_judge_creations);
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
