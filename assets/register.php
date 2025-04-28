<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $address = $_POST['address'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $username = $_POST['username'] ?? '';
    $password_raw = $_POST['password'] ?? '';

    if (empty($name) || empty($email) || empty($address) || empty($phone) || empty($username) || empty($password_raw)) {
        echo json_encode(["success" => false, "message" => "Semua field harus diisi!"]);
        exit();
    }

    $password = password_hash($password_raw, PASSWORD_DEFAULT);
    $verification_code = rand(100000, 999999);

    $stmt = $conn->prepare("INSERT INTO users (name, email, address, phone, username, password, verification_code, is_verified) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, 0)");
    $stmt->bind_param("sssssss", $name, $email, $address, $phone, $username, $password, $verification_code);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Registrasi berhasil! Kode verifikasi: $verification_code"]);
    } else {
        echo json_encode(["success" => false, "message" => "Gagal registrasi: " . $conn->error]);
    }
}
?>
