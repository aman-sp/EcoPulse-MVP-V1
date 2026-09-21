<?php

class Setting extends Model {
    protected $table = 'settings';

    public function getValue($key, $default = null) {
        $stmt = $this->db->prepare("SELECT setting_value FROM {$this->table} WHERE setting_key = ? LIMIT 1");
        $stmt->execute([$key]);
        $row = $stmt->fetch();
        return $row ? $row['setting_value'] : $default;
    }

    public function setValue($key, $value) {
        $existing = $this->findBy('setting_key', $key);
        if ($existing) {
            return $this->update($existing['id'], ['setting_value' => $value]);
        } else {
            return $this->create(['setting_key' => $key, 'setting_value' => $value]);
        }
    }

    public function getAllKeyValue() {
        $stmt = $this->db->prepare("SELECT setting_key, setting_value FROM {$this->table}");
        $stmt->execute();
        $rows = $stmt->fetchAll();
        $settings = [];
        foreach ($rows as $row) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }
        return $settings;
    }
}
