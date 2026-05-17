<?php
   include 'config.php';
   session_start();

   if ($_SERVER["REQUEST_METHOD"] == "POST") {
       $username = $_POST['username'];
       $password = $_POST['password'];

       $sql = "SELECT * FROM admins WHERE username='$username' AND password='$password'";
       $result = $conn->query($sql);
       
       if ($result->num_rows > 0) {
           $_SESSION['admin'] = $username;
           $_SESSION['id'] = $password;
           header("Location: dashboard.php");
           exit();
       } else {
           echo "<p style='color: red; text-align:center;'>Noto'g'ri login yoki parol!</p>";
       }
   }
?>
<!DOCTYPE html>
<html lang="uz">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1"> 
   <link rel="stylesheet" type="text/css" href="chiroy.css">
<style>
    * {
        box-sizing: border-box; /* input va button joyidan chiqib ketmasligi uchun */
    }
    body {
        font-family: Arial, sans-serif;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
        background: #f4f4f4;
    }
    .container {
        width: 90%;
        max-width: 350px;
        background: #fff;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        text-align: center;
    }
    img.logo {
        width: 320px;
        margin-bottom: 15px;
    }
    h2 {
        margin-bottom: 20px;
        font-size: 22px;
        color: #333;
    }
    input {
        width: 100%;
        padding: 12px;
        margin: 8px 0;
        border: 1px solid #ccc;
        border-radius: 8px;
        font-size: 16px;
        display: block;
    }
    button {
        width: 100%;
        padding: 14px;
        margin-top: 10px;
        background: #007bff;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        cursor: pointer;
    }
    button:hover {
        background: #0056b3;
    }
</style>

</head>
<body>
   <div class="container">
       <img src="images/logo.jpg" alt="Logo" class="logo">
       <h2>Shaxsiy kabinet</h2>
       <form method="POST">
           <input type="text" name="username" placeholder="Login" required>
           <input type="password" name="password" placeholder="Parol" required>
           <button type="submit">Kirish</button>
       </form>
   </div>
</body>
</html>
