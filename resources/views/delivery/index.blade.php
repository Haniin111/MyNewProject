@extends('layouts.master')

@section('title', 'Delivery Management')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Delivery Management</h1>
        <span class="badge bg-primary p-2">
            <i class="fas fa-user-shield me-2"></i> Delivery Manager
        </span>
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

    <div class="card mb-4">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Orders for Delivery</h5>
        </div>
        <div class="card-body">
            @if($pendingOrders->isEmpty())
                <div class="text-center p-4">
                    <i class="fas fa-truck-loading fa-4x text-muted mb-3"></i>
                    <h3>No Orders for Delivery</h3>
                    <p class="text-muted">All orders have been delivered. Check back later!</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Date</th>
                                <th>Customer</th>
                                <th>Location</th>
                                <th>Status</th>
                                <th>Payment Method</th>
                                <th>Payment Status</th>
                                <th>Amount</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pendingOrders as $order)
                                @php
                                    $address = json_decode($order->shipping_address, true);
                                @endphp
                                <tr>
                                    <td>{{ $order->id }}</td>
                                    <td>{{ $order->created_at->format('M d, Y') }}</td>
                                    <td>
                                        @if($address)
                                            {{ $address['first_name'] }} {{ $address['last_name'] }}<br>
                                            <small class="text-muted">{{ $address['phone'] }}</small>
                                        @else
                                            <span class="text-muted">No details</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($address)
                                            {{ $address['city'] }}, {{ $address['state'] }}
                                        @else
                                            <span class="text-muted">No details</span>
                                        @endif
                                    </td>
                                    <td>{!! $order->status_badge !!}</td>
                                    <td>
                                        @if($order->payment_method == 'credit')
                                            <span class="badge bg-info">
                                                <i class="fas fa-credit-card me-1"></i> Credit
                                            </span>
                                        @elseif($order->payment_method == 'cash')
                                            <span class="badge bg-secondary">
                                                <i class="fas fa-money-bill-wave me-1"></i> Cash
                                            </span>
                                        @endif
                                    </td>
                                    <td>{!! $order->payment_status_badge !!}</td>
                                    <td>${{ $order->formatted_total }}</td>
                                    <td>
                                        <a href="{{ route('delivery.show', $order) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 