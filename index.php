<?php
$servername = "localhost";
$username = "mtipikina";
$password = "neto1539";
$dbname = "mtipikina";
session_start();
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

    $sql
        = "CREATE TABLE `Car` ( `ID` INT NOT NULL AUTO_INCREMENT, 
                                  `name` VARCHAR(15) NOT NULL, 
                                  `price` INT NOT NULL, 
                                  `color` VARCHAR(15) NOT NULL,
                                  PRIMARY KEY (`ID`)) ENGINE = InnoDB DEFAULT CHARSET = utf8";
    $conn->exec($sql);
if(isset($_GET['db'])) {
    $getdb = $_GET['db'];
    $_SESSION['getdb'] = $getdb;
}
if(isset($_GET['delete'])) {
    $del = $_GET['delete'];
    $db = $_SESSION['getdb'];
    $tbl = $conn->query("ALTER TABLE $db DROP `$del`");
    header('Location: index.php?db='.$_SESSION['getdb']);
}
    if(!empty($_POST)) {
        $db = $_SESSION['getdb'];
        $type = $_POST['type'];
        $name = $_POST['name'];
        $oldtype =  $_POST['oldtype'];
    foreach ($_POST as $key => $value){
        if($key[0] === 'a' && $value !== ''){
            $a = substr($key,1);
            $tbl = $conn->prepare("ALTER TABLE $db CHANGE `$a` `$a` $type")->execute();
        }
        if($key[0] === 'b' && $value !== ''){
            $b = substr($key,1);
            $tbl = $conn->prepare("ALTER TABLE $db CHANGE `$b` `$name` $oldtype")->execute();
        }
    }
    }
}
catch(PDOException $e)
{
    die("Error: " . $e->getMessage());
}
?>

<h1> Список таблиц в базе данных <?php echo $dbname ?> </h1>
    <?php
$tbl = $conn->query('SHOW TABLES');
foreach ($tbl as $table){
    echo '<li><a href="index.php?db='. $table['Tables_in_mtipikina'].'">'.$table['Tables_in_mtipikina'].'</a></li><br>';
    }
    ?>
<table border="1", cellpadding="10", width="100%">
    <tr>
        <td align="center"><b> Field </b></td>
        <td align="center"><b> Type </b></td>
        <td align="center"><b> Null </b></td>
        <td align="center"><b> Key </b></td>
        <td align="center"><b> Default </b></td>
        <td align="center"><b> Extra </b></td>
        <td align="center"><b> Редактирование </b></td>
    </tr>

    <?php
    if(isset($_GET['db'])) {
    $gdb = $_GET['db'];
    $tbl = $conn->query("DESCRIBE $gdb");
    foreach($tbl as $table) {
        $d = $table['Field']; ?>
        <tr>
            <td align="center"><?php echo $table['Field'] ?></td>
            <td align="center"><?php echo $table['Type'] ?></td>
            <td align="center"><?php echo $table['Null'] ?></td>
            <td align="center"><?php echo $table['Key'] ?></td>
            <td align="center"><?php echo $table['Default'] ?></td>
            <td align="center"><?php echo $table['Extra'] ?></td>
            <td align="center">
                    <form method="post" action="" enctype="multipart/form-data">
                    <?php echo '<a href="index.php?db=' . $_SESSION['getdb'] . '&delete=' . $table['Field'] . '">Удалить поле</a> <br/>';
                    echo "<select name = 'type'>";
                    $data1=[];
                    $data1 = array('INT', 'VARCHAR', 'TEXT', 'DATE');
                    for($i=0; $i<count($data1); $i++) {
                        $a = $data1[$i];
                        echo "<option value = '$a' > $a </option>";
                    }
                    echo "</select>";?>
                    <input type="submit" name="<?= 'a'.$table['Field']; ?>" value="Изменить тип поля"><br/>
                    <?php //echo '<a href="index.php?db=' . $_SESSION['getdb'] . '&changetype=' . $table['Field'] . '">Изменить тип поля</a> <br/>'?>
                    <input type="text" name="name" placeholder="Имя">
                <input type="submit" name="<?='b'.$table['Field']; ?>" value="Изменить имя поля">
                <input type="hidden" name="oldtype" value="<?php echo $table['Type'] ?>">
                    <?php //echo '<a href="index.php?db=' . $_SESSION['getdb'] . '&changename=' . $table['Field'] . '">Изменить имя поля</a> <br/>'?>
                </form>
            </td>
        </tr>
        <?php
    }
    }
    ?>
</table>
