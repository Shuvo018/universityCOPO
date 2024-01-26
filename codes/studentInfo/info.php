<?php
include("./nav.php");

$_SESSION['term'] = "mid";
$_SESSION['ass_term'] = "mid";
$_SESSION['ct_term'] = "mid";
$_SESSION['exam_term'] = "mid";
$_SESSION['exam_coV'] = "co1";
if (isset($_GET['bctId'])) {
    $_SESSION['bct_id'] = $_GET['bctId'];
}

$getBCTid = $_SESSION['bct_id'];

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <title>Document</title>
</head>

<body>

    <?php
    //find batch, course id from batch_course_teacher db
    $bctSql = "SELECT `id`, `batch_id`, `course_id`, `teacher_id` FROM `batch_course_teacher`
     WHERE id = $getBCTid";
    $bctResult = mysqli_query($conn, $bctSql);
    if ($bctRow = mysqli_fetch_assoc($bctResult)) {
        $batch_id = $bctRow['batch_id'];
        $course_id = $bctRow['course_id'];
        //find batch ttile from batch db 
        $bSql = "SELECT `batch_no` FROM `batch` WHERE id = '$batch_id'";
        if ($bRow = mysqli_fetch_assoc(mysqli_query($conn, $bSql))) {
            $_SESSION['batch_no'] = $bRow['batch_no'];
            echo "<h1>batch: {$bRow['batch_no']}</h1><br>";
        }

        //find course ttile from course db 
        $cSql = "SELECT `course_code`, `course_title` FROM `course` WHERE id = '$course_id'";
        if ($cRow = mysqli_fetch_assoc(mysqli_query($conn, $cSql))) {
            $_SESSION['course_code'] = $cRow['course_code'];
            $_SESSION['course_title'] = $cRow['course_title'];
            echo "<h4>Course: {$cRow['course_code']}</h4>";
            echo "<h4>Course: {$cRow['course_title']}</h4>";
        }
    }
    ?>

    <table class="table mb-3">
        <thead>
            <tr>
                <th scope="col">stu_id</th>
                <th scope="col">stu name</th>
                <th scope="col">co</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // finding students from running course 
            $rcSql = "SELECT `id`, `b_c_t_id`, `stu_id` FROM `running_course` WHERE b_c_t_id = $getBCTid";
            $rcResult = mysqli_query($conn, $rcSql);
            while ($rcRow = mysqli_fetch_assoc($rcResult)) {
                $stu_id = $rcRow['stu_id'];
                $stu_cId = null;
                $stu_name = null;
                // find student name from student db
                $sSql = "SELECT `stu_cardId`, `stu_name` FROM `student` WHERE id = $stu_id";
                if ($sRow = mysqli_fetch_assoc(mysqli_query($conn, $sSql))) {
                    $stu_cId = $sRow['stu_cardId'];
                    $stu_name = $sRow['stu_name'];
                }
                echo "<tr>
                    <td>{$stu_cId}</td>
                    <td>{$stu_name}</td>
                    </tr>";
            }

            ?>
            <tr>

            </tr>
        </tbody>
    </table>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>