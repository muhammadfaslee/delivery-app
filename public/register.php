<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สมัครสมาชิก</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-green-100 via-green-200 to-green-300">
    <div class="w-full max-w-sm md:max-w-md bg-white p-6 md:p-8 rounded-2xl shadow-lg">
        <form action="../api/auth.php" method="POST" class="space-y-4">
            <input type="hidden" name="action" value="register">
            <h2 class="text-2xl font-bold text-green-700 text-center mb-4">สมัครสมาชิก</h2>

            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">ชื่อ</label>
                <input type="text" name="name" id="name" placeholder="ชื่อของคุณ" required
                    class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>

            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700">เบอร์โทร</label>
                <input type="text" name="phone" id="phone" placeholder="08xxxxxxxx" required
                    class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">รหัสผ่าน</label>
                <input type="password" name="password" id="password" placeholder="••••••••" required
                    class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>

            <button type="submit"
                class="w-full bg-green-600 text-white font-medium py-2 rounded-md hover:bg-green-700 transition duration-300">
                สมัครสมาชิก
            </button>

            <p class="text-sm text-center mt-4 text-gray-600">
                มีบัญชีแล้ว?
                <a href="login.php" class="text-green-600 hover:underline">เข้าสู่ระบบ</a>
            </p>
        </form>
    </div>
</body>

</html>
