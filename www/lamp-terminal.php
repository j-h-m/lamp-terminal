<!DOCTYPE html>
<html lang="en">

<head>
  <title>LAMP Terminal</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>

<body>

  <div class="jumbotron text-center">
    <h1 data-toggle="tooltip" title="Linux Apache MySQL PHP">LAMP Terminal</h1>

  </div>

  <div class="container">
    <div class="row" style="min-height: 100vh;">
      <div class="col-sm-6">
        <form action="" method="get">
          <h3 data-toggle="tooltip" title="Enter commands here">Input</h3>
          <input type="text" name="input" class="form-control" id="input" placeholder="command...">
          <br>
          <button type="submit" class="btn btn-primary" data-toggle="tooltip" title="Send command to server">Send</button>
          <?php
          if (isset($_GET["input"])) {
            $input = $_GET["input"];
            try {
              $result = passthru($input);
              // echo "<br><br><p>$result</p>";
              // save result to database
              $server = "172.20.0.2";
              $user = "lamp";
              $password = "lamp";
              $database = "lamp";
              $connect = new mysqli(
                $server,
                $user,
                $password,
                $database
              );
              if ($connect->connect_error) {
                die("Connection failed: " . $connect->connect_error);
              }

              function run_sql($sql, $connect)
              {
                $res = $connect->query($sql);
                if ($res === FALSE) {
                  echo "Error: " . $sql . "<br>" . $connect->error;
                } else {
                  echo "<br><p>Res: " . $res . "<p><br>";
                }
              }
              // create table
              $create_sql = "CREATE TABLE IF NOT EXISTS `lamp`.`command_history` (
                `id` MEDIUMINT NOT NULL AUTO_INCREMENT,
                `command` VARCHAR(256) NULL,
                `dt_created` DATETIME NULL,
                PRIMARY KEY (`id`));";
              run_sql($create_sql, $connect);
              // insert record
              $insert_sql = "INSERT INTO command_history (command, dt_created)
                VALUES ('$input', CURRENT_TIMESTAMP());";
              run_sql($insert_sql, $connect);
              $connect->close(); // close connection when finished
            } catch (Error $error) {
              echo "<br><br><p class=mt-3><b>$error</b></p>";
            }
          }
          ?>
        </form>
      </div>
      <div class="col-sm-6">
        <h3 data-toggle="tooltip" title="Output from commands appear here">Command History</h3>
        <?php
        // save result to database
        $server = "172.20.0.2";
        $user = "lamp";
        $password = "lamp";
        $database = "lamp";
        $connect = new mysqli(
          $server,
          $user,
          $password,
          $database
        );
        if ($connect->connect_error) {
          die("Connection failed: " . $connect->connect_error);
        }

        function run_sql($sql, $connect)
        {
          if ($connect->query($sql) === FALSE) {
            echo "Error: " . $sql . "<br>" . $connect->error;
          }
        }
        // create table
        $create_sql = "CREATE TABLE IF NOT EXISTS `lamp`.`command_history` (
                `id` MEDIUMINT NOT NULL AUTO_INCREMENT,
                `command` VARCHAR(256) NULL,
                `dt_created` DATETIME NULL,
                PRIMARY KEY (`id`));";
        run_sql($create_sql, $connect);

        // select data
        $select_sql = "select * from command_history;";
        $res = $connect->query($select_sql);
        $connect->close(); // close connection when finished

        if ($res->num_rows > 0) {
          echo "<ul>";
          while ($row = $res->fetch_assoc()) {
            echo "<li>" . $row["id"]  . " " . $row["command"] . $row["dt_created"]  . " " . "</li>";
          }
          echo "</ul>";
        }
        ?>
      </div>
    </div>
  </div>

</body>

</html>