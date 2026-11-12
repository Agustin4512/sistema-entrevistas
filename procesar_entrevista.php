<?php
// procesar_entrevista.php - Versión nativa REST

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // 1. Cargar y leer las credenciales del archivo JSON de Firebase
        $credentialsJson = __DIR__ . '/firebase_credentials.json';
        if (!file_exists($credentialsJson)) {
            throw new Exception("No se encontró el archivo de credenciales 'firebase_credentials.json'.");
        }

        $creds = json_decode(file_get_contents($credentialsJson), true);
        $projectId = $creds['project_id'] ?? '';
        $clientEmail = $creds['client_email'] ?? '';
        $privateKey = $creds['private_key'] ?? '';
        $privateKey = str_replace("\\n", "\n", $privateKey);

        if (empty($projectId) || empty($clientEmail) || empty($privateKey)) {
            throw new Exception("El archivo de credenciales JSON está incompleto o es inválido.");
        }

        // 2. Generar un Token de Acceso OAuth 2.0
        $token = generarAccessTokenJWT($clientEmail, $privateKey);

        if (!$token) {
            throw new Exception("No se pudo generar el token de autenticación para Firebase.");
        }

        // 3. Recoger los datos del formulario
        $matricula = trim($_POST['matricula'] ?? 'sin_matricula');
        $rasgos = isset($_POST['rasgos']) ? implode(', ', $_POST['rasgos']) : 'Ninguno';

        $datosEntrevista = [
            // SECCIÓN 1
            'nombre'             => ['stringValue' => $_POST['nombre'] ?? ''],
            'matricula'          => ['stringValue' => $matricula],
            'programa_educativo' => ['stringValue' => $_POST['programa_educativo'] ?? ''],
            'grupo_escolar'      => ['stringValue' => $_POST['grupo_escolar'] ?? ''],
            'edad'               => ['stringValue' => $_POST['edad'] ?? ''],
            'estado_civil'       => ['stringValue' => $_POST['estado_civil'] ?? ''],
            'telefono'           => ['stringValue' => $_POST['telefono'] ?? ''],
            'direccion'          => ['stringValue' => $_POST['direccion'] ?? ''],
            'nombre_tutor'       => ['stringValue' => $_POST['nombre_tutor'] ?? ''],

            // SECCIÓN 2
            'reside_ciudad'      => ['stringValue' => $_POST['reside_ciudad'] ?? ''],
            'vive_con'           => ['stringValue' => $_POST['vive_con'] ?? ''],
            'trabaja'            => ['stringValue' => $_POST['trabaja'] ?? ''],
            'detalles_trabajo'   => ['stringValue' => $_POST['detalles_trabajo'] ?? ''],
            'dependencia_economica' => ['stringValue' => $_POST['dependencia_economica'] ?? ''],
            'ingreso_familiar'   => ['stringValue' => $_POST['ingreso_familiar'] ?? ''],
            'ocupacion_papa'     => ['stringValue' => $_POST['ocupacion_papa'] ?? ''],
            'ocupacion_mama'     => ['stringValue' => $_POST['ocupacion_mama'] ?? ''],
            'hermanos'           => ['stringValue' => $_POST['hermanos'] ?? ''],
            'tipo_casa'          => ['stringValue' => $_POST['tipo_casa'] ?? ''],

            // SECCIÓN 3
            'enfermedades'       => ['stringValue' => $_POST['enfermedades'] ?? ''],
            'frecuencia_enfermedades' => ['stringValue' => $_POST['frecuencia_enfermedades'] ?? ''],
            'rasgos'             => ['stringValue' => $rasgos],
            'medicamentos'       => ['stringValue' => $_POST['medicamentos'] ?? ''],
            'fuma'               => ['stringValue' => $_POST['fuma'] ?? ''],
            'alcohol'            => ['stringValue' => $_POST['alcohol'] ?? ''],
            'cualidades'         => ['stringValue' => $_POST['cualidades'] ?? ''],
            'defectos'           => ['stringValue' => $_POST['defectos'] ?? ''],
            'valores'            => ['stringValue' => $_POST['valores'] ?? ''],
            'disgusta'           => ['stringValue' => $_POST['disgusta'] ?? ''],
            'temores'            => ['stringValue' => $_POST['temores'] ?? ''],
            'novio'              => ['stringValue' => $_POST['novio'] ?? ''],
            'planes_matrimonio'  => ['stringValue' => $_POST['planes_matrimonio'] ?? ''],
            'planes_futuro'      => ['stringValue' => $_POST['planes_futuro'] ?? ''],
            'planes_academico'   => ['stringValue' => $_POST['planes_academico'] ?? ''],
            'planes_profesional' => ['stringValue' => $_POST['planes_profesional'] ?? ''],
            'tiempo_libre'       => ['stringValue' => $_POST['tiempo_libre'] ?? ''],

            // SECCIÓN 4
            'bachillerato'       => ['stringValue' => $_POST['bachillerato'] ?? ''],
            'turno'              => ['stringValue' => $_POST['turno'] ?? ''],
            'localidad'          => ['stringValue' => $_POST['localidad'] ?? ''],
            'entidad'            => ['stringValue' => $_POST['entidad'] ?? ''],
            'promedio'           => ['stringValue' => $_POST['promedio'] ?? ''],
            'porque_universidad' => ['stringValue' => $_POST['porque_universidad'] ?? ''],
            'primera_opcion'     => ['stringValue' => $_POST['primera_opcion'] ?? ''],
            'carrera_primera_opcion' => ['stringValue' => $_POST['carrera_primera_opcion'] ?? ''],
            'esperas_carrera'    => ['stringValue' => $_POST['esperas_carrera'] ?? ''],
            'planes_otra_escuela' => ['stringValue' => $_POST['planes_otra_escuela'] ?? ''],
            'materias_dificiles' => ['stringValue' => $_POST['materias_dificiles'] ?? ''],
            'reprobado'          => ['stringValue' => $_POST['reprobado'] ?? ''],
            'tecnica_estudio'    => ['stringValue' => $_POST['tecnica_estudio'] ?? ''],
            'libros_casa'        => ['stringValue' => $_POST['libros_casa'] ?? ''],
            'tiene_computadora'  => ['stringValue' => $_POST['tiene_computadora'] ?? ''],
            
            'fecha_registro'     => ['stringValue' => date('Y-m-d H:i:s')]
        ];

        $documentoFirestore = [ 'fields' => $datosEntrevista ];

        // 4. Enviar los datos a Firestore
        $url = "https://firestore.googleapis.com/v1/projects/{$projectId}/databases/(default)/documents/entrevistas/" . urlencode($matricula);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($documentoFirestore));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json'
        ]);

        if ($_SERVER['SERVER_NAME'] === 'localhost' || $_SERVER['SERVER_NAME'] === '127.0.0.1') {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        // PANTALLA DE ÉXITO
        if ($httpCode >= 200 && $httpCode < 300) {
            echo "<!DOCTYPE html>
            <html lang='es'>
            <head>
                <meta charset='UTF-8'>
                <title>Registro Exitoso</title>
                <style>
                    body { font-family: 'Segoe UI', sans-serif; background-color: #f4f7f6; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
                    .card { background: white; padding: 40px; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); text-align: center; max-width: 400px; }
                    .icon { font-size: 60px; color: #28a745; margin-bottom: 20px; }
                    h2 { color: #333; margin-bottom: 10px; }
                    p { color: #666; font-size: 16px; margin-bottom: 30px; }
                    .btn { background-color: #007bff; color: white; text-decoration: none; padding: 12px 25px; border-radius: 8px; font-weight: bold; display: inline-block; }
                    .btn:hover { background-color: #0056b3; }
                </style>
            </head>
            <body>
                <div class='card'>
                    <div class='icon'>✅</div>
                    <h2>¡Registro Exitoso!</h2>
                    <p>Sus datos han sido registrados exitosamente.</p>
                    <a href='index.php' class='btn'>Volver al inicio</a>
                </div>
            </body>
            </html>";
            exit();
        } else {
            throw new Exception("Error en Firestore (Código HTTP $httpCode). Detalle: " . ($curlError ?: $response));
        }

    } catch (\Exception $e) {
        echo "<div style='font-family: Arial; padding: 20px; color: #721c24; background-color: #f8d7da; border: 1px solid #f5c6cb;'>";
        echo "<h3>Ocurrió un error:</h3>";
        echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
        echo "<br><a href='index.php'>Regresar al formulario</a>";
        echo "</div>";
    }
} else {
    header('Location: index.php');
    exit();
}

function generarAccessTokenJWT($clientEmail, $privateKey) {
    $header = json_encode(['alg' => 'RS256', 'typ' => 'JWT']);
    $now = time();
    $payload = json_encode([
        'iss'   => $clientEmail,
        'scope' => 'https://www.googleapis.com/auth/datastore',
        'aud'   => 'https://oauth2.googleapis.com/token',
        'iat'   => $now,
        'exp'   => $now + 3600
    ]);

    $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
    $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));

    $signature = '';
    openssl_sign($base64UrlHeader . "." . $base64UrlPayload, $signature, $privateKey, OPENSSL_ALGO_SHA256);

    $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
    $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;

    $ch = curl_init('https://oauth2.googleapis.com/token');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
        'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
        'assertion'  => $jwt
    ]));
    
    if ($_SERVER['SERVER_NAME'] === 'localhost' || $_SERVER['SERVER_NAME'] === '127.0.0.1') {
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    }

    $response = curl_exec($ch);
    curl_close($ch);

    $responseData = json_decode($response, true);
    return $responseData['access_token'] ?? false;
}