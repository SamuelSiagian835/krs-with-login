<div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
  <div class="sm:mx-auto sm:w-full sm:max-w-sm">
    <img class="mx-auto h-10 w-auto" src="https://tailwindui.com/img/logos/mark.svg?color=indigo&shade=600" alt="Your Company">
    <h2 class="mt-10 text-center text-2xl font-bold leading-9 tracking-tight text-gray-900">Sign up for an account</h2>
    <script src="https://cdn.tailwindcss.com"></script>
  </div>

  <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
    <form class="space-y-6" action="#" method="POST">
      <!-- Email input -->
      <div>
        <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Email</label>
        <div class="mt-2">
          <input id="email" name="email" type="email" autocomplete="email" required class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
        </div>
      </div>

      <!-- Password input -->
      <div>
        <label for="password" class="block text-sm font-medium leading-6 text-gray-900">Password</label>
        <div class="mt-2">
          <input id="password" name="password" type="password" autocomplete="new-password" required class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
        </div>
      </div>

      <!-- Confirm password input -->
      <div>
        <label for="confirm-password" class="block text-sm font-medium leading-6 text-gray-900">Confirm Password</label>
        <div class="mt-2">
          <input id="confirm-password" name="confirm-password" type="password" autocomplete="new-password" required class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
        </div>
      </div>

      <!-- Sign up button -->
      <div>
        <button type="submit" class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Sign up</button>
      </div>
    </form>

    <!-- Login option -->
    <p class="mt-10 text-center text-sm text-gray-500">
      Already have an account?
      <a href="login.php" class="font-semibold leading-6 text-indigo-600 hover:text-indigo-500">Log in</a>
    </p>
  </div>
</div>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Mendapatkan data dari form pendaftaran
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Menghubungkan ke database MySQL
    $servername = "localhost";
    $username = "root";
    $password_db = "samuel123";
    $dbname = "card";

    $conn = new mysqli($servername, $username, $password_db, $dbname);

    // Memeriksa koneksi ke database
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Mengenkripsi password menggunakan password_hash()
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Menyimpan data pendaftaran ke dalam tabel users
    $sql = "INSERT INTO users (email, password) VALUES ('$email', '$hashedPassword')";

    if ($conn->query($sql) === TRUE) {
        // Registrasi berhasil
        echo "Registration successful";
    } else {
        // Terjadi kesalahan saat melakukan pendaftaran
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>

