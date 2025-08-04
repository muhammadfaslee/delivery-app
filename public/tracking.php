<?php
session_start();

// ถ้ายังไม่ได้ login กลับไปหน้า login
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];
$orderId = $_GET['orderId'] ?? null;

if (!$orderId) {
    header("Location: orders.php");
    exit;
}

// Mock order status data
$orderStatuses = [
    'confirmed' => [
        'name' => 'ยืนยันคำสั่งซื้อแล้ว',
        'description' => 'ร้านค้าได้รับคำสั่งซื้อแล้ว',
        'icon' => '✅',
        'time' => '2 นาทีที่แล้ว',
        'completed' => true
    ],
    'preparing' => [
        'name' => 'กำลังเตรียมอาหาร',
        'description' => 'ร้านค้ากำลังทำอาหารให้คุณ',
        'icon' => '👨‍🍳',
        'time' => 'กำลังดำเนินการ',
        'completed' => true,
        'current' => true
    ],
    'ready' => [
        'name' => 'อาหารพร้อมแล้ว',
        'description' => 'รอไรเดอร์มารับ',
        'icon' => '📦',
        'time' => '',
        'completed' => false
    ],
    'pickup' => [
        'name' => 'ไรเดอร์รับออเดอร์แล้ว',
        'description' => 'กำลังเดินทางไปส่ง',
        'icon' => '🛵',
        'time' => '',
        'completed' => false
    ],
    'delivered' => [
        'name' => 'จัดส่งเรียบร้อย',
        'description' => 'อาหารถึงมือคุณแล้ว',
        'icon' => '🎉',
        'time' => '',
        'completed' => false
    ]
];

// Mock rider data
$rider = [
    'name' => 'สมชาย ใจดี',
    'phone' => '081-234-5678',
    'rating' => 4.8,
    'vehicle' => 'Honda Click 150i',
    'license' => 'กก-1234',
    'photo' => '../assets/images/rider.jpg'
];

// Mock delivery location
$deliveryLocation = [
    'lat' => 13.7563,
    'lng' => 100.5018,
    'address' => '123 ถนนสุขุมวิท แขวงคลองตัน เขตคลองตัน กรุงเทพฯ 10110'
];
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ติดตามสถานะ - Food Delivery</title>
    <link rel="stylesheet" href="./assets/css/tracking.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <div class="sticky top-0 bg-white z-40 shadow-sm">
        <div class="flex items-center justify-between p-4">
            <button onclick="goBack()" class="p-2 rounded-full hover:bg-gray-100">
                <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>
            <div class="text-center">
                <h1 class="text-lg font-bold text-gray-900">ติดตามออเดอร์</h1>
                <p class="text-sm text-gray-500"><?php echo $orderId; ?></p>
            </div>
            <button onclick="shareOrder()" class="p-2 rounded-full hover:bg-gray-100">
                <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                </svg>
            </button>
        </div>
    </div>

    <!-- Estimated Delivery Time -->
    <div class="bg-white p-4 border-b border-gray-100">
        <div class="text-center">
            <div class="text-3xl font-bold text-blue-600 mb-2" id="deliveryCountdown">15:30</div>
            <p class="text-gray-600">เวลาที่คาดว่าจะส่งถึง</p>
            <p class="text-sm text-gray-500 mt-1">ประมาณ 18:45 น.</p>
        </div>
        
        <!-- Progress Bar -->
        <div class="mt-4">
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-blue-500 h-2 rounded-full transition-all duration-1000" style="width: 40%" id="progressBar"></div>
            </div>
        </div>
    </div>

    <!-- Live Map (Mock) -->
    <div class="bg-white border-b border-gray-100">
        <div class="h-64 bg-gradient-to-br from-green-100 to-blue-100 relative overflow-hidden">
            <!-- Mock Map Background -->
            <div class="absolute inset-0 opacity-20">
                <div class="w-full h-full bg-gray-300 relative">
                    <!-- Mock streets -->
                    <div class="absolute top-20 left-0 w-full h-1 bg-gray-400"></div>
                    <div class="absolute top-40 left-0 w-full h-1 bg-gray-400"></div>
                    <div class="absolute top-0 left-20 w-1 h-full bg-gray-400"></div>
                    <div class="absolute top-0 left-40 w-1 h-full bg-gray-400"></div>
                </div>
            </div>
            
            <!-- Restaurant Pin -->
            <div class="absolute top-8 left-8 w-8 h-8 bg-red-500 rounded-full flex items-center justify-center text-white text-sm font-bold shadow-lg">
                🏪
            </div>
            
            <!-- Rider Pin (Animated) -->
            <div class="absolute top-24 left-32 w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white shadow-lg rider-pin">
                🛵
            </div>
            
            <!-- Delivery Pin -->
            <div class="absolute bottom-8 right-8 w-8 h-8 bg-green-500 rounded-full flex items-center justify-center text-white text-sm font-bold shadow-lg">
                📍
            </div>
            
            <!-- Route Line -->
            <svg class="absolute inset-0 w-full h-full">
                <path d="M40 40 Q120 80 200 160" stroke="#3b82f6" stroke-width="3" fill="none" stroke-dasharray="10,5" class="route-line"/>
            </svg>
            
            <!-- Map Controls -->
            <div class="absolute top-4 right-4 space-y-2">
                <button onclick="centerMap()" class="w-10 h-10 bg-white rounded-lg shadow-md flex items-center justify-center hover:bg-gray-50">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Order Status Timeline -->
    <div class="bg-white p-4 border-b border-gray-100">
        <h3 class="font-bold text-gray-900 mb-4">สถานะคำสั่งซื้อ</h3>
        
        <div class="space-y-4">
            <?php foreach ($orderStatuses as $key => $status): ?>
                <div class="status-item flex items-center space-x-4 <?php echo $status['completed'] ? 'completed' : ''; ?> <?php echo isset($status['current']) ? 'current' : ''; ?>">
                    <div class="status-icon w-10 h-10 rounded-full flex items-center justify-center text-lg
                        <?php echo $status['completed'] ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-400'; ?>">
                        <?php echo $status['icon']; ?>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-semibold <?php echo $status['completed'] ? 'text-gray-900' : 'text-gray-500'; ?>">
                            <?php echo $status['name']; ?>
                        </h4>
                        <p class="text-sm <?php echo $status['completed'] ? 'text-gray-600' : 'text-gray-400'; ?>">
                            <?php echo $status['description']; ?>
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs <?php echo $status['completed'] ? 'text-gray-500' : 'text-gray-400'; ?>">
                            <?php echo $status['time']; ?>
                        </p>
                    </div>
                </div>
                
                <?php if ($key !== array_key_last($orderStatuses)): ?>
                    <div class="status-line w-px h-8 bg-gray-200 ml-5 <?php echo $status['completed'] ? 'completed' : ''; ?>"></div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Rider Information -->
    <div class="bg-white p-4 border-b border-gray-100">
        <h3 class="font-bold text-gray-900 mb-3">ข้อมูลไรเดอร์</h3>
        
        <div class="flex items-center space-x-4">
            <div class="w-16 h-16 bg-gray-300 rounded-full flex items-center justify-center text-2xl">
                👨‍🦱
            </div>
            <div class="flex-1">
                <h4 class="font-semibold text-gray-900"><?php echo $rider['name']; ?></h4>
                <div class="flex items-center space-x-2 mt-1">
                    <span class="text-yellow-400">⭐</span>
                    <span class="text-sm text-gray-600"><?php echo $rider['rating']; ?></span>
                    <span class="text-gray-400">•</span>
                    <span class="text-sm text-gray-600"><?php echo $rider['vehicle']; ?></span>
                </div>
                <p class="text-xs text-gray-500"><?php echo $rider['license']; ?></p>
            </div>
            <div class="flex space-x-2">
                <button onclick="callRider()" class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center text-white hover:bg-green-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                    </svg>
                </button>
                <button onclick="chatRider()" class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center text-white hover:bg-blue-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Order Details -->
    <div class="bg-white p-4 border-b border-gray-100">
        <div class="flex items-center justify-between mb-3">
            <h3 class="font-bold text-gray-900">รายละเอียดคำสั่งซื้อ</h3>
            <button onclick="toggleOrderDetails()" class="text-blue-500 text-sm font-medium">
                <span id="toggleText">แสดง</span>
                <svg class="w-4 h-4 inline ml-1 transition-transform" id="toggleIcon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
        </div>
        
        <div id="orderDetails" class="hidden space-y-3">
            <!-- Order items will be loaded from sessionStorage -->
        </div>
    </div>

    <!-- Emergency Contact -->
    <div class="bg-white p-4">
        <div class="text-center">
            <p class="text-sm text-gray-600 mb-3">หากมีปัญหาในการจัดส่ง</p>
            <button onclick="contactSupport()" class="w-full bg-red-500 hover:bg-red-600 text-white py-3 rounded-lg font-medium transition-colors">
                🆘 ติดต่อศูนย์ช่วยเหลือ
            </button>
        </div>
    </div>

    <!-- Bottom spacing -->
    <div class="h-6"></div>

    <!-- Notification Modal -->
    <div id="notificationModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
        <div class="absolute bottom-0 left-0 right-0 bg-white rounded-t-lg p-6">
            <div class="text-center">
                <div class="text-4xl mb-4">🔔</div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">การแจ้งเตือน</h3>
                <p class="text-gray-600 mb-4" id="notificationMessage">ไรเดอร์กำลังเดินทางไปส่งอาหารของคุณ</p>
                <button onclick="closeNotification()" class="w-full bg-blue-500 text-white py-3 rounded-lg font-medium">
                    รับทราบ
                </button>
            </div>
        </div>
    </div>

    <script src="./assets/js/tracking.js"></script>
</body>
</html>