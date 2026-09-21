<?php

class Hospital extends Model {
    protected $table = 'hospitals';

    public function findByRegistration($regNo) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE registration_number = :regNo LIMIT 1");
        $stmt->execute(['regNo' => $regNo]);
        return $stmt->fetch();
    }

    public function getActive() {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE status = 'active' ORDER BY name ASC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getWithSubmissionStatus($month = null) {
        if (!$month) {
            $month = date('Y-m-01');
        }
        $stmt = $this->db->prepare("
            SELECT h.*, ms.status as submission_status, ms.id as submission_id
            FROM {$this->table} h
            LEFT JOIN monthly_submissions ms ON h.id = ms.hospital_id AND ms.month = :month
            ORDER BY h.name ASC
        ");
        $stmt->execute(['month' => $month]);
        return $stmt->fetchAll();
    }

    public function search($keyword) {
        $term = '%' . $keyword . '%';
        $stmt = $this->db->prepare("
            SELECT * FROM {$this->table} 
            WHERE name LIKE :keyword OR registration_number LIKE :keyword OR email LIKE :keyword OR city LIKE :keyword
            ORDER BY name ASC
        ");
        $stmt->execute(['keyword' => $term]);
        return $stmt->fetchAll();
    }

    public function countByStatus() {
        $stmt = $this->db->prepare("SELECT status, COUNT(*) as count FROM {$this->table} GROUP BY status");
        $stmt->execute();
        $results = $stmt->fetchAll();
        $counts = ['active' => 0, 'suspended' => 0, 'inactive' => 0];
        foreach ($results as $row) {
            $counts[$row['status']] = $row['count'];
        }
        return $counts;
    }
}
