<?php
require_once WWW_ROOT . 'dao/DAO.php';
class UserGroupsDAO extends DAO {

  public function selectAll() {
    $sql = "SELECT * FROM `tt_user_groups`";
    $qry = $this->pdo->prepare($sql);
    if($qry->execute()) {
      return $qry->fetchAll(pdo::FETCH_ASSOC);
    }
    return array();
  }

  public function selectById($id) {
    $sql = "SELECT * FROM `tt_user_groups` WHERE `id` = :id";
    $qry = $this->pdo->prepare($sql);
    $qry->bindValue(':id', $id);
    if($qry->execute()) {
      return $qry->fetch(pdo::FETCH_ASSOC);
    }
    return array();
  }

  public function insert($data) {
    $sql = "INSERT INTO `tt_user_groups` (`user_id`, `group_id`, `approved`) VALUES (:user_id, :group_id, :approved)";
    $qry = $this->pdo->prepare($sql);
    $qry->bindValue(':user_id', $data['creator_id']);
    $qry->bindValue(':group_id', $data['id']);
    $qry->bindValue(':approved', $data['approvedToGroup']);
    if($qry->execute()) {
      return $this->selectById($this->pdo->lastInsertId());
    }
    return array();
  }
}
