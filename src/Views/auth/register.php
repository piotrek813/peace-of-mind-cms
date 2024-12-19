<!DOCTYPE html>
<html data-theme="dark">
<head>
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.7.2/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-base-300 flex items-center justify-center">
    <div class="card w-96 bg-base-200 shadow-xl">
        <div class="card-body">
            <h2 class="card-title justify-center text-2xl font-bold mb-4 text-primary">Register</h2>
            
            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-error mb-4">
                    <span>
                        <?php
                        switch($_GET['error']) {
                            case 'missing_fields':
                                echo 'Please fill in all fields';
                                break;
                            case 'email_exists':
                                echo 'Email already exists';
                                break;
                            case 'username_exists':
                                echo 'Username already exists';
                                break;
                            case 'registration_failed':
                                echo 'Registration failed. Please try again';
                                break;
                            default:
                                echo 'An error occurred';
                        }
                        ?>
                    </span>
                </div>
            <?php endif; ?>

            <form method="POST" action="/register" class="space-y-4">
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Username</span>
                    </label>
                    <input type="text" 
                           name="username" 
                           class="input input-bordered bg-base-100" 
                           required />
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Password</span>
                    </label>
                    <input type="password" 
                           name="password" 
                           class="input input-bordered bg-base-100" 
                           required />
                </div>

                <div class="form-control mt-6">
                    <button type="submit" class="btn btn-primary">Register</button>
                </div>

                <div class="text-center mt-4 text-base-content/80">
                    Already have an account? 
                    <a href="/login" class="link link-primary">Login</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html> 