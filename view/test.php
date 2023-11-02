<?php
global $pdo;
include "../model/db_connect.php";
include "../debug/debug.php";
$searchErr = '';
$employee_details='';
if(isset($_POST['save']))
{
    if(!empty($_POST['search']))
    {
        $search = $_POST['search'];
        $stmt = $pdo->prepare("SELECT l.titre, a.nom AS auteur, e.nom AS editeur
                        FROM livre l
                        LEFT JOIN auteur a ON l.id_auteur = a.id
                        LEFT JOIN editeur e ON l.id_editeur = e.id
                        WHERE l.titre LIKE :search
                        OR a.nom LIKE :search
                        OR e.nom LIKE :search");

        $searchParam = '%' . $search . '%';

        $stmt->bindParam(':search', $searchParam, PDO::PARAM_STR);
        $stmt->execute();
        $searchResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
        print_r($searchResults);

    }
    else
    {
        $searchErr = "Please enter the information";
    }

}

?>
<html>
<head>
    <title>ajax example</title>
    <link rel="stylesheet" href="bootstrap.css" crossorigin="anonymous">
    <!-- Optional theme -->
    <link rel="stylesheet" href="bootstrap-theme.css" crossorigin="anonymous">
    <style>
        .container{
            width:70%;
            height:30%;
            padding:20px;
        }
    </style>
</head>

<body>
<div class="container">
    <h3><u>PHP MySQL search database and display results</u></h3>
    <br/><br/>
    <form class="form-horizontal" action="#" method="post">
        <div class="row">
            <div class="form-group">
                <label class="control-label col-sm-4" for="email"><b>Search Employee Information:</b>:</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="search" placeholder="search here">
                </div>
                <div class="col-sm-2">
                    <button type="submit" name="save" class="btn btn-success btn-sm">Submit</button>
                </div>
            </div>
            <div class="form-group">
                <span class="error" style="color:red;">* <?php echo $searchErr;?></span>
            </div>

        </div>
    </form>
    <br/><br/>
    <h3><u>Search Result</u></h3><br/>
    <div class="table-responsive">
        <table class="table">
            <thead>
            <tr>
                <th>#</th>
                <th>Employee Name</th>
                <th>Phone No</th>
                <th>Age</th>
                <th>Department</th>
            </tr>
            </thead>
            <tbody>
            <?php
            if(!$searchResults)
            {
                echo '<tr>No data found</tr>';
            }
            else{
                foreach($searchResults as $key=>$value)
                {
                    ?>
                    <tr>
                        <td><?php echo $key+1;?></td>
                        <td><?php echo $value['titre'];?></td>
                        <td><?php echo $value['auteur'];?></td>
                        <td><?php echo $value['editeur'];?></td>
<!--                        <td>--><?php //echo $value['department'];?><!--</td>-->
                    </tr>

                    <?php
                }

            }
            ?>

            </tbody>
        </table>
    </div>
</div>
<script src="jquery-3.2.1.min.js"></script>
<script src="bootstrap.min.js"></script>
</body>
</html>