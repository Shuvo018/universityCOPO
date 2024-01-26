<?php
include("./nav.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="../style.css">

    <title>Document</title>
</head>

<body>
    <div class="container">
        <h1>students class test</h1>
        <hr>
        <?php
        // for display batch , course
        include("header.php");
        ?>
    </div>
    <form action="ct.php" method="POST">
        <input type="submit" name="midBtn" value="mid" class='btn btn-primary'>
        <input type="submit" name="finalBtn" value="final" class='btn btn-primary'>
    </form>
    <?php
    $bctId = $_SESSION['bct_id'];

    // all buttons
    if (isset($_POST['finalBtn'])) {
        $_SESSION['ct_term'] = "final";
        echo "final clicked <br>";
    } else if (isset($_POST['midBtn'])) {
        $_SESSION['ct_term'] = "mid";
        echo "mid clicked <br>";
    }
    // student marks save button
    if (isset($_POST['saveBtn'])) {
        $stuCid = $_POST['stuId'];
        echo $stuCid;
        echo "save btn clicked <br>";
        $curStuId = getStuIdFun($stuCid);
        saveStuMark($bctId, $curStuId);
    }

    ?>
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
                <th scope="col">total</th>
                <th scope="col">term</th>
                <th scope="col">edit</th>
            </tr>
        </thead>
        <tbody>

            <?php
            $topRow = null;
            $ctRowId = null;
            if ($_SESSION['ct_term'] == "final") {
                echo "final <br>";
                if (query("final", $bctId) == null) {
                    newTopRow("final", $bctId);
                }
                $topRow = query("final", $bctId);
            } else {
                echo "mid <br>";
                if (query("mid", $bctId) == null) {
                    newTopRow("mid", $bctId);
                }
                $topRow = query("mid", $bctId);
            }
            $ctRowId = $topRow['id'];
            ?>
            <tr>
                <td></td>
                <td></td>
                <form action="ct.php" method="POST">
                    <td>
                        <input type='text' name='co1' value="<?php echo "{$topRow['co1']}"; ?>">
                    </td>
                    <td>
                        <input type='text' name='co2' value='<?php echo "{$topRow['co2']}"; ?>'>
                    </td>
                    <td>
                        <input type='text' name='co3' value='<?php echo "{$topRow['co3']}"; ?>'>
                    </td>
                    <td>
                        <input type='text' name='co4' value='<?php echo "{$topRow['co4']}"; ?>'>
                    </td>
                    <td>
                        <input type='text' name='co5' value='<?php echo "{$topRow['co5']}"; ?>'>
                    </td>
                    <td>
                        <input type='text' name='total' value='<?php echo "{$topRow['total']}"; ?>'>
                    </td>
                    <td><?php echo "{$topRow['term']}"; ?></td>
                    <td>
                        <input type='submit' name='set' value='set' class='btn btn-primary'>
                    </td>
                </form>
            </tr>

            <?php
            // finding students from running course 
            $rcSql = "SELECT `stu_id` FROM `running_course` WHERE b_c_t_id = '$bctId'";
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

                // attandence operations

                if (getStuCTMark($bctId, $stu_id) == null) {
                    newStu($bctId, $stu_id);
                }
                $stuMark = getStuCTMark($bctId, $stu_id);

                echo "<form action='ct.php' method='post'>
                <tr>

                <td><input name= 'stuId' value='{$stu_cId}'/></td>
                    <td>{$stu_name}</td>
                    <td><input type='text' name= 'sco1' value='{$stuMark['co1']}'/></td>
                    <td><input type='text' name= 'sco2' value='{$stuMark['co2']}'/></td>
                    <td><input type='text' name= 'sco3' value='{$stuMark['co3']}'/></td>
                    <td><input type='text' name = 'sco4' value='{$stuMark['co4']}'/></td>
                    <td><input type='text' name= 'sco5' value='{$stuMark['co5']}'/></td>
                    <td>{$stuMark['total']}</td>
                    <td>{$stuMark['term']}</td>
                    <td><input type='submit' name='saveBtn' value='save' class='btn btn-primary'></td>
                    </tr></form>";
            }
            // <td><a href = './editStuMark/editAttandence.php?stuAttMarkId={$stuMark['id']}' class='btn btn-primary'> edit </a></td>
            ?>
        </tbody>
    </table>


    <button type="button" class="btn btn-primary">save</button>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>

<?php

function getStuIdFun($stuCid)
{
    require "../db.php";
    $sql = "SELECT id FROM student WHERE stu_cardId = '$stuCid'";
    if ($row = mysqli_fetch_assoc(mysqli_query($conn, $sql))) {
        return $row['id'];
    }
}
function saveStuMark($bctId, $curStuId)
{
    $stuMarks = getStuMark();
    require "../db.php";
    $term = $_SESSION['ct_term'];
    $upSql = "UPDATE `classtest` SET`co1`='$stuMarks[0]',`co2`='$stuMarks[1]',`co3`='$stuMarks[2]',
    `co4`='$stuMarks[3]',`co5`='$stuMarks[4]',`total`='$stuMarks[5]'
    WHERE term = '$term' AND bct_id = '$bctId' AND stu_id = '$curStuId'";
    if (mysqli_query($conn, $upSql)) {
        echo "student mark added !";
    }
}

function getStuMark()
{
    $arr = array();
    $sum = 0;
    for ($i = 1; $i < 6; $i++) {
        $sum += $_POST["sco$i"];
        array_push($arr, $_POST["sco$i"]);
    }
    array_push($arr, $sum);
    return $arr;
}

// when first time this page is open

if (isset($_POST['set'])) {
    echo $ctRowId;
    updateConsValue($ctRowId);
}

// all function call here

function getStuCTMark($bctId, $stu_id)
{
    require "../db.php";
    $term = $_SESSION['ct_term'];
    $attanSql = "SELECT `id`, `co1`, `co2`, `co3`, `co4`, `co5`, `total`, `term`, `bct_id`, `stu_id` FROM `classtest` 
        WHERE term = '$term' AND bct_id = '$bctId' AND stu_id = '$stu_id'";
    if ($row = mysqli_fetch_assoc(mysqli_query($conn, $attanSql))) {
        return $row;
    }
    return null;
}
function newStu($bctId, $stu_id)
{
    require "../db.php";
    // if student value is null;
    $term = $_SESSION['ct_term'];
    $setSql = "INSERT INTO `classtest`(`co1`, `co2`, `co3`, `co4`, `co5`, `total`, `term`, `bct_id`, `stu_id`) VALUES 
    ('0','0','0','0','0','0','$term','$bctId','$stu_id')";
    if (mysqli_query($conn, $setSql)) {
    }
}
function updateConsValue($ARId)
{
    $updateArr = getFormValue();

    require "../db.php";
    $setSql = "UPDATE `constant` SET `co1`='$updateArr[0]',
    `co2`='$updateArr[1]',`co3`='$updateArr[2]',`co4`='$updateArr[3]',
    `co5`='$updateArr[4]',`total`='$updateArr[5]' WHERE id = $ARId";
    if (mysqli_query($conn, $setSql)) {
        echo "updated <br>";
        echo $ARId;
    }
}
function getFormValue()
{
    $arr = array();
    for ($i = 1; $i < 6; $i++) {
        if ($_POST["co$i"] > -1) {
            array_push($arr, $_POST["co$i"]);
        } else {
            array_push($arr, 0);
        }
    }
    array_push($arr, $_POST["total"]);

    return $arr;
}
function query($term, $bctId)
{
    require "../db.php";
    $sql = "SELECT * FROM constant WHERE action = 'classTest' AND term = '$term' AND bct_id = $bctId ";
    $result = mysqli_query($conn, $sql);
    if ($row = mysqli_fetch_assoc($result)) {
        return $row;
    }
}
function newTopRow($term, $bctId)
{
    require "../db.php";
    $insertSql = "INSERT INTO `constant`(`co1`, `co2`, `co3`, `co4`, `co5`, `total`, `action`, `term`, `bct_id`) VALUES
         ('0','0','0','0','0','0','classTest','$term','$bctId')";
    mysqli_query($conn, $insertSql);
}
?>