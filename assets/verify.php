<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $verificationCode = $_POST['verificationCode'] ?? '';

    if (empty($username) || empty($verificationCode)) {
        echo json_encode(["success" => false, "message" => "Username dan kode verifikasi wajib diisi!"]);
        exit();
    }

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND verification_code = ?");
    $stmt->bind_param("ss", $username, $verificationCode);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $update = $conn->prepare("UPDATE users SET is_verified = 1 WHERE username = ?");
        $update->bind_param("s", $username);
        if ($update->execute()) {
            echo json_encode(["success" => true, "message" => "Akun berhasil diverifikasi!"]);
        } else {
            echo json_encode(["success" => false, "message" => "Gagal update verifikasi: " . $conn->error]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Kode verifikasi salah atau username salah!"]);
    }
}
?>
