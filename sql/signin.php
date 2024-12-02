<?php
// เริ่มต้นเซสชัน
session_start();

// เชื่อมต่อฐานข้อมูล
$conn = new mysqli('localhost', 'root', '', 'testsignin');

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

// ตรวจสอบว่าเป็นการร้องขอแบบ POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // รับค่าที่ส่งมาจากฟอร์ม
    $username = $_POST['username'];
    $password = $_POST['password'];

    // สร้างคำสั่ง SQL (ไม่ป้องกัน SQL Injection)
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    // ตรวจสอบว่าพบผู้ใช้หรือไม่
    if ($result->num_rows > 0) {
        // บันทึกข้อมูลผู้ใช้ลงในเซสชัน
        $_SESSION['user'] = $username;

        // แสดงข้อความยินดีต้อนรับ
        echo "<!DOCTYPE html>
<html lang='th'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>ยินดีต้อนรับ</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .welcome-container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 300px;
        }
        .welcome-message {
            color: #4CAF50;
            margin-bottom: 15px;
        }
        .logout-link {
            text-decoration: none;
            color: red;
        }
    </style>
</head>
<body>
    <div class='welcome-container'>
        <div class='welcome-message'>ยินดีต้อนรับ, " . htmlspecialchars($username) . "!</div>
        <a class='logout-link' href='?logout=true'>Logout</a>
    </div>
</body>
</html>";
        exit();
    } else {
        // ข้อมูลไม่ถูกต้อง
        $error = 'Invalid username or password';
    }
}

// หากมีการออกจากระบบ
if (isset($_GET['logout'])) {
    // ล้างเซสชัน
    session_unset();
    session_destroy();

    // รีไดเรกต์ไปหน้า Sign In
    header('Location: index.html');
    exit();
}

// ปิดการเชื่อมต่อฐานข้อมูล
$conn->close();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In Error</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .error-container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
        }
        .error-message {
            color: red;
            margin-bottom: 15px;
        }
        .back-link {
            text-decoration: none;
            color: #4CAF50;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <?php if (isset($error)): ?>
            <div class="error-message"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <a class="back-link" href="index.html">Go back to Sign In</a>
    </div>
</body>
</html>
