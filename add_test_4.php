<?php 
include 'config2.php'; // MySQL ulanish fayli

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $question = $_POST['question'];
    $option_a = $_POST['option_a'];
    $option_b = $_POST['option_b'];
    $option_c = $_POST['option_c'];
    $option_d = $_POST['option_d'];
    $correct_answers = isset($_POST['correct_answers']) ? implode(',', $_POST['correct_answers']) : ''; // To‘g‘ri javoblar

    // Fayl yuklashga ruxsat berilgan formatlar
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

    // Savol uchun rasm yuklash
    $question_image_path = null;
    if (!empty($_FILES['question_image']['name']) && in_array($_FILES['question_image']['type'], $allowed_types)) {
        $question_image_name = basename($_FILES['question_image']['name']);
        $question_image_tmp = $_FILES['question_image']['tmp_name'];
        $question_image_path = "uploads/" . $question_image_name;
        move_uploaded_file($question_image_tmp, $question_image_path);
    }

    // Variantlar uchun rasmlarni yuklash
    $options_images = ['a' => null, 'b' => null, 'c' => null, 'd' => null];
    foreach (['a', 'b', 'c', 'd'] as $option) {
        $file_key = "option_{$option}_image";
        if (!empty($_FILES[$file_key]['name']) && in_array($_FILES[$file_key]['type'], $allowed_types)) {
            $image_name = basename($_FILES[$file_key]['name']);
            $image_tmp = $_FILES[$file_key]['tmp_name'];
            $image_path = "uploads/" . $image_name;
            move_uploaded_file($image_tmp, $image_path);
            $options_images[$option] = $image_path;
        }
    }

    // Ma'lumotni bazaga yozish
    $sql = "INSERT INTO tests4 (question, option_a, option_b, option_c, option_d, correct_answers, image_path, option_a_image, option_b_image, option_c_image, option_d_image) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("<div class='error'>MySQL tayyorgarlik xatosi: " . $conn->error . "</div>");
    }

    $stmt->bind_param(
        "sssssssssss", 
        $question, $option_a, $option_b, $option_c, $option_d, $correct_answers, 
        $question_image_path, $options_images['a'], $options_images['b'], $options_images['c'], $options_images['d']
    );

    if ($stmt->execute()) {
        echo "<div class='success'>Test savoli muvaffaqiyatli qo‘shildi!</div>";
    } else {
        echo "<div class='error'>Xatolik yuz berdi: " . $stmt->error . "</div>";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Savollari Qo‘shish</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            width: 400px;
            text-align: center;
        }
        h2 {
            color: #333;
        }
        input[type="text"], input[type="file"] {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        input[type="submit"] {
            background: #28a745;
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            width: 100%;
            border-radius: 5px;
            font-size: 16px;
        }
        input[type="submit"]:hover {
            background: #218838;
        }
        .success {
            background: #d4edda;
            color: #155724;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Test Savolini Qo‘shish</h2>
        <form method="post" enctype="multipart/form-data">
            <label for="question">Savol:</label>
            <input type="text" name="question" >
            
            <label for="question_image">Savol uchun rasm yuklash:</label>
            <input type="file" name="question_image" accept="image/*">
            
            <label for="option_a">Variant A:</label>
            <input type="text" name="option_a" >
            <input type="file" name="option_a_image" accept="image/*">
            
            <label for="option_b">Variant B:</label>
            <input type="text" name="option_b" >
            <input type="file" name="option_b_image" accept="image/*">
            
            <label for="option_c">Variant C:</label>
            <input type="text" name="option_c" >
            <input type="file" name="option_c_image" accept="image/*">
            
            <label for="option_d">Variant D:</label>
            <input type="text" name="option_d" >
            <input type="file" name="option_d_image" accept="image/*">
            
            <label>To‘g‘ri javob(lar):</label><br>
            <input type="checkbox" name="correct_answers[]" value="A"> A
            <input type="checkbox" name="correct_answers[]" value="B"> B
            <input type="checkbox" name="correct_answers[]" value="C"> C
            <input type="checkbox" name="correct_answers[]" value="D"> D
            <br><br>
            
            <input type="submit" value="Qo‘shish">
        </form>
    </div>
</body>
</html>
