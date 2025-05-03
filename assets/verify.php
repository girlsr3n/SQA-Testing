<?php
include 'config.php';
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

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
        $user = $result->fetch_assoc();
        $email = $user['email'];  // pastikan tabel 'users' ada kolom email
        $name = $user['username'];

        $update = $conn->prepare("UPDATE users SET is_verified = 1, verification_code = NULL WHERE username = ?");
        $update->bind_param("s", $username);

        if ($update->execute()) {
            // Kirim email konfirmasi
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'emailmu@gmail.com'; // ganti dengan emailmu
                $mail->Password = 'aplikasi-password'; // app password, jangan password biasa!
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                $mail->setFrom('emailmu@gmail.com', 'Nama Pengirim');
                $mail->addAddress($email, $name);

                $mail->isHTML(true);
                $mail->Subject = 'Akun Anda Berhasil Diverifikasi';
                $mail->Body = "
                    <h3>Halo $name,</h3>
                    <p>Selamat! Akun Anda telah berhasil diverifikasi.</p>
                    <p>Terima kasih telah bergabung.</p>
                ";

                $mail->send();
                echo json_encode(["success" => true, "message" => "Akun berhasil diverifikasi! Email konfirmasi telah dikirim."]);
            } catch (Exception $e) {
                echo json_encode(["success" => false, "message" => "Akun berhasil diverifikasi, tapi gagal kirim email. Error: {$mail->ErrorInfo}"]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Gagal update verifikasi: " . $conn->error]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Kode verifikasi salah atau username salah!"]);
    }
}
?>
