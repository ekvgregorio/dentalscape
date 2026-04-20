<?php
session_start();

if (
    !isset($_SESSION['admin_access']) ||
    $_SESSION['admin_access'] !== true ||
    !isset($_SESSION['admin_expire']) ||
    time() > $_SESSION['admin_expire']
) {
    // Destroy all session data securely
    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    session_destroy();
    header("Location: /dentalscape/");
    exit;
}

// OPTIONAL: Extend session if still active (e.g., 30 mins from now)
$_SESSION['admin_expire'] = time() + (30 * 60); // extend 30 minutes

// Regenerate session ID occasionally for safety
if (!isset($_SESSION['last_regen']) || time() - $_SESSION['last_regen'] > 300) {
    session_regenerate_id(true);
    $_SESSION['last_regen'] = time();
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DentalScape Iloilo</title>
    <link href="../assets/admin/images/logo1.png" rel="icon">
    <link href="../assets/admin/images/logo1.png" rel="apple-touch-icon">
    <link href="../assets/admin/images/logo1.png" rel="icon">
    <link href="../assets/admin/images/logo.1png" rel="apple-touch-icon">
</head>
<body>

<style>
    * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
}

body {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 100vh;
    background: linear-gradient(135deg, #f0f4ff 0%, #e8f0ff 100%);
    padding: 20px; /* Added padding for small screens */
}

.container {
    background: white;
    padding: 40px;
    border-radius: 12px;
    box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
    width: 100%;
    max-width: 450px;
    text-align: center;
}

.lock-icon {
    width: 80px;
    height: 80px;
    margin-bottom: 20px;
    color: #2563eb;
}

h1 {
    color: #1a1f36;
    font-size: 24px;
    margin-bottom: 12px;
    font-family: 'Montserrat', sans-serif;
}

p {
    color: #6b7280;
    margin-bottom: 30px;
    line-height: 1.5;
    font-family: 'Montserrat', sans-serif;
}

.form-group {
    margin-bottom: 20px;
    text-align: left;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    color: #374151;
    font-size: 14px;
    font-weight: 500;
}

.form-group input {
    width: 100%;
    padding: 12px;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    font-size: 16px;
    transition: border-color 0.2s;
    font-family: 'Montserrat', sans-serif;
}

.form-group input:focus {
    outline: none;
    border-color: #2563eb;
    box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.1);
}

.submit-btn {
    background: #2563eb;
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: 6px;
    font-size: 16px;
    font-weight: 500;
    cursor: pointer;
    width: 100%;
    margin-bottom: 20px;
    transition: background-color 0.2s;
    font-family: 'Montserrat', sans-serif;
}

.submit-btn:hover {
    background: #1d4ed8;
}

.back-link {
    color: #6b7280;
    text-decoration: none;
    font-size: 14px;
    display: inline-block;
    font-family: 'Montserrat', sans-serif;
}

.back-link:hover {
    color: #2563eb;
}

.message {
    padding: 10px 15px;
    border-radius: 4px;
    margin: 10px 0;
    width: 100%;
    font-size: 14px;
    text-align: center;
}

.message.error {
    background-color: #ffe6e6;
    color: #dc3545;
    border: 1px solid #dc3545;
    font-family: 'Montserrat', sans-serif;
    font-size: 16px;
}

.message.success {
    background-color: #e6ffe6;
    color: #28a745;
    border: 1px solid #28a745;
    font-family: 'Montserrat', sans-serif;
}

label {
    font-size: 16px; 
    font-family: 'Montserrat', sans-serif;
}

/* Responsive adjustments */
@media (max-width: 600px) {
    .container {
        padding: 20px;
        margin: 10px;
    }
    
    .lock-icon {
        width: 60px;
        height: 60px;
        margin-bottom: 15px;
    }
    
    h1 {
        font-size: 20px;
        margin-bottom: 10px;
    }
    
    p {
        margin-bottom: 20px;
        font-size: 14px;
    }
    
    .form-group {
        margin-bottom: 15px;
    }
    
    .form-group label {
        font-size: 13px;
    }
    
    .form-group input {
        padding: 10px;
        font-size: 15px;
    }
    
    .submit-btn {
        padding: 10px 20px;
        font-size: 15px;
        margin-bottom: 15px;
    }
    
    .message {
        font-size: 13px;
        padding: 8px 12px;
    }
    
    .message.error,
    .message.success {
        font-size: 14px;
    }
    
    label {
        font-size: 14px;
    }
}
    </style>

<div class="container">
<img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%232563eb' width='80' height='80'%3E%3Cpath d='M12 12c2.21 0 4-1.79 4-4S14.21 4 12 4 8 5.79 8 8s1.79 4 4 4zm6 2h-1v-1a5 5 0 00-10 0v1H6a2 2 0 00-2 2v4a2 2 0 002 2h12a2 2 0 002-2v-4a2 2 0 00-2-2zm-6-4a3 3 0 013 3v1h-6v-1a3 3 0 013-3zm6 8H6v-4h12v4z'/%3E%3C/svg%3E" class="lock-icon" alt="Admin Login">
    <h1>Admin Login</h1>
    <p></p>
    <form method="POST" action="admin_login_process.php" autocomplete="off">
        <div class="form-group">
            <label for="email">Admin Email</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" required>
        </div>
        <input type="hidden" id="neighbourhood" name="neighbourhood">
        <input type="hidden" id="municipality" name="municipality">
        <input type="hidden" id="latitude" name="latitude">
        <input type="hidden" id="longitude" name="longitude">
        <button type="submit" name="admin_login" class="submit-btn">Login</button>
    </form>
    <?php if (isset($_SESSION['error'])): ?>
        <p class="message error"><?= $_SESSION['error']; unset($_SESSION['error']); ?></p>
    <?php endif; ?>
</div>

<script>
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function(position) {
      document.getElementById("latitude").value = position.coords.latitude;
      document.getElementById("longitude").value = position.coords.longitude;
    }, function(error) {
      console.warn("Location access denied or unavailable");
    });
  }
</script>

<script src="../assets/js/security.js"></script>
<script>
function getLocationAndSendToPython() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (position) {
            const lat = position.coords.latitude;
            const lon = position.coords.longitude;

            console.log("Latitude:", lat, "Longitude:", lon);

            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lon;

            fetch('http://localhost:5002/get_location', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({ latitude: lat, longitude: lon })
            })
            .then(response => response.json())
            .then(data => {
                console.log("Location response:", data);

                // Set fields if data exists
                document.getElementById('neighbourhood').value = data.neighbourhood || '';
                document.getElementById('municipality').value = data.municipality || '';
            })
            .catch(err => {
                console.error("Fetch error:", err);
            });

        }, function (error) {
            console.warn("Geolocation error:", error.message);
            // no alert, just console warning
        });
    } else {
        console.warn("Your browser does not support geolocation.");
    }
}

document.addEventListener('DOMContentLoaded', getLocationAndSendToPython);
</script>

</body>
</html>
