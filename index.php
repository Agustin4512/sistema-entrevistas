<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entrevista Inicial - Prevención de Deserción Escolar</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-green: #198754;
            --light-green: #d1e7dd;
        }
        body { background-color: #f8f9fa; }
        .navbar-institutional { background-color: var(--primary-green); }
        
        /* Estilos para el asistente de progreso (Wizard) */
        .step-wizard {
            display: flex;
            justify-content: space-between;
            position: relative;
            margin-bottom: 30px;
        }
        .step-wizard::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            transform: translateY(-50%);
            width: 100%;
            height: 4px;
            background-color: #e9ecef;
            z-index: 1;
        }
        .step-item {
            position: relative;
            z-index: 2;
            text-align: center;
            background-color: #f8f9fa;
            padding: 0 10px;
        }
        .step-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #e9ecef;
            color: #6c757d;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin: 0 auto 8px auto;
            transition: all 0.3s ease;
        }
        .step-item.active .step-circle {
            background-color: var(--primary-green);
            color: white;
            box-shadow: 0 0 0 5px var(--light-green);
        }
        .step-item.completed .step-circle {
            background-color: var(--primary-green);
            color: white;
        }
        .step-label {
            font-size: 0.85rem;
            color: #6c757d;
            font-weight: 600;
        }
        .step-item.active .step-label { color: var(--primary-green); }
        
        .form-section { display: none; }
        .form-section.active { display: block; }

        /* ESTILOS PARA LA NOTIFICACIÓN FLOTANTE PERSONALIZADA */
        #custom-toast {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%) translateY(-100px);
            background-color: #fff3cd;
            color: #856404;
            border-left: 5px solid #ffc107;
            padding: 15px 25px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-weight: 500;
            z-index: 9999;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all 0.4s cubic-bezier(0.68, -0.55, 0.27, 1.55);
            opacity: 0;
        }
        #custom-toast.show {
            transform: translateX(-50%) translateY(0);
            opacity: 1;
        }
        #custom-toast.error {
            background-color: #f8d7da;
            color: #721c24;
            border-left: 5px solid #dc3545;
        }
    </style>
</head>
<body>

    <!-- Notificación Personalizada -->
    <div id="custom-toast">
        <span style="font-size: 20px;" id="toast-icon">⚠️</span>
        <span id="custom-toast-text">Mensaje</span>
    </div>

    <!-- Barra Superior Institucional -->
    <nav class="navbar navbar-dark navbar-institutional shadow-sm mb-4">
        <div class="container">
            <span class="navbar-brand mb-0 h1">📚 Sistema de Prevención de Deserción Escolar</span>
            <span class="text-white">Entrevista Inicial</span>
        </div>
    </nav>

    <div class="container mb-5">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="card shadow border-0 p-4">
                    <h3 class="text-center mb-4 text-success fw-bold">FORMATO DE ENTREVISTA INICIAL</h3>

                    <!-- Barra de Progreso Visual (4 Pasos) -->
                    <div class="step-wizard">
                        <div class="step-item active" id="indicator-1">
                            <div class="step-circle">1</div>
                            <div class="step-label">Generales</div>
                        </div>
                        <div class="step-item" id="indicator-2">
                            <div class="step-circle">2</div>
                            <div class="step-label">Socioeconómicos</div>
                        </div>
                        <div class="step-item" id="indicator-3">
                            <div class="step-circle">3</div>
                            <div class="step-label">Personales</div>
                        </div>
                        <div class="step-item" id="indicator-4">
                            <div class="step-circle">4</div>
                            <div class="step-label">Académicos</div>
                        </div>
                    </div>

                    <!-- Formulario Único con Envío al Backend -->
                    <form action="procesar_entrevista.php" method="POST" id="entrevistaForm" onsubmit="return validarEnvioFinal(event)">
                        
                        <!-- PASO 1: Datos Generales -->
                        <div class="form-section active" id="section-1">
                            <h5 class="border-bottom pb-2 mb-3 text-secondary">1. Datos Generales del Alumno</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Nombre Completo</label>
                                    <!-- oninput solo permite letras y espacios -->
                                    <input type="text" class="form-control" name="nombre" required oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ ]/g, '')" placeholder="Nombre ApellidoMaterno ApellidoPaterno">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Matrícula</label>
                                    <!-- Puede ser alfanumérico, tal como 2434D021 -->
                                    <input type="text" class="form-control" name="matricula" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Programa Educativo / Carrera</label>
                                    <!-- Select con las 3 carreras -->
                                    <select class="form-select" name="programa_educativo" required>
                                        <option value="" disabled selected>Seleccione una carrera...</option>
                                        <option value="Licenciatura en Ingeniería en Tecnologías de la Información e Innovación Digital">Licenciatura en Ingeniería en Tecnologías de la Información e Innovación Digital</option>
                                        <option value="Licenciatura en Contabilidad">Licenciatura en Contabilidad</option>
                                        <option value="Licenciatura en Educación">Licenciatura en Educación</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Grupo Escolar</label>
                                    <!-- Select con A, B, C -->
                                    <select class="form-select" name="grupo_escolar" required>
                                        <option value="" disabled selected>Grupo...</option>
                                        <option value="A">A</option>
                                        <option value="B">B</option>
                                        <option value="C">C</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Edad</label>
                                    <!-- min 19 y sin flechas para subir/bajar -->
                                    <input type="number" class="form-control" name="edad" required min="19" placeholder="Edad" oninput="this.value = Math.abs(this.value)">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Estado Civil</label>
                                    <select class="form-select" name="estado_civil">
                                        <option value="Soltero/a">Soltero/a</option>
                                        <option value="Casado/a">Casado/a</option>
                                        <option value="Unión Libre">Unión Libre</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Teléfono de Contacto</label>
                                    <!-- Máximo 10 dígitos -->
                                    <input type="tel" class="form-control" name="telefono" required maxlength="10" placeholder="10 dígitos" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Dirección Actual</label>
                                    <input type="text" class="form-control" name="direccion" required>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">Nombre del Tutor o Contacto de Emergencia</label>
                                    <input type="text" class="form-control" name="nombre_tutor" required oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ ]/g, '')">
                                </div>
                            </div>
                            <div class="d-flex justify-content-end mt-4">
                                <button type="button" class="btn btn-success px-4" onclick="nextStep(1)">Siguiente &raquo;</button>
                            </div>
                        </div>

                        <!-- PASO 2: Aspectos Socioeconómicos -->
                        <div class="form-section" id="section-2">
                            <h5 class="border-bottom pb-2 mb-3 text-secondary">2. Aspectos Socioeconómicos</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">¿Reside en la ciudad de la escuela?</label>
                                    <select class="form-select" name="reside_ciudad">
                                        <option value="Sí">Sí</option>
                                        <option value="No">No (Es foráneo)</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">¿Con quién vive actualmente?</label>
                                    <input type="text" class="form-control" name="vive_con" required placeholder="Ej. Padres, Familiares, Solo/a">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">¿Trabaja actualmente?</label>
                                    <select class="form-select" name="trabaja">
                                        <option value="No">No</option>
                                        <option value="Sí, medio tiempo">Sí, medio tiempo</option>
                                        <option value="Sí, tiempo completo">Sí, tiempo completo</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Ingreso Familiar Mensual Aproximado</label>
                                    <!-- SOLO NÚMEROS -->
                                    <input type="text" class="form-control" name="ingreso_familiar" required placeholder="Ej. 5000" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">Tipo de Vivienda</label>
                                    <input type="text" class="form-control" name="tipo_casa" required placeholder="Propia, Rentada, Prestada">
                                </div>
                            </div>
                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-secondary px-4" onclick="prevStep(2)">&laquo; Anterior</button>
                                <button type="button" class="btn btn-success px-4" onclick="nextStep(2)">Siguiente &raquo;</button>
                            </div>
                        </div>

                        <!-- PASO 3: Aspectos Personales -->
                        <div class="form-section" id="section-3">
                            <h5 class="border-bottom pb-2 mb-3 text-secondary">3. Aspectos Personales y de Salud</h5>
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="form-label">¿Padece alguna enfermedad crónica o condición médica importante?</label>
                                    <textarea class="form-control" name="enfermedades" rows="2" required placeholder="Describa si aplica o escriba 'Ninguna'"></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">¿Fuma?</label>
                                    <select class="form-select" name="fuma">
                                        <option value="No">No</option>
                                        <option value="Sí">Sí</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Consumo de bebidas alcohólicas</label>
                                    <select class="form-select" name="alcohol">
                                        <option value="No consume">No consume</option>
                                        <option value="Ocasional">Ocasional</option>
                                        <option value="Frecuente">Frecuente</option>
                                    </select>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">Mencione sus principales cualidades y pasatiempos</label>
                                    <textarea class="form-control" name="cualidades" rows="2" required></textarea>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-secondary px-4" onclick="prevStep(3)">&laquo; Anterior</button>
                                <button type="button" class="btn btn-success px-4" onclick="nextStep(3)">Siguiente &raquo;</button>
                            </div>
                        </div>

                        <!-- PASO 4: Aspectos Académicos -->
                        <div class="form-section" id="section-4">
                            <h5 class="border-bottom pb-2 mb-3 text-secondary">4. Aspectos Académicos</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Escuela de procedencia (Bachillerato)</label>
                                    <!-- Aquí sí se permiten números porque hay escuelas como "CBTIS 123" -->
                                    <input type="text" class="form-control" name="bachillerato" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Promedio obtenido anterior</label>
                                    <!-- Solo números y punto decimal -->
                                    <input type="number" step="0.1" class="form-control" name="promedio" min="0" max="10" required placeholder="Ej. 8.5" oninput="this.value = Math.abs(this.value)">
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">¿Qué materias o áreas se le dificultan más?</label>
                                    <input type="text" class="form-control" name="materias_dificiles" required placeholder="Ej. Matemáticas, Programación, Inglés">
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">¿Ha reprobado materias anteriormente o dejado de estudiar algún periodo?</label>
                                    <!-- Aquí le agregué el required -->
                                    <textarea class="form-control" name="reprobado" rows="2" required placeholder="Escriba 'No' si no ha reprobado"></textarea>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-secondary px-4" onclick="prevStep(4)">&laquo; Anterior</button>
                                <button type="submit" class="btn btn-success px-4 fw-bold">Guardar Entrevista Completa 💾</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Script de Navegación y Validaciones -->
    <script>
        function nextStep(current) {
            if(validarSeccion(current)) {
                document.getElementById(`section-${current}`).classList.remove('active');
                document.getElementById(`section-${current + 1}`).classList.add('active');

                document.getElementById(`indicator-${current}`).classList.remove('active');
                document.getElementById(`indicator-${current}`).classList.add('completed');
                document.getElementById(`indicator-${current + 1}`).classList.add('active');
                window.scrollTo(0, 0);
            }
        }

        function prevStep(current) {
            document.getElementById(`section-${current}`).classList.remove('active');
            document.getElementById(`section-${current - 1}`).classList.add('active');

            document.getElementById(`indicator-${current}`).classList.remove('active');
            document.getElementById(`indicator-${current - 1}`).classList.remove('completed');
            document.getElementById(`indicator-${current - 1}`).classList.add('active');
            window.scrollTo(0, 0);
        }

        // Función reutilizable para validar una sección en específico
        function validarSeccion(seccionId) {
            const currentSection = document.getElementById(`section-${seccionId}`);
            const inputs = currentSection.querySelectorAll('input, select, textarea');
            let valid = true;
            let errorMessage = "Por favor complete todos los campos correctamente.";

            inputs.forEach(input => {
                if (input.hasAttribute('required') && !input.value.trim()) {
                    valid = false;
                    input.classList.add('is-invalid');
                } 
                else if (input.name === 'edad' && parseInt(input.value) < 19) {
                    valid = false;
                    input.classList.add('is-invalid');
                    errorMessage = "La edad debe ser mayor o igual a 19 años.";
                } 
                else if (input.name === 'telefono' && input.value.length !== 10) {
                    valid = false;
                    input.classList.add('is-invalid');
                    errorMessage = "El teléfono debe tener exactamente 10 dígitos.";
                } 
                else {
                    input.classList.remove('is-invalid');
                }
            });

            if (!valid) {
                mostrarAlerta(errorMessage, 'error');
                return false;
            }
            return true;
        }

        // Validación final antes de enviar al servidor
        function validarEnvioFinal(event) {
            // Validamos TODAS las secciones antes de enviar
            let todoValido = true;
            let primerPasoConError = 1;

            for (let i = 1; i <= 4; i++) {
                if (!validarSeccion(i)) {
                    todoValido = false;
                    // Guardamos el primer paso que tenga error para regresar al usuario allí
                    if (todoValido === false && primerPasoConError === 1) {
                        primerPasoConError = i;
                    }
                }
            }

            if (!todoValido) {
                // Prevenimos el envío
                event.preventDefault();
                
                // Si el error está en otro paso, lo movemos a ese paso
                if (primerPasoConError !== 4) {
                    // Ocultamos la sección actual
                    document.querySelectorAll('.form-section').forEach(sec => sec.classList.remove('active'));
                    document.querySelectorAll('.step-item').forEach(step => step.classList.remove('active'));
                    
                    // Mostramos la sección con error
                    document.getElementById(`section-${primerPasoConError}`).classList.add('active');
                    document.getElementById(`indicator-${primerPasoConError}`).classList.add('active');
                    window.scrollTo(0, 0);
                }
                
                mostrarAlerta("Debe rellenar todos los campos en todas las secciones antes de guardar.", 'error');
                return false;
            }

            // Si todo está bien, permitimos el envío
            return true;
        }

        // FUNCIÓN PARA MOSTRAR LA NOTIFICACIÓN FLOTANTE
        function mostrarAlerta(mensaje, tipo = 'warning') {
            const toast = document.getElementById('custom-toast');
            const toastText = document.getElementById('custom-toast-text');
            const toastIcon = document.getElementById('toast-icon');
            
            toastText.innerText = mensaje;
            
            if (tipo === 'error') {
                toast.classList.add('error');
                toastIcon.innerText = "🚫";
            } else {
                toast.classList.remove('error');
                toastIcon.innerText = "⚠️";
            }

            toast.classList.add('show');

            setTimeout(() => {
                toast.classList.remove('show');
            }, 3500);
        }
    </script>
</body>
</html>