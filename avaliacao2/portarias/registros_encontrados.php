<?php
require_once('header.php');
require_once('dados_banco.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    header("Location: registros.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['placa_id'])) {
    $placa_id = $_POST['placa_id'];

    try {
        $dsn = "mysql:host=$servername;dbname=$dbname";
        $conn = new PDO($dsn, $username, $password);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT veiculos.placa, registro.data_hora
                FROM veiculos
                INNER JOIN registro ON veiculos.id = registro.veiculos_id
                WHERE veiculos.id = :placa_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':placa_id', $placa_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo $sql . "<br>" . $e->getMessage();
    }

    $conn = NULL;
}
?>

<!DOCTYPE html>
<html lang="pt_BR">
<head>
    <meta charset="UTF-8">
    <title>Portaria Fatec</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; text-align: center; }
    </style>
</head>
<body>
    <div class="page-header">
        <h2>
            <?php echo $_SESSION["username"]; ?>
            <br>
        </h2>
    </div>
    <p>
        <div class="form-group">
            <label>Data e Hora em que existe registro de entrada/saída</label>
            <br>
            <?php
                if (isset($result) && !empty($result)) {
                    echo "<div class='form-group'>";
                   ;
                foreach ($result as $row) {
                     echo "Placa: " . $row['placa'] . "<br>";
                     echo "Data e Hora: " . $row['data_hora'] . "<br>";
                     echo "<br>";
    }
    echo "</div>";
} else {
    echo "Nenhum registro encontrado para esta placa.";
}
?>

        </div>
        <a href="registros.php" class="btn btn-primary">Voltar</a>
        <br><br>
    </p>
</body>
</html>
