/**
 * Shopping Cart JavaScript
 */
 
document.addEventListener('DOMContentLoaded', function() {
    console.log('Cart JS loaded');
    
    // Track which buttons have already been initialized
    const initializedButtons = new Set();
    
    // Add to cart
    function initAddToCartButtons() {
        const addToCartButtons = document.querySelectorAll('.add-to-cart-btn');
        
        if (addToCartButtons.length > 0) {
            console.log('Found add to cart buttons:', addToCartButtons.length);
            
            addToCartButtons.forEach(button => {
                const productSlug = button.getAttribute('data-product-slug');
                // Create a unique identifier for this button
                const buttonId = productSlug || button.getAttribute('id') || button.innerText;
                
                // Only add event listener if this button hasn't been initialized
                if (!initializedButtons.has(buttonId)) {
                    // Mark this button as initialized
                    initializedButtons.add(buttonId);
                    
                    button.addEventListener('click', function(e) {
                        e.preventDefault();
                        // Prevent multiple clicks
                        if (this.dataset.processing === 'true') {
                            return;
                        }
                        
                        const productSlug = this.getAttribute('data-product-slug');
                        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                        
                        console.log('Add to cart button clicked for:', productSlug);
                        
                        // Disable button during processing
                        this.disabled = true;
                        this.dataset.processing = 'true';
                        const originalText = this.innerHTML;
                        this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding...';
                        
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
                            console.log('Server response:', response);
                            if (!response.ok) {
                                throw new Error('Server connection failed');
                            }
                            return response.json();
                        })
                        .then(data => {
                            console.log('Response data:', data);
                            
                            if (data.success) {
                                // Update cart count
                                updateCartCount(data.cartCount);
                                
                                // Show success message
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success!',
                                    text: 'Product added to cart successfully',
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                            } else {
                                console.error('Failed to add product to cart:', data.message);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: data.message || 'Error adding product to cart',
                                    showConfirmButton: true
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error adding product to cart:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'Error adding product to cart',
                                showConfirmButton: true
                            });
                        })
                        .finally(() => {
                            // Re-enable button when finished
                            this.disabled = false;
                            this.dataset.processing = 'false';
                            this.innerHTML = originalText;
                        });
                    });
                    
                    console.log('Initialized button with product slug:', productSlug);
                }
            });
        } else {
            console.log('No add to cart buttons found');
        }
    }
    
    // Update cart count
    function updateCartCount(count) {
        const cartIcon = document.getElementById('cartIcon');
        if (!cartIcon) {
            console.error('Cart icon not found');
            return;
        }
        
        let cartCountBadge = cartIcon.querySelector('.cart-count');
        
        console.log('Updating cart count:', count);
        
        if (count > 0) {
            if (cartCountBadge) {
                // Update existing badge
                cartCountBadge.textContent = count;
            } else {
                // Create new badge
                cartCountBadge = document.createElement('span');
                cartCountBadge.className = 'position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger cart-count';
                cartCountBadge.textContent = count;
                cartIcon.appendChild(cartCountBadge);
            }
        } else if (cartCountBadge) {
            // Remove badge if count is 0
            cartCountBadge.remove();
        }
    }
    
    // Initialize buttons only once at page load
    initAddToCartButtons();
    
    // Add observer to check for dynamically added buttons but prevent duplicates
    const observer = new MutationObserver(function(mutations) {
        let hasNewNodes = false;
        
        mutations.forEach(function(mutation) {
            if (mutation.addedNodes.length > 0) {
                hasNewNodes = true;
            }
        });
        
        if (hasNewNodes) {
            initAddToCartButtons();
        }
    });
    
    // Start observing changes in page content
    observer.observe(document.body, { childList: true, subtree: true });
    
    // Update cart count when page loads
    fetch('/cart/count', {
        method: 'GET',
        headers: {
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        updateCartCount(data.count);
    })
    .catch(error => {
        console.error('Error loading cart count:', error);
    });
}); 