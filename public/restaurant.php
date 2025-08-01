<?php
session_start();

// ถ้ายังไม่ได้ login กลับไปหน้า login
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

// Mock restaurant data
$restaurant = [
    'id' => 1,
    'name' => 'ร้านเมะเก๋า',
    'rating' => 4.5,
    'reviews' => 1250,
    'delivery_time' => '25-35',
    'delivery_fee' => 15,
    'min_order' => 100,
    'open_time' => '08:00',
    'close_time' => '22:00',
    'status' => 'open',
    'image' => '../assets/images/restaurant-banner.jpg',
    'description' => 'ร้านอาหารไทยต้นตำรับ รสชาติเข้มข้น หอมหวาน เผ็ดร้อน ครบรส'
];

// Mock menu data
$menu_categories = [
    'popular' => [
        'name' => 'เมนูยอดนิยม',
        'items' => [
            ['id' => 1, 'name' => 'ข้าวผัดกุ้ง', 'price' => 65, 'description' => 'ข้าวผัดกุ้งสด หอมเครื่องเทศ', 'image' => 'menu1.jpg'],
            ['id' => 2, 'name' => 'ต้มยำกุ้ง', 'price' => 85, 'description' => 'ต้มยำรสเข้มข้น กุ้งสดใหญ่', 'image' => 'menu2.jpg'],
            ['id' => 3, 'name' => 'ผัดไทย', 'price' => 55, 'description' => 'ผัดไทยกุ้งสด รสชาติเข้มข้น', 'image' => 'menu3.jpg']
        ]
    ],
    'rice' => [
        'name' => 'ข้าวและผัด',
        'items' => [
            ['id' => 4, 'name' => 'ข้าวผัดปู', 'price' => 75, 'description' => 'ข้าวผัดเนื้อปูแท้ หอมมัน', 'image' => 'menu4.jpg'],
            ['id' => 5, 'name' => 'ข้าวคลุกกะปิ', 'price' => 45, 'description' => 'ข้าวคลุกกะปิ พร้อมผักสด', 'image' => 'menu5.jpg'],
            ['id' => 6, 'name' => 'ข้าวผัดอเมริกัน', 'price' => 70, 'description' => 'ข้าวผัดสไตล์อเมริกัน ไส้กรอก ไข่ดาว', 'image' => 'menu6.jpg']
        ]
    ],
    'soup' => [
        'name' => 'ซุปและแกง',
        'items' => [
            ['id' => 7, 'name' => 'แกงเขียวหวานไก่', 'price' => 80, 'description' => 'แกงเขียวหวานไก่ เผ็ดหอมมัน', 'image' => 'menu7.jpg'],
            ['id' => 8, 'name' => 'ต้มโคล่งผักรวม', 'price' => 60, 'description' => 'ต้มโคล่งผักสด รสชาติจืดๆ', 'image' => 'menu8.jpg']
        ]
    ],
    'drinks' => [
        'name' => 'เครื่องดื่ม',
        'items' => [
            ['id' => 9, 'name' => 'ชาไทย', 'price' => 25, 'description' => 'ชาไทยเข้มข้น หวานมัน', 'image' => 'menu9.jpg'],
            ['id' => 10, 'name' => 'น้ำมะนาว', 'price' => 20, 'description' => 'น้ำมะนาวสด เปรียวซ่า', 'image' => 'menu10.jpg']
        ]
    ]
];
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $restaurant['name']; ?> - Food Delivery</title>
    <link rel="stylesheet" href="../assets/css/restaurant.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>

<body class="bg-gray-50">
    <!-- Header -->
    <div class="sticky top-0 bg-white z-40 shadow-sm">
        <div class="flex items-center justify-between p-4">
            <button onclick="goBack()" class="p-2 rounded-full hover:bg-gray-100">
                <a href="index.php">
                    <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
            </button>
            <h1 class="text-lg font-bold text-gray-900"><?php echo $restaurant['name']; ?></h1>
            <button onclick="toggleFavorite()" class="p-2 rounded-full hover:bg-gray-100">
                <svg class="w-6 h-6 text-gray-400 hover:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                </svg>
                </a>
            </button>
        </div>
    </div>

    <!-- Restaurant Banner -->
    <div class="relative">
        <div class="h-48 bg-gradient-to-r from-orange-400 to-red-500 flex items-center justify-center">
            <span class="text-8xl">🏪</span>
        </div>
        <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/50 to-transparent p-4 text-white">
            <h2 class="text-2xl font-bold"><?php echo $restaurant['name']; ?></h2>
            <p class="text-sm opacity-90"><?php echo $restaurant['description']; ?></p>
        </div>
    </div>

    <!-- Restaurant Info -->
    <div class="bg-white p-4 border-b border-gray-100">
        <div class="flex items-center justify-between mb-3">
            <div class="flex items-center space-x-4">
                <div class="flex items-center">
                    <span class="text-yellow-400 text-lg">⭐</span>
                    <span class="ml-1 font-semibold"><?php echo $restaurant['rating']; ?></span>
                    <span class="text-gray-500 text-sm ml-1">(<?php echo number_format($restaurant['reviews']); ?>+)</span>
                </div>
                <div class="flex items-center text-gray-600">
                    <span class="text-sm">🕒 <?php echo $restaurant['delivery_time']; ?> นาที</span>
                </div>
                <div class="flex items-center text-gray-600">
                    <span class="text-sm">🚚 ฿<?php echo $restaurant['delivery_fee']; ?></span>
                </div>
            </div>
            <div class="flex items-center">
                <?php if ($restaurant['status'] === 'open'): ?>
                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">เปิดอยู่</span>
                <?php else: ?>
                    <span class="px-2 py-1 bg-red-100 text-red-800 text-xs rounded-full">ปิดแล้ว</span>
                <?php endif; ?>
            </div>
        </div>

        <div class="text-sm text-gray-600">
            <span>เปิด <?php echo $restaurant['open_time']; ?> - <?php echo $restaurant['close_time']; ?></span>
            <span class="mx-2">•</span>
            <span>ขั้นต่ำ ฿<?php echo $restaurant['min_order']; ?></span>
        </div>
    </div>

    <!-- Menu Categories Navigation -->
    <div class="sticky top-16 bg-white z-30 border-b border-gray-100">
        <div class="flex overflow-x-auto px-4 py-3 space-x-6">
            <?php foreach ($menu_categories as $key => $category): ?>
                <button onclick="scrollToCategory('<?php echo $key; ?>')"
                    class="category-nav-btn whitespace-nowrap text-sm font-medium text-gray-600 hover:text-blue-600 transition-colors"
                    data-category="<?php echo $key; ?>">
                    <?php echo $category['name']; ?>
                </button>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Menu Items -->
    <div class="pb-32">
        <?php foreach ($menu_categories as $key => $category): ?>
            <div id="category-<?php echo $key; ?>" class="category-section">
                <div class="bg-gray-50 px-4 py-3 border-b border-gray-100">
                    <h3 class="font-bold text-gray-900"><?php echo $category['name']; ?></h3>
                </div>

                <div class="bg-white">
                    <?php foreach ($category['items'] as $item): ?>
                        <div class="menu-item border-b border-gray-100 p-4 hover:bg-gray-50 transition-colors">
                            <div class="flex items-start space-x-4">
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-900 mb-1"><?php echo $item['name']; ?></h4>
                                    <p class="text-sm text-gray-600 mb-2"><?php echo $item['description']; ?></p>
                                    <div class="flex items-center justify-between">
                                        <span class="text-lg font-bold text-gray-900">฿<?php echo $item['price']; ?></span>
                                        <button onclick="addToCart(<?php echo $item['id']; ?>, '<?php echo addslashes($item['name']); ?>', <?php echo $item['price']; ?>)"
                                            class="add-to-cart-btn bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                            เพิ่มลงตะกร้า
                                        </button>
                                    </div>
                                </div>
                                <div class="w-20 h-20 bg-gray-200 rounded-lg flex items-center justify-center">
                                    <span class="text-2xl">🍽️</span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Floating Cart Button -->
    <div id="floatingCart" class="fixed bottom-4 left-4 right-4 bg-blue-500 text-white rounded-lg shadow-lg hidden z-50">
        <button onclick="openCart()" class="w-full p-4 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <span id="cartCount" class="text-sm font-bold">0</span>
                </div>
                <span class="font-medium">ดูตะกร้า</span>
            </div>
            <div class="text-right">
                <div id="cartTotal" class="font-bold">฿0</div>
            </div>
        </button>
    </div>

    <!-- Cart Modal -->
    <div id="cartModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
        <div class="absolute bottom-0 left-0 right-0 bg-white rounded-t-lg max-h-[80vh] overflow-hidden">
            <!-- Cart Header -->
            <div class="flex items-center justify-between p-4 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-900">ตะกร้าสินค้า</h3>
                <button onclick="closeCart()" class="p-2 rounded-full hover:bg-gray-100">
                    <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Cart Items -->
            <div class="overflow-y-auto max-h-96">
                <div id="cartItems" class="p-4 space-y-4">
                    <!-- Cart items will be dynamically inserted here -->
                </div>
            </div>

            <!-- Discount Code -->
            <div class="p-4 border-t border-gray-100">
                <div class="flex space-x-2">
                    <input type="text"
                        id="discountCode"
                        placeholder="ใส่โค้ดส่วนลด"
                        class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm">
                    <button onclick="applyDiscount()"
                        class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-200">
                        ใช้โค้ด
                    </button>
                </div>
                <div id="discountMessage" class="mt-2 text-sm hidden"></div>
            </div>

            <!-- Cart Summary -->
            <div class="p-4 border-t border-gray-200 bg-gray-50">
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">ราคาสินค้า</span>
                        <span id="subtotal">฿0</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">ค่าจัดส่ง</span>
                        <span id="deliveryFee">฿<?php echo $restaurant['delivery_fee']; ?></span>
                    </div>
                    <div id="discountRow" class="flex justify-between text-green-600 hidden">
                        <span>ส่วนลด</span>
                        <span id="discountAmount">-฿0</span>
                    </div>
                    <hr class="my-2">
                    <div class="flex justify-between font-bold text-lg">
                        <span>รวมทั้งหมด</span>
                        <span id="finalTotal">฿<?php echo $restaurant['delivery_fee']; ?></span>
                    </div>
                </div>

                <button onclick="proceedToCheckout()"
                    id="checkoutBtn"
                    class="w-full mt-4 bg-blue-500 hover:bg-blue-600 text-white py-3 rounded-lg font-medium transition-colors disabled:bg-gray-300 disabled:cursor-not-allowed"
                    disabled>
                    สั่งซื้อ
                </button>

                <div id="minOrderWarning" class="mt-2 text-sm text-red-600 text-center hidden">
                    ขั้นต่ำ ฿<?php echo $restaurant['min_order']; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div id="loadingOverlay" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg p-6 flex items-center space-x-3">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500"></div>
            <span class="text-gray-700">กำลังดำเนินการ...</span>
        </div>
    </div>

    <script src="../assets/js/restaurant.js"></script>
</body>

</html>