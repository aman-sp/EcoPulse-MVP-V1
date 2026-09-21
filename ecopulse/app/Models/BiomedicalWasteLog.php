<?php

class BiomedicalWasteLog extends Model {
    protected $table = 'biomedical_waste_logs';

    public function findBySubmission($submissionId) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE submission_id = :submissionId LIMIT 1");
        $stmt->execute(['submissionId' => $submissionId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createOrUpdate($submissionId, $data) {
        $existing = $this->findBySubmission($submissionId);
        $data['submission_id'] = $submissionId;
        $data['updated_at'] = date('Y-m-d H:i:s');

        if ($existing) {
            $fields = [];
            $params = ['id' => $existing['id']];
            foreach ($data as $key => $value) {
                if ($key !== 'id') {
                    $fields[] = "$key = :$key";
                    $params[$key] = $value;
                }
            }
            $query = "UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE id = :id";
            $stmt = $this->db->prepare($query);
            return $stmt->execute($params) ? $existing['id'] : false;
        } else {
            $data['created_at'] = date('Y-m-d H:i:s');
            $columns = implode(', ', array_keys($data));
            $placeholders = ':' . implode(', :', array_keys($data));
            $query = "INSERT INTO {$this->table} ($columns) VALUES ($placeholders)";
            $stmt = $this->db->prepare($query);
            return $stmt->execute($data) ? $this->db->lastInsertId() : false;
        }
    }
}
