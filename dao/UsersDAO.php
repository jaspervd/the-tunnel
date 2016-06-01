<?php
require_once WWW_ROOT . 'dao/DAO.php';
class UsersDAO extends DAO {
	public function selectAll() {
		$sql = "SELECT * FROM `tt_users`";
		$qry = $this->pdo->prepare($sql);
		if($qry->execute()) {
			return $qry->fetchAll(pdo::FETCH_ASSOC);
		}
		return array();
	}

	public function selectById($id) {
		$sql = "SELECT * FROM `tt_users` WHERE `id` = :id";
		$qry = $this->pdo->prepare($sql);
		$qry->bindValue(':id', $id);
		if($qry->execute()) {
			return $qry->fetch(pdo::FETCH_ASSOC);
		}
		return array();
	}

	public function selectByInputAndPassword($input, $password) {
		$sql = "SELECT * FROM `tt_users` WHERE `username` = :input OR `email` = :input";
		$qry = $this->pdo->prepare($sql);
		$qry->bindValue(':input', $input);
		if($qry->execute()) {
			$result = $qry->fetch(pdo::FETCH_ASSOC);
			if(password_verify($password, $result['password'])) {
				return $result;
			}
		}
		return array();
	}

	public function insert($username, $password, $email, $firstname, $lastname, $bio) {
		$sql = "INSERT INTO `tt_users` (`username`, `password`, `email`, `firstname`, `lastname`, `bio`) VALUES (:username, :password, :email, :firstname, :lastname, :bio)";
		$qry = $this->pdo->prepare($sql);
		$qry->bindValue(':username', $username);
		$qry->bindValue(':password', password_hash($password, PASSWORD_BCRYPT));
		$qry->bindValue(':email', $email);
		$qry->bindValue(':firstname', $firstname);
		$qry->bindValue(':lastname', $lastname);
		$qry->bindValue(':bio', $bio);
		if($qry->execute()) {
			return $this->selectById($this->pdo->lastInsertId());
		}
		return array();
	}

	public function update($id, $username, $password, $email, $firstname, $lastname, $bio, $role_id, $hidden) {
		$sql = "UPDATE `tt_users` SET `username` = :username, `password` = :password, `email` = :email, `firstname` = :firstname, `lastname` = :lastname, `bio` = :bio, `role_id` = :role_id, `hidden` = :hidden WHERE `id` = :id";
		$qry = $this->pdo->prepare($sql);
		$qry->bindValue(':id', $id);
		$qry->bindValue(':username', $username);
		$qry->bindValue(':password', password_hash($password, PASSWORD_BCRYPT));
		$qry->bindValue(':email', $email);
		$qry->bindValue(':firstname', $firstname);
		$qry->bindValue(':lastname', $lastname);
		$qry->bindValue(':bio', $bio);
		$qry->bindValue(':role_id', $role_id);
		$qry->bindValue(':hidden', $hidden);
		if($qry->execute()) {
			return $this->selectById($id);
		}
		return array();
	}

	public function delete($id) {
		$sql = "DELETE FROM `tt_users` WHERE `id` = :id";
		$qry = $this->pdo->prepare($sql);
		$qry->bindValue(':id', $id);
		return $qry->execute();
	}
}
