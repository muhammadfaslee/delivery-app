// Checkout page JavaScript
class CheckoutPage {
    constructor() {
        this.orderData = null;
        this.selectedAddress = null;
        this.selectedPayment = 'cash';
        this.specialInstructions = '';
        
        this.initializeElements();
        this.loadOrderData();
        this.initializeEventListeners();
        this.updateTotals();
    }
    
    initializeElements() {
        this.orderSummary = document.getElementById('orderSummary');
        this.subtotalAmount = document.getElementById('subtotalAmount');
        this.deliveryFeeAmount = document.getElementById('deliveryFeeAmount');
        this.discountSummary = document.getElementById('discountSummary');
        this.discountSummaryAmount = document.getElementById('discountSummaryAmount');
        this.totalAmount = document.getElementById('totalAmount');
        this.bottomTotalAmount = document.getElementById('bottomTotalAmount');
        this.confirmOrderBtn = document.getElementById('confirmOrderBtn');
        this.specialInstructionsInput = document.getElementById('specialInstructions');
        this.addressModal = document.getElementById('addressModal');
        this.newAddressForm = document.getElementById('newAddressForm');
        this.loadingOverlay = document.getElementById('loadingOverlay');
    }
    
    loadOrderData() {
        // Load order data from sessionStorage
        const orderDataString = sessionStorage.getItem('orderData');
        if (orderDataString) {
            this.orderData = JSON.parse(orderDataString);
            this.renderOrderSummary();
        } else {
            // No order data, redirect back
            this.showError('ไม่พบข้อมูลคำสั่งซื้อ');
            setTimeout(() => {
                window.location.href = 'index.php';
            }, 2000);
        }
    }
    
    renderOrderSummary() {
        if (!this.orderData || !this.orderData.cart) return;
        
        this.orderSummary.innerHTML = this.orderData.cart.map(item => `
            <div class="order-item order-summary-enter">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <h4 class="font-medium text-gray-900">${item.name}</h4>
                        <p class="text-sm text-gray-600">฿${item.price} x ${item.quantity}</p>
                    </div>
                    <div class="text-right">
                        <span class="font-semibold text-gray-900">฿${item.price * item.quantity}</span>
                    </div>
                </div>
            </div>
        `).join('');
    }
    
    initializeEventListeners() {
        // Address selection
        document.querySelectorAll('input[name="delivery_address"]').forEach(radio => {
            radio.addEventListener('change', (e) => {
                this.selectedAddress = e.target.value;
                this.updateAddressSelection();
            });
        });
        
        // Payment method selection
        document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
            radio.addEventListener('change', (e) => {
                this.selectedPayment = e.target.value;
                this.updatePaymentSelection();
            });
        });
        
        // Special instructions
        this.specialInstructionsInput.addEventListener('input', (e) => {
            this.specialInstructions = e.target.value;
        });
        
        // Address type selection in modal
        document.querySelectorAll('input[name="addressType"]').forEach(radio => {
            radio.addEventListener('change', (e) => {
                this.updateAddressTypeSelection();
            });
        });
        
        // New address form
        this.newAddressForm.addEventListener('submit', (e) => {
            e.preventDefault();
            this.saveNewAddress();
        });
        
        // Close modal when clicking outside
        this.addressModal.addEventListener('click', (e) => {
            if (e.target === this.addressModal) {
                this.closeAddressModal();
            }
        });
        
        // Keyboard shortcuts
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.closeAddressModal();
            }
        });
        
        // Set initial selections
        this.selectedAddress = document.querySelector('input[name="delivery_address"]:checked')?.value;
        this.selectedPayment = document.querySelector('input[name="payment_method"]:checked')?.value;
    }
    
    updateAddressSelection() {
        // Visual feedback already handled by CSS
        console.log('Selected address:', this.selectedAddress);
    }
    
    updatePaymentSelection() {
        // Visual feedback already handled by CSS
        console.log('Selected payment:', this.selectedPayment);
    }
    
    updateAddressTypeSelection() {
        document.querySelectorAll('.address-type-btn').forEach(btn => {
            btn.classList.remove('border-blue-500', 'bg-blue-50', 'text-blue-700');
            btn.classList.add('border-gray-300');
        });
        
        const selectedRadio = document.querySelector('input[name="addressType"]:checked');
        if (selectedRadio) {
            const btn = selectedRadio.nextElementSibling;
            btn.classList.remove('border-gray-300');
            btn.classList.add('border-blue-500', 'bg-blue-50', 'text-blue-700');
        }
    }
    
    updateTotals() {
        if (!this.orderData) return;
        
        const subtotal = this.orderData.subtotal || 0;
        const deliveryFee = this.orderData.deliveryFee || 0;
        const discount = this.orderData.discountAmount || 0;
        const total = subtotal + deliveryFee - discount;
        
        this.subtotalAmount.textContent = `฿${subtotal}`;
        this.deliveryFeeAmount.textContent = `฿${deliveryFee}`;
        this.totalAmount.textContent = `฿${total}`;
        this.bottomTotalAmount.textContent = `฿${total}`;
        
        if (discount > 0) {
            this.discountSummary.classList.remove('hidden');
            this.discountSummaryAmount.textContent = `-฿${discount}`;
        }
    }
    
    addQuickNote(note) {
        const currentNotes = this.specialInstructionsInput.value;
        if (currentNotes.includes(note)) return;
        
        const newNotes = currentNotes ? `${currentNotes}, ${note}` : note;
        this.specialInstructionsInput.value = newNotes;
        this.specialInstructions = newNotes;
        
        // Visual feedback for button
        event.target.classList.add('selected');
        setTimeout(() => {
            event.target.classList.remove('selected');
        }, 200);
    }
    
    addNewAddress() {
        this.addressModal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    
    closeAddressModal() {
        this.addressModal.classList.add('hidden');
        document.body.style.overflow = 'auto';
        this.resetAddressForm();
    }
    
    resetAddressForm() {
        this.newAddressForm.reset();
        // Reset address type selection to home
        document.querySelector('input[name="addressType"][value="home"]').checked = true;
        this.updateAddressTypeSelection();
    }
    
    saveNewAddress() {
        const formData = new FormData(this.newAddressForm);
        const addressData = {
            type: formData.get('addressType'),
            address: formData.get('address'),
            phone: formData.get('phone'),
            setDefault: formData.get('setDefault') === 'on'
        };
        
        // Show loading
        const submitBtn = this.newAddressForm.querySelector('button[type="submit"]');
        submitBtn.classList.add('loading');
        submitBtn.textContent = 'กำลังบันทึก...';
        
        // Simulate API call
        setTimeout(() => {
            // Mock successful save
            this.showSuccess('บันทึกที่อยู่เรียบร้อยแล้ว');
            this.closeAddressModal();
            
            // In real app, would reload addresses from server
            console.log('New address saved:', addressData);
            
            // Reset button
            submitBtn.classList.remove('loading');
            submitBtn.textContent = 'บันทึก';
        }, 1500);
    }
    
    editOrder() {
        // Go back to restaurant page with current cart
        if (this.orderData) {
            sessionStorage.setItem('editingOrder', 'true');
        }
        window.history.back();
    }
    
    confirmOrder() {
        if (!this.validateOrder()) return;
        
        this.showLoading(true);
        
        const orderRequest = {
            cart: this.orderData.cart,
            subtotal: this.orderData.subtotal,
            deliveryFee: this.orderData.deliveryFee,
            discountCode: this.orderData.discountCode,
            discountAmount: this.orderData.discountAmount,
            total: this.orderData.total,
            restaurantName: this.orderData.restaurantName,
            deliveryAddress: this.selectedAddress,
            paymentMethod: this.selectedPayment,
            specialInstructions: this.specialInstructions,
            orderTime: new Date().toISOString()
        };
        
        // Simulate API call
        setTimeout(() => {
            this.showLoading(false);
            
            // Mock successful order
            const orderId = 'ORD' + Date.now();
            
            // Save order for tracking
            const orderForTracking = {
                ...orderRequest,
                orderId: orderId,
                status: 'preparing',
                estimatedDelivery: this.calculateEstimatedDelivery()
            };
            
            sessionStorage.setItem('currentOrder', JSON.stringify(orderForTracking));
            sessionStorage.removeItem('orderData'); // Clear cart
            
            // Navigate to tracking page
            window.location.href = `tracking.php?orderId=${orderId}`;
        }, 2000);
    }
    
    validateOrder() {
        if (!this.selectedAddress) {
            this.showError('กรุณาเลือกที่อยู่จัดส่ง');
            return false;
        }
        
        if (!this.selectedPayment) {
            this.showError('กรุณาเลือกวิธีชำระเงิน');
            return false;
        }
        
        if (!this.orderData || !this.orderData.cart || this.orderData.cart.length === 0) {
            this.showError('ไม่พบรายการสินค้า');
            return false;
        }
        
        return true;
    }
    
    calculateEstimatedDelivery() {
        const now = new Date();
        const estimatedMinutes = 30; // Base delivery time
        const deliveryTime = new Date(now.getTime() + estimatedMinutes * 60000);
        return deliveryTime.toISOString();
    }
    
    showLoading(show) {
        if (show) {
            this.loadingOverlay.classList.remove('hidden');
            this.confirmOrderBtn.disabled = true;
        } else {
            this.loadingOverlay.classList.add('hidden');
            this.confirmOrderBtn.disabled = false;
        }
    }
    
    showSuccess(message) {
        this.showNotification(message, 'success');
    }
    
    showError(message) {
        this.showNotification(message, 'error');
    }
    
    showNotification(message, type) {
        const notification = document.createElement('div');
        notification.className = `fixed top-20 left-4 right-4 z-50 ${type === 'success' ? 'success-message' : 'error-message'}`;
        notification.innerHTML = `
            <div class="flex items-center space-x-2">
                <span class="text-lg">${type === 'success' ? '✅' : '❌'}</span>
                <span class="font-medium">${message}</span>
            </div>
        `;
        
        document.body.appendChild(notification);
        
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

function editOrder() {
    checkout.editOrder();
}

function addQuickNote(note) {
    checkout.addQuickNote(note);
}

function addNewAddress() {
    checkout.addNewAddress();
}

function closeAddressModal() {
    checkout.closeAddressModal();
}

function confirmOrder() {
    checkout.confirmOrder();
}

// Initialize checkout page
let checkout;
document.addEventListener('DOMContentLoaded', () => {
    checkout = new CheckoutPage();
    console.log('Checkout page initialized');
});

// Handle page visibility change
document.addEventListener('visibilitychange', () => {
    if (!document.hidden && checkout) {
        // Page became visible - could refresh data
        checkout.updateTotals();
    }
});

// Handle back button
window.addEventListener('beforeunload', (e) => {
    // Could save form data or warn user
    if (checkout && checkout.specialInstructions) {
        sessionStorage.setItem('checkoutNotes', checkout.specialInstructions);
    }
});

// Restore form data if available
window.addEventListener('load', () => {
    const savedNotes = sessionStorage.getItem('checkoutNotes');
    if (savedNotes && checkout) {
        checkout.specialInstructionsInput.value = savedNotes;
        checkout.specialInstructions = savedNotes;
        sessionStorage.removeItem('checkoutNotes');
    }
});