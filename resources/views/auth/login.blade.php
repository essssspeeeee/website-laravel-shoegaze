<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-400 flex items-center justify-center h-screen">
    <div class="bg-white shadow-md p-8 w-96">
        <h2 class="text-center text-xl font-bold mb-6">LOGIN</h2>
        <form method="POST" action="/login">
            @csrf
            <label>Username</label>
            <input type="text" name="username" placeholder="Masukkan username"
                   class="w-full border p-2 mb-4 bg-white" required>
            <label>Password</label>
            <input type="password" name="password" placeholder="Masukkan password"
                   class="w-full border p-2 mb-4 bg-white" required>
            <button type="submit" 
        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 rounded">Masuk</button>
        </form>
        <p class="text-center mt-4 text-sm">Belum punya akun? <a href="/register" class="text-blue-600">Daftar</a></p>
    </div>
</body>
</html>