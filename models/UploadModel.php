<?php
class UploadModel {

    private $allowed = ['jpg', 'jpeg', 'png', 'gif'];
    private $maxSize = 2097152; 
    private $uploadDir;

    public function __construct() {
        $this->uploadDir = __DIR__ . '/../assets/uploads/';
    }

    public function upload($file) {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($ext, $this->allowed)) {
            return null;
        }

        if ($file['size'] > $this->maxSize) {
            return null;
        }

        $namaFile = uniqid('barang_') . '.' . $ext;
        $tujuan = $this->uploadDir . $namaFile;

        if (move_uploaded_file($file['tmp_name'], $tujuan)) {
            return $namaFile;
        }

        return null;
    }

    public function delete($namaFile) {
        $path = $this->uploadDir . $namaFile;
        if (file_exists($path)) {
            unlink($path);
        }
    }
}
?>