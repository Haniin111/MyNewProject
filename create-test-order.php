<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;

// Function to create test order
function createTestOrder($payment_method = 'cash', $status = 'processing')
{
    // Find customer user (separate from delivery manager)
    $customer = User::where('email', 'haniiiin11117@gmail.com')->first();
    
    if (!$customer) {
        echo "Customer not found. Please run the create-customer.php script first.\n";
        return;
    }
    
    // Make sure the customer has sufficient credit
    if ($customer->credit < 200) {
        $customer->credit = 500;
        $customer->save();
        echo "Added credit to customer's account. New balance: $" . $customer->credit . "\n";
    }
    
    // Create shipping address
    $shippingAddress = json_encode([
        'first_name' => $customer->name,
        'last_name' => 'Account',
        'email' => $customer->email,
        'phone' => '123456789',
        'address' => '123 Customer Street',
        'city' => 'Customer City',
        'state' => 'Test State',
        'zip' => '12345',
        'country' => 'Test Country'
    ]);
    
    // Create order
    $order = new Order();
    $order->user_id = $customer->id; // Using customer ID, not delivery manager
    $order->total = 99.99;
    $order->status = $status;
    $order->shipping_address = $shippingAddress;
    $order->payment_method = $payment_method;
    $order->payment_status = 'pending';
    $order->save();
    
    // Get some products
    $products = Product::take(2)->get();
    
    if ($products->isEmpty()) {
        echo "No products found. Creating a sample product...\n";
        
        $product = new Product();
        $product->name = 'Test Product';
        $product->description = 'This is a test product';
        $product->price = 49.99;
        $product->stock = 100;
        $product->is_active = true;
        $product->save();
        
        $products = collect([$product]);
    }
    
    // Check order_items table structure
    try {
        $columns = \DB::getSchemaBuilder()->getColumnListing('order_items');
        echo "Order items columns: " . implode(', ', $columns) . "\n";
        
        // Add order items based on actual columns
        foreach ($products as $index => $product) {
            $item = new OrderItem();
            $item->order_id = $order->id;
            $item->product_id = $product->id;
            
            // Check if columns exist before setting
            if (in_array('name', $columns)) {
                $item->name = $product->name;
            }
            
            $item->price = $product->price;
            $item->quantity = $index + 1;
            $item->save();
            
            echo "Added item #{$item->id} to order.\n";
        }
    } catch (\Exception $e) {
        echo "Error adding order items: " . $e->getMessage() . "\n";
        
        // Fallback method - direct SQL insertion
        try {
            foreach ($products as $index => $product) {
                \DB::table('order_items')->insert([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'price' => $product->price,
                    'quantity' => $index + 1,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
            echo "Added items to order using direct SQL.\n";
        } catch (\Exception $e2) {
            echo "Failed to add items using direct SQL: " . $e2->getMessage() . "\n";
        }
    }
    
    echo "Test order #{$order->id} created successfully for customer '{$customer->name}' with {$payment_method} payment method and {$status} status.\n";
    return $order->id;
}

// Create cash order with processing status
$cashOrderId = createTestOrder('cash', 'processing');

// Create credit order with pending status
$creditOrderId = createTestOrder('credit', 'pending');

echo "\n==============================================\n";
echo "TEST ORDERS CREATED SUCCESSFULLY\n";
echo "==============================================\n";
echo "Cash Order ID: {$cashOrderId}\n";
echo "Credit Order ID: {$creditOrderId}\n";
echo "==============================================\n";
echo "Use these order IDs to test payment processing in the delivery dashboard.\n"; 