<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entrevista Inicial </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root { --primary-green: #50a97f; --light-green: #d1e7dd; }
        body { background-color: #f8f9fa; }
        .navbar-institutional { background-color: var(--primary-green); }
        
        .step-wizard { display: flex; justify-content: space-between; position: relative; margin-bottom: 30px; }
        .step-wizard::before { content: ''; position: absolute; top: 50%; left: 0; transform: translateY(-50%); width: 100%; height: 4px; background-color: #e9ecef; z-index: 1; }
        .step-item { position: relative; z-index: 2; text-align: center; background-color: #f8f9fa; padding: 0 10px; }
        .step-circle { width: 40px; height: 40px; border-radius: 50%; background-color: #e9ecef; color: #6c757d; display: flex; align-items: center; justify-content: center; font-weight: bold; margin: 0 auto 8px auto; transition: all 0.3s ease; }
        .step-item.active .step-circle { background-color: var(--primary-green); color: white; box-shadow: 0 0 0 5px var(--light-green); }
        .step-item.completed .step-circle { background-color: var(--primary-green); color: white; }
        .step-label { font-size: 0.85rem; color: #6c757d; font-weight: 600; }
        .step-item.active .step-label { color: var(--primary-green); }
        
        .form-section { display: none; }
        .form-section.active { display: block; }

        #custom-toast { position: fixed; top: 20px; left: 50%; transform: translateX(-50%) translateY(-100px); background-color: #fff3cd; color: #856404; border-left: 5px solid #ffc107; padding: 15px 25px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-weight: 500; z-index: 9999; display: flex; align-items: center; gap: 12px; transition: all 0.4s cubic-bezier(0.68, -0.55, 0.27, 1.55); opacity: 0; }
        #custom-toast.show { transform: translateX(-50%) translateY(0); opacity: 1; }
        #custom-toast.error { background-color: #f8d7da; color: #721c24; border-left: 5px solid #dc3545; }
        
        .question-label { font-weight: 600; color: #444; margin-bottom: 5px; font-size: 0.95rem; }
    </style>
</head>
<body>

    <div id="custom-toast">
        <span style="font-size: 20px;" id="toast-icon">⚠️</span>
        <span id="custom-toast-text">Mensaje</span>
    </div>

    <nav class="navbar navbar-dark navbar-institutional shadow-sm mb-4">
        <div class="container">
            <!-- ⚠️ AQUÍ VA TU LOGO -->
            <a class="navbar-brand d-flex align-items-center gap-2" href="#">
                <img src="logotv.png" alt="Logo" style="height: 80px; border-radius: 5px;" onerror="this.style.display='none'">
                <span class="mb-0 h1">Sistema Escolar</span>
            </a>
            <span class="text-white d-none d-md-block">Entrevista Inicial</span>
        </div>
    </nav>

    <div class="container mb-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow border-0 p-4">
                    <h3 class="text-center mb-4 text-success fw-bold">FORMATO DE ENTREVISTA INICIAL</h3>

                    <div class="step-wizard">
                        <div class="step-item active" id="indicator-1"><div class="step-circle">1</div><div class="step-label">Generales</div></div>
                        <div class="step-item" id="indicator-2"><div class="step-circle">2</div><div class="step-label">Socioeconómicos</div></div>
                        <div class="step-item" id="indicator-3"><div class="step-circle">3</div><div class="step-label">Personales</div></div>
                        <div class="step-item" id="indicator-4"><div class="step-circle">4</div><div class="step-label">Académicos</div></div>
                    </div>

                    <form action="procesar_entrevista.php" method="POST" id="entrevistaForm" onsubmit="return validarEnvioFinal(event)">
                        
                        <!-- PASO 1: DATOS GENERALES -->
                        <div class="form-section active" id="section-1">
                            <h5 class="border-bottom pb-2 mb-3 text-secondary">1. Datos Generales del Alumno</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Nombre Completo</label>
                                    <input type="text" class="form-control" name="nombre" required oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ ]/g, '')">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Matrícula</label>
                                    <input type="text" class="form-control" name="matricula" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Programa Educativo / Carrera</label>
                                    <select class="form-select" name="programa_educativo" required>
                                        <option value="" disabled selected>Seleccione una carrera...</option>
                                        <option value="Licenciatura en Ingeniería en Tecnologías de la Información e Innovación Digital">Ing. en Tecnologías de la Información e Innovación Digital</option>
                                        <option value="Licenciatura en Contabilidad">Licenciatura en Contabilidad</option>
                                        <option value="Licenciatura en Educación">Licenciatura en Educación</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Grupo Escolar</label>
                                    <select class="form-select" name="grupo_escolar" required>
                                        <option value="" disabled selected>Grupo...</option>
                                        <option value="A">A</option><option value="B">B</option><option value="C">C</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Edad</label>
                                    <input type="number" class="form-control" name="edad" required min="19" placeholder="Min. 19" oninput="this.value = Math.abs(this.value)">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Estado Civil</label>
                                    <select class="form-select" name="estado_civil">
                                        <option value="Soltero/a">Soltero/a</option><option value="Casado/a">Casado/a</option><option value="Unión Libre">Unión Libre</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Teléfono de Contacto</label>
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

                        <!-- PASO 2: SOCIOECONÓMICOS -->
                        <div class="form-section" id="section-2">
                            <h5 class="border-bottom pb-2 mb-3 text-secondary">2. Aspectos Socioeconómicos</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="question-label">1. ¿Reside en esta ciudad?</label>
                                    <select class="form-select" name="reside_ciudad">
                                        <option value="Sí">Sí</option><option value="No (Es foráneo)">No</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="question-label">2. ¿Con quién vive actualmente?</label>
                                    <input type="text" class="form-control" name="vive_con" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="question-label">3. ¿Trabajas actualmente?</label>
                                    <select class="form-select" name="trabaja" id="trabaja_select" onchange="toggleTrabajo()">
                                        <option value="No">No</option><option value="Sí">Sí</option>
                                    </select>
                                </div>
                                <div class="col-md-6" id="trabajo_detalles" style="display:none;">
                                    <label class="question-label">¿En dónde? Ingreso y horas semanales:</label>
                                    <input type="text" class="form-control" name="detalles_trabajo">
                                </div>
                                <div class="col-md-6">
                                    <label class="question-label">4. ¿De quién dependes económicamente?</label>
                                    <input type="text" class="form-control" name="dependencia_economica" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="question-label">Ingreso mensual de quién dependes</label>
                                    <input type="number" class="form-control" name="ingreso_familiar" required oninput="this.value = Math.abs(this.value)" placeholder="Solo números">
                                </div>
                                <div class="col-md-6">
                                    <label class="question-label">5. ¿A qué se dedica tu papá?</label>
                                    <input type="text" class="form-control" name="ocupacion_papa" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="question-label">6. ¿A qué se dedica tu mamá?</label>
                                    <input type="text" class="form-control" name="ocupacion_mama" required>
                                </div>
                                <div class="col-md-12">
                                    <label class="question-label">7. Si tienes hermanos, señala cuántos son y su actividad principal:</label>
                                    <input type="text" class="form-control" name="hermanos" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="question-label">8. La casa que habitas es:</label>
                                    <select class="form-select" name="tipo_casa">
                                        <option value="Propia">Propia</option><option value="Rentada">Rentada</option><option value="Prestada">Prestada</option><option value="Otros">Otros</option>
                                    </select>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-secondary px-4" onclick="prevStep(2)">&laquo; Anterior</button>
                                <button type="button" class="btn btn-success px-4" onclick="nextStep(2)">Siguiente &raquo;</button>
                            </div>
                        </div>

                        <!-- PASO 3: PERSONALES -->
                        <div class="form-section" id="section-3">
                            <h5 class="border-bottom pb-2 mb-3 text-secondary">3. Aspectos Personales y de Salud</h5>
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="question-label">1. ¿Padeces alguna enfermedad o alergia?</label>
                                    <!-- CAMBIO A SELECT -->
                                    <select class="form-select" name="enfermedades" required>
                                        <option value="No">No</option>
                                        <option value="Sí">Sí</option>
                                    </select>
                                </div>
                                <div class="col-md-12">
                                    <label class="question-label">2. ¿Con qué frecuencia presentas enfermedades menores?</label>
                                    <input type="text" class="form-control" name="frecuencia_enfermedades" required>
                                </div>
                                <div class="col-md-12">
                                    <label class="question-label">3. Marca los aspectos observados (si aplica):</label>
                                    <div class="row">
                                        <div class="col-md-4"><div class="form-check"><input class="form-check-input" type="checkbox" name="rasgos[]" value="Obesidad"><label class="form-check-label">Obesidad</label></div></div>
                                        <div class="col-md-4"><div class="form-check"><input class="form-check-input" type="checkbox" name="rasgos[]" value="Delgadez extrema"><label class="form-check-label">Delgadez extrema</label></div></div>
                                        <div class="col-md-4"><div class="form-check"><input class="form-check-input" type="checkbox" name="rasgos[]" value="Manchas en la piel"><label class="form-check-label">Manchas en la piel</label></div></div>
                                        <div class="col-md-4"><div class="form-check"><input class="form-check-input" type="checkbox" name="rasgos[]" value="Falta de energía"><label class="form-check-label">Falta de energía</label></div></div>
                                        <div class="col-md-4"><div class="form-check"><input class="form-check-input" type="checkbox" name="rasgos[]" value="Problemas de dentadura"><label class="form-check-label">Problemas de dentadura</label></div></div>
                                        <div class="col-md-4"><div class="form-check"><input class="form-check-input" type="checkbox" name="rasgos[]" value="Problemas visuales"><label class="form-check-label">Problemas visuales</label></div></div>
                                        <div class="col-md-4"><div class="form-check"><input class="form-check-input" type="checkbox" name="rasgos[]" value="Problemas auditivos"><label class="form-check-label">Problemas auditivos</label></div></div>
                                        <div class="col-md-4"><div class="form-check"><input class="form-check-input" type="checkbox" name="rasgos[]" value="Discapacidades"><label class="form-check-label">Discapacidades</label></div></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="question-label">4. ¿Tomas algún medicamento periódicamente?</label>
                                    <select class="form-select" name="medicamentos" required>
                                        <option value="No">No</option><option value="Sí">Sí</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="question-label">5. ¿Fumas?</label>
                                    <select class="form-select" name="fuma" required>
                                        <option value="No">No</option><option value="Sí">Sí</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="question-label">6. ¿Ingieres bebidas alcohólicas?</label>
                                    <select class="form-select" name="alcohol" required>
                                        <option value="No">No</option><option value="Sí">Sí</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="question-label">7. ¿Cuáles consideras que son tus principales cualidades?</label>
                                    <input type="text" class="form-control" name="cualidades" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="question-label">8. ¿Cuáles consideras que son tus principales defectos?</label>
                                    <input type="text" class="form-control" name="defectos" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="question-label">9. ¿Qué valores aprecias más en la gente?</label>
                                    <input type="text" class="form-control" name="valores" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="question-label">10. ¿Qué es lo que más te disgusta de la gente?</label>
                                    <input type="text" class="form-control" name="disgusta" required>
                                </div>
                                <div class="col-md-12">
                                    <label class="question-label">11. Señala tres situaciones o aspectos que te provocan temor:</label>
                                    <input type="text" class="form-control" name="temores" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="question-label">12. ¿Actualmente tienes novio(a)?</label>
                                    <select class="form-select" name="novio"><option value="No">No</option><option value="Sí">Sí</option></select>
                                </div>
                                <div class="col-md-4">
                                    <label class="question-label">13. ¿Tienes planes de matrimonio a corto plazo?</label>
                                    <!-- CAMBIO A SELECT -->
                                    <select class="form-select" name="planes_matrimonio" required>
                                        <option value="No">No</option>
                                        <option value="Sí">Sí</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="question-label">14. ¿Qué planes tienes para tu futuro personal?</label>
                                    <input type="text" class="form-control" name="planes_futuro" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="question-label">15. ¿Qué planes tienes para tu futuro académico?</label>
                                    <input type="text" class="form-control" name="planes_academico" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="question-label">16. ¿Qué planes tienes para tu futuro profesional?</label>
                                    <input type="text" class="form-control" name="planes_profesional" required>
                                </div>
                                <div class="col-md-12">
                                    <label class="question-label">17. ¿A qué te dedicas en tu tiempo libre?</label>
                                    <input type="text" class="form-control" name="tiempo_libre" required>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-secondary px-4" onclick="prevStep(3)">&laquo; Anterior</button>
                                <button type="button" class="btn btn-success px-4" onclick="nextStep(3)">Siguiente &raquo;</button>
                            </div>
                        </div>

                        <!-- PASO 4: ACADÉMICOS -->
                        <div class="form-section" id="section-4">
                            <h5 class="border-bottom pb-2 mb-3 text-secondary">4. Aspectos Académicos</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="question-label">1. Bachillerato</label>
                                    <input type="text" class="form-control" name="bachillerato" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="question-label">Turno</label>
                                    <!-- CAMBIO A SELECT MATUTINO/VESPERTINO -->
                                    <select class="form-select" name="turno" required>
                                        <option value="" disabled selected>Seleccione turno...</option>
                                        <option value="Matutino">Matutino</option>
                                        <option value="Vespertino">Vespertino</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="question-label">Localidad</label>
                                    <input type="text" class="form-control" name="localidad" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="question-label">Entidad</label>
                                    <input type="text" class="form-control" name="entidad" required value="Puebla">
                                </div>
                                <div class="col-md-6">
                                    <label class="question-label">Promedio</label>
                                    <input type="number" step="0.1" class="form-control" name="promedio" required min="0" max="10">
                                </div>
                                <div class="col-md-12">
                                    <label class="question-label">2. ¿Por qué elegiste estudiar en una Universidad Tecnológica?</label>
                                    <input type="text" class="form-control" name="porque_universidad" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="question-label">3. ¿Esta Universidad era tu primera opción?</label>
                                    <select class="form-select" name="primera_opcion"><option value="Sí">Sí</option><option value="No">No</option></select>
                                </div>
                                <div class="col-md-6">
                                    <label class="question-label">4. ¿Esta carrera era tu primera opción?</label>
                                    <select class="form-select" name="carrera_primera_opcion"><option value="Sí">Sí</option><option value="No">No</option></select>
                                </div>
                                <div class="col-md-12">
                                    <label class="question-label">5. ¿Qué esperas de esta carrera?</label>
                                    <input type="text" class="form-control" name="esperas_carrera" required>
                                </div>
                                <div class="col-md-12">
                                    <label class="question-label">6. ¿Tienes planes de presentar examen de admisión para otra escuela o carrera?</label>
                                    <select class="form-select" name="planes_otra_escuela" required><option value="No">No</option><option value="Sí">Sí</option></select>
                                </div>
                                <div class="col-md-12">
                                    <label class="question-label">7. ¿Qué materias se te dificultan más?</label>
                                    <input type="text" class="form-control" name="materias_dificiles" required>
                                </div>
                                <div class="col-md-12">
                                    <label class="question-label">8. ¿Has reprobado alguna materia o presentado examen extraordinario?</label>
                                    <select class="form-select" name="reprobado" required><option value="No">No</option><option value="Sí">Sí</option></select>
                                </div>
                                <div class="col-md-12">
                                    <label class="question-label">9. ¿Utilizas alguna manera o técnica de estudio?</label>
                                    <select class="form-select" name="tecnica_estudio" required><option value="No">No</option><option value="Sí">Sí</option></select>
                                </div>
                                <div class="col-md-12">
                                    <label class="question-label">10. ¿Cuentas en tu casa con algunos libros que apoyan tus estudios?</label>
                                    <select class="form-select" name="libros_casa" required><option value="No">No</option><option value="Sí">Sí</option></select>
                                </div>
                                <div class="col-md-12">
                                    <label class="question-label">11. ¿Tienes computadora en tu casa como apoyo para tus trabajos y tareas escolares?</label>
                                    <select class="form-select" name="tiene_computadora" required><option value="No">No</option><option value="Sí">Sí</option></select>
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

    <script>
        function toggleTrabajo() {
            const select = document.getElementById('trabaja_select');
            const detalles = document.getElementById('trabajo_detalles');
            detalles.style.display = select.value === 'Sí' ? 'block' : 'none';
        }

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

        function validarSeccion(seccionId) {
            const currentSection = document.getElementById(`section-${seccionId}`);
            const inputs = currentSection.querySelectorAll('input[required], select[required], textarea[required]');
            let valid = true;
            let errorMessage = "Por favor complete los campos obligatorios.";

            inputs.forEach(input => {
                if (!input.value.trim()) {
                    valid = false;
                    input.classList.add('is-invalid');
                } 
                else if (input.name === 'edad' && parseInt(input.value) < 19) {
                    valid = false;
                    input.classList.add('is-invalid');
                    errorMessage = "La edad debe ser mayor o igual a 19 años.";
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

        function validarEnvioFinal(event) {
            let todoValido = true;
            for (let i = 1; i <= 4; i++) {
                if (!validarSeccion(i)) {
                    todoValido = false;
                    document.querySelectorAll('.form-section').forEach(sec => sec.classList.remove('active'));
                    document.querySelectorAll('.step-item').forEach(step => step.classList.remove('active'));
                    document.getElementById(`section-${i}`).classList.add('active');
                    document.getElementById(`indicator-${i}`).classList.add('active');
                    window.scrollTo(0, 0);
                    mostrarAlerta(`Faltan campos obligatorios o hay errores en la sección ${i}.`, 'error');
                    break;
                }
            }
            if (!todoValido) event.preventDefault();
            return todoValido;
        }

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
            setTimeout(() => { toast.classList.remove('show'); }, 3500);
        }
    </script>
</body>
</html>