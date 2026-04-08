<?php
session_start();
include("../includes/db_conn.php"); 

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$message = "";
$toastClass = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die("CSRF token validation failed");
}

    $checkEmailStmt = $conn->prepare("SELECT Kupac_ID FROM kupac WHERE Email = ?");
    $checkEmailStmt->bind_param("s", $email);
    $checkEmailStmt->execute();
    $checkEmailStmt->store_result();

    if ($checkEmailStmt->num_rows > 0) {
        $message = "Email već postoji";
        $toastClass = "#007bff";
    } else {
        $stmt = $conn->prepare("INSERT INTO kupac (Username, Email, Lozinka) VALUES (?, ?, ?)");
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $stmt->bind_param("sss", $username, $email, $hashed_password);

        if ($stmt->execute()) {
            $user_id = $stmt->insert_id; 
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $username;
            $_SESSION['email'] = $email;
            header("Location: ../utils/step-form.php"); 
            exit();
        } else {
            $message = "Greška: " . $stmt->error;
            $toastClass = "#dc3545"; 
        }

        $stmt->close();
    }

    $checkEmailStmt->close();
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="hr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="shortcut icon" href="https://cdn-icons-png.flaticon.com/512/295/295128.png">
    <title>Registracija | TechShop</title>
    <style>
        :root {
            --primary-color: #4e73df;
            --secondary-color: #f8f9fc;
            --accent-color: #2e59d9;
            --text-color: #5a5c69;
            --success-color: #1cc88a;
        }
        
        body {
            background-color:rgb(243, 243, 243);
            font-family: 'Nunito', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            color: var(--text-color);
            
        }
        
        .register-container {
            max-width: 450px;
            margin: 5rem auto;
            animation: fadeIn 0.5s ease-in-out;
        }
        
        .register-card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            overflow: hidden;
        }
        
        .card-header {
            background: linear-gradient(135deg, var(--success-color) 0%, #17a673 100%);
            color: white;
            text-align: center;
            padding: 1.5rem;
            border-bottom: none;
        }
        
        .card-body {
            padding: 2rem;
            background-color:rgb(255, 255, 255);
        }
        
        .form-control {
            padding: 0.75rem 1rem;
            border-radius: 0.35rem;
            border: 1px solid #d1d3e2;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        }
        
        .btn-register {
            background-color: var(--success-color);
            border: none;
            width: 100%;
            padding: 0.75rem;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .btn-register:hover {
            background-color: #17a673;
            transform: translateY(-2px);
        }
        
        .register-icon {
            font-size: 5rem;
            margin-bottom: 1rem;
            color: white;
        }
        
        .links a {
            color: var(--primary-color);
            text-decoration: none;
            transition: all 0.2s;
        }
        
        .links a:hover {
            color: var(--accent-color);
            text-decoration: underline;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .input-group-text {
            background-color: #f8f9fc;
            border: 1px solid #d1d3e2;
        }
        
        .password-strength {
            height: 5px;
            margin-top: 5px;
            background: #eee;
            border-radius: 3px;
            overflow: hidden;
        }
        
        .password-strength-bar {
            height: 100%;
            width: 0;
            transition: width 0.3s;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="register-container">
            <div class="card register-card">
                <div class="card-header">
                    <i class="fas fa-user-plus register-icon"></i>
                    <h3 class="mb-0">Kreirajte svoj račun</h3>
                </div>
                <div class="card-body">
                    <?php if ($message): ?>
                    <div class="alert <?php echo $toastClass === '#007bff' ? 'alert-primary' : 'alert-danger'; ?> alert-dismissible fade show" role="alert">
                        <?php echo $message; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php endif; ?>
                    
                    <form method="post" id="registerForm">
                        <div class="mb-4">
                            <label for="username" class="form-label fw-bold">Korisničko ime</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <input type="text" name="username" id="username" class="form-control" placeholder="Unesite korisničko ime" required>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="email" class="form-label fw-bold">Email adresa</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                <input type="email" name="email" id="email" class="form-control" placeholder="Unesite e-mail adresu:" required>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="password" class="form-label fw-bold">Lozinka</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" name="password" id="password" class="form-control" placeholder="Unesite lozinku" required>
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <div class="password-strength mt-2">
                                <div class="password-strength-bar" id="passwordStrengthBar"></div>
                            </div>
                            <small class="text-muted">Lozinka mora imati najmanje 8 znakova</small>
                        </div>
                        
                        <button type="submit" class="btn btn-success btn-register btn-block mb-4">
                            <i class="fas fa-user-plus me-2"></i> Registriraj se
                        </button>
                        
                        <div class="text-center links">
                            Već imate račun? <a href="./login.php">Prijavite se</a>
                        </div>
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const icon = this.querySelector('i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        });

        document.getElementById('password').addEventListener('input', function() {
            const strengthBar = document.getElementById('passwordStrengthBar');
            const strength = calculatePasswordStrength(this.value);
            
            strengthBar.style.width = strength.percentage + '%';
            strengthBar.style.backgroundColor = strength.color;
        });

        function calculatePasswordStrength(password) {
            let strength = 0;
            
            if (password.length > 7) strength += 1;
            if (password.length > 11) strength += 1;
            
            if (/[A-Z]/.test(password)) strength += 1;
            if (/[0-9]/.test(password)) strength += 1;
            if (/[^A-Za-z0-9]/.test(password)) strength += 1;
            
            const percentage = Math.min(strength * 25, 100);
            
            let color;
            if (percentage < 40) color = '#dc3545'; 
            else if (percentage < 70) color = '#fd7e14'; 
            else if (percentage < 90) color = '#ffc107'; 
            else color = '#28a745'; 
            
            return { percentage, color };
        }

        document.addEventListener('DOMContentLoaded', function() {
            var alertEl = document.querySelector('.alert');
            if (alertEl) {
                var alert = new bootstrap.Alert(alertEl);
                setTimeout(function() {
                    alert.close();
                }, 5000);
            }
        });
    </script>
</body>

</html>