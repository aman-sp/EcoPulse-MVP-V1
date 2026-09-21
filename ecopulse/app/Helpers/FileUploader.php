<?php

class FileUploader {
    public static function upload($file, $destinationFolder = 'misc', $allowedTypes = ['pdf', 'png', 'jpg', 'jpeg'], $maxSize = 10485760) {
        if (!isset($file['tmp_name']) || empty($file['tmp_name'])) {
            return false;
        }

        if ($file['error'] !== UPLOAD_ERR_OK) {
            return false;
        }

        if ($file['size'] > $maxSize) {
            return false;
        }

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowedTypes)) {
            return false;
        }

        $uploadDir = STORAGE_PATH . '/uploads/' . $destinationFolder;
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $filename = time() . '_' . bin2hex(random_bytes(8)) . '.' . $ext;
        $destination = $uploadDir . '/' . $filename;

        if (move_uploaded_file($file['tmp_name'], $destination)) {
            return 'storage/uploads/' . $destinationFolder . '/' . $filename;
        }

        return false;
    }
}
