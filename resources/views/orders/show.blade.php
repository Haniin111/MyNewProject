@extends('layouts.master')
@section('title', 'Order #' . $order->id)

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-6">
            <h1>Order #{{ $order->id }}</h1>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('orders.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-2"></i>Back to Orders
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
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
                            <span>Total:</span>
                            <span>${{ $order->formatted_total }}</span>
                        </li>
                    </ul>
                </div>
            </div>

            @if($order->shipping_address)
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Shipping Address</h5>
                </div>
                <div class="card-body">
                    @php
                        $address = json_decode($order->shipping_address);
                    @endphp
                    @if($address)
                        <p class="mb-1"><strong>{{ $address->first_name }} {{ $address->last_name }}</strong></p>
                        <p class="mb-1">{{ $address->address }}</p>
                        <p class="mb-1">{{ $address->city }}, {{ $address->state }} {{ $address->zip }}</p>
                        <p class="mb-1">Phone: {{ $address->phone }}</p>
                        <p class="mb-1">Email: {{ $address->email }}</p>
                        @if(!empty($address->notes))
                            <p class="mt-3 mb-0"><strong>Notes:</strong> {{ $address->notes }}</p>
                        @endif
                    @else
                        <p class="mb-0">{{ $order->shipping_address }}</p>
                    @endif
                </div>
            </div>
            @endif

            @if($order->status === 'pending')
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('orders.cancel', $order) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Are you sure you want to cancel this order?')">
                            <i class="fas fa-times me-2"></i>Cancel Order
                        </button>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection 