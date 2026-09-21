<?php

class Notification extends Model {
    protected $table = 'notifications';

    public function getUnread($userType, $userId) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE user_type = ? AND user_id = ? AND is_read = 0 ORDER BY created_at DESC");
        $stmt->execute([$userType, (int)$userId]);
        return $stmt->fetchAll();
    }

    public function markRead($id) {
        return $this->update($id, ['is_read' => 1]);
    }

    public function markAllRead($userType, $userId) {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET is_read = 1 WHERE user_type = ? AND user_id = ?");
        return $stmt->execute([$userType, (int)$userId]);
    }

    public function createNotification($userType, $userId, $title, $message) {
        return $this->create([
            'user_type' => $userType,
            'user_id' => (int)$userId,
            'title' => $title,
            'message' => $message,
            'is_read' => 0
        ]);
    }
}
