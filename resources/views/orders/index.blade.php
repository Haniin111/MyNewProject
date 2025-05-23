@extends('layouts.master')
@section('title', 'My Orders')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">My Orders</h1>

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

    @if($orders->isEmpty())
        <div class="card">
            <div class="card-body text-center p-5">
                <i class="fas fa-shopping-bag fa-4x text-muted mb-3"></i>
                <h3>No Orders Found</h3>
                <p class="text-muted">You haven't placed any orders yet.</p>
                <a href="{{ route('products.shop') }}" class="btn btn-primary mt-3">
                    <i class="fas fa-shopping-cart me-2"></i>Start Shopping
                </a>
            </div>
        </div>
    @else
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Payment</th>
                                <th>Total</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                                <tr>
                                    <td>{{ $order->id }}</td>
                                    <td>{{ $order->created_at->format('M d, Y') }}</td>
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
                                        @else
                                            <span class="badge bg-light text-dark">
                                                {{ $order->payment_method ?? 'Not specified' }}
                                            </span>
                                        @endif
                                    </td>
                                    <td>${{ $order->formatted_total }}</td>
                                    <td>
                                        <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection 