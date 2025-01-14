<?php partial('head'); ?>
<body class="min-h-screen bg-base-300 flex items-center justify-center">
    <div class="card w-96 bg-base-200 shadow-xl">
        <div class="card-body">
            <h2 class="card-title justify-center text-2xl font-bold mb-4 text-primary">Login</h2>
            
            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-error mb-4">
                    <span>
                        <?php
                        switch($_GET['error']) {
                            case 'invalid_credentials':
                                echo 'Invalid email or password';
                                break;
                            case 'missing_fields':
                                echo 'Please fill in all fields';
                                break;
                            default:
                                echo 'An error occurred';
                        }
                        ?>
                    </span>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['success']) && $_GET['success'] === 'registered'): ?>
                <div class="alert alert-success mb-4">
                    <span>Registration successful! Please login.</span>
                </div>
            <?php endif; ?>

            <form method="POST" action="login" class="space-y-4">
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
                    <button type="submit" class="btn btn-primary">Login</button>
                </div>

                <div class="text-center mt-4 text-base-content/80">
                    Don't have an account? 
                    <a href="register" class="link link-primary">Register</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html> 
