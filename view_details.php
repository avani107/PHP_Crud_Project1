<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>

    <title>View Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 20px;
        }

        h2 {
            color: #333;
        }

        .search-form {
            margin-bottom: 20px;
        }

        .search-form input[type=text] {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 200px;
            box-sizing: border-box;
        }

        .search-form input[type=submit], .dropdown .dropbtn {
            padding: 8px 12px;
            cursor: pointer;
            border: none;
            border-radius: 4px;
            background-color: #4CAF50;
            color: white;
            font-size: 14px;
            margin-left: 10px;
        }

        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #fff;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .dropdown-content a {
            color: #333;
            padding: 10px 12px;
            text-decoration: none;
            display: block;
            cursor: pointer;
        }

        .dropdown-content a:hover {
            background-color: #f9f9f9;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }

        table th {
            background-color: #4CAF50;
            color: white;
        }

        table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        table tr:hover {
            background-color: #ddd;
        }
    </style>
</head>
<body>

<div class="search-form">
    <form action="view_details.php" method="get">
        Search: <input type="text" name="query" value="<?php echo isset($_GET['query']) ? htmlspecialchars($_GET['query']) : ''; ?>">
        <input type="submit" value="Search">
        <div class="dropdown">
            <button class="dropbtn">Sort by &#9660;</button>
            <div class="dropdown-content">
                <a href="view_details.php?sort=name<?php if(isset($_GET['query'])) echo '&query=' . urlencode($_GET['query']); ?>">Name</a>
                <a href="view_details.php?sort=usn<?php if(isset($_GET['query'])) echo '&query=' . urlencode($_GET['query']); ?>">USN</a>
                <a href="view_details.php?sort=phone<?php if(isset($_GET['query'])) echo '&query=' . urlencode($_GET['query']); ?>">Phone Number</a>
            </div>
        </div>
    </form>
</div>

<h2>View Details</h2>

    <!-- Display Records -->
    <table border="1">
        <tr>
            <th>Name</th>
            <th>USN</th>
            <th>Phone Number</th>
            <th>Delete Record</th>
            <th>Update Record</th>
        </tr>

        <?php
        $conn = new mysqli('localhost', 'root', '', 'wshop');
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $search_query = "";
        if (isset($_GET['query'])) {
            $search_query = $_GET['query'];
        }

        $sort_by = "name";
        if (isset($_GET['sort'])) {
            $sort_by = $_GET['sort'];
        }

        $sql = "SELECT * FROM students";
        $result = $conn->query($sql);

        $search_query = "";
        if (isset($_GET['query'])) {
            $search_query = $_GET['query'];
            }

        $sql = "SELECT * FROM students WHERE name LIKE '%$search_query%' OR usn LIKE '%$search_query%' OR phone LIKE '%$search_query%'";

        $sort_by = "name";
        if (isset($_GET['sort'])) {
            $sort_by = $_GET['sort'];
        }

        $sql = "SELECT * FROM students ORDER BY $sort_by";

        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . $row["name"] . "</td>
                        <td>" . $row["usn"] . "</td>
                        <td>" . $row["phone"] . "</td>
                        <td><form action='delete.php' method='post' style='display:inline-block;'>
                                <input type='hidden' name='id' value='" . $row["id"] . "'>
                                <input type='submit' value='Delete'>
                            </form> </td> <td>
                            <form action='update.php' method='post' style='display:inline-block;'>
                                <input type='hidden' name='id' value='" . $row["id"] . "'>
                                <input type='submit' value='Update'>
                            </form>
                            </td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No records found</td></tr>";
        }
        $conn->close();
        ?>
    </table>
</body>
</html>
