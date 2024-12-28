// Function to load items in cart
function displayCartItems() {
    const cart = JSON.parse(localStorage.getItem("cart")) || [];
    const cartItemsDiv = document.getElementById("cart-items");

    cartItemsDiv.innerHTML = "";
    if (cart.length > 0) {
        cart.forEach(item => {
            const itemDiv = document.createElement("div");
            itemDiv.textContent = item;
            cartItemsDiv.appendChild(itemDiv);
        });
    } else {
        cartItemsDiv.textContent = "Your cart is empty.";
    }
}

// Function to proceed to checkout
function proceedToCheckout() {
    const cart = JSON.parse(localStorage.getItem("cart")) || [];
    if (cart.length === 0) {
        alert("Your cart is empty. Please add items before proceeding to checkout.");
        return;
    }
    window.location.href = "checkout.html"; // Redirect to checkout page
}

// Function to display items in checkout
function displayCheckoutItems() {
    const cart = JSON.parse(localStorage.getItem("cart")) || [];
    const checkoutItemsDiv = document.getElementById("checkout-items");

    checkoutItemsDiv.innerHTML = "";
    if (cart.length > 0) {
        cart.forEach(item => {
            const itemDiv = document.createElement("div");
            itemDiv.textContent = item;
            checkoutItemsDiv.appendChild(itemDiv);
        });
    } else {
        checkoutItemsDiv.textContent = "No items in your cart.";
    }

    const savedAddress = localStorage.getItem("address");
    if (savedAddress) {
        document.getElementById("address-info").innerHTML = `Shipping to: ${savedAddress} <a href="#" onclick="addOrUpdateAddress()">Add/Update Address</a>`;
    }

    const savedCard = localStorage.getItem("card");
    if (savedCard) {
        document.getElementById("card-info").innerHTML = `Card on file: ${savedCard} <a href="add-card.html">Add/Update Card</a>`;
    }
}

// Save card information
function saveCard() {
    const cardNumber = document.getElementById("card-number").value;
    const expiryDate = document.getElementById("expiry-date").value;

    if (cardNumber.length !== 16 || !expiryDate.match(/^(0[1-9]|1[0-2])\/\d{2}$/)) {
        alert("Please enter a valid card number and expiry date.");
        return;
    }

    // Save masked card number, e.g., "**** **** **** 1234"
    const maskedCard = "**** **** **** " + cardNumber.slice(-4);
    localStorage.setItem("card", maskedCard);
    alert("Card saved successfully!");
    window.location.href = "checkout.html"; // Redirect to checkout page after saving
}

// Confirm purchase function
function confirmPurchase() {
    const address = localStorage.getItem("address");
    const card = localStorage.getItem("card");

    if (!address) {
        alert("Please add your shipping address.");
        return;
    }

    if (!card) {
        alert("Please add your payment method.");
        return;
    }

    alert("Thank you for your purchase! Your items will be shipped to " + address + ".");
    localStorage.removeItem("cart"); // Clear the cart after purchase
    window.location.href = "index.html"; // Redirect to home page
}

// Load items on the correct page
if (document.getElementById("cart-items")) {
    displayCartItems(); // Display cart items in cart.html
}
if (document.getElementById("checkout-items")) {
    displayCheckoutItems(); // Display items in checkout.html
}
