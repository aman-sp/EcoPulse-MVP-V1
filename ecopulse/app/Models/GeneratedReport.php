<?php

class GeneratedReport extends Model {
    protected $table = 'generated_reports';

    public function findByHospital($hospitalId) {
        $stmt = $this->db->prepare("
            SELECT r.*, ms.month 
            FROM {$this->table} r 
            LEFT JOIN monthly_submissions ms ON r.submission_id = ms.id 
            WHERE r.hospital_id = ? 
            ORDER BY r.created_at DESC
        ");
        $stmt->execute([(int)$hospitalId]);
        return $stmt->fetchAll();
    }

    public function findBySubmission($submissionId) {
        $stmt = $this->db->prepare("
            SELECT r.*, ms.month 
            FROM {$this->table} r 
            LEFT JOIN monthly_submissions ms ON r.submission_id = ms.id 
            WHERE r.submission_id = ? 
            LIMIT 1
        ");
        $stmt->execute([(int)$submissionId]);
        return $stmt->fetch();
    }

    public function getRecent($limit = 10) {
        $stmt = $this->db->prepare("
            SELECT r.*, h.name as hospital_name, ms.month 
            FROM {$this->table} r 
            LEFT JOIN hospitals h ON r.hospital_id = h.id 
            LEFT JOIN monthly_submissions ms ON r.submission_id = ms.id 
            ORDER BY r.created_at DESC 
            LIMIT :limit
        ");
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
