<?php

class Student
{
	private $id;
	private $name;
	private $grades;
	private $school_board;

	public function __construct(int $id)
	{
		require_once __DIR__ . '/../db/DB.php';
		$db = new DB();

		$student = $db->fetch("SELECT * FROM students WHERE id = {$id};");
		if (empty($student)) {
			throw new Exception('Student does not exist');
		}

		// NOTE: Saving only grades values
		$student_grades = array_map(function ($row) {
			return intval($row['grade']);
		}, $db->fetchAll("SELECT grade FROM student_grades WHERE student_id = {$id};"));

		// NOTE: Student must attend a school board
		$school_board = $db->fetch("SELECT sb.name FROM school_boards sb "
			. "INNER JOIN school_board_student sbs "
			. "ON sbs.school_board_id = sb.id AND sbs.student_id = {$id};");
		if (empty($school_board)) {
			throw new Exception('Student does not belong to any school board');
		}

		// NOTE: Set values
		$this->setId($id);
		$this->setName($student['name']);
		$this->setGrades($student_grades);
		$this->setSchoolBoard($school_board['name']);
	}

	/**
	 * Return average grade for the student
	 *
	 * @return integer
	 */
	public function getAvgGrade()
	{
		return array_sum($this->getGrades()) / count($this->getGrades());
	}

	/**
	 * Check if student has passing grades
	 *
	 * @return boolean
	 */
	public function getFinalResult()
	{
		switch ($this->getSchoolBoard()) {
			case 'CSM':
				// CSM considers pass if the average is bigger or equal to 7 and fail otherwise.
				return $this->getAvgGrade() >= 7;
			case 'CSMB':
				// CSMB discards the lowest grade, if you have more than 2 grades, and considers pass if
				// his biggest grade is bigger than 8.
				$grades = $this->getGrades();
				if (count($grades) > 2) {
					unset($grades[array_search(min($grades), $grades)]);
				}
				return max($grades) > 8;
			default:
				// NOTE: School board is not implemented
				return null;
		}
	}

	/**
	 * Prepare result by school board standards
	 *
	 * @return Array
	 */
	public function prepareResult()
	{
		$final_result = $this->getFinalResult();
		$final_result = isset($final_result) ? ($final_result ? 'Pass' : 'Fail') : 'Undefined';

		$result = [
			'Student_ID' => $this->getId(),
			'Name' => $this->getName(),
			'Grades' => $this->getGrades(),
			'Average_Grade' => $this->getAvgGrade(),
			'Final_Result' => $final_result,
		];

		switch ($this->getSchoolBoard()) {
			case 'CSM':
				// JSON
				return [
					'header' => 'Content-Type: application/json',
					'data' => json_encode($result, JSON_PRETTY_PRINT + JSON_UNESCAPED_UNICODE),
				];
			case 'CSMB':
				// XML
				$xml = new SimpleXMLElement('<?xml version="1.0"?><Student></Student>');
				foreach ($result as $key => $value) {
					if (is_array($value)) {
						$subnode = $xml->addChild($key);
						foreach ($value as $row) {
							$subnode->addChild('Grade', $row);
						}
					} else {
						$xml->addChild($key, $value);
					}
				}
				return [
					'header' => 'Content-Type: text/xml',
					'data' => $xml->asXML(),
				];
			default:
				// NOTE: School board is not implemented
				return null;
		}
	}

	/**
	 * @return integer
	 */
	public function getId()
	{
		return intval($this->id);
	}

	/**
	 * @return String
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @return Array
	 */
	public function getGrades()
	{
		return $this->grades;
	}

	/**
	 * @return String
	 */
	public function getSchoolBoard()
	{
		return $this->school_board;
	}

	/**
	 * @param integer $id
	 */
	public function setId(int $id)
	{
		$this->id = $id;
	}

	/**
	 * @param String $name
	 */
	public function setName($name)
	{
		$this->name = $name;
	}

	/**
	 * @param String $school_board
	 */
	public function setSchoolBoard($school_board)
	{
		$this->school_board = $school_board;
	}

	/**
	 * @param Array $grades
	 */
	public function setGrades(array $grades)
	{
		$this->grades = $grades;
	}

}
