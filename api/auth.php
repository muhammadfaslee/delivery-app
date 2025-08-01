<?php
session_start();
include ("db.php"); // เชื่อมต่อฐานข้อมูลแบบ mysqli

$action = $_POST['action'] ?? '';

if ($action === 'login') {
    $phone = $_POST['phone'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE phone = '$phone'";
    $result = mysqli_query($conn, $sql);

    if ($user = mysqli_fetch_assoc($result)) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user;
            header("Location: ../public/index.php");
            exit;
        } else {
            echo "❌ รหัสผ่านไม่ถูกต้อง";
        }
    } else {
        echo "❌ ไม่พบผู้ใช้งาน";
    }

} elseif ($action === 'register') {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // ตรวจสอบว่าเบอร์นี้มีอยู่แล้วไหม
    $check_sql = "SELECT * FROM users WHERE phone = '$phone'";
    $check_result = mysqli_query($conn, $check_sql);

    if (mysqli_num_rows($check_result) > 0) {
        echo "❌ เบอร์โทรนี้ถูกใช้งานแล้ว";
    } else {
        $sql = "INSERT INTO users (name, phone, password) VALUES ('$name', '$phone', '$password')";
        if (mysqli_query($conn, $sql)) {
            header("Location: ../public/login.php");
            exit;
        } else {
            echo "❌ เกิดข้อผิดพลาด: " . mysqli_error($conn);
        }
    }
} else {
    echo "❌ ไม่พบ action ที่ต้องการ";
}
