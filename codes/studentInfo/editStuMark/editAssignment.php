<?php
// include("../nav.php");
session_start();

if (isset($_GET['stuRowId'])) {
    $_SESSION['stuRowId'] = $_GET['stuRowId'];
}
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
    <h1>Edit student assignment</h1>
    <?php
    include("../header.php");

    $stuMarkId = $_SESSION['stuRowId'];

    echo $stuMarkId;
    ?>
    <!-- start from here -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th scope="col">Id</th>
                <th scope="col">Name</th>
                <th scope="col">co1</th>
                <th scope="col">co2</th>
                <th scope="col">co3</th>
                <th scope="col">co4</th>
                <th scope="col">co5</th>
                <th scope="col">term</th>
                <th scope="col">total</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // get co1...co5 form attandence db;
            $stuMark = getStuAssignMark($stuMarkId);
            $stu = getStuDetails($stuMark['stu_id']);
            ?>
            <tr>
                <td><?php echo "{$stu['stu_cardId']}"; ?></td>
                <td><?php echo "{$stu['stu_name']}"; ?></td>
                <form action="#" method="POST">
                    <td>
                        <input type='text' name='co1' value='<?php echo "{$stuMark['co1']}"; ?>'>
                    </td>
                    <td>
                        <input type='text' name='co2' value='<?php echo "{$stuMark['co2']}"; ?>'>
                    </td>
                    <td>
                        <input type='text' name='co3' value='<?php echo "{$stuMark['co3']}"; ?>'>
                    </td>
                    <td>
                        <input type='text' name='co4' value='<?php echo "{$stuMark['co4']}"; ?>'>
                    </td>
                    <td>
                        <input type='text' name='co5' value='<?php echo "{$stuMark['co5']}"; ?>'>
                    </td>
                    <td><?php echo "{$stuMark['term']}"; ?></td>
                    <td><?php echo "{$stuMark['total']}"; ?></td>
                    <td>
                        <input type='submit' name='set' value='set'>
                    </td>
                </form>
            </tr>
        </tbody>
    </table>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>

<?php
if (isset($_POST['set'])) {
    updateStuValue($stuMarkId);
}


function updateStuValue($id)
{
    require "db.php";

    $arr = getFormValue();
    $updateSql = "UPDATE `assignment` SET `co1`='$arr[0]',`co2`='$arr[1]',`co3`='$arr[2]',`co4`='$arr[3]',
        `co5`='$arr[4]',`total`='$arr[5]' WHERE id = $id";
    if (mysqli_query($conn, $updateSql)) {
        echo "update successfully";
    }
}

function getFormValue()
{
    $arr = array();
    $sum = 0;
    for ($i = 1; $i < 6; $i++) {
        $sum += $_POST["co$i"];
        array_push($arr, $_POST["co$i"]);
    }
    array_push($arr, $sum);
    return $arr;
}
function getStuAssignMark($stuMarkId)
{

    require "db.php";
    $term = $_SESSION['ass_term'];

    $sql = "SELECT `id`, `co1`, `co2`, `co3`, `co4`, `co5`, `total`, `term`, `bct_id`, `stu_id` FROM `assignment` 
        WHERE id = '$stuMarkId' AND term = '$term'";
    if ($row = mysqli_fetch_assoc(mysqli_query($conn, $sql))) {
        return $row;
    }
}

function getStuDetails($stu_id)
{
    // find student name from student db
    require "db.php";
    $sSql = "SELECT `stu_cardId`, `stu_name` FROM `student` WHERE id = $stu_id";
    if ($sRow = mysqli_fetch_assoc(mysqli_query($conn, $sSql))) {
        return $sRow;
    }
}

?>