<?php

$host = 'localhost';
$user = 'root';
$password = '';
$database = 'api';

$conection = new mysqli($host, $user, $password, $database);

if ($conection->connect_error) {
  die('Conexión no establecida' . $conection->connect_error);
}

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
// print_r($method);
$path = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO']: '/';
$searchId = explode('/', $path);
$id = ($path !== '/') ? end($searchId): null;

switch ($method) {
    // SELECT
  case 'GET':
    // echo 'Consulta de registros - GET ';
    select($conection, $id);
    break;
    // INSERT
  case 'POST':
    // echo 'Consulta de registros - POST';
    insert($conection);
    break;
    // UPDATE
  case 'PUT':
    put($conection, $id);
    // echo 'Edición de registros - PUT';
    break;
    // DELETE
  case 'DELETE':
    delete($conection, $id);
    // echo 'Borrado de registros - DELETE';
    break;
  default:
    echo 'Método no permitido';
    break;
}

function select($conection, $id){
  $sql = ($id === null) ? "SELECT id FROM usuarios" : "SELECT * FROM usuarios WHERE id = $id";
  $result = $conection -> query($sql);

  if ($result) {
    $data = array();
    while($row = $result -> fetch_assoc()){
      $data[] = $row;
    }
    echo json_encode($data);
  }
}

function insert($conection){
  $data = json_decode(file_get_contents('php://input'), true);
  $name = $data['nombre'];

  $sql = "INSERT INTO usuarios(nombre) VALUES ('$name')";
  $result = $conection -> query($sql);

  if ($result) {
    $data['id'] = $conection->insert_id;
    echo json_encode($data);
  }else{
    echo json_encode(array('error'=>'Error al crear el usuario'));
  }
}

function delete($conection, $id){
  // echo 'El ID a borrar es el ' . $id;

  $sql = "DELETE FROM usuarios WHERE id = $id";
  $result = $conection -> query($sql);
  
  if ($result) {
    echo json_encode(array('mensaje' => 'Usuario borrado'));
  }else{
    echo json_encode(array('error' => 'Error al borrar el usuario'));
  }
}

function put($conection, $id){
  $data = json_decode(file_get_contents('php://input'), true);
  $name = $data['nombre'];

  echo 'El ID a editar es el ' . $id . ' con el dato ' . $name;
  
  $sql = "UPDATE usuarios SET nombre = '$name' WHERE id = $id";
  $result = $conection -> query($sql);

  if ($result) {
    echo json_encode(array('mensaje' => 'Usuario actualizado'));
  }else{
    echo json_encode(array('error' => 'Error al actualizar el usuario'));
  }
}