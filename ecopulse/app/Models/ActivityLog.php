<?php

class ActivityLog extends Model {
    protected $table = 'activity_logs';

    public function log($userType, $userId, $action, $description = null) {
        return $this->create([
            'user_type' => $userType,
            'user_id' => $userId,
            'action' => $action,
            'description' => $description,
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0'
        ]);
    }

    public function getRecent($limit = 20) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} ORDER BY created_at DESC LIMIT ?");
        $stmt->bindValue(1, (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getByUser($userType, $userId) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE user_type = ? AND user_id = ? ORDER BY created_at DESC");
        $stmt->execute([$userType, $userId]);
        return $stmt->fetchAll();
    }
}
