<?php
include '../back-end/config.php'; // $supabase_url, $supabase_key
session_start();
$message = '';

if(isset($_POST['login'])){
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Fetch user from Supabase 'users' table
    $ch = curl_init("$supabase_url/rest/v1/users?email=eq.$email");
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "apikey: $supabase_key",
        "Authorization: Bearer $supabase_key",
        "Content-Type: application/json",
        "Accept: application/json"
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curl_error = curl_error($ch);
    curl_close($ch);

    if($http_code === 200){
        $users = json_decode($response, true);
        if(count($users) > 0){
            $user = $users[0]; // Supabase returns an array
            // Verify password
            if(password_verify($password, $user['password'])){
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['role'] = $user['role'] ?? 'citizen';

                // Redirect based on role
                if($_SESSION['role'] === 'admin'){
                    header("Location: ../back-end/admin_dashboard.php");
                } else {
                    header("Location: citizen_dashboard.php");
                }
                exit();
            } else {
                $message = "Login failed: Incorrect password.";
            }
        } else {
            $message = "Login failed: User not found.";
        }
    } else {
        $error_message = $curl_error ?: $response;
        $message = "Login failed: " . htmlspecialchars($error_message);
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supabase Secure Login</title>
    <!-- Load Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Custom Tailwind Configuration -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');
        
        :root {
            --primary: #10B981; /* Emerald-500 */
            --secondary: #1F2937; /* Gray-800 */
            --background: #F9FAFB; /* Gray-50 */
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--background);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }
        .login-card {
            background-color: white;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        .input-field {
            transition: border-color 0.3s, box-shadow 0.3s;
        }
        .input-field:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.3); /* Emerald shadow */
        }
    </style>
</head>
<body>

    <div class="login-card w-full max-w-md p-8 sm:p-10 rounded-xl border border-gray-200">
        <!-- Header -->
        <div class="text-center mb-8">
            <svg class="mx-auto h-10 w-auto text-emerald-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
            <h2 class="mt-4 text-3xl font-extrabold text-gray-900">
                Sign in to your account
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                Powered by Supabase Auth
            </p>
        </div>

        <!-- Error/Message Display -->
        <?php if (!empty($message)): ?>
            <div id="message-box" class="mb-6 p-4 text-sm font-medium text-white bg-red-500 rounded-lg shadow-md flex items-center justify-between">
                <span><?php echo htmlspecialchars($message); ?></span>
                <button onclick="document.getElementById('message-box').style.display='none'" class="text-white hover:text-red-100 focus:outline-none ml-4">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        <?php endif; ?>

        <!-- Login Form -->
        <form class="space-y-6" action="" method="POST">
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                    Email address
                </label>
                <input id="email" name="email" type="email" autocomplete="email" required
                    class="input-field appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none sm:text-sm"
                    placeholder="you@example.com">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                    Password
                </label>
                <input id="password" name="password" type="password" autocomplete="current-password" required
                    class="input-field appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none sm:text-sm"
                    placeholder="••••••••">
            </div>

            <div class="flex items-center justify-between">
                <div class="text-sm">
                    <a href="#" class="font-medium text-emerald-600 hover:text-emerald-500">
                        Forgot your password?
                    </a>
                </div>
            </div>

            <div>
                <button type="submit" name="login"
                    class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition duration-150 ease-in-out">
                    Sign in
                </button>
            </div>
        </form>

        <div class="mt-6 text-center text-sm text-gray-500">
            Need an account?
            <a href="register.php" class="font-medium text-emerald-600 hover:text-emerald-500">
                Sign up
            </a>
        </div>
    </div>


    
</body>
</html>