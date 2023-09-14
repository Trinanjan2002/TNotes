<?php
$server = "localhost";
$username = "root";
$password = "";
$database = "notesDB";

$insert = false;
$update = false;
$delete = false;

$conn = mysqli_connect($server, $username, $password, $database);

if (!$conn) {
    die("<br> Connection to database failed" . mysqli_connect_error());
}

if (isset($_GET['delete'])) {
    $sno = $_GET['delete'];
    $sql = "DELETE FROM notes WHERE sno = $sno;";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        $delete = true;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['snoEdit'])) {
        $titleEdit = $_POST['titleEdit'];
        $descEdit = $_POST['descEdit'];
        $sno = $_POST['snoEdit'];

        $sql = "UPDATE notes SET title = '$titleEdit', des = '$descEdit' WHERE sno = $sno;";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            $update = true;
        }
    } else {
        $title = $_POST['title'];
        $desc = $_POST['desc'];

        $sql = "INSERT INTO notes (title, des) VALUES ('$title', '$desc');";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            $insert = true;
        }
    }
}
?>


<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

    <link rel="stylesheet" href="//cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <title>TNotes - Notes Taking Made Easy</title>
</head>

<body>
    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="./index.php" method="post">
                    <div class="modal-header">
                        <h2 class="modal-title" id="editModalLabel">Edit Note</h2>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="snoEdit" id="snoEdit">
                        <div class="form-group">
                            <label for="titleEdit">Note Title</label>
                            <input type="text" class="form-control" name="titleEdit" id="titleEdit" aria-describedby="emailHelp">
                        </div>
                        <div class="form-group">
                            <label for="descEdit">Note Description</label>
                            <textarea class="form-control" name="descEdit" id="descEdit" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">TNotes</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Contact Us</a>
                </li>
            </ul>
            <form class="form-inline my-2 my-lg-0">
                <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
            </form>
        </div>
    </nav>

    <?php
    if ($insert) {
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
<strong>Success!</strong> Your Note Has Been Saved Successfully...
<button type="button" class="close" data-dismiss="alert" aria-label="Close">
  <span aria-hidden="true">&times;</span>
</button>
</div>';
    }

    if ($update) {
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success!</strong> Your Note Has Been Updated Successfully...
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
              </div>';
    }

    if ($delete) {
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success!</strong> Your Note Has Been Deleted Successfully...
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
              </div>';
    }
    ?>


    <div class="container my-4 w-75">
        <h2>Add a Note</h2>
        <form action="./index.php" method="post">
            <div class="form-group">
                <label for="title">Note Title</label>
                <input type="text" class="form-control" name="title" id="title" aria-describedby="emailHelp">
            </div>
            <div class="form-group">
                <label for="desc">Note Description</label>
                <textarea class="form-control" name="desc" id="desc" rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Add Note</button>
        </form>
    </div>

    <div class="container mb-5">
        <table id="myTable" class="table">
            <thead>
                <tr>
                    <th scope="col">SNo.</th>
                    <th scope="col">Title</th>
                    <th scope="col">Description</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM notes;";
                $result = mysqli_query($conn, $sql);
                $sno = 1;

                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                            <th scope='row'>" . $sno++ . "</th>
                            <td>" . $row['title'] . "</td>
                            <td>" . $row['des'] . "</td>
                            <td>
                                <button class='edit btn btn-sm btn-success m-1' data-toggle='modal' data-target='#editModal' id='" . $row['sno'] . "'>Edit</button>
                                <button class='delete btn btn-sm btn-danger m-1' id='d" . $row['sno'] . "'>Delete</button>
                            </td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>


    <script>
        let edits = document.getElementsByClassName('edit');
        Array.from(edits).forEach(element => {
            element.addEventListener("click", (e) => {
                let tr = e.target.parentNode.parentNode;
                title = tr.getElementsByTagName("td")[0].innerText;
                description = tr.getElementsByTagName("td")[1].innerText;
                descEdit.value = description;
                titleEdit.value = title;
                snoEdit.value = e.target.id;
            })
        });

        let deletes = document.getElementsByClassName('delete');
        Array.from(deletes).forEach(element => {
            element.addEventListener("click", (e) => {
                let sno = e.target.id.substr(1);

                if (confirm("Are you sure you want to delete this note?")) {
                    // console.log("yes");
                    window.location = `./index.php?delete=${sno}`;
                } else {
                    // console.log("no");
                }
            })
        });
    </script>

    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>

    <script src="//cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        let table = new DataTable('#myTable');
    </script>
</body>

</html>