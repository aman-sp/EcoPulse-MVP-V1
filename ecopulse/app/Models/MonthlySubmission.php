<?php

class MonthlySubmission extends Model {
    protected $table = 'monthly_submissions';

    public function findByHospitalAndMonth($hospitalId, $month) {
        $timestamp = strtotime($month);
        if (!$timestamp) {
            $timestamp = strtotime($month . '-01') ?: time();
        }
        $formattedMonth = date('Y-m-01', $timestamp);
        $likeMonth = date('Y-m', $timestamp) . '%';

        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE hospital_id = :hospitalId AND (month = :formattedMonth OR month LIKE :likeMonth OR month = '0000-00-00') ORDER BY id DESC LIMIT 1");
        $stmt->execute([
            'hospitalId' => $hospitalId,
            'formattedMonth' => $formattedMonth,
            'likeMonth' => $likeMonth
        ]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getDrafts($hospitalId) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE hospital_id = :hospitalId AND status = 'draft' ORDER BY month DESC");
        $stmt->execute(['hospitalId' => $hospitalId]);
        return $stmt->fetchAll();
    }

    public function getSubmitted($hospitalId) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE hospital_id = :hospitalId AND status = 'submitted' ORDER BY month DESC");
        $stmt->execute(['hospitalId' => $hospitalId]);
        return $stmt->fetchAll();
    }

    public function getRecentByHospital($hospitalId, $limit = 12) {
        $stmt = $this->db->prepare("
            SELECT ms.*, cr.total_co2e as total_carbon, gr.id as report_id 
            FROM {$this->table} ms 
            LEFT JOIN carbon_results cr ON ms.id = cr.submission_id 
            LEFT JOIN generated_reports gr ON ms.id = gr.submission_id 
            WHERE ms.hospital_id = :hospitalId 
            ORDER BY ms.month DESC 
            LIMIT :limit
        ");
        $stmt->bindValue(':hospitalId', (int)$hospitalId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function countByStatus() {
        $stmt = $this->db->prepare("SELECT status, COUNT(*) as count FROM {$this->table} GROUP BY status");
        $stmt->execute();
        $results = $stmt->fetchAll();
        $counts = ['draft' => 0, 'submitted' => 0, 'approved' => 0, 'rejected' => 0];
        foreach ($results as $row) {
            $counts[$row['status']] = $row['count'];
        }
        return $counts;
    }

    public function getMonthlyTrend($months = 12) {
        $stmt = $this->db->prepare("
            SELECT month, COUNT(*) as count 
            FROM {$this->table} 
            WHERE status = 'submitted' 
            GROUP BY month 
            ORDER BY month DESC 
            LIMIT :limit
        ");
        $stmt->bindValue(':limit', (int)$months, PDO::PARAM_INT);
        $stmt->execute();
        $results = $stmt->fetchAll();
        return array_reverse($results);
    }
}
