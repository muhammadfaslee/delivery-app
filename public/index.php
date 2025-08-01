<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สั่งสื่อ คุณสบาย</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <!-- Status Bar -->
    <div class="status-bar">
        <div class="signal-bars">
            <div class="bar h-2"></div>
            <div class="bar h-3"></div>
            <div class="bar h-4"></div>
            <div class="bar h-5"></div>
        </div>
        <div class="battery"></div>
    </div>

    <!-- Header -->
    <div class="bg-white px-5 py-4">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-2xl font-bold text-gray-900">สั่งสื่อ คุณสบาย</h1>
            <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                <span class="text-gray-600 text-xs">👤</span>
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
                   placeholder="ค้นหาสินค้าหรือ ร้านค้า" 
                   class="w-full pl-10 pr-4 py-3 bg-gray-100 rounded-lg border-0 text-gray-700 placeholder-gray-500">
        </div>
    </div>

    <!-- Promotion Cards -->
    <div class="px-5 py-2">
        <div class="flex gap-3">
            <!-- Profile Card -->
            <div class="w-20 h-20 bg-gray-200 rounded-lg flex items-center justify-center">
                <div class="w-12 h-12 bg-gray-400 rounded-full flex items-center justify-center">
                    <span class="text-gray-600 text-xl">👤</span>
                </div>
            </div>
            
            <!-- Discount Card -->
            <div class="flex-1 h-20 bg-orange-100 rounded-lg flex items-center justify-center border border-orange-200">
                <span class="text-orange-600 font-bold text-lg">ลด 20%</span>
            </div>
        </div>
    </div>

    <!-- Menu Section -->
    <div class="px-5 py-4">
        <!-- ร้านเมะเก๋า Section -->
        <div class="mb-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-gray-900">ร้านเมะเก๋า</h2>
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </div>

            <div class="space-y-3">
                <!-- Restaurant Item 1 -->
                <div class="flex items-center space-x-4 bg-white p-4 rounded-lg shadow-sm">
                    <div class="w-12 h-12 bg-gray-300 rounded-full flex items-center justify-center">
                        <span class="text-gray-600">🍜</span>
                    </div>
                    <div class="flex-1">
                        <div class="font-semibold text-gray-900">ครอบขนม</div>
                        <div class="text-sm text-gray-500">1.5 km</div>
                    </div>
                    <div class="text-right">
                        <div class="font-bold text-gray-900">฿55</div>
                    </div>
                </div>

                <!-- Restaurant Item 2 -->
                <div class="flex items-center space-x-4 bg-white p-4 rounded-lg shadow-sm">
                    <div class="w-12 h-12 bg-gray-300 rounded-full flex items-center justify-center">
                        <span class="text-gray-600">🍕</span>
                    </div>
                    <div class="flex-1">
                        <div class="font-semibold text-gray-900">โดลมินรัต</div>
                        <div class="text-sm text-gray-500">1.3 km</div>
                    </div>
                    <div class="text-right">
                        <div class="font-bold text-gray-900">฿40</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ร้านเมะนำ Section -->
        <div class="mb-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-gray-900">ร้านเมะนำ</h2>
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </div>

            <div class="space-y-3">
                <!-- Restaurant Item 3 -->
                <div class="flex items-center space-x-4 bg-white p-4 rounded-lg shadow-sm">
                    <div class="w-12 h-12 bg-gray-300 rounded-full flex items-center justify-center">
                        <span class="text-gray-600">🍛</span>
                    </div>
                    <div class="flex-1">
                        <div class="font-semibold text-gray-900">ข้าวผัดกุ้ง</div>
                        <div class="text-sm text-gray-500">0.8 km</div>
                    </div>
                    <div class="text-right">
                        <div class="font-bold text-gray-900">฿65</div>
                    </div>
                </div>

                <!-- Restaurant Item 4 -->
                <div class="flex items-center space-x-4 bg-white p-4 rounded-lg shadow-sm">
                    <div class="w-12 h-12 bg-gray-300 rounded-full flex items-center justify-center">
                        <span class="text-gray-600">🍲</span>
                    </div>
                    <div class="flex-1">
                        <div class="font-semibold text-gray-900">ต้มยำกุ้งนำ้ใส</div>
                        <div class="text-sm text-gray-500">0.9 km</div>
                    </div>
                    <div class="text-right">
                        <div class="font-bold text-gray-900">฿85</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Navigation -->
    <div class="fixed bottom-0 left-1/2 transform -translate-x-1/2 w-full max-w-[375px] bg-white border-t border-gray-200">
        <div class="flex justify-around py-2">
            <button class="flex flex-col items-center py-2 px-4">
                <span class="text-2xl mb-1">🏠</span>
                <span class="text-xs text-gray-600">หน้าแรก</span>
            </button>
            <button class="flex flex-col items-center py-2 px-4">
                <span class="text-2xl mb-1">🔍</span>
                <span class="text-xs text-gray-600">ค้นหา</span>
            </button>
            <button class="flex flex-col items-center py-2 px-4">
                <span class="text-2xl mb-1">📦</span>
                <span class="text-xs text-gray-600">คำสั่งซื้อ</span>
            </button>
            <button class="flex flex-col items-center py-2 px-4">
                <span class="text-2xl mb-1">👤</span>
                <span class="text-xs text-gray-600">โปรไฟล์</span>
            </button>
        </div>
    </div>

    <!-- Add some bottom padding to account for fixed navigation -->
    <div class="h-20"></div>

    <script>
        // Add click interactions
        document.querySelectorAll('.bg-white.p-4').forEach(item => {
            item.addEventListener('click', function() {
                // Add selection effect
                this.classList.add('bg-blue-50');
                setTimeout(() => {
                    this.classList.remove('bg-blue-50');
                    this.classList.add('bg-white');
                }, 200);
                
                const restaurantName = this.querySelector('.font-semibold').textContent;
                console.log('Selected:', restaurantName);
            });
        });

        // Search functionality
        document.querySelector('input').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            console.log('Searching for:', searchTerm);
            // Add search logic here
        });

        // Bottom navigation
        document.querySelectorAll('.fixed button').forEach(button => {
            button.addEventListener('click', function() {
                const label = this.querySelector('.text-xs').textContent;
                console.log('Navigate to:', label);
                
                // Remove active state from all buttons
                document.querySelectorAll('.fixed button').forEach(btn => {
                    btn.classList.remove('text-blue-500');
                });
                
                // Add active state to clicked button
                this.classList.add('text-blue-500');
            });
        });

        // Set home as active by default
        document.querySelector('.fixed button').classList.add('text-blue-500');
    </script>
</body>
</html>