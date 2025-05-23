<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

// Create a unique email and password
$email = 'delivery.manager@example.com';
$password = 'delivery123';
$name = 'Delivery Manager';

// Clear permission cache
app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

// Check if the user already exists
$existingUser = User::where('email', $email)->first();

if ($existingUser) {
    echo "User already exists, assigning role to existing user...\n";
    $user = $existingUser;
} else {
    // Create a new user
    $user = new User();
    $user->name = $name;
    $user->email = $email;
    $user->password = Hash::make($password);
    $user->email_verified_at = now(); // Automatically verify the email
    $user->save();
    
    echo "Created new user with ID: " . $user->id . "\n";
}

// Create the Delivery Manager role if it doesn't exist
$deliveryRole = Role::firstOrCreate(['name' => 'Delivery Manager']);

// Assign the necessary permissions to the role
$permissions = ['view_orders', 'update_order_status'];
foreach ($permissions as $permission) {
    try {
        $perm = \Spatie\Permission\Models\Permission::firstOrCreate(['name' => $permission]);
        if (!$deliveryRole->hasPermissionTo($permission)) {
            $deliveryRole->givePermissionTo($permission);
            echo "Added permission: $permission to role\n";
        }
    } catch (\Exception $e) {
        echo "Error adding permission $permission: " . $e->getMessage() . "\n";
    }
}

// Assign the role to the user
$user->assignRole($deliveryRole);

// Clear permission cache again
app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

echo "\n==============================================\n";
echo "DELIVERY MANAGER ACCOUNT CREATED SUCCESSFULLY\n";
echo "==============================================\n";
echo "Email: $email\n";
echo "Password: $password\n";
echo "==============================================\n";
echo "Please use these credentials to log in.\n";
echo "The delivery dashboard is now accessible for this user.\n"; 