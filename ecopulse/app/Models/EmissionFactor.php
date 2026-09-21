<?php

class EmissionFactor extends Model {
    protected $table = 'emission_factors';

    public function getActive() {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE is_active = 1 ORDER BY category ASC, name ASC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findByCategory($category) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE category = ? AND is_active = 1");
        $stmt->execute([$category]);
        return $stmt->fetchAll();
    }

    public function getGroupedByCategory() {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} ORDER BY category ASC, id ASC");
        $stmt->execute();
        $rows = $stmt->fetchAll();
        
        $grouped = [];
        foreach ($rows as $row) {
            $cat = $row['category'];
            if (!isset($grouped[$cat])) {
                $grouped[$cat] = [];
            }
            $grouped[$cat][] = $row;
        }
        return $grouped;
    }
}
