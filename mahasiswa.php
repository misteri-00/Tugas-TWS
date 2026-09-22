<?php
header("Content-Type: application/json");
require "data.php";

$method = $_SERVER['REQUEST_METHOD'];


if ($method === "GET") {
    if (isset($_GET['id'])) {
        $id = (int)$_GET['id'];
        foreach ($students as $student) {
            if ($student['id'] === $id) {
                http_response_code(200);
                echo json_encode($student);
                exit;
            }
        }
        http_response_code(404);
        echo json_encode(["error" => "Mahasiswa dengan ID $id tidak ditemukan"]);
        exit;
    }

    http_response_code(200);
    echo json_encode($students);
    exit;
}

if ($method === "POST") {
    $input = json_decode(file_get_contents("php://input"), true);

    if (!isset($input['nim']) || !isset($input['name']) || !isset($input['major'])) {
        http_response_code(400);
        echo json_encode(["error" => "Field nim, name, dan major wajib diisi"]);
        exit;
    }

    $newId = count($students) + 1;
    $newStudent = [
        "id" => $newId,
        "nim" => $input['nim'],
        "name" => $input['name'],
        "major" => $input['major'],
    ];

    http_response_code(201);
    header("Location: /mahasiswa.php?id=$newId");
    echo json_encode($newStudent);
    exit;
}

if ($method === "PUT") {
    if (!isset($_GET['id'])) {
        http_response_code(400);
        echo json_encode(["error" => "Parameter ID wajib diberikan (contoh: /mahasiswa.php?id=1)"]);
        exit;
    }

    $id = (int)$_GET['id'];
    $input = json_decode(file_get_contents("php://input"), true);

    $foundIndex = -1;
    foreach ($students as $index => $student) {
        if ($student['id'] === $id) {
            $foundIndex = $index;
            break;
        }
    }

    if ($foundIndex === -1) {
        http_response_code(404);
        echo json_encode(["error" => "Mahasiswa dengan ID $id tidak ditemukan"]);
        exit;
    }

    if (!isset($input['nim']) || !isset($input['name']) || !isset($input['major'])) {
        http_response_code(400);
        echo json_encode(["error" => "Field nim, name, dan major wajib diisi"]);
        exit;
    }

    $updatedStudent = [
        "id" => $id,
        "nim" => $input['nim'],
        "name" => $input['name'],
        "major" => $input['major'],
    ];

    http_response_code(200);
    echo json_encode([
        "message" => "Data mahasiswa berhasil diperbarui",
        "data" => $updatedStudent
    ]);
    exit;
}

if ($method === "DELETE") {
    if (!isset($_GET['id'])) {
        http_response_code(400);
        echo json_encode(["error" => "Parameter ID wajib diberikan (contoh: /mahasiswa.php?id=1)"]);
        exit;
    }

    $id = (int)$_GET['id'];
    $foundIndex = -1;

    foreach ($students as $index => $student) {
        if ($student['id'] === $id) {
            $foundIndex = $index;
            break;
        }
    }

    if ($foundIndex === -1) {
        http_response_code(404);
        echo json_encode(["error" => "Mahasiswa dengan ID $id tidak ditemukan"]);
        exit;
    }

    http_response_code(200);
    echo json_encode([
        "message" => "Data mahasiswa dengan ID $id berhasil dihapus"
    ]);
    exit;
}

http_response_code(405);
echo json_encode(["error" => "Method tidak didukung"]);