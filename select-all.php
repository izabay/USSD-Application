<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All members</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .operation {
            color: white;
            text-decoration: none;
        }

        .center-table {
            margin: 0 auto; /* Center the table horizontally */
            margin-top: 30px;
            
            margin-right: 14px;
           
            
        }
        button{
            margin-top: 10px;
            width: auto;
            
        }
        tbody tr td.values {
            max-width: 200px; /* Adjust the max-width to fit your layout */
    overflow: hidden;
    text-overflow: ellipsis; /* This will add ellipsis (...) if the text overflows */
    white-space: nowrap; /* This will prevent wrapping */
    }
    .container{

        margin-top: 90px;
    }
    </style>
</head>
<body>
    <?php
    include "header.php";
    include 'navigation.php';
    // session_start();
    // include '../connection.php';

    // Prepare the SQL query
    $select_query = "SELECT * FROM members";
    $statement = $conn->prepare($select_query);

    // Execute the query
    $statement->execute();

    // Fetch all rows
    $members = $statement->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <div class="container">
        <div class="row">
            <div class="col-lg-10 center-table"> <!-- Adjust the width of the table and center it -->
                <table class="table table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>NAMES</th>
                            <th>NATIONAL ID</th>
                            <th>TELEPHONE</th>
                            <th>EMAIL</th>
                            <th>PASSWORD</th>
                            <th>OPERATION</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($members as $row): ?>
                            <tr>
                                <td><?php echo $row['MemberId']; ?></td>
                                <td class="values" ><?php echo $row['names']; ?></td>
                                <td><?php echo $row['national_identity']; ?></td>
                                <td><?php echo $row['telephone']; ?></td>
                                <td><?php echo $row['email']; ?></td>
                                <td><?php echo $row['password']; ?></td>
                                <td>
                                    <button class="btn btn-primary"><a href="update.php?updateid=<?php echo $row['MemberId']; ?>" class="operation">Update</a></button>
                                    <button class="btn btn-danger"><a href="delete.php?deleteid=<?php echo $row['MemberId']; ?>" class="operation">Delete</a></button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
<?php include 'footer.php';?>
</html>
