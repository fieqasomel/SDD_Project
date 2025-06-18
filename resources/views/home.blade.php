@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}
                    
                    <div class="mt-4">
                        <h4>Quick Links</h4>
                        <div class="list-group mt-3">
                            <a href="{{ route('publicuser.inquiries') }}" class="list-group-item list-group-item-action">
                                <i class="fas fa-question-circle mr-2"></i> View My Inquiries
                            </a>
                            <a href="{{ route('publicuser.inquiries.create') }}" class="list-group-item list-group-item-action">
                                <i class="fas fa-plus-circle mr-2"></i> Submit New Inquiry
                            </a>
                            <a href="{{ route('assignments.index') }}" class="list-group-item list-group-item-action">
                                <i class="fas fa-tasks mr-2"></i> View Assignments
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection