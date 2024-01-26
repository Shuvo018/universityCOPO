<?php
include("./nav.php");
// session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <title>exam</title>
</head>

<body>
    <div class="container">
        <h1>students exam</h1>
        <hr>
        <?php
        // for display batch , course
        include("header.php");
        ?>
    </div>
    <form action="exam.php" method="POST">
        <input type="submit" name="midBtn" value="mid" class='btn btn-primary'>
        <input type="submit" name="finalBtn" value="final" class='btn btn-primary'>
    </form>
    <?php
    $bctId = $_SESSION['bct_id'];

    // all buttons

    if (isset($_POST['co1V'])) {
        $_SESSION['exam_coV'] = $_POST['co1V'];
    } else if (isset($_POST['co2V'])) {
        $_SESSION['exam_coV'] = $_POST['co2V'];
    } else if (isset($_POST['co3V'])) {
        $_SESSION['exam_coV'] = $_POST['co3V'];
    } else if (isset($_POST['co4V'])) {
        $_SESSION['exam_coV'] = $_POST['co4V'];
    } else if (isset($_POST['co5V'])) {
        $_SESSION['exam_coV'] = $_POST['co5V'];
    }

    echo "secssion co : {$_SESSION['exam_coV']} <br>";

    if (isset($_POST['finalBtn'])) {
        $_SESSION['exam_term'] = "final";
        echo "final clicked <br>";
    } else if (isset($_POST['midBtn'])) {
        $_SESSION['exam_term'] = "mid";
        echo "mid clicked <br>";
    }
    // student marks save button
    if (isset($_POST['saveBtn'])) {
        $stuCid = $_POST['stuId'];
        echo $stuCid;
        echo " save btn clicked <br>";
        $curStuId = getStuIdFun($stuCid);
        saveStuMark($bctId, $curStuId);
    }

    ?>
    <form action="exam.php" method="POST">
        <input type="submit" name="co1V" value="co1" class='btn btn-primary'>
        <input type="submit" name="co2V" value="co2" class='btn btn-primary'>
        <input type="submit" name="co3V" value="co3" class='btn btn-primary'>
        <input type="submit" name="co4V" value="co4" class='btn btn-primary'>
        <input type="submit" name="co5V" value="co5" class='btn btn-primary'>
    </form>
    <?php
    ?>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th scope="col">Id</th>
                <th scope="col">Name</th>
                <th scope="col">1a</th>
                <th scope="col">1b</th>
                <th scope="col">2a</th>
                <th scope="col">2b</th>
                <th scope="col">3a</th>
                <th scope="col">3b</th>
                <th scope="col">4a</th>
                <th scope="col">4b</th>
                <th scope="col">5a</th>
                <th scope="col">5b</th>
                <th scope="col">6a</th>
                <th scope="col">6b</th>
                <th scope="col">7a</th>
                <th scope="col">7b</th>
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
            $topRowId = null;
            if ($_SESSION['exam_term'] == "final") {
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
            $topRowId = $topRow['id'];
            ?>
            <tr>
                <td></td>
                <td></td>
                <form action="exam.php" method="POST">
                    <td>
                    </td>
                    <td>
                    </td>
                    <td>
                    </td>
                    <td>
                    </td>
                    <td>
                    </td>
                    <td>
                    </td>
                    <td>
                    </td>
                    <td>
                    </td>
                    <td>
                    </td>
                    <td>
                    </td>
                    <td>
                    </td>
                    <td>
                    </td>
                    <td>
                    </td>
                    <td>

                    </td>
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
                        <?php echo "{$topRow['total']}"; ?>
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

                if (getStuExamMark($bctId, $stu_id, $_SESSION['exam_coV']) == null) {
                    newStu($bctId, $stu_id, $_SESSION['exam_coV']);
                }
                $stuMark = getStuExamMark($bctId, $stu_id, $_SESSION['exam_coV']);
                if (getStuCoMark($bctId, $stu_id) == null) {
                    newStuCoMark($bctId, $stu_id);
                }
                $stuCoMark = getStuCoMark($bctId, $stu_id);

                echo "<form action='exam.php' method='post'>
                <tr>

                <td><input name= 'stuId' value='{$stu_cId}'/></td>
                    <td>{$stu_name}</td>
                    <td><input type='text' name= 'sa1' value='{$stuMark['1a']}'/></td>
                    <td><input type='text' name= 'sb1' value='{$stuMark['1b']}'/></td>
                    <td><input type='text' name= 'sa2' value='{$stuMark['2a']}'/></td>
                    <td><input type='text' name= 'sb2' value='{$stuMark['2b']}'/></td>
                    <td><input type='text' name= 'sa3' value='{$stuMark['3a']}'/></td>
                    <td><input type='text' name= 'sb3' value='{$stuMark['3b']}'/></td>
                    <td><input type='text' name= 'sa4' value='{$stuMark['4a']}'/></td>
                    <td><input type='text' name= 'sb4' value='{$stuMark['4b']}'/></td>
                    <td><input type='text' name= 'sa5' value='{$stuMark['5a']}'/></td>
                    <td><input type='text' name= 'sb5' value='{$stuMark['5b']}'/></td>
                    <td><input type='text' name= 'sa6' value='{$stuMark['6a']}'/></td>
                    <td><input type='text' name= 'sb6' value='{$stuMark['6b']}'/></td>
                    <td><input type='text' name= 'sa7' value='{$stuMark['7a']}'/></td>
                    <td><input type='text' name= 'sb7' value='{$stuMark['7b']}'/></td>
                    <td><input type='text' name= 'sco1' value='{$stuCoMark['co1']}'/></td>
                    <td><input type='text' name= 'sco2' value='{$stuCoMark['co2']}'/></td>
                    <td><input type='text' name= 'sco3' value='{$stuCoMark['co3']}'/></td>
                    <td><input type='text' name = 'sco4' value='{$stuCoMark['co4']}'/></td>
                    <td><input type='text' name= 'sco5' value='{$stuCoMark['co5']}'/></td>
                    <td>{$stuCoMark['total']}</td>
                    <td>{$stuCoMark['term']}</td>
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



if (isset($_POST['set'])) {
    echo $topRowId;
    updateValue($topRowId);
}

// all function call here
function getStuExamMark($bctId, $stu_id, $co)
{
    require "../db.php";
    $term = $_SESSION['exam_term'];
    $sql = "SELECT `id`, `1a`, `1b`, `2a`, `2b`, `3a`, `3b`, `4a`, `4b`, `5a`, `5b`, `6a`, `6b`, `7a`, `7b`, 
    `co`, `term`, `bct_id`, `stu_id` FROM `exam` 
    WHERE co = '$co' AND term = '$term' AND bct_id = '$bctId' AND stu_id = '$stu_id'";

    if ($row = mysqli_fetch_assoc(mysqli_query($conn, $sql))) {
        return $row;
    }
    return null;
}
function newStu($bctId, $stu_id, $co)
{
    require "../db.php";
    // if student value is null;
    $term = $_SESSION['exam_term'];

    $setSql = "INSERT INTO `exam`(`1a`, `1b`, `2a`, `2b`, `3a`, `3b`, 
    `4a`, `4b`, `5a`, `5b`, `6a`, `6b`, `7a`, `7b`, `co`,`term`, `bct_id`, `stu_id`) VALUES
      ('0','0','0',
      '0','0','0','0','0',
      '0','0','0','0','0',
      '0','$co','$term','$bctId','$stu_id')";
    if (mysqli_query($conn, $setSql)) {
    }
}
function getStuCoMark($bctId, $stu_id)
{
    require "../db.php";
    $term = $_SESSION['exam_term'];
    $sql = "SELECT `id`, `co1`, `co2`, `co3`, `co4`, `co5`, `total`, `term` FROM `constant_stu_exam` 
    WHERE term = '$term' AND bct_id = $bctId AND stu_id = $stu_id";
    $result = mysqli_query($conn, $sql);
    if ($row = mysqli_fetch_assoc($result)) {
        return $row;
    }
    return null;
}
function newStuCoMark($bctId, $stu_id)
{
    require "../db.php";
    $term = $_SESSION['exam_term'];
    $sql = "INSERT INTO `constant_stu_exam`(`co1`, `co2`, `co3`, `co4`, `co5`, `total`, `term`, `bct_id`, `stu_id`) 
    VALUES ('0','0','0','0','0','0','$term','$bctId','$stu_id')";
    mysqli_query($conn, $sql);
}
// constant db operation
function updateValue($TRId)
{
    $updateArr = getFormValue();

    require "../db.php";
    $setSql = "UPDATE `constant` SET `co1`='$updateArr[0]',
    `co2`='$updateArr[1]',`co3`='$updateArr[2]',`co4`='$updateArr[3]',
    `co5`='$updateArr[4]',`total`='$updateArr[5]' WHERE id = $TRId";
    if (mysqli_query($conn, $setSql)) {
        echo "updated <br>";
    }
}
function getFormValue()
{
    $arr = array();
    $totalSum = 0;
    for ($i = 1; $i < 6; $i++) {
        if ($_POST["co$i"] > -1) {
            $totalSum += $_POST["co$i"];
            array_push($arr, $_POST["co$i"]);
        } else {
            array_push($arr, 0);
        }
    }
    array_push($arr, $totalSum);
    return $arr;
}

function query($term, $bctId)
{
    require "../db.php";
    $sql = "SELECT * FROM constant WHERE action = 'exam' AND term = '$term' AND bct_id = $bctId ";
    $result = mysqli_query($conn, $sql);
    if ($row = mysqli_fetch_assoc($result)) {
        return $row;
    }
}
function newTopRow($term, $bctId)
{
    require "../db.php";
    $insertSql = "INSERT INTO `constant`(`co1`, `co2`, `co3`, `co4`, `co5`, `total`, `action`, `term`, `bct_id`) 
    VALUES ('0','0','0','0','0','0','exam','$term','$bctId')";
    mysqli_query($conn, $insertSql);
}

// ***student mark insert operations***
// get student id from student card id
function getStuIdFun($stuCid)
{
    require "../db.php";
    $sql = "SELECT id FROM student WHERE stu_cardId = '$stuCid'";
    if ($row = mysqli_fetch_assoc(mysqli_query($conn, $sql))) {
        return $row['id'];
    }
}
//save student mark
function saveStuMark($bctId, $curStuId)
{
    require "../db.php";
    $stuMarks = getStuMark();
    // echo $stuMarks[14];
    /////////
    // 
    //  working start from this
    // 

    $term = $_SESSION['exam_term'];
    $co = $_SESSION['exam_coV'];
    $sql = "UPDATE `exam` SET `1a`='$stuMarks[0]',`1b`='$stuMarks[1]',
    `2a`='$stuMarks[2]',`2b`='$stuMarks[3]',`3a`='$stuMarks[4]',`3b`='$stuMarks[5]',`4a`='$stuMarks[6]',
    `4b`='$stuMarks[7]',`5a`='$stuMarks[8]',`5b`='$stuMarks[9]',`6a`='$stuMarks[10]',`6b`='$stuMarks[11]',
    `7a`='$stuMarks[12]',`7b`='$stuMarks[13]' 
    WHERE bct_id = $bctId AND stu_id = $curStuId AND term = '$term' AND co = '$co'";

    mysqli_query($conn, $sql);

    //find co value

    $coValues = getCoValues($stuMarks[14]);
    // echo $coValues[0];

    $coSql = "UPDATE `constant_stu_exam` SET `co1`='$coValues[0]',`co2`='$coValues[1]',
    `co3`='$coValues[2]',`co4`='$coValues[3]',`co5`='$coValues[4]',`total`='$coValues[5]'
     WHERE term = '$term' AND bct_id = '$bctId' AND stu_id = '$curStuId'";
    mysqli_query($conn, $coSql);
}
function getCoValues($value)
{
    // find totol for which co
    $arr = array();
    for ($i = 1; $i < 6; $i++) {
        if ($_SESSION['exam_coV'] == "co$i") { // if co2 is active then we add co2 value only
            array_push($arr, $value);
        } else {
            array_push($arr, 0);
        }
    }

    // co operation 
    $newArr = array();
    $coSum = 0;
    for ($i = 1; $i < 6; $i++) {
        // arr[0] = co1, arr[1] = co2...
        // if arr[2] is active it has a value of 1a, 1b...7a
        if ($arr[$i - 1] > 0) {
            $coSum += $arr[$i - 1];
            array_push($newArr, $arr[$i - 1]);
        } else {
            $coSum += $_POST["sco{$i}"];
            array_push($newArr, $_POST["sco{$i}"]);
        }
    }
    array_push($newArr, $coSum);

    return $newArr;
}
function getStuMark()
{
    $arr = array();
    $sum = 0;
    for ($i = 1; $i < 8; $i++) {
        $sum += $_POST["sa{$i}"];
        $sum += $_POST["sb{$i}"];
        array_push($arr, $_POST["sa{$i}"]);
        array_push($arr, $_POST["sb{$i}"]);
    }
    array_push($arr, $sum);
    return $arr;
}
?>