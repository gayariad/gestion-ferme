@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="max-w-md w-full bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-2xl font-bold mb-6">Changer votre mot de passe</h2>
        <form action="{{ route('password.first.update') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="password" class="block text-gray-700">Nouveau mot de passe</label>
                <input type="password" id="password" name="password" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div class="mb-4">
                <label for="password_confirmation" class="block text-gray-700">Confirmez le nouveau mot de passe</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div class="flex justify-end">
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">Changer le mot de passe</button>
            </div>
        </form>
    </div>
</div>
@endsection
