@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-indigo-50 to-blue-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div class="text-center">
            <div class="mx-auto h-20 w-20 bg-indigo-600 rounded-full flex items-center justify-center mb-4">
                <i class="fas fa-envelope text-white text-2xl"></i>
            </div>
            <h2 class="text-center text-3xl font-extrabold text-gray-900">
                Verify Your Email Address
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Before proceeding, please check your email for a verification link.
            </p>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-8">
            <div class="text-center">
                <i class="fas fa-envelope text-4xl text-blue-600 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Check Your Email</h3>
                <p class="text-sm text-gray-600 mb-6">
                    We've sent a verification link to <strong>{{ Auth::user()->email }}</strong>
                </p>
                
                <div class="bg-blue-50 border border-blue-200 rounded-md p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-info-circle text-blue-400"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800">What to do next:</h3>
                            <div class="mt-2 text-sm text-blue-700">
                                <ul class="list-disc pl-5 space-y-1">
                                    <li>Check your email inbox</li>
                                    <li>Click the verification link in the email</li>
                                    <li>If you don't see the email, check your spam folder</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                        <i class="fas fa-paper-plane mr-2"></i>
                        Resend Verification Email
                    </button>
                </form>

                <div class="mt-6">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm text-gray-600 hover:text-gray-900">
                            <i class="fas fa-sign-out-alt mr-1"></i>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 