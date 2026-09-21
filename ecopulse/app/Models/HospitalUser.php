<?php

class HospitalUser extends Model {
    protected $table = 'hospital_users';

    public function findByUsername($username) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE username = :username LIMIT 1");
        $stmt->execute(['username' => $username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findByHospitalId($hospitalId) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE hospital_id = :hospitalId");
        $stmt->execute(['hospitalId' => $hospitalId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateLastLogin($id) {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET last_login = NOW() WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
