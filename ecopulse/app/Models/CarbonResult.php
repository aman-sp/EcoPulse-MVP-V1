<?php

class CarbonResult extends Model {
    protected $table = 'carbon_results';

    public function findBySubmission($submissionId) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE submission_id = ? LIMIT 1");
        $stmt->execute([(int)$submissionId]);
        return $stmt->fetch();
    }

    public function createOrUpdate($submissionId, $data) {
        $existing = $this->findBySubmission($submissionId);
        $data['submission_id'] = (int)$submissionId;

        if ($existing) {
            return $this->update($existing['id'], $data);
        } else {
            return $this->create($data);
        }
    }

    public function getTrendByHospital($hospitalId, $limit = 12) {
        $stmt = $this->db->prepare("
            SELECT m.month, c.total_co2e, c.scope1_total, c.scope2_total 
            FROM monthly_submissions m 
            JOIN carbon_results c ON m.id = c.submission_id 
            WHERE m.hospital_id = :hospitalId AND m.status = 'submitted' 
            ORDER BY m.month DESC 
            LIMIT :limit
        ");
        $stmt->bindValue(':hospitalId', (int)$hospitalId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return array_reverse($stmt->fetchAll());
    }
}
