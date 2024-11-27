<template>
    <div class="bg-gray-500 flex items-center justify-center min-h-screen">
        <div class="bg-white p-8 rounded-lg shadow-md w-128">
            <h1 class="text-2xl font-semibold text-center mb-6">Login</h1>
            <form action="#" method="post">
                <div class="mb-4">
                    <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                    <input type="text" id="username" name="username" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" id="password" name="password" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <button type="submit" class="w-full bg-blue-500 text-white font-semibold py-2 rounded-md hover:bg-blue-600">Login</button>
                <div class="mt-6 text-center">
                    <a href="#" class="text-blue-500 hover:underline">Forgot Password?</a>
                    <br>
                    <a href="#" class="text-blue-500 hover:underline">Create an Account</a>
                </div>
            </form>
        </div>
    </div>
</template>