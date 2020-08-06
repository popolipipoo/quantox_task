<?php

// NOTE: Entry point example: domain-of-your-app.test/students/<ID>
$requestURL = isset($_SERVER['REQUEST_URI']) ? parse_url($_SERVER['REQUEST_URI']) : null;
$requestPath = empty($requestURL['path']) ? '/' : $requestURL['path'];
$request = explode('/', ltrim($requestPath, '/'));

$student_id = isset($request[2]) ? intval($request[2]) : null;
if (!isset($student_id)) {
	// NOTE: Entry point example: domain-of-your-app.test?student=<ID>
	$student_id = isset($_GET['student']) ? intval($_GET['student']) : null;
}
if (empty($student_id)) {
	die('Please provide a student id.');
}

require_once __DIR__ . '/classes/Student.php';

try {
	$student = new Student($student_id);
} catch (Exception $e) {
	die("Error fetching student with id #{$student_id}: " . $e->getMessage());
}

$preparedResult = $student->prepareResult();
header($preparedResult['header']);
echo $preparedResult['data'];
