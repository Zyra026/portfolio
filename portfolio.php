<?php
include('tracking_db.php');
session_start();

if (!isset($_SESSION['user_name']) || !isset($_SESSION['user_id'])) {
  header('Location: /coda/landing/Register/SignIn/signin.php');
  exit();
}

// Store user's name in a variable
$user_name = $_SESSION['user_name'];

if (!isset($_GET['professor_id'])) {
  header('Location: studentpage.php');
  exit();
}

$faculty_id = $_GET['professor_id']; 

// Modify the query to fetch schedules for the specific faculty member
$query = "SELECT * FROM faculties WHERE faculty_id = $faculty_id";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
  // Fetch faculty details
  $faculty_row = mysqli_fetch_assoc($result);
  $faculty_name = $faculty_row['names'];
  $faculty_coordinator = $faculty_row['coordinator'];
  $faculty_contact = $faculty_row['contact_no'];
  $faculty_email = $faculty_row['email'];
  $faculty_address = $faculty_row['address'];
  $faculty_image = $faculty_row['image'];
?>

<!-- Your HTML code here -->


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portfolio</title>
    <link rel="stylesheet" href="css/portfolio.css">
    <link rel="stylesheet" href="css/all.min.css" />
    <link rel="stylesheet" href="css/fontawesome.min.css" />
    <script src="js/jquery-3.4.1.min.js"></script>
</head>
<body>
<div class="container">
       <div class="profile-card"> 
            <div class="profile-pic">
                <img src="imahe/profile/<?php echo $faculty_image; ?>" alt="user avatar">
            </div>

            <div class="profile-details">
                 <div class="intro">
                    <h2><?php echo $faculty_name; ?></h2>
                    <h4><?php echo $faculty_coordinator; ?></h4>
                 </div>

                 <div class="contact-info">
                    <div class="row">
                         <div class="icon">
                            <i class="fa fa-phone"  style="color:var(--light-green)"></i>
                         </div>
                         <div class="content">
                            <span>Contact No.</span>
                            <h5><?php echo $faculty_contact; ?></h5>
                         </div>
                    </div>

                    <div class="row">
                        <div class="icon">
                           <i class="fa fa-envelope-open"  style="color:var(--light-green)"></i>
                        </div>
                        <div class="content">
                           <span>Email</span>
                           <h5><?php echo $faculty_email; ?></h5>
                        </div>
                   </div>
    
                   <div class="row">
                    <div class="icon">
                       <i class="fa fa-map-marker"  style="color:var(--light-purple)"></i>
                    </div>
                    <div class="content">
                       <span>Address</span>
                       <h5><?php echo $faculty_address; ?></h5>
                    </div>
                 </div>
            </div>
         </div>
       </div>

       <div class="about">
    <h1><?php echo $faculty_name; ?> Schedule</h1>
    <?php
      $schedule_query = "SELECT s.*, r.room_name, c.course_code, CONCAT(c.course_code, ', ', s.yr_and_block) AS course_and_block FROM schedules s 
                    INNER JOIN rooms r ON s.room_id = r.room_id 
                    INNER JOIN courses c ON s.course_id = c.course_id 
                    WHERE s.faculty_id = $faculty_id";
      $schedule_result = mysqli_query($conn, $schedule_query);

      if ($schedule_result && mysqli_num_rows($schedule_result) > 0) {
      echo '<table>';
      echo '<tr><th>Course, Yr&Block</th><th>Time</th><th>Day of Week</th><th>Subject</th><th>Room Name</th></tr>';
      while ($row = mysqli_fetch_assoc($schedule_result)) {
        echo '<tr>';
        echo '<td>' . $row['course_and_block'] . '</td>';
        echo '<td>' . $row['start_time'] . ' - ' . $row['end_time'] . '</td>';
        echo '<td>' . $row['day_of_week'] . '</td>';
        echo '<td>' . $row['subject'] . '</td>';
        echo '<td>' . $row['room_name'] . '</td>'; 
        echo '</tr>';
      }
      echo '</table>';
      } else {
         echo '<p>No schedules found.</p>';
      }
      ?>
</div>
</div>   
</div>
</body>
</html>

<?php
} else {
  // No faculty found
  echo '<p>Faculty not found.</p>';
}
?>
