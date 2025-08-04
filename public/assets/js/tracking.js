// Tracking page JavaScript
class TrackingPage {
    constructor() {
        this.orderId = new URLSearchParams(window.location.search).get('orderId');
        this.orderData = null;
        this.deliveryTime = 25 * 60; // 25 minutes in seconds
        this.countdownInterval = null;
        this.statusUpdateInterval = null;
        this.currentStatus = 'preparing';
        
        this.initializeElements();
        this.loadOrderData();
        this.startCountdown();
        this.startStatusUpdates();
        this.initializeMap();
    }
    
    initializeElements() {
        this.deliveryCountdown = document.getElementById('deliveryCountdown');
        this.progressBar = document.getElementById('progressBar');
        this.orderDetails = document.getElementById('orderDetails');
        this.toggleIcon = document.getElementById('toggleIcon');
        this.toggleText = document.getElementById('toggleText');
        this.notificationModal = document.getElementById('notificationModal');
        this.notificationMessage = document.getElementById('notificationMessage');
    }
    
    loadOrderData() {
        // Load order data from sessionStorage
        const orderDataString = sessionStorage.getItem('currentOrder');
        if (orderDataString) {
            this.orderData = JSON.parse(orderDataString);
            this.renderOrderDetails();
        } else {
            // Mock data if no session data
            this.orderData = {
                orderId: this.orderId,
                cart: [
                    { name: 'ข้าวผัดกุ้ง', price: 65, quantity: 1 },
                    { name: 'ต้มยำกุ้ง', price: 85, quantity: 1 }
                ],
                total: 165,
                restaurantName: 'ร้านเมะเก๋า'
            };
            this.renderOrderDetails();
        }
    }
    
    renderOrderDetails() {
        if (!this.orderData || !this.orderData.cart) return;
        
        const itemsHtml = this.orderData.cart.map(item => `
            <div class="flex items-center justify-between py-2">
                <div class="flex-1">
                    <span class="font-medium text-gray-900">${item.name}</span>
                    <span class="text-sm text-gray-500 ml-2">x${item.quantity}</span>
                </div>
                <span class="font-semibold text-gray-900">฿${item.price * item.quantity}</span>
            </div>
        `).join('');
        
        this.orderDetails.innerHTML = `
            <div class="space-y-2 text-sm">
                ${itemsHtml}
                <hr class="my-2">
                <div class="flex justify-between font-bold">
                    <span>รวมทั้งหมด</span>
                    <span>฿${this.orderData.total}</span>
                </div>
                <div class="text-xs text-gray-500 mt-2">
                    จาก: ${this.orderData.restaurantName}
                </div>
            </div>
        `;
    }
    
    startCountdown() {
        this.countdownInterval = setInterval(() => {
            this.deliveryTime--;
            
            if (this.deliveryTime <= 0) {
                this.deliveryTime = 0;
                clearInterval(this.countdownInterval);
                this.showNotification('อาหารของคุณถึงแล้ว! 🎉');
                this.updateOrderStatus('delivered');
            }
            
            this.updateCountdownDisplay();
        }, 1000);
    }
    
    updateCountdownDisplay() {
        const minutes = Math.floor(this.deliveryTime / 60);
        const seconds = this.deliveryTime % 60;
        this.deliveryCountdown.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;
        
        // Update progress bar
        const totalTime = 25 * 60; // 25 minutes
        const progress = ((totalTime - this.deliveryTime) / totalTime) * 100;
        this.progressBar.style.width = `${Math.min(progress, 100)}%`;
    }
    
    startStatusUpdates() {
        // Simulate status updates
        const statusTimings = {
            'preparing': 3000,   // 3 seconds for demo
            'ready': 8000,       // 8 seconds
            'pickup': 12000,     // 12 seconds
            'delivered': 20000   // 20 seconds
        };
        
        Object.entries(statusTimings).forEach(([status, timing]) => {
            setTimeout(() => {
                this.updateOrderStatus(status);
            }, timing);
        });
    }
    
    updateOrderStatus(newStatus) {
        this.currentStatus = newStatus;
        
        // Update UI based on status
        const statusElements = document.querySelectorAll('.status-item');
        const statusKeys = ['confirmed', 'preparing', 'ready', 'pickup', 'delivered'];
        const currentIndex = statusKeys.indexOf(newStatus);
        
        statusElements.forEach((element, index) => {
            const statusKey = statusKeys[index];
            element.classList.remove('completed', 'current');
            
            if (index <= currentIndex) {
                element.classList.add('completed');
                element.querySelector('.status-icon').style.backgroundColor = '#dcfce7';
                element.querySelector('.status-icon').style.color = '#16a34a';
            }
            
            if (index === currentIndex) {
                element.classList.add('current');
                element.classList.add('success-flash');
                setTimeout(() => {
                    element.classList.remove('success-flash');
                }, 500);
            }
        });
        
        // Update status lines
        const statusLines = document.querySelectorAll('.status-line');
        statusLines.forEach((line, index) => {
            if (index < currentIndex) {
                line.classList.add('completed');
            }
        });
        
        // Show notifications for important status changes
        const statusMessages = {
            'ready': 'อาหารของคุณพร้อมแล้ว! รอไรเดอร์มารับ 📦',
            'pickup': 'ไรเดอร์รับออเดอร์แล้ว กำลังเดินทางไปส่ง 🛵',
            'delivered': 'จัดส่งเรียบร้อยแล้ว! หวังว่าคุณจะอร่อยนะ 🎉'
        };
        
        if (statusMessages[newStatus]) {
            setTimeout(() => {
                this.showNotification(statusMessages[newStatus]);
            }, 500);
        }
        
        console.log('Order status updated:', newStatus);
    }
    
    initializeMap() {
        // Simulate real-time rider movement
        const riderPin = document.querySelector('.rider-pin');
        if (!riderPin) return;
        
        let position = { x: 32, y: 24 };
        const targetPosition = { x: 200, y: 160 };
        
        const moveRider = () => {
            const dx = (targetPosition.x - position.x) * 0.005;
            const dy = (targetPosition.y - position.y) * 0.005;
            
            position.x += dx;
            position.y += dy;
            
            riderPin.style.left = `${position.x}px`;
            riderPin.style.top = `${position.y}px`;
            
            if (this.currentStatus !== 'delivered') {
                requestAnimationFrame(moveRider);
            }
        };
        
        // Start movement when rider picks up the order
        setTimeout(() => {
            if (this.currentStatus === 'pickup' || this.currentStatus === 'delivered') {
                moveRider();
            }
        }, 12000);
    }
    
    toggleOrderDetails() {
        const isHidden = this.orderDetails.classList.contains('hidden');
        
        if (isHidden) {
            this.orderDetails.classList.remove('hidden');
            this.orderDetails.classList.add('show');
            this.toggleIcon.classList.add('rotated');
            this.toggleText.textContent = 'ซ่อน';
        } else {
            this.orderDetails.classList.remove('show');
            this.orderDetails.classList.add('hidden');
            this.toggleIcon.classList.remove('rotated');
            this.toggleText.textContent = 'แสดง';
        }
    }
    
    callRider() {
        // Simulate calling rider
        const riderPhone = '081-234-5678';
        
        if (confirm(`โทรหาไรเดอร์ ${riderPhone}?`)) {
            // In real app, would initiate phone call
            window.location.href = `tel:${riderPhone}`;
        }
    }
    
    chatRider() {
        // Simulate opening chat with rider
        this.showNotification('เปิดแชทกับไรเดอร์ (ฟีเจอร์นี้จะมาเร็วๆ นี้) 💬');
    }
    
    contactSupport() {
        // Show support contact options
        const supportNumber = '02-123-4567';
        
        if (confirm(`ติดต่อศูนย์ช่วยเหลือ ${supportNumber}?`)) {
            window.location.href = `tel:${supportNumber}`;
        }
    }
    
    centerMap() {
        // Simulate centering map on current location
        const mapContainer = document.querySelector('.h-64');
        if (mapContainer) {
            mapContainer.style.transform = 'scale(1.1)';
            setTimeout(() => {
                mapContainer.style.transform = 'scale(1)';
            }, 300);
        }
        
        this.showNotification('จัดตำแหน่งแผนที่แล้ว 📍');
    }
    
    shareOrder() {
        // Share order tracking link
        if (navigator.share) {
            navigator.share({
                title: 'ติดตามออเดอร์อาหาร',
                text: `ติดตามออเดอร์ ${this.orderId} ของฉัน`,
                url: window.location.href
            });
        } else {
            // Fallback - copy to clipboard
            navigator.clipboard.writeText(window.location.href).then(() => {
                this.showNotification('คัดลอกลิงก์ติดตามแล้ว 📋');
            });
        }
    }
    
    showNotification(message) {
        this.notificationMessage.textContent = message;
        this.notificationModal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        
        // Auto close after 5 seconds
        setTimeout(() => {
            this.closeNotification();
        }, 5000);
    }
    
    closeNotification() {
        this.notificationModal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
    
    cleanup() {
        if (this.countdownInterval) {
            clearInterval(this.countdownInterval);
        }
        if (this.statusUpdateInterval) {
            clearInterval(this.statusUpdateInterval);
        }
    }
}

// Global functions for HTML onclick handlers
function goBack() {
    if (window.history.length > 1) {
        window.history.back();
    } else {
        window.location.href = 'orders.php';
    }
}

function toggleOrderDetails() {
    tracking.toggleOrderDetails();
}

function callRider() {
    tracking.callRider();
}

function chatRider() {
    tracking.chatRider();
}

function contactSupport() {
    tracking.contactSupport();
}

function centerMap() {
    tracking.centerMap();
}

function shareOrder() {
    tracking.shareOrder();
}

function closeNotification() {
    tracking.closeNotification();
}

// Initialize tracking page
let tracking;
document.addEventListener('DOMContentLoaded', () => {
    tracking = new TrackingPage();
    console.log('Tracking page initialized');
});

// Handle page visibility change
document.addEventListener('visibilitychange', () => {
    if (document.hidden) {
        // Page is hidden - could pause updates
        console.log('Tracking page hidden');
    } else {
        // Page is visible - resume updates
        console.log('Tracking page visible');
    }
});

// Handle page unload
window.addEventListener('beforeunload', () => {
    if (tracking) {
        tracking.cleanup();
    }
});

// Handle keyboard shortcuts
document.addEventListener('keydown', (e) => {
    switch(e.key) {
        case 'Escape':
            tracking.closeNotification();
            break;
        case 'r':
        case 'R':
            if (e.ctrlKey || e.metaKey) {
                e.preventDefault();
                location.reload();
            }
            break;
    }
});

// Handle network status
window.addEventListener('online', () => {
    tracking.showNotification('เชื่อมต่ออินเทอร์เน็ตแล้ว ✅');
});

window.addEventListener('offline', () => {
    tracking.showNotification('ขาดการเชื่อมต่ออินเทอร์เน็ต ❌');
});