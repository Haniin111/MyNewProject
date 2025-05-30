<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
<<<<<<< HEAD
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

=======
>>>>>>> 37832177f92ffd6ce3d73febe73a42b600edf666
    public function index()
    {
        try {
            $user = auth()->user();
            Log::info('Accessing cart page', [
                'user_id' => $user->id,
                'user_role' => $user->roles->pluck('name'),
                'is_authenticated' => auth()->check()
            ]);
            
            $cartItems = $user->cart()->with('product')->get();
            $total = $cartItems->sum(function($item) {
                return $item->quantity * $item->product->price;
            });
            
            Log::info('Cart items retrieved', [
                'user_id' => $user->id,
                'items_count' => $cartItems->count(),
                'total' => $total
            ]);
            
            return view('users.cart', compact('cartItems', 'total'));
        } catch (\Exception $e) {
            Log::error('Error accessing cart', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage()
            ]);
            return redirect()->route('login');
        }
    }

    public function add(Request $request, $slug)
    {
        try {
            $user = auth()->user();
<<<<<<< HEAD
            
            if (!$user) {
                Log::error('User not authenticated when trying to add to cart');
                return response()->json([
                    'success' => false,
                    'message' => 'You must be logged in to add items to your cart'
                ], 401);
            }
            
            $product = Product::where('slug', $slug)->firstOrFail();
            
            if ($product->stock <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sorry, this product is out of stock'
                ], 400);
            }
            
            $quantity = $request->input('quantity', 1);

            Log::info('Adding to cart', [
                'user_id' => $user->id,
                'product_id' => $product->id,
                'product_slug' => $slug,
                'quantity' => $quantity
            ]);

            // Check if the item already exists in the cart
            $cartItem = $user->cart()->where('product_id', $product->id)->first();
            
            if ($cartItem) {
                // Update existing cart item
                $cartItem->update([
                    'quantity' => $cartItem->quantity + $quantity
                ]);
                
                Log::info('Cart item updated', [
                    'cart_id' => $cartItem->id,
                    'user_id' => $user->id,
                    'product_id' => $product->id,
                    'quantity' => $cartItem->quantity
                ]);
            } else {
                // Create new cart item
                $cartItem = $user->cart()->create([
                    'product_id' => $product->id,
                    'quantity' => $quantity
                ]);
                
                Log::info('Cart item created', [
                    'cart_id' => $cartItem->id,
                    'user_id' => $user->id,
                    'product_id' => $product->id,
                    'quantity' => $cartItem->quantity
                ]);
            }
=======
            $product = Product::where('slug', $slug)->firstOrFail();
            $quantity = $request->input('quantity', 1);

            // Add to cart logic
            $cartItem = $user->cart()->updateOrCreate(
                ['product_id' => $product->id],
                ['quantity' => DB::raw('quantity + ' . $quantity)]
            );
>>>>>>> 37832177f92ffd6ce3d73febe73a42b600edf666

            // Get updated cart count
            $cartCount = $user->cart()->sum('quantity');

<<<<<<< HEAD
            Log::info('Cart updated', [
                'cart_item_id' => $cartItem->id,
                'user_id' => $user->id,
                'product_id' => $product->id,
                'quantity' => $cartItem->quantity,
                'cart_count' => $cartCount
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Product added to cart successfully',
                'cartCount' => $cartCount,
                'itemId' => $cartItem->id
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to add to cart', [
                'user_id' => auth()->id(),
                'product_slug' => $slug,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to add product to cart: ' . $e->getMessage()
=======
            return response()->json([
                'success' => true,
                'message' => 'Product added to cart successfully',
                'cartCount' => $cartCount
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add product to cart'
>>>>>>> 37832177f92ffd6ce3d73febe73a42b600edf666
            ], 500);
        }
    }

<<<<<<< HEAD
    public function remove($slug)
    {
        try {
            $product = Product::where('slug', $slug)->firstOrFail();
            auth()->user()->cart()->where('product_id', $product->id)->delete();
            
            return redirect()->back()->with('success', 'Product removed from cart successfully!');
        } catch (\Exception $e) {
            Log::error('Error removing item from cart', [
                'user_id' => auth()->id(),
                'product_slug' => $slug,
                'error' => $e->getMessage()
            ]);
            
            return redirect()->back()->with('error', 'Failed to remove product from cart');
        }
    }

    public function update(Request $request, $slug)
    {
        try {
            $request->validate([
                'quantity' => 'required|integer|min:1'
            ]);

            $product = Product::where('slug', $slug)->firstOrFail();
            $cartItem = auth()->user()->cart()->where('product_id', $product->id)->first();
            
            if ($cartItem) {
                $cartItem->update([
                    'quantity' => $request->quantity
                ]);
                
                Log::info('Cart item updated', [
                    'user_id' => auth()->id(),
                    'product_id' => $product->id,
                    'quantity' => $request->quantity
                ]);
            }

            return redirect()->back()->with('success', 'Cart updated successfully!');
        } catch (\Exception $e) {
            Log::error('Error updating cart', [
                'user_id' => auth()->id(),
                'product_slug' => $slug,
                'error' => $e->getMessage()
            ]);
            
            return redirect()->back()->with('error', 'Failed to update cart');
        }
=======
    public function remove(Product $product)
    {
        auth()->user()->cart()->where('product_id', $product->id)->delete();
        return redirect()->back()->with('success', 'Product removed from cart successfully!');
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cartItem = auth()->user()->cart()->where('product_id', $product->id)->first();
        
        if ($cartItem) {
            $cartItem->update([
                'quantity' => $request->quantity
            ]);
        }

        return redirect()->back()->with('success', 'Cart updated successfully!');
>>>>>>> 37832177f92ffd6ce3d73febe73a42b600edf666
    }

    public function clear()
    {
        try {
            $user = auth()->user();
            $user->cart()->delete();
            
            \Illuminate\Support\Facades\Log::info('Cart cleared', [
                'user_id' => $user->id
            ]);
            
            return redirect()->route('cart')->with('success', 'Cart cleared successfully');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error clearing cart', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage()
            ]);
            
            return redirect()->route('cart')->with('error', 'Failed to clear cart');
        }
    }

    public function checkout()
    {
        $cartItems = auth()->user()->cart()->with('product')->get();
        $total = $cartItems->sum(function($item) {
            return $item->quantity * $item->product->price;
        });

        return view('users.checkout', compact('cartItems', 'total'));
    }

    public function count()
    {
        try {
            $user = auth()->user();
            $cartCount = $user->cart()->sum('quantity');
            
            return response()->json(['count' => $cartCount]);
        } catch (\Exception $e) {
            return response()->json(['count' => 0]);
        }
    }
}
