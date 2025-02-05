<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MangaFlow Admin</title>
    <script src="https://unpkg.com/netlify-cms@^2.10.0/dist/netlify-cms.js" de></script>
</head>
<body>
    <h1>MangaFlow Admin Panel</h1>

    <h2>Add New Manga</h2>
    <form method="POST">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" required><br>
        <label for="author">Author:</label>
        <input type="text" id="author" name="author" required><br>
        <label for="description">Description:</label>
        <textarea id="description" name="description" required></textarea><br>
        <button type="submit" name="add_manga">Add Manga</button>
    </form>

    <h2>Manage Mangas</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Author</th>
            <th>Description</th>
            <th>Action</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['title']}</td>
                        <td>{$row['author']}</td>
                        <td>{$row['description']}</td>
                        <td>
                            <form method='POST' style='display:inline;'>
                                <input type='hidden' name='id' value='{$row['id']}'>
                                <button type='submit' name='delete_manga'>Delete</button>
                            </form>
                            <form method='POST' style='display:inline;'>
                                <input type='hidden' name='id' value='{$row['id']}'>
                                <input type='text' name='title' value='{$row['title']}' required>
                                <input type='text' name='author' value='{$row['author']}' required>
                                <textarea name='description' required>{$row['description']}</textarea>
                                <button type='submit' name='edit_manga'>Edit</button>
                            </form>
                        </td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No mangas found</td></tr>";
        }
        ?>
    </table>
</body>
</html>

<?php
$conn->close();
?>
// Add pagination
$limit = 10; // Number of entries to show in a page.
if (isset($_GET["page"])) {
    $page  = $_GET["page"];
} else {
    $page = 1;
};
$start_from = ($page - 1) * $limit;

$sql = "SELECT * FROM mangas LIMIT $start_from, $limit";
$rs_result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MangaFlow Admin</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1 class="mt-5">MangaFlow Admin Panel</h1>

        <h2 class="mt-4">Add New Manga</h2>
        <form method="POST" class="mb-4">
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="form-group">
                <label for="author">Author:</label>
                <input type="text" class="form-control" id="author" name="author" required>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea class="form-control" id="description" name="description" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary" name="add_manga">Add Manga</button>
        </form>

        <h2>Manage Mangas</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($rs_result->num_rows > 0) {
                    while ($row = $rs_result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['id']}</td>
                                <td>{$row['title']}</td>
                                <td>{$row['author']}</td>
                                <td>{$row['description']}</td>
                                <td>
                                    <form method='POST' style='display:inline;'>
                                        <input type='hidden' name='id' value='{$row['id']}'>
                                        <button type='submit' class='btn btn-danger' name='delete_manga'>Delete</button>
                                    </form>
                                    <form method='POST' style='display:inline;'>
                                        <input type='hidden' name='id' value='{$row['id']}'>
                                        <input type='text' class='form-control' name='title' value='{$row['title']}' required>
                                        <input type='text' class='form-control' name='author' value='{$row['author']}' required>
                                        <textarea class='form-control' name='description' required>{$row['description']}</textarea>
                                        <button type='submit' class='btn btn-warning' name='edit_manga'>Edit</button>
                                    </form>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No mangas found</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <?php
        $sql = "SELECT COUNT(id) FROM mangas";
        $rs_result = $conn->query($sql);
        $row = $rs_result->fetch_row();
        $total_records = $row[0];
        $total_pages = ceil($total_records / $limit);
        $pagLink = "<nav><ul class='pagination'>";
        for ($i = 1; $i <= $total_pages; $i++) {
            $pagLink .= "<li class='page-item'><a class='page-link' href='cms.php?page=" . $i . "'>" . $i . "</a></li>";
        }
        echo $pagLink . "</ul></nav>";
        ?>
    </div>
</body>
</html>