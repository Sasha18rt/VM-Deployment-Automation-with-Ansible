<!-- Файл: process_fill_patient_card.php -->
<?php
$mysqli = new mysqli('localhost', 'root', '', 'remote-med');

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $p_id = $_POST["p_id"];
    $allergies = $_POST["allergies"];
    $current_med = $_POST["current_med"];
    $medical_history = $_POST["medical_history"];

    // Перевірте, чи існує картка паціента
    $sql_check_card = "SELECT * FROM patient_card WHERE p_id = ?";
    $stmt_check_card = $mysqli->prepare($sql_check_card);
    $stmt_check_card->bind_param("i", $p_id);
    $stmt_check_card->execute();
    $result_check_card = $stmt_check_card->get_result();
    $patient_card_exists = $result_check_card->num_rows > 0;
    $stmt_check_card->close();

    if ($patient_card_exists) {
        // Оновіть інформацію у картці паціента
        $sql_update_card = "UPDATE patient_card SET Allergies = ?, Current_med = ?, Medical_history = ? WHERE p_id = ?";
        $stmt_update_card = $mysqli->prepare($sql_update_card);
        $stmt_update_card->bind_param("sssi", $allergies, $current_med, $medical_history, $p_id);
        $stmt_update_card->execute();
        $stmt_update_card->close();
    } else {
        // Якщо картки не існує, вставте новий запис
        $sql_insert_card = "INSERT INTO patient_card (p_id, Allergies, Current_med, Medical_history) VALUES (?, ?, ?, ?)";
        $stmt_insert_card = $mysqli->prepare($sql_insert_card);
        $stmt_insert_card->bind_param("isss", $p_id, $allergies, $current_med, $medical_history);
        $stmt_insert_card->execute();
        $stmt_insert_card->close();
    }
}

$mysqli->close();
header("Location: /MyAccount.php"); // Перенаправлення на основну сторінку облікового запису
exit();
?>
