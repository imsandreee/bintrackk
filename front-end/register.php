<?php
// PHP logic provided by the user
include '../back-end/config.php'; // Assuming this file exists and defines $supabase_url and $supabase_key
session_start(); // Start session to potentially store messages or tokens, though not used in this specific logic

$message = '';

// Exponential backoff configuration
$max_retries = 3;
$initial_delay = 1;

if(isset($_POST['register'])){
    $email = $_POST['email'];
    $password = $_POST['password'];
    $username = $_POST['username'];
    $role = $_POST['role'] ?? 'citizen'; // Default role to 'citizen' if not explicitly set

    $data = [
        'email' => $email,
        'password' => $password,
        // The 'data' key here is specific to Supabase's user_metadata field
        'options' => ['data' => ['username'=>$username,'role'=>$role]]
    ];

    $delay = $initial_delay;
    $result = null;

    for ($attempt = 1; $attempt <= $max_retries; $attempt++) {
        $ch = curl_init("$supabase_url/auth/v1/signup");
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "apikey: $supabase_key",
            "Content-Type: application/json"
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_error = curl_error($ch);
        $result = json_decode($response, true);
        curl_close($ch);

        // Success condition (Supabase returns 200/201 on successful signup)
        if ($http_code === 200 || $http_code === 201) {
            break; 
        }
        
        // Retry logic for server errors/timeouts
        if ($http_code >= 500 || $curl_error) {
            if ($attempt < $max_retries) {
                usleep($delay * 1000000); 
                $delay *= 2; 
                continue;
            } else {
                $result = ['message' => "Supabase service unavailable or API error. Max retries reached."];
                break;
            }
        }
        
        // Non-retryable error (e.g., 400 Bad Request)
        break;
    }


    if(isset($result['user']) && $result['user'] !== null){
        // Supabase often sends a confirmation email (if enabled). User should check their inbox.
        $message = "Registration successful! Please check your email to confirm your account. <a href='index.php' class='font-medium text-emerald-600 hover:text-emerald-500'>Login here</a>";
    } else {
        $error_message = $result['message'] ?? 'An unknown error occurred.';
        // Map common errors to user-friendly messages
        if (strpos($error_message, 'User already registered') !== false) {
             $message = "Registration failed: This email address is already in use.";
        } else {
             $message = "Registration failed: " . htmlspecialchars($error_message);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supabase Registration</title>
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
        .register-card {
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

    <div class="register-card w-full max-w-md p-8 sm:p-10 rounded-xl border border-gray-200">
        <!-- Header -->
        <div class="text-center mb-8">
            <svg class="mx-auto h-10 w-auto text-emerald-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
            </svg>
            <h2 class="mt-4 text-3xl font-extrabold text-gray-900">
                Create a new account
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                Join the platform quickly and easily.
            </p>
        </div>

        <!-- Error/Message Display -->
        <?php if (!empty($message)): ?>
            <div id="message-box" class="mb-6 p-4 text-sm font-medium <?php echo strpos($message, 'successful') !== false ? 'text-white bg-green-500' : 'text-white bg-red-500'; ?> rounded-lg shadow-md flex items-center justify-between">
                <span><?php echo $message; ?></span>
                <button onclick="document.getElementById('message-box').style.display='none'" class="text-white hover:opacity-75 focus:outline-none ml-4">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        <?php endif; ?>

        <!-- Registration Form -->
        <form class="space-y-6" action="" method="POST">
            <div>
                <label for="username" class="block text-sm font-medium text-gray-700 mb-1">
                    Username
                </label>
                <input id="username" name="username" type="text" autocomplete="username" required
                    class="input-field appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none sm:text-sm"
                    placeholder="JohnDoe">
            </div>
            
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
                <input id="password" name="password" type="password" autocomplete="new-password" required
                    class="input-field appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none sm:text-sm"
                    placeholder="••••••••">
            </div>

            <!-- Role Selection (Optional for user self-registration, but included as per your PHP logic) -->
            <div>
                <label for="role" class="block text-sm font-medium text-gray-700 mb-1">
                    Account Type
                </label>
                <select id="role" name="role"
                    class="input-field appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none sm:text-sm">
                    <option value="citizen">Citizen</option>
                    <option value="admin">Admin (Requires approval)</option>
                </select>
                <p class="mt-1 text-xs text-gray-500">Choosing 'Admin' will save the role metadata, but access control should be enforced on the backend.</p>
            </div>

            <div>
                <button type="submit" name="register"
                    class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition duration-150 ease-in-out">
                    Register Account
                </button>
            </div>
        </form>

        <div class="mt-6 text-center text-sm text-gray-500">
            Already have an account?
            <a href="index.php" class="font-medium text-emerald-600 hover:text-emerald-500">
                Sign in
            </a>
        </div>
    </div>

</body>
</html>