<?php

class Database
{
    private $host;
    private $username;
    private $password;
    private $database;
    private $pdo;

    public function __construct($host, $username, $password, $database)
    {
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
        $this->database = $database;
    }

    public function connect()
    {
        $dsn = "mysql:host=$this->host;dbname=$this->database";
        $this->pdo = new PDO($dsn, $this->username, $this->password);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function disconnect()
    {
        $this->pdo = null;
    }

    public function getSelectedCourses($nim)
    {
        $query = "SELECT course.* FROM course INNER JOIN student_course ON course.id = student_course.course_id WHERE student_course.student_id = :nim";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':nim', $nim, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

class CoursePage
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function renderSelectedCourses($nim)
    {
        $this->database->connect();

        $selectedCourses = $this->database->getSelectedCourses($nim);

        echo "<h2>NIM: $nim</h2>";
        echo "<table>";
        echo "<tr><th>ID</th><th>Course Name</th><th>Credit Hours</th><th>Select</th></tr>";

        foreach ($selectedCourses as $course) {
            $id = $course['id'];
            $courseName = $course['course_name'];
            $creditHours = $course['credit_hours'];

            echo "<tr>";
            echo "<td>$id</td>";
            echo "<td>$courseName</td>";
            echo "<td>$creditHours</td>";
            echo "<td><input type='checkbox' name='selectedCourses[]' value='$id'></td>"; // Checkbox for course selection
            echo "</tr>";
        }

        echo "</table>";
        echo '<button type="submit">Submit</button>';
        echo "</form><br>";

        echo "<a href='students.php'>&larr; Back to Students</a>";

        $this->database->disconnect();
    }
}

// koneksi database
$host = 'localhost';
$username = 'root';
$password = 'samuel123';
$database = 'card';

// Create a new instance of the Database class
$databaseObj = new Database($host, $username, $password, $database);

// Create a new instance of the CoursePage class
$coursePage = new CoursePage($databaseObj);

// Get the student ID (NIM) from the query string
if (isset($_GET['student_id'])) {
    $nim = $_GET['student_id'];

    // Render the selected courses for the student
    $coursePage->renderSelectedCourses($nim);
}
?>
