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

	public function selectByGroupId($group_id) {
		$sql = "SELECT `tt_users`.*, `tt_user_groups`.`user_id` FROM `tt_users` LEFT JOIN `tt_user_groups` ON `tt_users`.`id` = `tt_user_groups`.`user_id` WHERE `tt_user_groups`.`group_id` = :group_id";
		$qry = $this->pdo->prepare($sql);
		$qry->bindValue(':group_id', $group_id);
		if($qry->execute()) {
			return $qry->fetchAll(pdo::FETCH_ASSOC);
		}
		return array();
	}

	public function selectByInputAndPassword($input, $password) {
		$sql = "SELECT * FROM `tt_users` WHERE `username` = :input OR `email` = :input";
		$qry = $this->pdo->prepare($sql);
		$qry->bindValue(':input', $input);
		$qry->bindValue(':input', $input);
		if($qry->execute()) {
			$result = $qry->fetch(pdo::FETCH_ASSOC);
			if(password_verify($password, $result['password'])) {
				return $result;
			}
		}
		return array();
	}

	public function insert($data) {
		$sql = "INSERT INTO `tt_users` (`username`, `password`, `email`, `firstname`, `lastname`, `bio`) VALUES (:username, :password, :email, :firstname, :lastname, :bio)";
		$qry = $this->pdo->prepare($sql);
		$qry->bindValue(':username', $data['username']);
		$qry->bindValue(':password', password_hash($data['password'], PASSWORD_BCRYPT));
		$qry->bindValue(':email', $data['email']);
		$qry->bindValue(':firstname', $data['firstname']);
		$qry->bindValue(':lastname', $data['lastname']);
		$qry->bindValue(':bio', $data['bio']);
		if($qry->execute()) {
			return $this->selectById($this->pdo->lastInsertId());
		}
		return array();
	}

	public function update($data) {
		$sql = "UPDATE `tt_users` SET `username` = :username, `password` = :password, `email` = :email, `firstname` = :firstname, `lastname` = :lastname, `bio` = :bio WHERE `id` = :id";
		$qry = $this->pdo->prepare($sql);
		$qry->bindValue(':id', $data['id']);
		$qry->bindValue(':username', $data['username']);
		$qry->bindValue(':password', password_hash($data['password'], PASSWORD_BCRYPT));
		$qry->bindValue(':email', $data['email']);
		$qry->bindValue(':firstname', $data['firstname']);
		$qry->bindValue(':lastname', $data['lastname']);
		$qry->bindValue(':bio', $data['bio']);
		if($qry->execute()) {
			return $this->selectById($id);
		}
		return array();
	}

	public function setRole($id, $role_id) {
		$sql = "UPDATE `tt_users` SET `role_id` = :role_id WHERE `id` = :id";
		$qry = $this->pdo->prepare($sql);
		$qry->bindValue(':id', $id);
		$qry->bindValue(':role_id', $role_id);
		if($qry->execute()) {
			return $this->selectById($id);
		}
		return array();
	}

	public function setHidden($id, $hidden) {
		$sql = "UPDATE `tt_users` SET `hidden` = :hidden WHERE `id` = :id";
		$qry = $this->pdo->prepare($sql);
		$qry->bindValue(':id', $id);
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

	public function validate($data) {
		$errors = array();
		if(empty($data['username'])) {
			$errors['username'] = 'Gelieve een gebruikersnaam op te geven';
		}
		if(strlen($data['password']) < 7) {
			$errors['password'] = 'Gelieve een wachtwoord van minstens 7 tekens op te geven';
		}
		if(empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
			$errors['email'] = 'Gelieve een geldig e-mailadres op te geven';
		}
		if(empty($data['firstname'])) {
			$errors['firstname'] = 'Gelieve een voornaam op te geven';
		}
		if(empty($data['lastname'])) {
			$errors['lastname'] = 'Gelieve een achternaam op te geven';
		}
		if(empty($data['bio'])) {
			$errors['bio'] = 'Gelieve een korte bio te geven';
		}
		return $errors;
	}
}
