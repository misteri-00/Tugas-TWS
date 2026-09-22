<?php
header("Content-Type: application/json");

// Data Dummy Tenaga Kependidikan (Tendik)
$tendik_list = [
    [
        "id" => 1,
        "nip" => "19880315201501",
        "name" => "Bambang Kurniawan, A.Md.",
        "unit" => "Laboratorium Komputer",
        "role" => "Laboran"
    ],
    [
        "id" => 2,
        "nip" => "19910722201802",
        "name" => "Rina Widiyanti, S.Sos.",
        "unit" => "Perpustakaan Pusat",
        "role" => "Pustakawan"
    ],
];

$method = $_SERVER['REQUEST_METHOD'];


if ($method === "GET") {
    if (isset($_GET['id'])) {
        $id = (int)$_GET['id'];
        foreach ($tendik_list as $t) {
            if ($t['id'] === $id) {
                http_response_code(200);
                echo json_encode($t);
                exit;
            }
        }
        http_response_code(404);
        echo json_encode(["error" => "Data Tendik dengan ID $id tidak ditemukan"]);
        exit;
    }

    http_response_code(200);
    echo json_encode($tendik_list);
    exit;
}


if ($method === "POST") {
    $input = json_decode(file_get_contents("php://input"), true);

    if (!isset($input['nip']) || !isset($input['name']) || !isset($input['unit']) || !isset($input['role'])) {
        http_response_code(400);
        echo json_encode(["error" => "Field nip, name, unit, dan role wajib diisi"]);
        exit;
    }

    $newTendik = [
        "id" => count($tendik_list) + 1,
        "nip" => $input['nip'],
        "name" => $input['name'],
        "unit" => $input['unit'],
        "role" => $input['role']
    ];

    http_response_code(201);
    echo json_encode($newTendik);
    exit;
}

if ($method === "PUT") {
    if (!isset($_GET['id'])) {
        http_response_code(400);
        echo json_encode(["error" => "Parameter ID wajib disertakan (contoh: /tendik.php?id=1)"]);
        exit;
    }

    $id = (int)$_GET['id'];
    $input = json_decode(file_get_contents("php://input"), true);

    if (!isset($input['nip']) || !isset($input['name']) || !isset($input['unit']) || !isset($input['role'])) {
        http_response_code(400);
        echo json_encode(["error" => "Field nip, name, unit, dan role wajib diisi"]);
        exit;
    }

    $found = false;
    foreach ($tendik_list as $t) {
        if ($t['id'] === $id) {
            $found = true;
            break;
        }
    }

    if (!$found) {
        http_response_code(404);
        echo json_encode(["error" => "Data Tendik dengan ID $id tidak ditemukan"]);
        exit;
    }

    $updatedTendik = array_merge(["id" => $id], $input);

    http_response_code(200);
    echo json_encode([
        "message" => "Data Tendik berhasil diperbarui",
        "data" => $updatedTendik
    ]);
    exit;
}

if ($method === "DELETE") {
    if (!isset($_GET['id'])) {
        http_response_code(400);
        echo json_encode(["error" => "Parameter ID wajib disertakan (contoh: /tendik.php?id=1)"]);
        exit;
    }

    $id = (int)$_GET['id'];
    $found = false;

    foreach ($tendik_list as $t) {
        if ($t['id'] === $id) {
            $found = true;
            break;
        }
    }

    if (!$found) {
        http_response_code(404);
        echo json_encode(["error" => "Data Tendik dengan ID $id tidak ditemukan"]);
        exit;
    }

    http_response_code(200);
    echo json_encode([
        "message" => "Data Tendik dengan ID $id berhasil dihapus"
    ]);
    exit;
}


http_response_code(405);
echo json_encode(["error" => "Method tidak diizinkan"]);