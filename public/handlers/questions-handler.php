<?php
session_start();
require_once '../security/auth-guard.php';
require_auth();
require_once '../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    switch ($action) {
        case 'add':
            $module = trim($_POST['module'] ?? '');
            $question_text = trim($_POST['question'] ?? '');
            $answer1 = trim($_POST['option_a'] ?? '');
            $answer2 = trim($_POST['option_b'] ?? '');
            $answer3 = trim($_POST['option_c'] ?? '');
            $answer4 = trim($_POST['option_d'] ?? '');

            $vector_map = ['A' => 1, 'B' => 2, 'C' => 3, 'D' => 4];
            $correct_answer = $vector_map[$_POST['correct_vector'] ?? 'A'] ?? 1;

            if (!empty($module) && !empty($question_text)) {
                $stmt = $db->prepare("INSERT INTO questions (module, question, answer1, answer2, answer3, answer4, correct_answer) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssssi", $module, $question_text, $answer1, $answer2, $answer3, $answer4, $correct_answer);
                $stmt->execute();
            };
            $_SESSION['alert'] = [
                'type'=>'success',
                'message' => 'Question ajoutee avec succes'
            ];
            break;

        case 'edit':
            $id = (int)($_POST['id'] ?? 0);
            $module = trim($_POST['module'] ?? '');
            $question_text = trim($_POST['question'] ?? '');
            $answer1 = trim($_POST['option_a'] ?? '');
            $answer2 = trim($_POST['option_b'] ?? '');
            $answer3 = trim($_POST['option_c'] ?? '');
            $answer4 = trim($_POST['option_d'] ?? '');

            $vector_map = ['A' => 1, 'B' => 2, 'C' => 3, 'D' => 4];
            $correct_answer = $vector_map[$_POST['correct_vector'] ?? 'A'] ?? 1;

            if ($id > 0 && !empty($module) && !empty($question_text)) {
                $stmt = $db->prepare("UPDATE questions SET module = ?, question = ?, answer1 = ?, answer2 = ?, answer3 = ?, answer4 = ?, correct_answer = ? WHERE id = ?");
                $stmt->bind_param("ssssssii", $module, $question_text, $answer1, $answer2, $answer3, $answer4, $correct_answer, $id);
                $stmt->execute();
            }
            $_SESSION['alert'] = [
                'type'=>'success',
                'message' => 'Question modifiee avec succes'
            ];
            break;

        case 'delete':
            $id = (int)($_POST['id'] ?? 0);
            if ($id > 0) {
                $stmt = $db->prepare("DELETE FROM questions WHERE id = ?");
                $stmt->bind_param("i", $id);
                $stmt->execute();
            }
            $_SESSION['alert'] = [
                'type'=>'success',
                'message' => 'Question supprimee avec succes'
            ];
            break;
    }
}

header("Location: /admin/manage-questions.php");
exit();