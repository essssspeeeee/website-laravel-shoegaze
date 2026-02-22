<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-400 flex items-center justify-center h-screen">
    <div class="bg-white shadow-md p-8 w-96">
        <h2 class="text-center text-xl font-bold mb-6">REGISTER</h2>
        <form method="POST" action="/register">
            @csrf
            <label>Nama</label>
            <input type="text" name="name" placeholder="Masukkan Nama"
                   class="w-full border p-2 mb-4 bg-white" required>

            <label>Username</label>
            <input type="text" name="username" placeholder="Masukkan Username"
                   class="w-full border p-2 mb-4 bg-white" required>

            <label>Email</label>
            <input type="email" name="email" placeholder="Masukkan Email"
                   class="w-full border p-2 mb-4 bg-white" required>

            <label>Password</label>
            <input type="password" name="password" placeholder="Masukkan Password"
                   class="w-full border p-2 mb-4 bg-white" required>

            <button type="submit" 
        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 rounded">Daftar</button>
        </form>
        <p class="text-center mt-4 text-sm">Sudah punya akun? <a href="/login" class="text-blue-600">Login</a></p>
    </div>
</body>
</html>