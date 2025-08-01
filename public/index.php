<?php
session_start();

// ถ้ายังไม่ได้ login กลับไปหน้า login
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

// ดึงข้อมูลผู้ใช้จาก session
$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Food Delivery</title>
    <link rel="stylesheet" href="./assets/css/index.css">
    <script src="./assets/js/index.js" defer></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>

<body class="bg-gray-50">
    <!-- Header -->
    <div class="bg-white px-5 py-4 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">สวัสดีคุณ <?php echo htmlspecialchars($user['name']); ?> 👋</h1>
                <p class="text-sm text-gray-500">อยากกินอะไรวันนี้?</p>
            </div>

            <!-- Avatar with Dropdown -->
            <div class="relative">
                <button id="avatarBtn" class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center hover:shadow-lg transition-all duration-200">
                    <span class="text-white text-lg font-bold"><?php echo strtoupper(substr($user['name'], 0, 1)); ?></span>
                </button>

                <!-- Dropdown Menu -->
                <div id="avatarDropdown" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-100 z-50 hidden">
                    <div class="py-2">
                        <div class="px-4 py-2 border-b border-gray-100">
                            <p class="font-semibold text-gray-900"><?php echo htmlspecialchars($user['name']); ?></p>
                            <p class="text-sm text-gray-500"><?php echo htmlspecialchars($user['email'] ?? ''); ?></p>
                        </div>
                        <a href="profile.php" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-50">
                            <span class="mr-3">👤</span>
                            โปรไฟล์
                        </a>
                        <a href="orders.php" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-50">
                            <span class="mr-3">📦</span>
                            ประวัติการสั่งซื้อ
                        </a>
                        <!-- <a href="settings.php" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-50">
                            <span class="mr-3">⚙️</span>
                            การตั้งค่า
                        </a> -->
                        <hr class="my-1">
                        <a href="logout.php" class="flex items-center px-4 py-2 text-red-600 hover:bg-red-50">
                            <span class="mr-3">🚪</span>
                            ออกจากระบบ
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search Bar -->
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <input type="text"
                id="searchInput"
                placeholder="ค้นหาร้านอาหาร หรือ เมนูที่ชอบ"
                class="w-full pl-10 pr-4 py-3 bg-gray-100 rounded-lg border-0 text-gray-700 placeholder-gray-500 focus:bg-white focus:shadow-md transition-all duration-200">
        </div>
    </div>

    <!-- Category Section -->
    <div class="px-5 py-4">
        <h3 class="text-lg font-bold text-gray-900 mb-3">หมวดหมู่</h3>
        <div class="grid grid-cols-4 gap-4">
            <div class="category-item flex flex-col items-center p-3 bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow">
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mb-2">
                    <span class="text-2xl">🍜</span>
                </div>
                <span class="text-xs text-gray-700 text-center">อาหารไทย</span>
            </div>
            <div class="category-item flex flex-col items-center p-3 bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow">
                <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center mb-2">
                    <span class="text-2xl">🍕</span>
                </div>
                <span class="text-xs text-gray-700 text-center">ฟาสต์ฟู้ด</span>
            </div>
            <div class="category-item flex flex-col items-center p-3 bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow">
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mb-2">
                    <span class="text-2xl">🥗</span>
                </div>
                <span class="text-xs text-gray-700 text-center">สุขภาพ</span>
            </div>
            <div class="category-item flex flex-col items-center p-3 bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow">
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mb-2">
                    <span class="text-2xl">🧋</span>
                </div>
                <span class="text-xs text-gray-700 text-center">เครื่องดื่ม</span>
            </div>
        </div>
    </div>

    <!-- Promotion Slider -->
    <div class="px-5 py-2">
        <div class="promotion-slider overflow-x-auto">
            <div class="flex gap-4 pb-2">
                <div class="flex-none w-80 h-32 bg-gradient-to-r from-orange-400 to-pink-500 rounded-lg flex items-center justify-between p-4 text-white">
                    <div>
                        <h4 class="font-bold text-lg">ส่วนลดพิเศษ!</h4>
                        <p class="text-sm opacity-90">ลดสูงสุด 50% สำหรับลูกค้าใหม่</p>
                        <button class="mt-2 px-3 py-1 bg-white text-orange-500 rounded-full text-xs font-semibold">สั่งเลย</button>
                    </div>
                    <div class="text-4xl">🎉</div>
                </div>

                <div class="flex-none w-80 h-32 bg-gradient-to-r from-green-400 to-blue-500 rounded-lg flex items-center justify-between p-4 text-white">
                    <div>
                        <h4 class="font-bold text-lg">ฟรีค่าส่ง!</h4>
                        <p class="text-sm opacity-90">สั่งขั้นต่ำ 200 บาท รับฟรีค่าส่ง</p>
                        <button class="mt-2 px-3 py-1 bg-white text-green-500 rounded-full text-xs font-semibold">ดูเงื่อนไข</button>
                    </div>
                    <div class="text-4xl">🚀</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recommended Restaurants -->
    <div class="px-5 py-4">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold text-gray-900">ร้านแนะนำ</h2>
            <button class="text-blue-500 text-sm font-medium" ><a href="restaurant.php">ดูทั้งหมด</a></button>
        </div>

        <!-- Featured Restaurant -->
        <div class="mb-4">
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="h-32 bg-gradient-to-r from-gray-300 to-gray-400 flex items-center justify-center">
                    <span class="text-4xl">🏪</span>
                </div>
                <div class="p-4">
                    <div class="flex items-start justify-between mb-2">
                        <div>
                            <h3 class="font-bold text-gray-900">ร้านอาหารพิเศษ</h3>
                            <div class="flex items-center mt-1">
                                <span class="text-yellow-400">⭐</span>
                                <span class="text-sm text-gray-600 ml-1">4.8 (500+)</span>
                                <span class="text-gray-400 mx-2">•</span>
                                <span class="text-sm text-gray-600">0.5 km</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-green-600 text-sm font-medium">เปิดอยู่</span>
                            <div class="text-xs text-gray-500">15-25 นาที</div>
                        </div>
                    </div>
                    <p class="text-sm text-gray-600">อาหารไทย • ส่วนลด 20%</p>
                </div>
            </div>
        </div>

        <!-- Restaurant List -->
        <div class="space-y-3" id="restaurantList">
            <!-- ร้านเมะเก๋า Section -->
            <div class="restaurant-section">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="font-semibold text-gray-800">ร้านค้า</h3>
                    <a href="restaurant.php">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                    </a>
                </div>

                <div class="space-y-2">
                    <div class="restaurant-item flex items-center space-x-4 bg-white p-4 rounded-lg shadow-sm hover:shadow-md transition-shadow cursor-pointer">
                        <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                            <span class="text-orange-600 text-xl">🍜</span>
                        </div>
                        <div class="flex-1">
                            <div class="font-semibold text-gray-900">ครอบขนม</div>
                            <div class="text-sm text-gray-500 flex items-center">
                                <span>1.5 km</span>
                                <span class="mx-2">•</span>
                                <span class="text-yellow-500">⭐ 4.5</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="font-bold text-gray-900">฿55</div>
                            <div class="text-xs text-green-600">ส่วนลด 10%</div>
                        </div>
                    </div>

                    <div class="restaurant-item flex items-center space-x-4 bg-white p-4 rounded-lg shadow-sm hover:shadow-md transition-shadow cursor-pointer">
                        <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                            <span class="text-red-600 text-xl">🍕</span>
                        </div>
                        <div class="flex-1">
                            <div class="font-semibold text-gray-900">โดลมินรัต</div>
                            <div class="text-sm text-gray-500 flex items-center">
                                <span>1.3 km</span>
                                <span class="mx-2">•</span>
                                <span class="text-yellow-500">⭐ 4.2</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="font-bold text-gray-900">฿40</div>
                            <div class="text-xs text-blue-600">ฟรีค่าส่ง</div>
                        </div>
                    </div>
                    <div class="restaurant-item flex items-center space-x-4 bg-white p-4 rounded-lg shadow-sm hover:shadow-md transition-shadow cursor-pointer">
                        <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                            <span class="text-yellow-600 text-xl">🍛</span>
                        </div>
                        <div class="flex-1">
                            <div class="font-semibold text-gray-900">ข้าวผัดกุ้ง</div>
                            <div class="text-sm text-gray-500 flex items-center">
                                <span>0.8 km</span>
                                <span class="mx-2">•</span>
                                <span class="text-yellow-500">⭐ 4.7</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="font-bold text-gray-900">฿65</div>
                            <div class="text-xs text-green-600">มาใหม่!</div>
                        </div>
                    </div>

                    <div class="restaurant-item flex items-center space-x-4 bg-white p-4 rounded-lg shadow-sm hover:shadow-md transition-shadow cursor-pointer">
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                            <span class="text-green-600 text-xl">🍲</span>
                        </div>
                        <div class="flex-1">
                            <div class="font-semibold text-gray-900">ต้มยำกุ้งนำ้ใส</div>
                            <div class="text-sm text-gray-500 flex items-center">
                                <span>0.9 km</span>
                                <span class="mx-2">•</span>
                                <span class="text-yellow-500">⭐ 4.6</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="font-bold text-gray-900">฿85</div>
                            <div class="text-xs text-red-600">ยอดนิยม</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ร้านเเนะนำ Section -->
            <!-- <div class="restaurant-section">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="font-semibold text-gray-800">ร้านเเนะนำ</h3>
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>

                <div class="space-y-2">
                    
                </div>
            </div> -->
        </div>
    </div>

    <!-- Bottom Navigation -->
    <div class="fixed bottom-0 left-1/2 transform -translate-x-1/2 w-full  bg-white border-t border-gray-200 shadow-lg">
        <div class="flex justify-around py-2">
            <button class="nav-btn flex flex-col items-center py-2 px-4 text-blue-500" data-page="home">
                <span class="text-2xl mb-1">🏠</span>
                <span class="text-xs">หน้าแรก</span>
            </button>
            <button class="nav-btn flex flex-col items-center py-2 px-4 text-gray-600" data-page="search">
                <span class="text-2xl mb-1">🔍</span>
                <span class="text-xs">ค้นหา</span>
            </button>
            <button class="nav-btn flex flex-col items-center py-2 px-4 text-gray-600" data-page="orders">
                <span class="text-2xl mb-1">📦</span>
                <span class="text-xs">คำสั่งซื้อ</span>
            </button>
            <button class="nav-btn flex flex-col items-center py-2 px-4 text-gray-600" data-page="profile">
                <span class="text-2xl mb-1">👤</span>
                <span class="text-xs">โปรไฟล์</span>
            </button>
        </div>
    </div>

    <!-- Add some bottom padding to account for fixed navigation -->
    <div class="h-20"></div>

    <!-- Loading Overlay -->
    <div id="loadingOverlay" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg p-6 flex items-center space-x-3">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500"></div>
            <span class="text-gray-700">กำลังโหลด...</span>
        </div>
    </div>

    <script src="../assets/js/index.js"></script>
</body>

</html>