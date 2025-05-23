/**
 * Shopping Cart JavaScript - Debug Version
 * 
 * This file is for testing and debugging the cart functionality
 */
 
console.log('Cart debug script loaded');

document.addEventListener('DOMContentLoaded', function() {
    console.log('Cart debug mode activated');
    
    // Track initialized buttons to prevent duplicate listeners
    const debugListenersAdded = new Set();
    
    // Print information about add to cart buttons
    const addToCartButtons = document.querySelectorAll('.add-to-cart-btn');
    console.log('Number of add to cart buttons:', addToCartButtons.length);
    
    // Print information about each button
    addToCartButtons.forEach((button, index) => {
        const productSlug = button.getAttribute('data-product-slug');
        console.log(`Button #${index + 1}:`, {
            html: button.outerHTML,
            productSlug: productSlug,
            hasEventListeners: window.getEventListeners ? !!window.getEventListeners(button).click : 'unknown',
            hasProcessingAttr: button.dataset.processing === 'true'
        });
    });
    
    // Check if cart icon exists
    const cartIcon = document.getElementById('cartIcon');
    console.log('Cart icon:', cartIcon ? 'found' : 'not found');
    
    if (cartIcon) {
        const cartCountBadge = cartIcon.querySelector('.cart-count');
        console.log('Cart count badge:', cartCountBadge ? `found (${cartCountBadge.textContent})` : 'not found');
    }
    
    // Add debug listeners to add to cart buttons (without interfering with main functionality)
    addToCartButtons.forEach((button, index) => {
        const productSlug = button.getAttribute('data-product-slug');
        const buttonId = `debug_${productSlug || index}`;
        
        if (!debugListenersAdded.has(buttonId)) {
            debugListenersAdded.add(buttonId);
            
            // Use capture phase to log before the actual handler executes
            button.addEventListener('click', function(e) {
                console.log(`Debug: Add to cart button #${index + 1} clicked`);
                console.log('Button state:', {
                    productSlug: this.getAttribute('data-product-slug'),
                    disabled: this.disabled,
                    processing: this.dataset.processing === 'true',
                    classList: Array.from(this.classList)
                });
                // Don't prevent default here - let the main handler do its job
            }, true); // true = capture phase
            
            console.log(`Debug listener added to button #${index + 1} for product:`, productSlug || 'unknown');
        }
    });
    
    // Get cart count from server
    fetch('/cart/count', {
        method: 'GET',
        headers: {
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        console.log('Cart count from server:', data.count);
    })
    .catch(error => {
        console.error('Error getting cart count:', error);
    });
    
    // Test add to cart functionality
    window.testAddToCart = function(productSlug) {
        console.log('Testing add to cart for product:', productSlug);
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        console.log('CSRF Token:', csrfToken);
        
        fetch('/cart/add/' + productSlug, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                quantity: 1
            })
        })
        .then(response => {
            console.log('Server response:', {
                ok: response.ok,
                status: response.status,
                statusText: response.statusText
            });
            return response.text().then(text => {
                try {
                    return JSON.parse(text);
                } catch (e) {
                    console.log('Text response (not JSON):', text);
                    throw new Error('Failed to parse JSON response: ' + e.message);
                }
            });
        })
        .then(data => {
            console.log('Response data:', data);
            if (data.success) {
                console.log('Product added successfully! Cart count:', data.cartCount);
                
                // Manually update UI
                const cartIcon = document.getElementById('cartIcon');
                if (cartIcon) {
                    let cartCountBadge = cartIcon.querySelector('.cart-count');
                    if (cartCountBadge) {
                        cartCountBadge.textContent = data.cartCount;
                    } else {
                        cartCountBadge = document.createElement('span');
                        cartCountBadge.className = 'position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger cart-count';
                        cartCountBadge.textContent = data.cartCount;
                        cartIcon.appendChild(cartCountBadge);
                    }
                }
                
                // Show success message
                if (window.Swal) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Product added to cart',
                        showConfirmButton: false,
                        timer: 1500
                    });
                } else {
                    alert('Product added to cart');
                }
            } else {
                console.error('Failed to add product:', data.message);
                
                // Show error message
                if (window.Swal) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: data.message || 'Error adding product to cart',
                        showConfirmButton: true
                    });
                } else {
                    alert('Error: ' + (data.message || 'Error adding product to cart'));
                }
            }
        })
        .catch(error => {
            console.error('Error adding product to cart:', error);
            
            // Show error message
            if (window.Swal) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Error adding product to cart: ' + error.message,
                    showConfirmButton: true
                });
            } else {
                alert('Error: ' + error.message);
            }
        });
    };
    
    console.log('To test adding a product to cart, use the browser console and run:');
    console.log('testAddToCart("product-slug")');
    console.log('Replace "product-slug" with the actual product slug.');

    // Add debug button to help with testing
    const debugButton = document.createElement('button');
    debugButton.textContent = 'Test Add to Cart';
    debugButton.style.position = 'fixed';
    debugButton.style.bottom = '20px';
    debugButton.style.right = '20px';
    debugButton.style.zIndex = '9999';
    debugButton.style.padding = '10px 15px';
    debugButton.style.backgroundColor = '#cb0404';
    debugButton.style.color = 'white';
    debugButton.style.border = 'none';
    debugButton.style.borderRadius = '5px';
    debugButton.style.cursor = 'pointer';
    debugButton.style.fontWeight = 'bold';
    
    debugButton.addEventListener('click', function() {
        // Get first add to cart button on page
        const firstButton = document.querySelector('.add-to-cart-btn');
        if (firstButton) {
            const productSlug = firstButton.getAttribute('data-product-slug');
            if (productSlug) {
                console.log('Testing add to cart for product:', productSlug);
                window.testAddToCart(productSlug);
            } else {
                alert('Product ID (data-product-slug) not found on button');
            }
        } else {
            alert('No add to cart button found on this page');
        }
    });
    
    document.body.appendChild(debugButton);
}); 