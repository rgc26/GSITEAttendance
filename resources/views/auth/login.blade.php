@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full space-y-8">
        <div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Sign in to your account
            </h2>
        </div>
        <form class="mt-8 space-y-6" action="{{ route('login') }}" method="POST">
            @csrf
            <div class="rounded-md shadow-sm -space-y-px">
                <div>
                    <label for="email" class="sr-only">Email address</label>
                    <input id="email" name="email" type="email" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Email address" value="{{ old('email') }}">
                </div>
                <div>
                    <label for="password" class="sr-only">Password</label>
                    <input id="password" name="password" type="password" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Password">
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div class="text-sm">
                    <a href="{{ route('password.request') }}" class="font-medium text-indigo-600 hover:text-indigo-500">
                        Forgot your password?
                    </a>
                </div>
            </div>



            <div class="flex justify-center">
                @if(config('services.hcaptcha.site_key') && config('services.hcaptcha.secret_key'))
                    <div class="h-captcha" data-sitekey="{{ config('services.hcaptcha.site_key') }}"></div>
                @endif
            </div>

            <div>
                <button type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-sign-in-alt mr-2"></i>
                    Sign in
                </button>
            </div>

            <div class="text-center space-y-4">
                <div class="border-t border-gray-200 pt-4">
                    <p class="text-sm text-gray-600 mb-2">
                        Don't have an account?
                    </p>
                    <div class="flex flex-col space-y-2">
                        <a href="{{ route('register.student') }}" class="inline-flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <i class="fas fa-user-graduate mr-2"></i>
                            Register
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
@if(config('services.hcaptcha.site_key') && config('services.hcaptcha.secret_key'))
<script src="https://js.hcaptcha.com/1/api.js" async defer></script>
@endif
@endpush 