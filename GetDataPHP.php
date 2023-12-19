<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Obtener datos de API en PHP</title>
  <link rel="stylesheet" href="./styles.css">
</head>

<body>
  <?php
  // Inicializa una nueva sesión de cURL
  $ch = curl_init();

  // URL del endpoint / API a utilizar
  $url = 'https://pokeapi.co/api/v2/pokemon/pikachu/';

  curl_setopt($ch, CURLOPT_URL, $url);

  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

  $response = curl_exec($ch);

  if (curl_errno($ch)) {
    $error_msg = curl_error($ch);
    echo 'Error al conectarse al Endpoint';
  } else {
    curl_close($ch);

    $pokemon_data = json_decode($response, true);

    echo '<h1>' . ucfirst($pokemon_data['name']) . '</h1>';
    echo '<img src="' . $pokemon_data['sprites']['front_default'] . '" alt="' . ucfirst($pokemon_data['name']) . '" />';
    echo '<ul>';
    echo '<li><strong>Nombre: </strong>' . ucfirst($pokemon_data['name']) . '</li>';
    echo '<li><strong>Altura: </strong>' . $pokemon_data['height'] . ' m.</li>';
    echo '<li><strong>Peso: </strong>' . $pokemon_data['weight'] . ' kg.</li>';

    echo '<li><strong>Habilidades: </strong></li>';
    echo '<ul>';
    foreach ($pokemon_data['abilities'] as $ability) {
      echo '<li>Habilidad: ' . ucfirst($ability['ability']['name']) . '</li>';
    }
    echo '</ul>';

    echo '</ul>';
  }



  ?>
</body>

</html>