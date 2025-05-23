@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h2>{{ __('Access Denied') }}</h2>
                </div>
                <div class="card-body py-5">
                    <div class="text-center mb-4">
                        <i class="fas fa-exclamation-triangle fa-4x text-danger"></i>
                    </div>
                    <h3 class="text-center mb-4">{{ $exception->getMessage() ?: __('You do not have permission to access this page') }}</h3>
                    <p class="text-center">
                        {{ __('Please contact an administrator if you believe this is an error.') }}
                    </p>
                    <div class="text-center mt-4">
                        <a href="{{ route('home') }}" class="btn btn-primary">
                            <i class="fas fa-home me-2"></i> {{ __('Return to Home') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 