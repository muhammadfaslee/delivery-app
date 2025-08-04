// Orders page JavaScript
class OrdersPage {
    constructor() {
        this.currentOrderId = null;
        this.currentRating = 0;
        this.loadedOrders = 4; // Initial number of orders loaded
        
        this.initializeElements();
        this.initializeEventListeners();
        this.initializeAnimations();
    }
    
    initializeElements() {
        this.orderDetailsModal = document.getElementById('orderDetailsModal');
        this.orderDetailsContent = document.getElementById('orderDetailsContent');
        this.ratingModal = document.getElementById('ratingModal');
        this.filterModal = document.getElementById('filterModal');
        this.ratingStars = document.querySelectorAll('.rating-star');
        this.ratingText = document.getElementById('ratingText');
        this.reviewText = document.getElementById('reviewText');
        this.submitRatingBtn = document.getElementById('submitRatingBtn');
        this.loadMoreBtn = document.getElementById('loadMoreBtn');
    }
    
    initializeEventListeners() {
        // Close modals when clicking outside
        [this.orderDetailsModal, this.ratingModal, this.filterModal].forEach(modal => {
            if (modal) {
                modal.addEventListener('click', (e) => {
                    if (e.target === modal) {
                        this.closeAllModals();
                    }
                });
            }
        });
        
        // Rating stars interaction
        this.ratingStars.forEach((star, index) => {
            star.addEventListener('mouseenter', () => this.highlightStars(index + 1));
            star.addEventListener('mouseleave', () => this.highlightStars(this.currentRating));
            star.addEventListener('click', () => this.setRating(index + 1));
        });
        
        // Close filter modal when clicking outside
        document.addEventListener('click', (e) => {
            if (!e.target.closest('#filterModal') && !e.target.closest('button[onclick="showFilterMenu()"]')) {
                this.closeFilterMenu();
            }
        });
        
        // Keyboard shortcuts
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.closeAllModals();
            }
        });
    }
    
    initializeAnimations() {
        // Stagger animation for order cards
        const orderCards = document.querySelectorAll('.order-card');
        orderCards.forEach((card, index) => {
            card.style.animationDelay = `${index * 0.1}s`;
        });
    }
    
    viewOrderDetails(orderId) {
        this.currentOrderId = orderId;
        
        // Mock order details data - in real app, would fetch from API
        const orderDetails = {
            'ORD001': {
                restaurant: 'ร้านเมะเก๋า',
                orderDate: '15/03/2024 18:30',
                deliveryDate: '15/03/2024 19:15',
                deliveryAddress: '123 ถนนสุขุมวิท แขวงคลองตัน เขตคลองตัน กรุงเทพฯ 10110',
                paymentMethod: 'เงินสด',
                items: [
                    { name: 'ข้าวผัดกุ้ง', quantity: 1, price: 65 },
                    { name: 'ต้มยำกุ้ง', quantity: 1, price: 85 }
                ],
                subtotal: 150,
                deliveryFee: 15,
                total: 165,
                rider: 'สมชาย ใจดี',
                riderPhone: '081-234-5678'
            }
        };
        
        const order = orderDetails[orderId] || orderDetails['ORD001'];
        
        this.orderDetailsContent.innerHTML = `
            <div class="space-y-4">
                <div>
                    <h4 class="font-semibold text-gray-900 mb-2">ข้อมูลร้านอาหาร</h4>
                    <p class="text-gray-600">${order.restaurant}</p>
                </div>
                
                <div>
                    <h4 class="font-semibold text-gray-900 mb-2">วันที่สั่ง</h4>
                    <p class="text-gray-600">${order.orderDate}</p>
                </div>
                
                ${order.deliveryDate ? `
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-2">วันที่จัดส่ง</h4>
                        <p class="text-gray-600">${order.deliveryDate}</p>
                    </div>
                ` : ''}
                
                <div>
                    <h4 class="font-semibold text-gray-900 mb-2">ที่อยู่จัดส่ง</h4>
                    <p class="text-gray-600 text-sm">${order.deliveryAddress}</p>
                </div>
                
                <div>
                    <h4 class="font-semibold text-gray-900 mb-2">วิธีชำระเงิน</h4>
                    <p class="text-gray-600">${order.paymentMethod}</p>
                </div>
                
                <div>
                    <h4 class="font-semibold text-gray-900 mb-2">รายการสินค้า</h4>
                    <div class="bg-gray-50 rounded-lg p-3 space-y-2">
                        ${order.items.map(item => `
                            <div class="flex justify-between text-sm">
                                <span>${item.name} x${item.quantity}</span>
                                <span>฿${item.price * item.quantity}</span>
                            </div>
                        `).join('')}
                        
                        <hr class="my-2">
                        <div class="flex justify-between text-sm">
                            <span>ราคาสินค้า</span>
                            <span>฿${order.subtotal}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span>ค่าจัดส่ง</span>
                            <span>฿${order.deliveryFee}</span>
                        </div>
                        <div class="flex justify-between font-bold">
                            <span>รวมทั้งหมด</span>
                            <span>฿${order.total}</span>
                        </div>
                    </div>
                </div>
                
                ${order.rider ? `
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-2">ไรเดอร์</h4>
                        <div class="flex items-center justify-between bg-gray-50 rounded-lg p-3">
                            <div>
                                <p class="font-medium">${order.rider}</p>
                                <p class="text-sm text-gray-600">${order.riderPhone}</p>
                            </div>
                            <button onclick="window.location.href='tel:${order.riderPhone}'" 
                                    class="bg-green-500 text-white px-3 py-2 rounded-lg text-sm">
                                โทร
                            </button>
                        </div>
                    </div>
                ` : ''}
            </div>
        `;
        
        this.orderDetailsModal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    
    closeOrderDetails() {
        this.orderDetailsModal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
    
    rateOrder(orderId) {
        this.currentOrderId = orderId;
        this.currentRating = 0;
        this.reviewText.value = '';
        this.updateRatingDisplay();
        this.ratingModal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    
    setRating(rating) {
        this.currentRating = rating;
        this.updateRatingDisplay();
        this.submitRatingBtn.disabled = false;
        
        const ratingTexts = {
            1: 'แย่มาก 😞',
            2: 'ไม่ชอب 😐',
            3: 'ปานกลาง 🙂',
            4: 'ดี 😊',
            5: 'ยอดเยี่ยม! 🤩'
        };
        
        this.ratingText.textContent = ratingTexts[rating];
        this.ratingText.classList.add('rating-selected');
    }
    
    highlightStars(rating) {
        this.ratingStars.forEach((star, index) => {
            if (index < rating) {
                star.classList.add('selected');
            } else {
                star.classList.remove('selected');
            }
        });
    }
    
    updateRatingDisplay() {
        this.highlightStars(this.currentRating);
        
        if (this.currentRating === 0) {
            this.ratingText.textContent = 'แตะเพื่อให้คะแนน';
            this.ratingText.classList.remove('rating-selected');
            this.submitRatingBtn.disabled = true;
        }
    }
    
    submitRating() {
        const rating = this.currentRating;
        const review = this.reviewText.value.trim();
        
        if (rating === 0) return;
        
        // Show loading state
        this.submitRatingBtn.classList.add('loading');
        this.submitRatingBtn.textContent = 'กำลังส่ง...';
        this.submitRatingBtn.disabled = true;
        
        // Simulate API call
        setTimeout(() => {
            // Update UI to show rating was submitted
            const orderCard = document.querySelector(`[onclick="rateOrder('${this.currentOrderId}')"]`)?.closest('.order-card');
            if (orderCard) {
                // Add rating display to order card
                const ratingDisplay = document.createElement('div');
                ratingDisplay.className = 'flex items-center space-x-1 mt-2';
                ratingDisplay.innerHTML = Array.from({length: 5}, (_, i) => 
                    `<span class="text-sm ${i < rating ? 'text-yellow-400' : 'text-gray-300'}">⭐</span>`
                ).join('');
                
                const orderHeader = orderCard.querySelector('.border-b');
                if (orderHeader && !orderHeader.querySelector('.flex.items-center.space-x-1')) {
                    orderHeader.appendChild(ratingDisplay);
                }
                
                // Remove rate button and add review if provided
                const rateBtn = orderCard.querySelector(`[onclick="rateOrder('${this.currentOrderId}')"]`);
                if (rateBtn) {
                    rateBtn.remove();
                    
                    // Add review if provided
                    if (review) {
                        const reviewDiv = document.createElement('div');
                        reviewDiv.className = 'px-4 pb-4';
                        reviewDiv.innerHTML = `
                            <div class="bg-blue-50 rounded-lg p-3">
                                <p class="text-sm text-gray-700 italic">"${review}"</p>
                            </div>
                        `;
                        orderCard.appendChild(reviewDiv);
                    }
                }
                
                orderCard.classList.add('success-animation');
                setTimeout(() => {
                    orderCard.classList.remove('success-animation');
                }, 600);
            }
            
            this.showNotification('ขอบคุณสำหรับการรีวิว! 🙏', 'success');
            this.closeRatingModal();
            
            // Reset button state
            this.submitRatingBtn.classList.remove('loading');
            this.submitRatingBtn.textContent = 'ส่งรีวิว';
        }, 1500);
    }
    
    closeRatingModal() {
        this.ratingModal.classList.add('hidden');
        document.body.style.overflow = 'auto';
        this.currentRating = 0;
        this.updateRatingDisplay();
    }
    
    reorder(orderId) {
        if (confirm('ต้องการสั่งอาหารชุดเดิมอีกครั้งใช่หรือไม่?')) {
            this.showNotification('กำลังเพิ่มรายการลงตะกร้า...', 'info');
            
            // Simulate adding to cart
            setTimeout(() => {
                // In real app, would add items to cart and redirect
                this.showNotification('เพิ่มรายการลงตะกร้าแล้ว! 🛒', 'success');
                
                setTimeout(() => {
                    window.location.href = 'restaurant.php';
                }, 1000);
            }, 1000);
        }
    }
    
    trackOrder(orderId) {
        window.location.href = `tracking.php?orderId=${orderId}`;
    }
    
    loadMoreOrders() {
        const btn = this.loadMoreBtn;
        btn.classList.add('loading');
        btn.textContent = 'กำลังโหลด...';
        
        // Simulate API call
        setTimeout(() => {
            // Mock additional orders
            const additionalOrders = [
                {
                    id: 'ORD005',
                    restaurant: 'ร้านส้มตำนางโรง',
                    items: ['ส้มตำไทย', 'ข้าวเหนียว'],
                    total: 120,
                    status: 'delivered',
                    date: '2024-03-11 15:20:00'
                },
                {
                    id: 'ORD006',
                    restaurant: 'ก๋วยเตี๋ยวเรือป้าแก้ว',
                    items: ['ก๋วยเตี๋ยวเรือน้ำข้น', 'เกี๊ยวทอด'],
                    total: 95,
                    status: 'delivered',
                    date: '2024-03-10 12:45:00'
                }
            ];
            
            // Add new order cards (mock HTML injection)
            const ordersContainer = document.querySelector('.space-y-4');
            additionalOrders.forEach((order, index) => {
                const orderCard = this.createOrderCard(order);
                orderCard.style.animationDelay = `${(this.loadedOrders + index) * 0.1}s`;
                ordersContainer.appendChild(orderCard);
            });
            
            this.loadedOrders += additionalOrders.length;
            
            // Reset button
            btn.classList.remove('loading');
            btn.textContent = 'โหลดเพิ่มเติม';
            
            // Hide load more if no more orders
            if (this.loadedOrders >= 8) {
                btn.style.display = 'none';
            }
            
            this.showNotification('โหลดคำสั่งซื้อเพิ่มเติมแล้ว', 'success');
        }, 1500);
    }
    
    createOrderCard(order) {
        // This would create the HTML for a new order card
        // Simplified version for demo
        const div = document.createElement('div');
        div.className = 'order-card bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden';
        div.innerHTML = `
            <div class="p-4 border-b border-gray-100">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center">
                            <span class="text-2xl">🏪</span>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">${order.restaurant}</h3>
                            <p class="text-sm text-gray-500">${this.timeAgo(order.date)}</p>
                        </div>
                    </div>
                    <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        จัดส่งแล้ว
                    </span>
                </div>
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">คำสั่งซื้อ: ${order.id}</p>
                        <p class="font-bold text-gray-900">฿${order.total}</p>
                    </div>
                </div>
            </div>
            <div class="p-4 flex items-center justify-between">
                <div class="flex space-x-2">
                    <button onclick="viewOrderDetails('${order.id}')" class="text-blue-500 hover:text-blue-600 text-sm font-medium">
                        ดูรายละเอียด
                    </button>
                    <span class="text-gray-300">•</span>
                    <button onclick="reorder('${order.id}')" class="text-green-500 hover:text-green-600 text-sm font-medium">
                        สั่งซ้ำ
                    </button>
                </div>
            </div>
        `;
        return div;
    }
    
    timeAgo(dateString) {
        const date = new Date(dateString);
        const now = new Date();
        const diffInMs = now - date;
        const diffInDays = Math.floor(diffInMs / (1000 * 60 * 60 * 24));
        
        if (diffInDays > 0) {
            return `${diffInDays} วันที่แล้ว`;
        } else {
            const diffInHours = Math.floor(diffInMs / (1000 * 60 * 60));
            return diffInHours > 0 ? `${diffInHours} ชั่วโมงที่แล้ว` : 'เมื่อสักครู่';
        }
    }
    
    showFilterMenu() {
        this.filterModal.classList.remove('hidden');
    }
    
    closeFilterMenu() {
        this.filterModal.classList.add('hidden');
    }
    
    closeAllModals() {
        this.closeOrderDetails();
        this.closeRatingModal();
        this.closeFilterMenu();
    }
    
    showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        const colors = {
            success: 'bg-green-500',
            error: 'bg-red-500',
            info: 'bg-blue-500'
        };
        
        notification.className = `fixed top-20 left-4 right-4 ${colors[type]} text-white px-4 py-3 rounded-lg shadow-lg z-50 transition-all duration-300`;
        notification.innerHTML = `
            <div class="flex items-center space-x-2">
                <span class="text-lg">${type === 'success' ? '✅' : type === 'error' ? '❌' : 'ℹ️'}</span>
                <span class="font-medium">${message}</span>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Animate in
        setTimeout(() => {
            notification.style.transform = 'translateY(0)';
        }, 10);
        
        // Remove after 3 seconds
        setTimeout(() => {
            notification.style.transform = 'translateY(-100%)';
            notification.style.opacity = '0';
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 300);
        }, 3000);
    }
}

// Global functions for HTML onclick handlers
function goBack() {
    if (window.history.length > 1) {
        window.history.back();
    } else {
        window.location.href = 'index.php';
    }
}

function goToHome() {
    window.location.href = 'index.php';
}

function viewOrderDetails(orderId) {
    orders.viewOrderDetails(orderId);
}

function closeOrderDetails() {
    orders.closeOrderDetails();
}

function rateOrder(orderId) {
    orders.rateOrder(orderId);
}

function setRating(rating) {
    orders.setRating(rating);
}

function closeRatingModal() {
    orders.closeRatingModal();
}

function submitRating() {
    orders.submitRating();
}

function reorder(orderId) {
    orders.reorder(orderId);
}

function trackOrder(orderId) {
    orders.trackOrder(orderId);
}

function loadMoreOrders() {
    orders.loadMoreOrders();
}

function showFilterMenu() {
    orders.showFilterMenu();
}

function closeFilterMenu() {
    orders.closeFilterMenu();
}

// Initialize orders page
let orders;
document.addEventListener('DOMContentLoaded', () => {
    orders = new OrdersPage();
    console.log('Orders page initialized');
});

// Handle page visibility change
document.addEventListener('visibilitychange', () => {
    if (!document.hidden) {
        // Page became visible - could refresh order statuses
        console.log('Orders page visible - could refresh data');
    }
});

// Handle network status
window.addEventListener('online', () => {
    orders.showNotification('เชื่อมต่ออินเทอร์เน็ตแล้ว ✅', 'success');
});

window.addEventListener('offline', () => {
    orders.showNotification('ขาดการเชื่อมต่ออินเทอร์เน็ต ❌', 'error');
});