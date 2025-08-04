<?php
session_start();

// ถ้ายังไม่ได้ login กลับไปหน้า login
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];

// Mock user address data
$addresses = [
    [
        'id' => 1,
        'type' => 'home',
        'label' => 'บ้าน',
        'address' => '123 ถนนสุขุมวิท แขวงคลองตัน เขตคลองตัน กรุงเทพฯ 10110',
        'phone' => '081-234-5678',
        'is_default' => true
    ],
    [
        'id' => 2,
        'type' => 'work',
        'label' => 'ที่ทำงาน',
        'address' => '456 ถนนสีลม แขวงสีลม เขตบางรัก กรุงเทพฯ 10500',
        'phone' => '081-234-5678',
        'is_default' => false
    ]
];

// Payment methods
$payment_methods = [
    [
        'id' => 'cash',
        'name' => 'เงินสด',
        'description' => 'ชำระเงินสดเมื่อได้รับสินค้า',
        'icon' => '💵',
        'available' => true
    ],
    [
        'id' => 'promptpay',
        'name' => 'พร้อมเพย์',
        'description' => 'สแกน QR Code ผ่านแอปธนาคาร',
        'icon' => '📱',
        'available' => true
    ],
    [
        'id' => 'card',
        'name' => 'บัตรเครดิต',
        'description' => 'Visa, Mastercard, JCB',
        'icon' => '💳',
        'available' => false
    ]
];
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ยืนยันคำสั่งซื้อ - Food Delivery</title>
    <link rel="stylesheet" href="./assets/css/checkout.css">
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
            <h1 class="text-lg font-bold text-gray-900">ยืนยันคำสั่งซื้อ</h1>
            <div class="w-10"></div>
        </div>
    </div>

    <!-- Order Summary -->
    <div class="bg-white p-4 border-b border-gray-100">
        <div class="flex items-center justify-between mb-3">
            <h2 class="text-lg font-bold text-gray-900">รายการสินค้า</h2>
            <button onclick="editOrder()" class="text-blue-500 text-sm font-medium">แก้ไข</button>
        </div>
        
        <div id="orderSummary" class="space-y-3">
            <!-- Order items will be loaded here -->
        </div>
        
        <div class="mt-4 pt-4 border-t border-gray-100">
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600">ราคาสินค้า</span>
                    <span id="subtotalAmount">฿0</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">ค่าจัดส่ง</span>
                    <span id="deliveryFeeAmount">฿0</span>
                </div>
                <div id="discountSummary" class="flex justify-between text-green-600 hidden">
                    <span>ส่วนลด</span>
                    <span id="discountSummaryAmount">-฿0</span>
                </div>
                <hr class="my-2">
                <div class="flex justify-between font-bold text-lg">
                    <span>รวมทั้งหมด</span>
                    <span id="totalAmount">฿0</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Delivery Address -->
    <div class="bg-white p-4 border-b border-gray-100">
        <h3 class="text-lg font-bold text-gray-900 mb-3">ที่อยู่จัดส่ง</h3>
        
        <div class="space-y-3">
            <?php foreach ($addresses as $address): ?>
                <label class="address-option block cursor-pointer">
                    <input type="radio" 
                           name="delivery_address" 
                           value="<?php echo $address['id']; ?>" 
                           class="hidden"
                           <?php echo $address['is_default'] ? 'checked' : ''; ?>>
                    <div class="border-2 border-gray-200 rounded-lg p-4 transition-all hover:border-blue-300">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-2 mb-2">
                                    <span class="text-lg"><?php echo $address['type'] === 'home' ? '🏠' : '🏢'; ?></span>
                                    <span class="font-semibold text-gray-900"><?php echo $address['label']; ?></span>
                                    <?php if ($address['is_default']): ?>
                                        <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">ค่าเริ่มต้น</span>
                                    <?php endif; ?>
                                </div>
                                <p class="text-gray-600 text-sm mb-1"><?php echo $address['address']; ?></p>
                                <p class="text-gray-500 text-sm">📞 <?php echo $address['phone']; ?></p>
                            </div>
                            <div class="radio-indicator w-5 h-5 border-2 border-gray-300 rounded-full flex items-center justify-center">
                                <div class="w-3 h-3 bg-blue-500 rounded-full hidden"></div>
                            </div>
                        </div>
                    </div>
                </label>
            <?php endforeach; ?>
            
            <button onclick="addNewAddress()" class="w-full border-2 border-dashed border-gray-300 rounded-lg p-4 text-gray-500 hover:border-blue-300 hover:text-blue-500 transition-colors">
                <div class="flex items-center justify-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    <span class="font-medium">เพิ่มที่อยู่ใหม่</span>
                </div>
            </button>
        </div>
    </div>

    <!-- Payment Method -->
    <div class="bg-white p-4 border-b border-gray-100">
        <h3 class="text-lg font-bold text-gray-900 mb-3">วิธีชำระเงิน</h3>
        
        <div class="space-y-3">
            <?php foreach ($payment_methods as $method): ?>
                <label class="payment-option block cursor-pointer <?php echo !$method['available'] ? 'opacity-50 cursor-not-allowed' : ''; ?>">
                    <input type="radio" 
                           name="payment_method" 
                           value="<?php echo $method['id']; ?>" 
                           class="hidden"
                           <?php echo !$method['available'] ? 'disabled' : ''; ?>
                           <?php echo $method['id'] === 'cash' ? 'checked' : ''; ?>>
                    <div class="border-2 border-gray-200 rounded-lg p-4 transition-all hover:border-blue-300">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <span class="text-2xl"><?php echo $method['icon']; ?></span>
                                <div>
                                    <div class="font-semibold text-gray-900 flex items-center">
                                        <?php echo $method['name']; ?>
                                        <?php if (!$method['available']): ?>
                                            <span class="ml-2 px-2 py-1 bg-gray-100 text-gray-500 text-xs rounded-full">ไม่พร้อมใช้งาน</span>
                                        <?php endif; ?>
                                    </div>
                                    <p class="text-gray-600 text-sm"><?php echo $method['description']; ?></p>
                                </div>
                            </div>
                            <div class="radio-indicator w-5 h-5 border-2 border-gray-300 rounded-full flex items-center justify-center">
                                <div class="w-3 h-3 bg-blue-500 rounded-full hidden"></div>
                            </div>
                        </div>
                    </div>
                </label>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Special Instructions -->
    <div class="bg-white p-4 border-b border-gray-100">
        <h3 class="text-lg font-bold text-gray-900 mb-3">หมายเหตุเพิ่มเติม</h3>
        
        <textarea id="specialInstructions" 
                  placeholder="เช่น ไม่ใส่ผักชี, เพิ่มน้ำแข็ง, วางของหน้าประตู..."
                  class="w-full p-3 border border-gray-300 rounded-lg resize-none focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                  rows="3"></textarea>
        
        <!-- Quick Options -->
        <div class="mt-3">
            <p class="text-sm text-gray-600 mb-2">ตัวเลือกด่วน:</p>
            <div class="flex flex-wrap gap-2">
                <button onclick="addQuickNote('ไม่ใส่ผักชี')" class="quick-note-btn px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm hover:bg-gray-200">ไม่ใส่ผักชี</button>
                <button onclick="addQuickNote('เผ็ดน้อย')" class="quick-note-btn px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm hover:bg-gray-200">เผ็ดน้อย</button>
                <button onclick="addQuickNote('เผ็ดมาก')" class="quick-note-btn px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm hover:bg-gray-200">เผ็ดมาก</button>
                <button onclick="addQuickNote('ไม่ใส่น้ำตาล')" class="quick-note-btn px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm hover:bg-gray-200">ไม่ใส่น้ำตาล</button>
                <button onclick="addQuickNote('วางหน้าประตู')" class="quick-note-btn px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm hover:bg-gray-200">วางหน้าประตู</button>
            </div>
        </div>
    </div>

    <!-- Estimated Delivery Time -->
    <div class="bg-white p-4 border-b border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="font-bold text-gray-900">เวลาจัดส่งโดยประมาณ</h3>
                <div class="flex items-center space-x-2 mt-1">
                    <span class="text-2xl">🕒</span>
                    <span class="text-gray-600">25-35 นาที</span>
                </div>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-500">จัดส่งโดย</p>
                <p class="font-medium text-gray-900">🛵 ไรเดอร์</p>
            </div>
        </div>
    </div>

    <!-- Bottom spacing -->
    <div class="h-32"></div>

    <!-- Fixed Bottom Bar -->
    <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 p-4 shadow-lg">
        <div class="max-w-md mx-auto">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <p class="text-sm text-gray-600">ยอดรวม</p>
                    <p class="text-xl font-bold text-gray-900" id="bottomTotalAmount">฿0</p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-600">เวลาจัดส่ง</p>
                    <p class="font-medium text-gray-900">25-35 นาที</p>
                </div>
            </div>
            
            <button onclick="confirmOrder()" 
                    id="confirmOrderBtn"
                    class="w-full bg-blue-500 hover:bg-blue-600 text-white py-3 rounded-lg font-medium transition-colors disabled:bg-gray-300 disabled:cursor-not-allowed">
                ยืนยันสั่งซื้อ
            </button>
        </div>
    </div>

    <!-- Add New Address Modal -->
    <div id="addressModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
        <div class="absolute bottom-0 left-0 right-0 bg-white rounded-t-lg max-h-[80vh] overflow-hidden">
            <div class="flex items-center justify-between p-4 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-900">เพิ่มที่อยู่ใหม่</h3>
                <button onclick="closeAddressModal()" class="p-2 rounded-full hover:bg-gray-100">
                    <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <div class="p-4 overflow-y-auto">
                <form id="newAddressForm" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">ประเภทที่อยู่</label>
                        <div class="flex space-x-3">
                            <label class="flex-1">
                                <input type="radio" name="addressType" value="home" class="hidden" checked>
                                <div class="address-type-btn border-2 border-blue-500 bg-blue-50 text-blue-700 rounded-lg p-3 text-center cursor-pointer">
                                    <div class="text-2xl mb-1">🏠</div>
                                    <div class="text-sm font-medium">บ้าน</div>
                                </div>
                            </label>
                            <label class="flex-1">
                                <input type="radio" name="addressType" value="work" class="hidden">
                                <div class="address-type-btn border-2 border-gray-300 rounded-lg p-3 text-center cursor-pointer hover:border-blue-300">
                                    <div class="text-2xl mb-1">🏢</div>
                                    <div class="text-sm font-medium">ที่ทำงาน</div>
                                </div>
                            </label>
                            <label class="flex-1">
                                <input type="radio" name="addressType" value="other" class="hidden">
                                <div class="address-type-btn border-2 border-gray-300 rounded-lg p-3 text-center cursor-pointer hover:border-blue-300">
                                    <div class="text-2xl mb-1">📍</div>
                                    <div class="text-sm font-medium">อื่นๆ</div>
                                </div>
                            </label>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">ที่อยู่</label>
                        <textarea name="address" 
                                  placeholder="เลขที่ ซอย ถนน แขวง เขต จังหวัด รหัสไปรษณีย์"
                                  class="w-full p-3 border border-gray-300 rounded-lg resize-none focus:outline-none focus:ring-2 focus:ring-blue-500"
                                  rows="3" required></textarea>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">เบอร์โทรศัพท์</label>
                        <input type="tel" 
                               name="phone" 
                               placeholder="081-234-5678"
                               class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                               required>
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" 
                               name="setDefault" 
                               id="setDefault"
                               class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                        <label for="setDefault" class="ml-2 text-sm text-gray-700">ตั้งเป็นที่อยู่เริ่มต้น</label>
                    </div>
                    
                    <div class="flex space-x-3 pt-4">
                        <button type="button" 
                                onclick="closeAddressModal()"
                                class="flex-1 py-3 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50">
                            ยกเลิก
                        </button>
                        <button type="submit" 
                                class="flex-1 py-3 bg-blue-500 text-white rounded-lg font-medium hover:bg-blue-600">
                            บันทึก
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div id="loadingOverlay" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg p-6 flex items-center space-x-3">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500"></div>
            <span class="text-gray-700">กำลังสั่งซื้อ...</span>
        </div>
    </div>

    <script src="./assets/js/checkout.js"></script>
</body>
</html>