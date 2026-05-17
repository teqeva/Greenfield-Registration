<?php
session_start();
include 'includes/db.php';

/* -----------------------------
   FETCH COURSES FROM DATABASE
------------------------------ */

$sql = "
    SELECT 
        c.id,
        c.code,
        c.name,
        c.credits,
        c.instructor,
        c.capacity,
        COUNT(r.id) AS enrolled
    FROM courses c
    LEFT JOIN registrations r ON c.id = r.course_id
    GROUP BY c.id
";

$result = mysqli_query($conn, $sql);

$courses = [];

while($row = mysqli_fetch_assoc($result)){
    $courses[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Catalog - Greenfield Institute</title>

    <!-- ✅ FIXED PATHS (MATCH index.php STYLE) -->
    <link rel="stylesheet" href="assets/css/base.css">
    <link rel="stylesheet" href="assets/css/courses.css">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar">
    <div class="nav-container">

        <a href="index.php" class="logo-wrapper">
            <div class="logo-icon">
                <div class="logo-shield"></div>

                <div class="logo-text">
                    <span class="logo-text-main">GREENFIELD</span>
                    <span class="logo-text-sub">INSTITUTE</span>
                </div>
            </div>
        </a>

        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="courses.php" class="active">Courses</a></li>

            <li><a href="login.php">Login</a></li>
            <li><a href="register.php">Register</a></li>
        </ul>

    </div>
</nav>

<main>
<div class="container">

    <div class="page-header">
        <h1>Course Catalog</h1>
        <p>Explore available courses and enroll instantly</p>
    </div>

    <div class="search-section">
        <div class="search-container">

            <div class="search-wrapper">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" placeholder="Search courses...">
            </div>

        </div>
    </div>

    <div class="courses-grid" id="courses-container">

        <?php foreach($courses as $course): ?>

            <div class="course-card"
                 data-name="<?php echo strtolower($course['name']); ?>">

                <h3><?php echo $course['name']; ?></h3>

                <p><strong>Code:</strong> <?php echo $course['code']; ?></p>
                <p><strong>Instructor:</strong> <?php echo $course['instructor']; ?></p>
                <p><strong>Credits:</strong> <?php echo $course['credits']; ?></p>

                <p>
                    <strong>Enrolled:</strong>
                    <?php echo $course['enrolled']; ?> /
                    <?php echo $course['capacity']; ?>
                </p>

                <form method="POST" action="enroll.php">

                    <input type="hidden" name="course_id" value="<?php echo $course['id']; ?>">

                    <button type="submit" class="btn btn-primary">
                        Enroll
                    </button>

                </form>

            </div>

        <?php endforeach; ?>

    </div>

</div>
</main>

<footer class="footer">
    <div class="footer-content">
        <div class="footer-section">
            <h3>Greenfield Institute</h3>
            <p>Empowering futures through quality education.</p>
        </div>
    </div>
</footer>

<script src="js/main.js"></script>

<script>
const searchInput = document.getElementById("searchInput");
const cards = document.querySelectorAll(".course-card");

searchInput.addEventListener("input", function() {

    let value = this.value.toLowerCase();

    cards.forEach(card => {

        let name = card.dataset.name;

        card.style.display =
            name.includes(value) ? "block" : "none";
    });

});
</script>

</body>
</html>