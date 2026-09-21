<?php

class HospitalProfile extends Model {
    protected $table = 'hospital_profile';

    public function findByHospitalId($hospitalId) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE hospital_id = :hospitalId LIMIT 1");
        $stmt->execute(['hospitalId' => $hospitalId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createOrUpdate($hospitalId, $data) {
        $existing = $this->findByHospitalId($hospitalId);
        $data['hospital_id'] = $hospitalId;
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
