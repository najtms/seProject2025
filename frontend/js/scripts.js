// ==================== REGISTRATION FUNCTIONALITY ====================

function toggleAdminPasswordField() {
    let role = document.getElementById("role");
    let adminPasswordField = document.getElementById("adminPasswordField");

    if (role && adminPasswordField) {
        adminPasswordField.style.display = role.value === "Admin" ? "block" : "none";
    }
}

function validatePasswords() {
    let password = document.getElementById("password");
    let confirmPassword = document.getElementById("confirmPassword");
    let errorMessage = document.getElementById("passwordError");

    if (password && confirmPassword && errorMessage) {
        if (password.value !== confirmPassword.value && confirmPassword.value.length > 0) {
            errorMessage.style.display = "block";
            return false;
        } else {
            errorMessage.style.display = "none";
            return true;
        }
    }
    return false;
}

function handleRegistration(event) {
    event.preventDefault();

    const name = document.getElementById("name").value;
    const email = document.getElementById("email").value;
    const role = document.getElementById("role").value;
    const password = document.getElementById("password").value;
    const confirmPassword = document.getElementById("confirmPassword").value;
    const adminPassword = document.getElementById("adminPassword")?.value;

    if (!validatePasswords()) {
        alert("Passwords do not match!");
        return;
    }

    if (role === "Admin" && adminPassword !== "adminadmin") {
        alert("Incorrect Admin Password!");
        return;
    }

    localStorage.setItem("registeredName", name);
    localStorage.setItem("registeredEmail", email);
    localStorage.setItem("registeredRole", role);
    localStorage.setItem("registeredPassword", password);
    localStorage.setItem("isLoggedIn", "true");

    console.log("Data stored in localStorage:", {
        registeredName: name,
        registeredEmail: email,
        registeredRole: role,
        registeredPassword: password,
        isLoggedIn: true,
    });

    console.log("User Registered:", { name, email, role, password });
    alert("Registration Successful!");

    window.location.href = "index.html";
}

function initializeRegisterPage() {
    const roleSelect = document.getElementById("role");
    const registerForm = document.getElementById("registerForm");

    if (roleSelect) {
        roleSelect.addEventListener("change", toggleAdminPasswordField);
    }

    if (registerForm) {
        registerForm.addEventListener("submit", handleRegistration);
    }
}

// ==================== NAVBAR FUNCTIONALITY ====================

function updateNavbar() {
    const isLoggedIn = localStorage.getItem("isLoggedIn") === "true";

    const profileSection = document.getElementById("profileSection");
    const logoutButton = document.getElementById("logoutButton");
    const authButtons = document.getElementById("authButtons");

    if (isLoggedIn) {
        if (profileSection) profileSection.style.display = "block";
        if (logoutButton) logoutButton.style.display = "inline-block";
        if (authButtons) authButtons.style.display = "none";
    } else {
        if (profileSection) profileSection.style.display = "none";
        if (logoutButton) logoutButton.style.display = "none";
        if (authButtons) authButtons.style.display = "block";
    }
}

function logout() {
    localStorage.removeItem("isLoggedIn");
    console.log("User logged out");
    window.location.href = "index.html";
}

function initializeLogoutButton() {
    const logoutButton = document.getElementById("logoutButton");
    if (logoutButton) {
        logoutButton.addEventListener("click", logout);
    } else {
        console.error("Logout button not found!");
    }
}

// ==================== LOGIN FUNCTIONALITY ====================

function handleLogin(event) {
    event.preventDefault();

    const email = document.getElementById("email").value;
    const password = document.getElementById("password").value;

    const storedEmail = localStorage.getItem("registeredEmail");
    const storedPassword = localStorage.getItem("registeredPassword");

    console.log("Entered Credentials:", { email, password });
    console.log("Stored Credentials:", { storedEmail, storedPassword });

    if (email === storedEmail && password === storedPassword) {
        console.log("Login successful");
        alert("Login Successful!");
        localStorage.setItem("isLoggedIn", "true");
        window.location.href = "index.html";
    } else {
        console.error("Invalid email or password!");
        alert("Invalid email or password. Please try again.");
    }
}

function initializeLoginPage() {
    const loginForm = document.getElementById("loginForm");
    if (loginForm) {
        loginForm.addEventListener("submit", handleLogin);
    } else {
        console.error("Login form not found!");
    }
}

// ==================== INITIALIZE PAGES ====================

function initializePage() {
    console.log("initializePage function called");

    updateNavbar();

    if (window.location.pathname.includes("register.html")) {
        console.log("Initializing register page");
        initializeRegisterPage();
    } else if (window.location.pathname.includes("login.html")) {
        console.log("Initializing login page");
        initializeLoginPage();
    } else if (window.location.pathname.includes("index.html")) {
        console.log("Initializing index page");
        initializeLogoutButton();
    }
}

document.addEventListener("DOMContentLoaded", function () {
    console.log("DOM fully loaded");
    initializePage();
});

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
        loop: true, // Enable infinite loop
        autoplay: {
            delay: 4000, // Change slide every 4 seconds
            disableOnInteraction: false, // Keep autoplay running after interaction
        },
        navigation: {
            nextEl: ".swiper-button-next", // Right button
            prevEl: ".swiper-button-prev", // Left button
        },
        pagination: {
            el: ".swiper-pagination", // Dots navigation
            clickable: true, // Click dots to navigate
        },
        speed: 1000, // Smooth transition speed
    });
});


