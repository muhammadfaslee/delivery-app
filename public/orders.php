<?php
session_start();

// ถ้ายังไม่ได้ login กลับไปหน้า login
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];

// Mock orders data
$orders = [
    [
        'id' => 'ORD001',
        'restaurant_name' => 'ร้านเมะเก๋า',
        'restaurant_image' => 'restaurant1.jpg',
        'items' => [
            ['name' => 'ข้าวผัดกุ้ง', 'quantity' => 1, 'price' => 65],
            ['name' => 'ต้มยำกุ้ง', 'quantity' => 1, 'price' => 85]
        ],
        'total' => 165,
        'status' => 'delivered',
        'order_date' => '2024-03-15 18:30:00',
        'delivery_date' => '2024-03-15 19:15:00',
        'rating' => 5,
        'review' => 'อร่อยมาก ส่งเร็วด้วย'
    ],
    [
        'id' => 'ORD002',
        'restaurant_name' => 'ร้านเมะนำ',
        'restaurant_image' => 'restaurant2.jpg',
        'items' => [
            ['name' => 'แกงเขียวหวานไก่', 'quantity' => 1, 'price' => 80],
            ['name' => 'ข้าวผัดปู', 'quantity' => 1, 'price' => 75]
        ],
        'total' => 170,
        'status' => 'delivered',
        'order_date' => '2024-03-14 12:15:00',
        'delivery_date' => '2024-03-14 13:00:00',
        'rating' => 4,
        'review' => 'รสชาติดี แต่เผ็ดไปนิด'
    ],
    [
        'id' => 'ORD003',
        'restaurant_name' => 'ร้านอาหารพิเศษ',
        'restaurant_image' => 'restaurant3.jpg',
        'items' => [
            ['name' => 'ผัดไทย', 'quantity' => 2, 'price' => 55],
            ['name' => 'ชาไทย', 'quantity' => 1, 'price' => 25]
        ],
        'total' => 135,
        'status' => 'cancelled',
        'order_date' => '2024-03-13 19:45:00',
        'cancel_reason' => 'ร้านปิดกะทันหัน'
    ],
    [
        'id' => 'ORD004',
        'restaurant_name' => 'ร้านเมะเก๋า',
        'restaurant_image' => 'restaurant1.jpg',
        'items' => [
            ['name' => 'ข้าวคลุกกะปิ', 'quantity' => 1, 'price' => 45],
            ['name' => 'น้ำมะนาว', 'quantity' => 1, 'price' => 20]
        ],
        'total' => 80,
        'status' => 'delivered',
        'order_date' => '2024-03-12 11:30:00',
        'delivery_date' => '2024-03-12 12:10:00',
        'rating' => 5,
        'review' => 'สด สะอาด อร่อย'
    ]
];

// Filter orders by status if specified
$filter = $_GET['filter'] ?? 'all';
if ($filter !== 'all') {
    $orders = array_filter($orders, function($order) use ($filter) {
        return $order['status'] === $filter;
    });
}

// Status labels
$status_labels = [
    'delivered' => ['label' => 'จัดส่งแล้ว', 'color' => 'bg-green-100 text-green-800'],
    'preparing' => ['label' => 'กำลังเตรียม', 'color' => 'bg-yellow-100 text-yellow-800'],
    'shipping' => ['label' => 'กำลังจัดส่ง', 'color' => 'bg-blue-100 text-blue-800'],
    'cancelled' => ['label' => 'ยกเลิกแล้ว', 'color' => 'bg-red-100 text-red-800']
];

function formatDate($dateString) {
    $date = new DateTime($dateString);
    return $date->format('d/m/Y H:i');
}

function timeAgo($dateString) {
    $date = new DateTime($dateString);
    $now = new DateTime();
    $diff = $now->diff($date);
    
    if ($diff->days > 0) {
        return $diff->days . ' วันที่แล้ว';
    } elseif ($diff->h > 0) {
        return $diff->h . ' ชั่วโมงที่แล้ว';
    } else {
        return $diff->i . ' นาทีที่แล้ว';
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ประวัติคำสั่งซื้อ - Food Delivery</title>
    <link rel="stylesheet" href="./assets/css/orders.css">
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
            <h1 class="text-lg font-bold text-gray-900">ประวัติคำสั่งซื้อ</h1>
            <button onclick="showFilterMenu()" class="p-2 rounded-full hover:bg-gray-100">
                <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z"></path>
                </svg>
            </button>
        </div>
        
        <!-- Filter Tabs -->
        <div class="flex overflow-x-auto px-4 pb-2">
            <div class="flex space-x-4 min-w-max">
                <a href="?filter=all" class="filter-tab <?php echo $filter === 'all' ? 'active' : ''; ?>">
                    ทั้งหมด
                </a>
                <a href="?filter=delivered" class="filter-tab <?php echo $filter === 'delivered' ? 'active' : ''; ?>">
                    จัดส่งแล้ว
                </a>
                <a href="?filter=preparing" class="filter-tab <?php echo $filter === 'preparing' ? 'active' : ''; ?>">
                    กำลังเตรียม
                </a>
                <a href="?filter=cancelled" class="filter-tab <?php echo $filter === 'cancelled' ? 'active' : ''; ?>">
                    ยกเลิกแล้ว
                </a>
            </div>
        </div>
    </div>

    <!-- Orders List -->
    <div class="p-4">
        <?php if (empty($orders)): ?>
            <!-- Empty State -->
            <div class="text-center py-16">
                <div class="text-6xl mb-4">📦</div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">ไม่มีคำสั่งซื้อ</h3>
                <p class="text-gray-500 mb-6">คุณยังไม่เคยสั่งอาหารเลย</p>
                <button onclick="goToHome()" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg font-medium">
                    เริ่มสั่งอาหาร
                </button>
            </div>
        <?php else: ?>
            <div class="space-y-4">
                <?php foreach ($orders as $order): ?>
                    <div class="order-card bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
                        <!-- Order Header -->
                        <div class="p-4 border-b border-gray-100">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center space-x-3">
                                    <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center">
                                        <span class="text-2xl">🏪</span>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-gray-900"><?php echo $order['restaurant_name']; ?></h3>
                                        <p class="text-sm text-gray-500"><?php echo timeAgo($order['order_date']); ?></p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="px-2 py-1 rounded-full text-xs font-medium <?php echo $status_labels[$order['status']]['color']; ?>">
                                        <?php echo $status_labels[$order['status']]['label']; ?>
                                    </span>
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-gray-600">คำสั่งซื้อ: <?php echo $order['id']; ?></p>
                                    <p class="font-bold text-gray-900">฿<?php echo $order['total']; ?></p>
                                </div>
                                <?php if ($order['status'] === 'delivered' && isset($order['rating'])): ?>
                                    <div class="flex items-center space-x-1">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <span class="text-sm <?php echo $i <= $order['rating'] ? 'text-yellow-400' : 'text-gray-300'; ?>">⭐</span>
                                        <?php endfor; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <!-- Order Items -->
                        <div class="px-4 py-3 bg-gray-50">
                            <div class="space-y-1">
                                <?php foreach ($order['items'] as $item): ?>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600"><?php echo $item['name']; ?> x<?php echo $item['quantity']; ?></span>
                                        <span class="text-gray-900">฿<?php echo $item['price'] * $item['quantity']; ?></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        
                        <!-- Order Actions -->
                        <div class="p-4 flex items-center justify-between">
                            <div class="flex space-x-2">
                                <button onclick="viewOrderDetails('<?php echo $order['id']; ?>')" 
                                        class="text-blue-500 hover:text-blue-600 text-sm font-medium">
                                    ดูรายละเอียด
                                </button>
                                
                                <?php if ($order['status'] === 'delivered'): ?>
                                    <span class="text-gray-300">•</span>
                                    <button onclick="reorder('<?php echo $order['id']; ?>')" 
                                            class="text-green-500 hover:text-green-600 text-sm font-medium">
                                        สั่งซ้ำ
                                    </button>
                                    
                                    <?php if (!isset($order['rating'])): ?>
                                        <span class="text-gray-300">•</span>
                                        <button onclick="rateOrder('<?php echo $order['id']; ?>')" 
                                                class="text-orange-500 hover:text-orange-600 text-sm font-medium">
                                            ให้คะแนน
                                        </button>
                                    <?php endif; ?>
                                <?php elseif ($order['status'] === 'cancelled'): ?>
                                    <span class="text-gray-300">•</span>
                                    <button onclick="reorder('<?php echo $order['id']; ?>')" 
                                            class="text-green-500 hover:text-green-600 text-sm font-medium">
                                        สั่งใหม่
                                    </button>
                                <?php endif; ?>
                            </div>
                            
                            <?php if ($order['status'] === 'preparing' || $order['status'] === 'shipping'): ?>
                                <button onclick="trackOrder('<?php echo $order['id']; ?>')" 
                                        class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium">
                                    ติดตาม
                                </button>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Review (if exists) -->
                        <?php if (isset($order['review'])): ?>
                            <div class="px-4 pb-4">
                                <div class="bg-blue-50 rounded-lg p-3">
                                    <p class="text-sm text-gray-700 italic">"<?php echo $order['review']; ?>"</p>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Cancel Reason (if cancelled) -->
                        <?php if ($order['status'] === 'cancelled' && isset($order['cancel_reason'])): ?>
                            <div class="px-4 pb-4">
                                <div class="bg-red-50 border-l-4 border-red-500 p-3">
                                    <p class="text-sm text-red-700">
                                        <span class="font-medium">เหตุผลการยกเลิก:</span> <?php echo $order['cancel_reason']; ?>
                                    </p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Load More Button -->
    <?php if (count($orders) >= 4): ?>
        <div class="text-center p-4">
            <button onclick="loadMoreOrders()" 
                    id="loadMoreBtn"
                    class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-3 rounded-lg font-medium">
                โหลดเพิ่มเติม
            </button>
        </div>
    <?php endif; ?>

    <!-- Bottom spacing -->
    <div class="h-6"></div>

    <!-- Order Details Modal -->
    <div id="orderDetailsModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
        <div class="absolute bottom-0 left-0 right-0 bg-white rounded-t-lg max-h-[80vh] overflow-hidden">
            <div class="flex items-center justify-between p-4 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-900">รายละเอียดคำสั่งซื้อ</h3>
                <button onclick="closeOrderDetails()" class="p-2 rounded-full hover:bg-gray-100">
                    <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <div class="p-4 overflow-y-auto max-h-96" id="orderDetailsContent">
                <!-- Order details will be loaded here -->
            </div>
        </div>
    </div>

    <!-- Rating Modal -->
    <div id="ratingModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
        <div class="absolute bottom-0 left-0 right-0 bg-white rounded-t-lg p-6">
            <div class="text-center">
                <h3 class="text-lg font-bold text-gray-900 mb-4">ให้คะแนนและรีวิว</h3>
                
                <!-- Star Rating -->
                <div class="flex justify-center space-x-2 mb-4">
                    <button onclick="setRating(1)" class="rating-star text-3xl text-gray-300 hover:text-yellow-400">⭐</button>
                    <button onclick="setRating(2)" class="rating-star text-3xl text-gray-300 hover:text-yellow-400">⭐</button>
                    <button onclick="setRating(3)" class="rating-star text-3xl text-gray-300 hover:text-yellow-400">⭐</button>
                    <button onclick="setRating(4)" class="rating-star text-3xl text-gray-300 hover:text-yellow-400">⭐</button>
                    <button onclick="setRating(5)" class="rating-star text-3xl text-gray-300 hover:text-yellow-400">⭐</button>
                </div>
                
                <p class="text-gray-600 mb-4" id="ratingText">แตะเพื่อให้คะแนน</p>
                
                <!-- Review Text -->
                <textarea id="reviewText" 
                          placeholder="เขียนรีวิวเพิ่มเติม (ไม่บังคับ)"
                          class="w-full p-3 border border-gray-300 rounded-lg resize-none mb-4"
                          rows="3"></textarea>
                
                <!-- Action Buttons -->
                <div class="flex space-x-3">
                    <button onclick="closeRatingModal()" 
                            class="flex-1 py-3 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50">
                        ยกเลิก
                    </button>
                    <button onclick="submitRating()" 
                            id="submitRatingBtn"
                            class="flex-1 py-3 bg-blue-500 text-white rounded-lg font-medium hover:bg-blue-600 disabled:bg-gray-300"
                            disabled>
                        ส่งรีวิว
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Menu Modal -->
    <div id="filterModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
        <div class="absolute top-16 right-4 bg-white rounded-lg shadow-lg min-w-[200px] overflow-hidden">
            <div class="py-2">
                <a href="?filter=all" class="block px-4 py-3 hover:bg-gray-50 <?php echo $filter === 'all' ? 'bg-blue-50 text-blue-600' : 'text-gray-700'; ?>">
                    <div class="flex items-center justify-between">
                        <span>ทั้งหมด</span>
                        <?php if ($filter === 'all'): ?>
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                        <?php endif; ?>
                    </div>
                </a>
                <a href="?filter=delivered" class="block px-4 py-3 hover:bg-gray-50 <?php echo $filter === 'delivered' ? 'bg-blue-50 text-blue-600' : 'text-gray-700'; ?>">
                    <div class="flex items-center justify-between">
                        <span>จัดส่งแล้ว</span>
                        <?php if ($filter === 'delivered'): ?>
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                        <?php endif; ?>
                    </div>
                </a>
                <a href="?filter=preparing" class="block px-4 py-3 hover:bg-gray-50 <?php echo $filter === 'preparing' ? 'bg-blue-50 text-blue-600' : 'text-gray-700'; ?>">
                    <div class="flex items-center justify-between">
                        <span>กำลังเตรียม</span>
                        <?php if ($filter === 'preparing'): ?>
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                        <?php endif; ?>
                    </div>
                </a>
                <a href="?filter=cancelled" class="block px-4 py-3 hover:bg-gray-50 <?php echo $filter === 'cancelled' ? 'bg-blue-50 text-blue-600' : 'text-gray-700'; ?>">
                    <div class="flex items-center justify-between">
                        <span>ยกเลิกแล้ว</span>
                        <?php if ($filter === 'cancelled'): ?>
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                        <?php endif; ?>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <script src="./assets/js/orders.js"></script>
</body>
</html>