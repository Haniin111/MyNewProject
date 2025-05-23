@extends('layouts.master')

@section('title', 'Delivery - Order #' . $order->id)

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-6">
            <h1>Order #{{ $order->id }}</h1>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('delivery.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-2"></i>Back to Orders
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="row">
        <div class="col-md-8">
            <!-- Order Items -->
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Order Items</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($item->product && $item->product->image)
                                                <img src="{{ asset('storage/' . $item->product->image) }}" 
                                                    alt="{{ $item->product ? $item->product->name : 'Product' }}" 
                                                    class="img-thumbnail me-3" 
                                                    style="width: 50px; height: 50px; object-fit: cover;">
                                            @endif
                                            <div>
                                                <h6 class="mb-0">{{ $item->product ? $item->product->name : 'Product Not Available' }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>${{ number_format($item->price, 2) }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td class="text-end">${{ number_format($item->price * $item->quantity, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                    <td class="text-end"><strong>${{ $order->formatted_total }}</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Order Status Management -->
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Delivery Management</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('delivery.update-status', $order) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="status" class="form-label">Update Order Status</label>
                            <select name="status" id="status" class="form-select">
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>In Progress</option>
                                <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                            </select>
                        </div>
                        
                        @if($order->payment_method == 'cash')
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="payment_received" id="payment_received" {{ $order->payment_status == 'paid' ? 'checked' : '' }}>
                            <label class="form-check-label" for="payment_received">
                                Customer paid in cash
                            </label>
                        </div>
                        @endif
                        
                        <button type="submit" class="btn btn-primary w-100 mb-3">
                            <i class="fas fa-save me-2"></i>Update Status
                        </button>
                    </form>
                    
                    @if($order->payment_method == 'cash' && $order->payment_status != 'paid')
                    <form action="{{ route('delivery.collect-cash', $order) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success w-100">
                            <i class="fas fa-money-bill-wave me-2"></i>Mark as Paid by Customer
                        </button>
                    </form>
                    @endif
                </div>
            </div>
            
            <!-- Order Information -->
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Order Information</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span>Status:</span>
                            <span>{!! $order->status_badge !!}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span>Order Date:</span>
                            <span>{{ $order->created_at->format('M d, Y') }}</span>
                        </li>
                        @if($order->delivered_at)
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span>Delivered Date:</span>
                            <span>{{ \Carbon\Carbon::parse($order->delivered_at)->format('M d, Y H:i') }}</span>
                        </li>
                        @endif
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span>Payment Method:</span>
                            <span class="text-capitalize">
                                @if($order->payment_method == 'credit')
                                    <i class="fas fa-credit-card me-1"></i> Credit
                                @elseif($order->payment_method == 'cash')
                                    <i class="fas fa-money-bill-wave me-1"></i> Cash on Delivery
                                @else
                                    {{ $order->payment_method ?? 'Not specified' }}
                                @endif
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span>Payment Status:</span>
                            <span>{!! $order->payment_status_badge !!}</span>
                        </li>
                        @if($order->paid_at)
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span>Payment Date:</span>
                            <span>{{ \Carbon\Carbon::parse($order->paid_at)->format('M d, Y H:i') }}</span>
                        </li>
                        @endif
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span>Total:</span>
                            <span>${{ $order->formatted_total }}</span>
                        </li>
                    </ul>
                    
                    @if($order->payment_notes)
                    <div class="mt-3">
                        <h6 class="mb-2">Payment Notes:</h6>
                        <div class="bg-light p-2 rounded">
                            {{ $order->payment_notes }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Customer Information -->
            @if($order->shipping_address)
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Customer Information</h5>
                </div>
                <div class="card-body">
                    @php
                        $address = json_decode($order->shipping_address);
                    @endphp
                    @if($address)
                        <p class="mb-1"><strong>{{ $address->first_name }} {{ $address->last_name }}</strong></p>
                        <p class="mb-1">{{ $address->address }}</p>
                        <p class="mb-1">{{ $address->city }}, {{ $address->state }} {{ $address->zip }}</p>
                        <p class="mb-1">Phone: <a href="tel:{{ $address->phone }}" class="text-decoration-none">{{ $address->phone }}</a></p>
                        <p class="mb-1">Email: <a href="mailto:{{ $address->email }}" class="text-decoration-none">{{ $address->email }}</a></p>
                        @if(!empty($address->notes))
                            <p class="mt-3 mb-0"><strong>Notes:</strong> {{ $address->notes }}</p>
                        @endif
                        
                        @if($order->payment_method == 'cash' && $order->payment_status != 'paid')
                        <hr>
                        <div class="card bg-light mt-3">
                            <div class="card-body p-3">
                                <h6 class="mb-2">Credit Information</h6>
                                <p class="mb-1"><strong>Current Credit:</strong> ${{ number_format($order->user->credit, 2) }}</p>
                                <p class="mb-1"><strong>Amount to Pay:</strong> ${{ $order->formatted_total }}</p>
                                @if($order->user->credit < $order->total)
                                <div class="alert alert-danger mt-2 mb-0 p-2">
                                    <i class="fas fa-exclamation-triangle me-1"></i> 
                                    <strong>Insufficient funds!</strong> Customer needs to add more credit.
                                </div>
                                @else
                                <p class="mb-0"><strong>New Balance After Payment:</strong> ${{ number_format($order->user->credit - $order->total, 2) }}</p>
                                @endif
                            </div>
                        </div>
                        @endif
                    @else
                        <p class="mb-0">{{ $order->shipping_address }}</p>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection 