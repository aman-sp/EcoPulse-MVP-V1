<?php

class Recommendation extends Model {
    protected $table = 'recommendations';

    public function findBySubmission($submissionId) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE submission_id = ? ORDER BY id ASC");
        $stmt->execute([(int)$submissionId]);
        return $stmt->fetchAll();
    }

    public function deleteBySubmission($submissionId) {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE submission_id = ?");
        return $stmt->execute([(int)$submissionId]);
    }
}
