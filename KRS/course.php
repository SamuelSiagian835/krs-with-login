<style>
    /* Styling for the table */
    table {
        width: 100%;
        border-collapse: collapse;
    }

    th, td {
        padding: 10px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    th {
        background-color: #f2f2f2;
    }

    /* Styling for links */
    a {
        text-decoration: none;
        color: #337ab7;
    }

    a:hover {
        text-decoration: underline;
    }

    /* Styling for the body */
    body {
        font-family: Arial, sans-serif;
        margin: 0px;
    }
</style>

 
<?php

// Database configuration
$host = 'localhost';
$username = 'root';
$password = 'samuel123';
$database = 'card';

// Create a new PDO instance
$dsn = "mysql:host=$host;dbname=$database";
$pdo = new PDO($dsn, $username, $password);

// Process selected courses
if (isset($_POST['selectedCourses'])) {
    $selectedCourses = $_POST['selectedCourses'];
    
    // Get the student_id from another PHP file (assuming the file is named "other_file.php")
    $student_id = ''; // Initialize student_id variable
    if (isset($_GET['student_id'])) {
        $student_id = $_GET['student_id'];
    } else if (isset($_POST['student_id'])) {
        $student_id = $_POST['student_id'];
    } else {
        // Handle the case when student_id is not provided
        echo "student_id is not provided.";
        exit();
    }

    // Check if the selected courses already exist for the given student_id
    $existingCoursesQuery = "SELECT course_id FROM student_course WHERE student_id = :student_id";
    $existingCoursesStmt = $pdo->prepare($existingCoursesQuery);
    $existingCoursesStmt->bindParam(':student_id', $student_id);
    $existingCoursesStmt->execute();
    $existingCourses = $existingCoursesStmt->fetchAll(PDO::FETCH_COLUMN);
    

    // Remove duplicate courses from the selected courses
    $selectedCourses = array_unique($selectedCourses);

    // Check which courses need to be inserted or deleted
    $coursesToInsert = array_diff($selectedCourses, $existingCourses);
    $coursesToDelete = array_diff($existingCourses, $selectedCourses);

    // Insert new courses
    if (!empty($coursesToInsert)) {
        $insertQuery = "INSERT INTO student_course (student_id, course_id) VALUES ";
        $insertValues = [];

        foreach ($coursesToInsert as $courseId) {     
            $insertValues[] = "('$student_id', '$courseId')";
        }

        $insertQuery .= implode(',', $insertValues);
        $insertStmt = $pdo->prepare($insertQuery);
        $insertStmt->execute();
    }

    // Delete courses
    if (!empty($coursesToDelete)) {
        $deleteQuery = "DELETE FROM student_course WHERE student_id = :student_id AND course_id IN (";
        $deleteQuery .= implode(',', $coursesToDelete);
        $deleteQuery .= ")";

        $deleteStmt = $pdo->prepare($deleteQuery);
        $deleteStmt->bindParam(':student_id', $student_id);
        $deleteStmt->execute();
    }

    // Redirect to view.php with the student_id parameter
    header("Location: view.php?nim=$student_id");
    exit();
}

// Display course data in a table

$student_id = $_GET['student_id'];
echo '<div class="min-h-full">
<nav class="bg-gray-800">
  <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
    <div class="flex h-16 items-center justify-between">
      <div class="flex items-center">
        <div class="flex-shrink-0">
          <img class="h-8 w-8" src="https://tailwindui.com/img/logos/mark.svg?color=indigo&shade=500" alt="Your Company">
          <script src="https://cdn.tailwindcss.com"></script>
        </div>
        <div class="hidden md:block">
          <div class="ml-10 flex items-baseline space-x-4">
            <!-- Current: "bg-gray-900 text-white", Default: "text-gray-300 hover:bg-gray-700 hover:text-white" -->
            <a href="#" class="bg-gray-900 text-white rounded-md px-3 py-2 text-sm font-medium" aria-current="page">Dashboard</a>
            <a href="students.php" class="text-gray-300 hover:bg-gray-700 hover:text-white rounded-md px-3 py-2 text-sm font-medium">Students</a>
            <a href="courselist.php" class="text-gray-300 hover:bg-gray-700 hover:text-white rounded-md px-3 py-2 text-sm font-medium">Course</a>
          </div>
        </div>
      </div>
      <div class="hidden md:block">
        <div class="ml-4 flex items-center md:ml-6">
          <button type="button" class="rounded-full bg-gray-800 p-1 text-gray-400 hover:text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800">
            <span class="sr-only">View notifications</span>
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
            </svg>
          </button>

          <!-- Profile dropdown -->
          <div class="relative ml-3">
            <div>
              <button type="button" class="flex max-w-xs items-center rounded-full bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800" id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                <span class="sr-only">Open user menu</span>
                <img class="h-8 w-8 rounded-full" src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="">
              </button>
            </div>

            <!--
              Dropdown menu, show/hide based on menu state.

              Entering: "transition ease-out duration-100"
                From: "transform opacity-0 scale-95"
                To: "transform opacity-100 scale-100"
              Leaving: "transition ease-in duration-75"
                From: "transform opacity-100 scale-100"
                To: "transform opacity-0 scale-95"
            -->
            <div class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1">
              <!-- Active: "bg-gray-100", Not Active: "" -->
              <a href="#" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" id="user-menu-item-0">Your Profile</a>
              <a href="#" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" id="user-menu-item-1">Settings</a>
              <a href="#" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" id="user-menu-item-2">Sign out</a>
            </div>
          </div>
        </div>
      </div>
      <div class="-mr-2 flex md:hidden">
        <!-- Mobile menu button -->
        <button type="button" class="inline-flex items-center justify-center rounded-md bg-gray-800 p-2 text-gray-400 hover:bg-gray-700 hover:text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800" aria-controls="mobile-menu" aria-expanded="false">
          <span class="sr-only">Open main menu</span>
          <!-- Menu open: "hidden", Menu closed: "block" -->
          <svg class="block h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
          </svg>
          <!-- Menu open: "block", Menu closed: "hidden" -->
          <svg class="hidden h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
    </div>
  </div>

  <!-- Mobile menu, show/hide based on menu state. -->
  <div class="md:hidden" id="mobile-menu">
    <div class="space-y-1 px-2 pb-3 pt-2 sm:px-3">
      <!-- Current: "bg-gray-900 text-white", Default: "text-gray-300 hover:bg-gray-700 hover:text-white" -->
      <a href="#" class="bg-gray-900 text-white block rounded-md px-3 py-2 text-base font-medium" aria-current="page">Dashboard</a>
      <a href="students.php" class="text-gray-300 hover:bg-gray-700 hover:text-white rounded-md px-3 py-2 text-sm font-medium">Students</a>
      <a href="courselist.php" class="text-gray-300 hover:bg-gray-700 hover:text-white rounded-md px-3 py-2 text-sm font-medium">Course</a>
    </div>
    <div class="border-t border-gray-700 pb-3 pt-4">
      <div class="flex items-center px-5">
        <div class="flex-shrink-0">
          <img class="h-10 w-10 rounded-full" src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="">
        </div>
        <div class="ml-3">
          <div class="text-base font-medium leading-none text-white">Tom Cook</div>
          <div class="text-sm font-medium leading-none text-gray-400">tom@example.com</div>
        </div>
        
        <button type="button" class="ml-auto flex-shrink-0 rounded-full bg-gray-800 p-1 text-gray-400 hover:text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800">
          <span class="sr-only">View notifications</span>
          <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
          </svg>
        </button>
      </div>
      <div class="mt-3 space-y-1 px-2">
        <a href="#" class="block rounded-md px-3 py-2 text-base font-medium text-gray-400 hover:bg-gray-700 hover:text-white">Your Profile</a>
        <a href="#" class="block rounded-md px-3 py-2 text-base font-medium text-gray-400 hover:bg-gray-700 hover:text-white">Settings</a>
        <a href="#" class="block rounded-md px-3 py-2 text-base font-medium text-gray-400 hover:bg-gray-700 hover:text-white">Sign out</a>
      </div>
    </div>
  </div>
</nav>

<div class="bg-white shadow">
  <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold tracking-tight text-gray-900">Selected Courses
      
    </h1>
  </div>
</div>
<main>
  <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">';
echo "<form method='post' action='course.php?student_id=$student_id'>"; // Add the student_id parameter to the form action
echo "<table>";
echo "<tr><th>ID</th><th>Course Name</th><th>Credit Hours</th><th>Select</th></tr>";

// Fetch all courses from the database
$query = "SELECT * FROM course";
$stmt = $pdo->query($query);

// Get the selected courses for the given student_id
$selectedCoursesQuery = "SELECT course_id FROM student_course WHERE student_id = :student_id";
$selectedCoursesStmt = $pdo->prepare($selectedCoursesQuery);
$selectedCoursesStmt->bindParam(':student_id', $student_id);
$selectedCoursesStmt->execute();
$selectedCourses = $selectedCoursesStmt->fetchAll(PDO::FETCH_COLUMN);

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $id = $row['id'];
    $courseName = $row['course_name'];
    $creditHours = $row['credit_hours'];

    $isChecked = in_array($id, $selectedCourses) ? 'checked' : '';

    echo "<tr>";
    echo "<td>$id</td>";
    echo "<td>$courseName</td>";
    echo "<td>$creditHours</td>";
    echo "<td><input type='checkbox' name='selectedCourses[]' value='$id' $isChecked></td>"; // Checkbox for course selection
    echo "</tr>";
}

echo "</table>";
echo '<button type="submit">Submit</button>';
echo "</form>";

echo "<a href='students.php'>&larr; Back to Students</a>";

// Close the database connection
$pdo = null;
?>

