<?php

$base_url = "https://n6c8z4dtf8.execute-api.ap-south-1.amazonaws.com/dev/user";
$response = "";
$formattedResponse = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $action = $_POST['action'];
    $userId = $_POST['userId'] ?? "";
    $name   = $_POST['name'] ?? "";
    $email  = $_POST['email'] ?? "";

    $url = $base_url;
    $data = [];
    $method = "GET";

    if ($action == "create") {
        $data = ["userId"=>$userId,"name"=>$name,"email"=>$email];
        $method = "POST";
    }

    if ($action == "read_one") {
        $url = $base_url . "/" . $userId;
    }

    if ($action == "update") {
        $url = $base_url . "/" . $userId;
        $data = ["name"=>$name,"email"=>$email];
        $method = "PUT";
    }

    if ($action == "delete") {
        $url = $base_url . "/" . $userId;
        $method = "DELETE";
    }

    if ($action == "read_all") {
        $method = "GET";
    }

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);

    if ($method == "POST") {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    }

    if ($method == "PUT" || $method == "DELETE") {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        if (!empty($data)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
    }

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        $response = "Curl error: " . curl_error($ch);
    }

    curl_close($ch);

    $formattedResponse = json_encode(json_decode($response, true), JSON_PRETTY_PRINT);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User CRUD - Modern UI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">

    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">User CRUD Dashboard</h4>
        </div>
        <div class="card-body">

            <form method="POST">
                <div class="row mb-3">
                    <div class="col">
                        <input type="text" name="userId" class="form-control" placeholder="User ID">
                    </div>
                    <div class="col">
                        <input type="text" name="name" class="form-control" placeholder="Name">
                    </div>
                    <div class="col">
                        <input type="email" name="email" class="form-control" placeholder="Email">
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button name="action" value="create" class="btn btn-success">Create</button>
                    <button name="action" value="read_all" class="btn btn-primary">Read All</button>
                    <button name="action" value="read_one" class="btn btn-info text-white">Read One</button>
                    <button name="action" value="update" class="btn btn-warning">Update</button>
                    <button name="action" value="delete" class="btn btn-danger">Delete</button>
                </div>
            </form>

        </div>
    </div>

    <?php if($formattedResponse): ?>
    <div class="card mt-4 shadow">
        <div class="card-header bg-dark text-white">
            API Response
        </div>
        <div class="card-body">
            <pre class="bg-light p-3 rounded"><?php echo $formattedResponse; ?></pre>
        </div>
    </div>
    <?php endif; ?>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
