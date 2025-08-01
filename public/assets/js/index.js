// DOM Elements
const avatarBtn = document.getElementById('avatarBtn');
const avatarDropdown = document.getElementById('avatarDropdown');
const searchInput = document.getElementById('searchInput');
const restaurantList = document.getElementById('restaurantList');
const loadingOverlay = document.getElementById('loadingOverlay');
const navBtns = document.querySelectorAll('.nav-btn');
const categoryItems = document.querySelectorAll('.category-item');
const restaurantItems = document.querySelectorAll('.restaurant-item');

// Avatar dropdown functionality
avatarBtn.addEventListener('click', (e) => {
    e.stopPropagation();
    toggleDropdown();
});

// Close dropdown when clicking outside
document.addEventListener('click', (e) => {
    if (!avatarDropdown.contains(e.target) && !avatarBtn.contains(e.target)) {
        closeDropdown();
    }
});

// Dropdown functions
function toggleDropdown() {
    if (avatarDropdown.classList.contains('hidden')) {
        showDropdown();
    } else {
        closeDropdown();
    }
}

function showDropdown() {
    avatarDropdown.classList.remove('hidden');
    setTimeout(() => {
        avatarDropdown.classList.add('show');
    }, 10);
}

function closeDropdown() {
    avatarDropdown.classList.remove('show');
    setTimeout(() => {
        avatarDropdown.classList.add('hidden');
    }, 200);
}

// Search functionality
let searchTimeout;
searchInput.addEventListener('input', (e) => {
    const searchTerm = e.target.value.toLowerCase().trim();
    
    // Clear previous timeout
    clearTimeout(searchTimeout);
    
    // Add loading state
    searchInput.classList.add('loading-pulse');
    
    // Debounce search
    searchTimeout = setTimeout(() => {
        performSearch(searchTerm);
        searchInput.classList.remove('loading-pulse');
    }, 300);
});

function performSearch(searchTerm) {
    const restaurants = document.querySelectorAll('.restaurant-item');
    let hasResults = false;
    
    restaurants.forEach(item => {
        const restaurantName = item.querySelector('.font-semibold').textContent.toLowerCase();
        
        if (searchTerm === '' || restaurantName.includes(searchTerm)) {
            item.style.display = 'flex';
            item.classList.add('slide-in');
            hasResults = true;
        } else {
            item.style.display = 'none';
            item.classList.remove('slide-in');
        }
    });
    
    // Show no results message
    showNoResults(!hasResults && searchTerm !== '');
    
    console.log('Searching for:', searchTerm);
}

function showNoResults(show) {
    let noResultsDiv = document.getElementById('noResults');
    
    if (show && !noResultsDiv) {
        noResultsDiv = document.createElement('div');
        noResultsDiv.id = 'noResults';
        noResultsDiv.className = 'text-center py-8';
        noResultsDiv.innerHTML = `
            <div class="text-6xl mb-4">🔍</div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">ไม่พบร้านอาหารที่ค้นหา</h3>
            <p class="text-gray-500">ลองค้นหาด้วยคำอื่น หรือเลือกจากหมวดหมู่</p>
        `;
        restaurantList.appendChild(noResultsDiv);
    } else if (!show && noResultsDiv) {
        noResultsDiv.remove();
    }
}

// Category selection
categoryItems.forEach(item => {
    item.addEventListener('click', function() {
        // Remove active state from all categories
        categoryItems.forEach(cat => cat.classList.remove('ring-2', 'ring-blue-500'));
        
        // Add active state to clicked category
        this.classList.add('ring-2', 'ring-blue-500');
        
        const categoryName = this.querySelector('span').textContent;
        console.log('Selected category:', categoryName);
        
        // Filter restaurants by category (mock implementation)
        filterByCategory(categoryName);
        
        // Add selection feedback
        this.style.transform = 'scale(0.95)';
        setTimeout(() => {
            this.style.transform = 'scale(1)';
        }, 150);
    });
});

function filterByCategory(category) {
    showLoading(true);
    
    setTimeout(() => {
        // Mock filtering logic - in real app, this would make API call
        const restaurants = document.querySelectorAll('.restaurant-item');
        restaurants.forEach(item => {
            item.style.display = 'flex';
            item.classList.add('slide-in');
        });
        
        showLoading(false);
        console.log('Filtered by category:', category);
    }, 500);
}

// Restaurant item interactions
restaurantItems.forEach(item => {
    item.addEventListener('click', function() {
        const restaurantName = this.querySelector('.font-semibold').textContent;
        const restaurantPrice = this.querySelector('.font-bold').textContent;
        
        // Add click effect
        this.style.transform = 'scale(0.98)';
        setTimeout(() => {
            this.style.transform = 'scale(1)';
        }, 150);
        
        // Show loading and navigate
        showLoading(true);
        
        setTimeout(() => {
            console.log('Navigate to restaurant:', restaurantName, restaurantPrice);
            // window.location.href = `restaurant.php?name=${encodeURIComponent(restaurantName)}`;
            showLoading(false);
        }, 800);
    });
    
    // Add hover effect for better UX
    item.addEventListener('mouseenter', function() {
        this.style.boxShadow = '0 8px 25px -5px rgba(0, 0, 0, 0.1)';
    });
    
    item.addEventListener('mouseleave', function() {
        this.style.boxShadow = '';
    });
});

// Bottom navigation
navBtns.forEach(btn => {
    btn.addEventListener('click', function() {
        const page = this.getAttribute('data-page');
        
        // Remove active state from all buttons
        navBtns.forEach(b => b.classList.remove('active', 'text-blue-500'));
        navBtns.forEach(b => b.classList.add('text-gray-600'));
        
        // Add active state to clicked button
        this.classList.remove('text-gray-600');
        this.classList.add('active', 'text-blue-500');
        
        // Navigation logic
        navigateToPage(page);
        
        // Add click feedback
        this.style.transform = 'scale(0.9)';
        setTimeout(() => {
            this.style.transform = 'scale(1)';
        }, 150);
    });
});

function navigateToPage(page) {
    console.log('Navigate to:', page);
    
    const pages = {
        'home': 'index.php',
        'search': 'search.php',
        'orders': 'orders.php',
        'profile': 'profile.php'
    };
    
    if (pages[page] && page !== 'home') {
        showLoading(true);
        setTimeout(() => {
            // window.location.href = pages[page];
            showLoading(false);
            console.log('Would navigate to:', pages[page]);
        }, 500);
    }
}

// Promotion slider auto-scroll
function initPromotionSlider() {
    const slider = document.querySelector('.promotion-slider .flex');
    if (!slider) return;
    
    let currentIndex = 0;
    const slides = slider.children;
    const totalSlides = slides.length;
    
    setInterval(() => {
        currentIndex = (currentIndex + 1) % totalSlides;
        const scrollAmount = currentIndex * (slides[0].offsetWidth + 16); // width + gap
        slider.scrollTo({
            left: scrollAmount,
            behavior: 'smooth'
        });
    }, 4000);
}

// Loading overlay functions
function showLoading(show) {
    if (show) {
        loadingOverlay.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    } else {
        loadingOverlay.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
}

// Intersection Observer for animations
function initScrollAnimations() {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('slide-in');
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    });
    
    // Observe restaurant items
    restaurantItems.forEach(item => {
        observer.observe(item);
    });
}

// Pull to refresh functionality
let startY = 0;
let currentY = 0;
let isPulling = false;

document.addEventListener('touchstart', (e) => {
    if (window.scrollY === 0) {
        startY = e.touches[0].clientY;
        isPulling = true;
    }
});

document.addEventListener('touchmove', (e) => {
    if (!isPulling) return;
    
    currentY = e.touches[0].clientY;
    const pullDistance = currentY - startY;
    
    if (pullDistance > 80) {
        // Show refresh indicator
        console.log('Pull to refresh triggered');
        refreshPage();
        isPulling = false;
    }
});

document.addEventListener('touchend', () => {
    isPulling = false;
});

function refreshPage() {
    showLoading(true);
    
    setTimeout(() => {
        // Mock refresh - in real app, this would reload data
        console.log('Page refreshed');
        showLoading(false);
        
        // Reset search
        searchInput.value = '';
        performSearch('');
        
        // Reset categories
        categoryItems.forEach(cat => cat.classList.remove('ring-2', 'ring-blue-500'));
    }, 1000);
}

// Keyboard shortcuts
document.addEventListener('keydown', (e) => {
    // Ctrl/Cmd + K to focus search
    if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
        e.preventDefault();
        searchInput.focus();
        searchInput.select();
    }
    
    // Escape to close dropdown
    if (e.key === 'Escape') {
        closeDropdown();
        searchInput.blur();
    }
});

// Service Worker registration (for future PWA features)
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js')
            .then(registration => {
                console.log('SW registered: ', registration);
            })
            .catch(registrationError => {
                console.log('SW registration failed: ', registrationError);
            });
    });
}

// Network status monitoring
window.addEventListener('online', () => {
    console.log('Network connection restored');
    // Could show success toast here
});

window.addEventListener('offline', () => {
    console.log('Network connection lost');
    // Could show offline toast here
});

// Initialize all features when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    console.log('Home page loaded');
    
    // Initialize features
    initPromotionSlider();
    initScrollAnimations();
    
    // Set home navigation as active by default
    document.querySelector('[data-page="home"]').classList.add('active', 'text-blue-500');
    document.querySelector('[data-page="home"]').classList.remove('text-gray-600');
    
    // Add initial animations
    setTimeout(() => {
        document.body.classList.add('loaded');
    }, 100);
});

// Performance monitoring
const perfObserver = new PerformanceObserver((list) => {
    for (const entry of list.getEntries()) {
        if (entry.entryType === 'navigation') {
            console.log('Page load time:', entry.loadEventEnd - entry.loadEventStart, 'ms');
        }
    }
});

if (typeof PerformanceObserver !== 'undefined') {
    perfObserver.observe({ entryTypes: ['navigation'] });
}

// Error handling
window.addEventListener('error', (e) => {
    console.error('JavaScript error:', e.error);
    // Could send error to logging service
});

window.addEventListener('unhandledrejection', (e) => {
    console.error('Unhandled promise rejection:', e.reason);
    // Could send error to logging service
});