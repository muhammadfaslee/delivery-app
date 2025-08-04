// Restaurant page JavaScript
class RestaurantPage {
    constructor() {
        this.cart = [];
        this.deliveryFee = 15;
        this.minOrder = 100;
        this.discountCode = null;
        this.discountAmount = 0;
        
        this.initializeElements();
        this.initializeEventListeners();
        this.updateCart();
    }
    
    initializeElements() {
        this.floatingCart = document.getElementById('floatingCart');
        this.cartModal = document.getElementById('cartModal');
        this.cartCount = document.getElementById('cartCount');
        this.cartTotal = document.getElementById('cartTotal');
        this.cartItems = document.getElementById('cartItems');
        this.subtotal = document.getElementById('subtotal');
        this.deliveryFeeElement = document.getElementById('deliveryFee');
        this.finalTotal = document.getElementById('finalTotal');
        this.checkoutBtn = document.getElementById('checkoutBtn');
        this.minOrderWarning = document.getElementById('minOrderWarning');
        this.discountCodeInput = document.getElementById('discountCode');
        this.discountMessage = document.getElementById('discountMessage');
        this.discountRow = document.getElementById('discountRow');
        this.discountAmountElement = document.getElementById('discountAmount');
        this.loadingOverlay = document.getElementById('loadingOverlay');
        this.categoryNavBtns = document.querySelectorAll('.category-nav-btn');
    }
    
    initializeEventListeners() {
        // Close cart modal when clicking outside
        this.cartModal.addEventListener('click', (e) => {
            if (e.target === this.cartModal) {
                this.closeCart();
            }
        });
        
        // Category navigation scroll spy
        window.addEventListener('scroll', () => this.handleScroll());
        
        // Keyboard shortcuts
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.closeCart();
            }
        });
    }
    
    handleScroll() {
        const sections = document.querySelectorAll('.category-section');
        const navBtns = document.querySelectorAll('.category-nav-btn');
        
        let current = '';
        sections.forEach(section => {
            const sectionTop = section.offsetTop - 150;
            if (window.scrollY >= sectionTop) {
                current = section.id.replace('category-', '');
            }
        });
        
        navBtns.forEach(btn => {
            btn.classList.remove('active');
            if (btn.getAttribute('data-category') === current) {
                btn.classList.add('active');
            }
        });
    }
    
    addToCart(id, name, price) {
        const existingItem = this.cart.find(item => item.id === id);
        
        if (existingItem) {
            existingItem.quantity += 1;
        } else {
            this.cart.push({
                id: id,
                name: name,
                price: price,
                quantity: 1
            });
        }
        
        this.updateCart();
        this.showAddToCartFeedback(name);
    }
    
    removeFromCart(id) {
        this.cart = this.cart.filter(item => item.id !== id);
        this.updateCart();
    }
    
    updateQuantity(id, quantity) {
        const item = this.cart.find(item => item.id === id);
        if (item) {
            if (quantity <= 0) {
                this.removeFromCart(id);
            } else {
                item.quantity = quantity;
                this.updateCart();
            }
        }
    }
    
    updateCart() {
        const totalItems = this.cart.reduce((sum, item) => sum + item.quantity, 0);
        const subtotalAmount = this.cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        const finalAmount = subtotalAmount + this.deliveryFee - this.discountAmount;
        
        // Update floating cart
        this.cartCount.textContent = totalItems;
        this.cartTotal.textContent = `฿${finalAmount}`;
        
        if (totalItems > 0) {
            this.floatingCart.classList.remove('hidden');
        } else {
            this.floatingCart.classList.add('hidden');
        }
        
        // Update cart modal
        this.renderCartItems();
        this.subtotal.textContent = `฿${subtotalAmount}`;
        this.finalTotal.textContent = `฿${finalAmount}`;
        
        // Update checkout button
        const canCheckout = subtotalAmount >= this.minOrder;
        this.checkoutBtn.disabled = !canCheckout || totalItems === 0;
        
        if (subtotalAmount > 0 && subtotalAmount < this.minOrder) {
            this.minOrderWarning.classList.remove('hidden');
            this.minOrderWarning.textContent = `สั่งเพิ่มอีก ฿${this.minOrder - subtotalAmount} เพื่อสั่งซื้อ`;
        } else {
            this.minOrderWarning.classList.add('hidden');
        }
    }
    
    renderCartItems() {
        if (this.cart.length === 0) {
            this.cartItems.innerHTML = `
                <div class="empty-cart">
                    <div class="empty-cart-icon">🛒</div>
                    <p class="text-lg font-medium">ตะกร้าว่างเปล่า</p>
                    <p class="text-sm">เพิ่มสินค้าลงตะกร้าเพื่อสั่งซื้อ</p>
                </div>
            `;
            return;
        }
        
        this.cartItems.innerHTML = this.cart.map(item => `
            <div class="cart-item">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <h4 class="font-medium text-gray-900">${item.name}</h4>
                        <p class="text-sm text-gray-600">฿${item.price}</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="flex items-center space-x-2">
                            <button onclick="restaurant.updateQuantity(${item.id}, ${item.quantity - 1})" 
                                    class="quantity-btn minus">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                </svg>
                            </button>
                            <input type="number" 
                                   value="${item.quantity}" 
                                   min="1" 
                                   class="quantity-input"
                                   onchange="restaurant.updateQuantity(${item.id}, parseInt(this.value))">
                            <button onclick="restaurant.updateQuantity(${item.id}, ${item.quantity + 1})" 
                                    class="quantity-btn plus">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                            </button>
                        </div>
                        <button onclick="restaurant.removeFromCart(${item.id})" 
                                class="text-red-500 hover:text-red-700 p-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="mt-2 text-right">
                    <span class="font-semibold text-gray-900">฿${item.price * item.quantity}</span>
                </div>
            </div>
        `).join('');
    }
    
    showAddToCartFeedback(itemName) {
        // Create temporary notification
        const notification = document.createElement('div');
        notification.className = 'fixed top-20 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50 transition-all duration-300';
        notification.innerHTML = `
            <div class="flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span class="text-sm font-medium">เพิ่ม ${itemName} ลงตะกร้าแล้ว</span>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Animate in
        setTimeout(() => {
            notification.style.transform = 'translateX(0)';
        }, 10);
        
        // Remove after 3 seconds
        setTimeout(() => {
            notification.style.transform = 'translateX(100%)';
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 300);
        }, 3000);
    }
    
    applyDiscount() {
        const code = this.discountCodeInput.value.trim().toUpperCase();
        
        if (!code) {
            this.showDiscountMessage('กรุณาใส่โค้ดส่วนลด', 'error');
            return;
        }
        
        // Mock discount codes
        const discountCodes = {
            'SAVE10': { type: 'percentage', value: 10, description: 'ลด 10%' },
            'SAVE20': { type: 'fixed', value: 20, description: 'ลด 20 บาท' },
            'FREESHIP': { type: 'free_shipping', value: this.deliveryFee, description: 'ฟรีค่าส่ง' },
            'WELCOME50': { type: 'fixed', value: 50, description: 'ลด 50 บาท' }
        };
        
        if (discountCodes[code]) {
            const discount = discountCodes[code];
            const subtotalAmount = this.cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            
            let discountAmount = 0;
            if (discount.type === 'percentage') {
                discountAmount = Math.round(subtotalAmount * discount.value / 100);
            } else {
                discountAmount = discount.value;
            }
            
            // Apply discount
            this.discountCode = code;
            this.discountAmount = discountAmount;
            this.discountRow.classList.remove('hidden');
            this.discountAmountElement.textContent = `-฿${discountAmount}`;
            
            this.updateCart();
            this.showDiscountMessage(`ใช้โค้ด ${code} สำเร็จ! ${discount.description}`, 'success');
            this.discountCodeInput.value = '';
        } else {
            this.showDiscountMessage('โค้ดส่วนลดไม่ถูกต้อง', 'error');
        }
    }
    
    showDiscountMessage(message, type) {
        this.discountMessage.textContent = message;
        this.discountMessage.className = `mt-2 text-sm ${type === 'success' ? 'text-green-600' : 'text-red-600'}`;
        this.discountMessage.classList.remove('hidden');
        
        setTimeout(() => {
            this.discountMessage.classList.add('hidden');
        }, 3000);
    }
    
    openCart() {
        this.cartModal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    
    closeCart() {
        this.cartModal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
    
    proceedToCheckout() {
        this.showLoading(true);
        
        // Simulate API call
        setTimeout(() => {
            this.showLoading(false);
            
            // Save cart data to session/localStorage for checkout page
            const orderData = {
                cart: this.cart,
                subtotal: this.cart.reduce((sum, item) => sum + (item.price * item.quantity), 0),
                deliveryFee: this.deliveryFee,
                discountCode: this.discountCode,
                discountAmount: this.discountAmount,
                total: this.cart.reduce((sum, item) => sum + (item.price * item.quantity), 0) + this.deliveryFee - this.discountAmount,
                restaurantName: document.querySelector('h1').textContent
            };
            
            sessionStorage.setItem('orderData', JSON.stringify(orderData));
            
            // Navigate to checkout
            window.location.href = 'checkout.php';
        }, 1000);
    }
    
    showLoading(show) {
        if (show) {
            this.loadingOverlay.classList.remove('hidden');
        } else {
            this.loadingOverlay.classList.add('hidden');
        }
    }
}

// Global functions for HTML onclick handlers
function addToCart(id, name, price) {
    restaurant.addToCart(id, name, price);
}

function openCart() {
    restaurant.openCart();
}

function closeCart() {
    restaurant.closeCart();
}

function applyDiscount() {
    restaurant.applyDiscount();
}

function proceedToCheckout() {
    restaurant.proceedToCheckout();
}

function scrollToCategory(categoryId) {
    const element = document.getElementById(`category-${categoryId}`);
    if (element) {
        element.scrollIntoView({ behavior: 'smooth' });
    }
}

function goBack() {
    if (window.history.length > 1) {
        window.history.back();
    } else {
        window.location.href = 'index.php';
    }
}

function toggleFavorite() {
    const btn = event.currentTarget;
    const svg = btn.querySelector('svg');
    
    if (svg.classList.contains('text-red-500')) {
        svg.classList.remove('text-red-500');
        svg.classList.add('text-gray-400');
    } else {
        svg.classList.remove('text-gray-400');
        svg.classList.add('text-red-500');
        btn.classList.add('favorite-btn', 'active');
        
        setTimeout(() => {
            btn.classList.remove('active');
        }, 600);
    }
}

// Initialize restaurant page
let restaurant;
document.addEventListener('DOMContentLoaded', () => {
    restaurant = new RestaurantPage();
    console.log('Restaurant page initialized');
});

// Performance monitoring
const perfObserver = new PerformanceObserver((list) => {
    for (const entry of list.getEntries()) {
        if (entry.entryType === 'navigation') {
            console.log('Restaurant page load time:', entry.loadEventEnd - entry.loadEventStart, 'ms');
        }
    }
});

if (typeof PerformanceObserver !== 'undefined') {
    perfObserver.observe({ entryTypes: ['navigation'] });
}

// Handle page visibility change
document.addEventListener('visibilitychange', () => {
    if (document.hidden) {
        // Page is hidden - could pause timers, etc.
        console.log('Page hidden');
    } else {
        // Page is visible - could resume functionality
        console.log('Page visible');
        if (restaurant) {
            restaurant.updateCart(); // Refresh cart state
        }
    }
});

// Network status handling
window.addEventListener('online', () => {
    console.log('Network connection restored');
    // Could show success message
});

window.addEventListener('offline', () => {
    console.log('Network connection lost');
    // Could show offline message and disable ordering
});