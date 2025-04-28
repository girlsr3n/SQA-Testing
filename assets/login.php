<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        echo json_encode(["success" => false, "message" => "Username dan password wajib diisi!"]);
        exit();
    }

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            if ($user['is_verified'] == 1) {
                echo json_encode(["success" => true, "message" => "Login berhasil!"]);
            } else {
                echo json_encode(["success" => false, "message" => "Akun belum diverifikasi!"]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Password salah!"]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Username tidak ditemukan!"]);
    }
}
?>
