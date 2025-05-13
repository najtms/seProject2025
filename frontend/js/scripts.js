// scripts.js

// Show/hide admin password field based on role
const roleSelect = document.getElementById("role");
if (roleSelect) {
    roleSelect.addEventListener("change", () => {
        const adminField = document.getElementById("adminPasswordField");
        adminField.style.display = roleSelect.value === "Admin" ? "block" : "none";
    });
}

function handleRegistration() {
    const username = document.getElementById("registerUsername").value;
    const email = document.getElementById("registerEmail").value;
    const password = document.getElementById("registerPassword").value;
    const role = document.getElementById("role").value;
    const adminPassword = document.getElementById("adminPassword")?.value;

    // Basic validation
    if (!username || !email || !password) {
        alert("Please fill in all required fields.");
        return;
    }

    if (role === "Admin" && adminPassword !== "admin123") {
        alert("Incorrect admin password.");
        return;
    }

    // Save user
    const users = JSON.parse(localStorage.getItem("users") || "[]");
    if (users.some(u => u.email === email)) {
        alert("Email already exists.");
        return;
    }

    users.push({ username, email, password, role });
    localStorage.setItem("users", JSON.stringify(users));

    alert("Registration successful! Please log in.");
    window.location.hash = "#login"; // redirect to login page
}




// Login form handling
function handleLogin() {
    const email = document.getElementById("loginEmail").value;
    const password = document.getElementById("loginPassword").value;

    const users = JSON.parse(localStorage.getItem("users") || "[]");
    const user = users.find(u => u.email === email && u.password === password);

    if (!user) {
        alert("Invalid email or password.");
        return;
    }

    localStorage.setItem("currentUser", JSON.stringify(user));
    alert("Login successful!");
    updateNavbar();
    window.location.hash = "#home";
}


function updateNavbar() {
    const user = JSON.parse(localStorage.getItem("currentUser"));
    const profileSection = document.getElementById("profileSection");
    const authButtons = document.getElementById("authButtons");
    const logoutButton = document.getElementById("logoutButton");

    if (user) {
        if (profileSection) profileSection.style.display = "flex";
        if (authButtons) authButtons.style.display = "none";
        if (logoutButton) logoutButton.style.display = "inline-block";
    } else {
        if (profileSection) profileSection.style.display = "none";
        if (authButtons) authButtons.style.display = "flex";
        if (logoutButton) logoutButton.style.display = "none";
    }
}
function logout() {
    localStorage.removeItem("currentUser");
    alert("Logged out successfully.");
    updateNavbar();
    window.location.hash = "#home";
}



// ==================== INITIALIZE PAGES ====================
function initializePage() {
    updateNavbar();

    // SPA route change: re-initialize after each section load
    $(document).on("spapp:contentChanged", function () {
        bindFormHandlers();
        updateNavbar(); // Re-check for login status
    });

    // Initial load
    bindFormHandlers();
    updateNavbar();
}

function bindFormHandlers() {
    const registerForm = document.getElementById("registerForm");
    const loginForm = document.getElementById("loginForm");

    if (registerForm) {
        registerForm.addEventListener("submit", function (e) {
            e.preventDefault();
            handleRegistration();
        });

        const roleSelect = document.getElementById("role");
        if (roleSelect) {
            roleSelect.addEventListener("change", function () {
                const adminField = document.getElementById("adminPasswordField");
                if (adminField) {
                    adminField.style.display = this.value === "Admin" ? "block" : "none";
                }
            });
        }
    }

    if (loginForm) {
        loginForm.addEventListener("submit", function (e) {
            e.preventDefault();
            handleLogin();
        });
    }

    const logoutButton = document.getElementById("logoutButton");
    if (logoutButton) {
        logoutButton.addEventListener("click", logout);
    }
}



// Initialize when page loads
document.addEventListener("DOMContentLoaded", initializePage);

$(document).ready(function () {
    console.log("scripts.js loaded successfully!");

    var app = $.spapp({
        defaultView: "#home",
        templateDir: "frontend/views/"
    });

    app.run();
});
document.addEventListener("DOMContentLoaded", function () {
    var swiper = new Swiper(".mySwiper", {
        loop: true, 
        autoplay: {
            delay: 4000, 
            disableOnInteraction: false, 
        },
        navigation: {
            nextEl: ".swiper-button-next", 
            prevEl: ".swiper-button-prev", 
        },
        pagination: {
            el: ".swiper-pagination", 
            clickable: true, 
        },
        speed: 1000, 
    });
});

// ======================
// CART SYSTEM - MAIN CODE
// ======================

// Initialize when page loads
document.addEventListener("DOMContentLoaded", function() {
    // Set up cart if it doesn't exist
    if (!localStorage.getItem("cart")) {
        localStorage.setItem("cart", JSON.stringify([]));
    }
    
    // Update cart counter everywhere
    updateCartCounter();
    
    // Load cart if on cart page
    if (document.getElementById("cart-items")) {
        loadCart();
    }
});

// ======================
// CORE CART FUNCTIONS
// ======================

/**
 * Adds item to cart or increases quantity if already exists
 * @param {string} id - Unique product ID
 * @param {string} name - Product name
 * @param {number} price - Product price
 * @param {string} image - Path to product image
 */
function addToCart(id, name, price, image) {
    // Get current cart
    const cart = JSON.parse(localStorage.getItem("cart"));
    
    // Check if product exists
    const existingIndex = cart.findIndex(item => item.id === id);
    
    if (existingIndex >= 0) {
        // Increase quantity if exists
        cart[existingIndex].quantity++;
    } else {
        // Add new item
        cart.push({
            id: id,
            name: name,
            price: parseFloat(price),
            image: image,
            quantity: 1
        });
    }
    
    // Save back to storage
    localStorage.setItem("cart", JSON.stringify(cart));
    
    // Update UI
    updateCartCounter();
    showNotification(`${name} was added to your cart!`);
    
    // If on cart page, refresh display
    if (document.getElementById("cart-items")) {
        loadCart();
    }
}

/**
 * Loads and displays cart items
 */
function loadCart() {
    const cart = JSON.parse(localStorage.getItem("cart"));
    const container = document.getElementById("cart-items");
    const subtotalEl = document.getElementById("subtotal");
    
    // Clear previous items
    container.innerHTML = "";
    
    if (cart.length === 0) {
        container.innerHTML = `
            <tr>
                <td colspan="5" class="text-center py-4">Your cart is empty</td>
            </tr>
        `;

        return;
    }
    
    let subtotal = 0;
    
    // Add each item to display
    cart.forEach((item, index) => {
        const totalPrice = item.price * item.quantity;
        subtotal += totalPrice;
        
        container.innerHTML += `
            <tr>
                <td class="align-middle">
                    <div class="d-flex align-items-center">
                        <img src="${item.image}" alt="${item.name}" width="60" class="me-3 rounded">
                        ${item.name}
                    </div>
                </td>
                <td class="align-middle">$${item.price.toFixed(2)}</td>
                <td class="align-middle">
                    <input type="number" min="1" value="${item.quantity}" 
                           class="form-control form-control-sm quantity-input"
                           data-index="${index}"
                           style="width: 70px;">
                </td>
                <td class="align-middle">$${totalPrice.toFixed(2)}</td>
                <td class="align-middle">
                    <button class="btn btn-outline-danger btn-sm remove-btn" 
                            data-index="${index}">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            </tr>
        `;
    });
    
    // Update subtotal
    subtotalEl.textContent = `$${subtotal.toFixed(2)}`;
    
    // Add event listeners
    document.querySelectorAll(".quantity-input").forEach(input => {
        input.addEventListener("change", function() {
            updateQuantity(this.dataset.index, this.value);
        });
    });
    
    document.querySelectorAll(".remove-btn").forEach(btn => {
        btn.addEventListener("click", function() {
            removeFromCart(this.dataset.index);
        });
    });
}

/**
 * Updates item quantity in cart
 */
function updateQuantity(index, newQuantity) {
    const cart = JSON.parse(localStorage.getItem("cart"));
    newQuantity = parseInt(newQuantity);
    
    if (newQuantity > 0) {
        cart[index].quantity = newQuantity;
    } else {
        cart.splice(index, 1); // Remove if quantity is 0
    }
    
    localStorage.setItem("cart", JSON.stringify(cart));
    loadCart();
    updateCartCounter();
}

/**
 * Removes item from cart
 */
function removeFromCart(index) {
    const cart = JSON.parse(localStorage.getItem("cart"));
    const removedItem = cart.splice(index, 1)[0];
    localStorage.setItem("cart", JSON.stringify(cart));
    
    showNotification(`${removedItem.name} was removed from cart`);
    loadCart();
    updateCartCounter();
}

/**
 * Clears entire cart
 */
function clearCart() {
    if (confirm("Are you sure you want to clear your cart?")) {
        localStorage.setItem("cart", JSON.stringify([]));
        showNotification("Cart has been cleared");
        loadCart();
        updateCartCounter();
    }
}

// ======================
// HELPER FUNCTIONS
// ======================

/**
 * Updates cart counter in navigation
 */
function updateCartCounter() {
    const cart = JSON.parse(localStorage.getItem("cart")) || [];
    const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
    
    document.querySelectorAll(".cart-counter").forEach(el => {
        el.textContent = totalItems;
    });
}

/**
 * Shows notification to user
 */
function showNotification(message) {
    // Create notification element
    const notification = document.createElement("div");
    notification.className = "cart-notification alert alert-success";
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    // Add to notification container
    const container = document.getElementById("notification-container") || createNotificationContainer();
    container.innerHTML = "";
    container.appendChild(notification);
    
    // Auto-remove after 3 seconds
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

/**
 * Creates notification container if it doesn't exist
 */
function createNotificationContainer() {
    const container = document.createElement("div");
    container.id = "notification-container";
    container.className = "position-fixed top-0 end-0 p-3";
    container.style.zIndex = "9999";
    document.body.appendChild(container);
    return container;
}