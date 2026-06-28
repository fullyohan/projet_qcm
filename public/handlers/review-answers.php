<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    exit(json_encode(['status' => 'error', 'message' => 'Unauthorized.']));
}

require_once '../../config/db.php'; 
$user_id = $_SESSION['user_id'];

$data = json_decode(file_get_contents('php://input'), true);

if (empty($data['module']) || empty($data['question_ids']) || !is_array($data['question_ids'])) {
    http_response_code(400);
    exit(json_encode(['status' => 'error', 'message' => 'Incomplete exam data.']));
}

$module = $data['module'];
$exam_question_ids = $data['question_ids'];
$user_responses = $data['responses'] ?? [];

$placeholders = implode(',', array_fill(0, count($exam_question_ids), '?'));
$sql = "SELECT id, correct_answer FROM questions WHERE id IN ($placeholders) AND module = ?";
$stmt = $db->prepare($sql);
$types = str_repeat('i', count($exam_question_ids)) . 's';
$params = array_merge($exam_question_ids, [$module]);

$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

$correct_count = 0;
$total_questions = $result->num_rows;
$detailed_results = [];

while ($row = $result->fetch_assoc()) {
    $q_id = $row['id'];
    $real_answer = (int)$row['correct_answer'];
    $user_answer = isset($user_responses[$q_id]) ? (int)$user_responses[$q_id] : 0;
    
    $is_correct = ($user_answer !== 0 && $user_answer === $real_answer) ? 1 : 0;
    if ($is_correct) $correct_count++;

    $detailed_results[] = [
        'question_id' => $q_id,
        'user_answer' => $user_answer,
        'is_correct'  => $is_correct
    ];
}
$final_score = ($total_questions > 0) ? ($correct_count / $total_questions) * 20 : 0;

$db->begin_transaction();

$stmt_attempt = $db->prepare("INSERT INTO attempts (user_id, score, date) VALUES (?, ?, NOW())");
$stmt_attempt->bind_param("id", $user_id, $final_score);
$stmt_attempt->execute();
$attempt_id = $db->insert_id; 

$stmt_answer = $db->prepare("INSERT INTO answers (attempt_id, question_id, user_answer, is_correct) VALUES (?, ?, ?, ?)");

foreach ($detailed_results as $res) {
    $stmt_answer->bind_param("iiii", $attempt_id, $res['question_id'], $res['user_answer'], $res['is_correct']);
    $stmt_answer->execute();
}

$db->commit();

echo json_encode([
    'status'     => 'success',
    'attempt_id' => $attempt_id,
]);

