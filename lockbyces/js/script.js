// ==========================================
// 1. INTEGRANTES DE LOCKBYCES
// ==========================================
const integrantes = [
    "Tomas Sepulveda Velasco", 
    "Juan Jose Rocha Cardenas", 
    "Laura Londoño López", 
    "Danna Nicoll Guapacha Soto", 
    "Juan Esteban Rojas Ortiz",
    "Maria Camila Villegas", 
    "Angelica Maria Barrera González"
];

function cargarIntegrantes() {
    const cuerpoTabla = document.getElementById('cuerpo-tabla');
    if (cuerpoTabla) {
        cuerpoTabla.innerHTML = '';
        integrantes.forEach((nombre, index) => {
            let filaHTML = `
                <tr>
                    <td>${index + 1}</td>
                    <td>${nombre}</td>
                </tr>
            `;
            cuerpoTabla.innerHTML += filaHTML;
        });
    }
}

// ==========================================
// 2. CARRUSEL DE MENSAJES INFINITO
// ==========================================
const listaMensajes = [
    "¡Bienvenido a LockByces!",
    "Seguridad inteligente para tu tranquilidad.",
    "Deja tu bicicleta en nuestras manos.",
    "Para mayor seguridad, elige LOCKBYCES.",
    "Cambia las cadenas por seguridad IoT."
];

let posicion = 0;
function iniciarCarrusel() {
    const textoCambiante = document.getElementById('mensaje-infinito');
    if (textoCambiante) {
        textoCambiante.textContent = listaMensajes[posicion];
        setInterval(() => {
            posicion = (posicion + 1) % listaMensajes.length;
            textoCambiante.textContent = listaMensajes[posicion];
        }, 4000); 
    }
}

// ==========================================
// 3. OCULTAR / MOSTRAR CONTRASEÑA
// ==========================================
function cambiarVista() {
    const inputContrasena = document.getElementById("input-contrasena");
    const btnMostrarOcultar = document.getElementById("btn-mostrar-ocultar");

    if (inputContrasena && btnMostrarOcultar) {
        if (inputContrasena.type === "password") {
            inputContrasena.type = "text";
            btnMostrarOcultar.textContent = "🙈";
        } else {
            inputContrasena.type = "password";
            btnMostrarOcultar.textContent = "👁️";
        }
    }
}

// ==========================================
// 4. CONTROL DE SECCIONES EN EL DASHBOARD
// ==========================================
function mostrarSeccion(idSeccion, enlaceClickeado) {
    const secciones = document.querySelectorAll('.seccion-contenido');
    secciones.forEach(seccion => {
        seccion.style.display = 'none';
    });

    const botones = document.querySelectorAll('.nav-btn');
    botones.forEach(btn => {
        btn.classList.remove('active');
    });

    const seccionObjetivo = document.getElementById('seccion-' + idSeccion);
    if (seccionObjetivo) {
        seccionObjetivo.style.display = 'block';
    }

    if (enlaceClickeado) {
        enlaceClickeado.classList.add('active');
    }

    if (idSeccion === 'admin-usuarios') {
        cargarUsuariosAdmin();
    } else if (idSeccion === 'monitoreo') {
        // Redimensionar el mapa de Cali cuando se muestra la pestaña para recalcular tamaño
        setTimeout(() => {
            if (mapaCali) mapaCali.invalidateSize();
        }, 200);
    } else if (idSeccion === 'estadisticas') {
        if (chartModalidadesInstancia) chartModalidadesInstancia.resize();
        if (chartCiudadesInstancia) chartCiudadesInstancia.resize();
    }
}

// ==========================================
// 5. LÓGICA DE MONITOREO Y RASTREO IOT - CALI GPS
// ==========================================
let alarmTriggerCount = 0;
let mapaCali = null;
let marcadorCali = null;

// Puntos de referencia en Cali, Colombia
const puntosCali = [
    { lat: 3.4516, lng: -76.5320, sector: "Plaza de Cayzedo, Centro" },
    { lat: 3.4472, lng: -76.5398, sector: "Barrio San Antonio" },
    { lat: 3.4582, lng: -76.5342, sector: "Barrio Granada" },
    { lat: 3.3752, lng: -76.5336, sector: "Universidad del Valle, Meléndez" },
    { lat: 3.4241, lng: -76.5441, sector: "Parque del Perro, San Fernando" },
    { lat: 3.4731, lng: -76.5273, sector: "Chipichape, Norte" }
];

function inicializarMapaCali() {
    const mapaDiv = document.getElementById('mapa-cali');
    if (!mapaDiv) return;

    // Coordenadas iniciales: Centro de Cali, Colombia
    const latInicial = 3.4516;
    const lngInicial = -76.5320;

    // Crear el mapa de Leaflet
    mapaCali = L.map('mapa-cali').setView([latInicial, lngInicial], 14);

    // Cargar mapa visual estilo oscuro
    L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; OpenStreetMap &copy; CARTO',
        maxZoom: 18
    }).addTo(mapaCali);

    // Crear el marcador del punto amarillo flotante con pulso
    const miIconoAmarillo = L.divIcon({
        className: 'marcador-gps-leaflet',
        iconSize: [18, 18],
        iconAnchor: [9, 9]
    });

    marcadorCali = L.marker([latInicial, lngInicial], { icon: miIconoAmarillo }).addTo(mapaCali);
    marcadorCali.bindPopup("<b>Bicicleta LOCKBYCES</b><br>Estado: Protegida (Cali)").openPopup();
}

function simulateSoundTrigger() {
    alarmTriggerCount++;
    const alarmCountElem = document.getElementById('alarm-count');
    if (alarmCountElem) alarmCountElem.innerText = alarmTriggerCount;

    const alarmLog = document.getElementById('alarm-log');
    if (alarmLog) {
        const now = new Date();
        const timeString = now.toLocaleTimeString();

        const emptyLog = alarmLog.querySelector('.empty-log');
        if (emptyLog) emptyLog.remove();

        const newEntry = document.createElement('li');
        newEntry.style.padding = '5px 0';
        newEntry.style.borderBottom = '1px solid rgba(255,255,255,0.1)';
        newEntry.innerText = `[${timeString}] Alerta: Movimiento detectado (>85dB)`;
        alarmLog.insertBefore(newEntry, alarmLog.firstChild);
    }
}

function resetAlarmCount() {
    alarmTriggerCount = 0;
    const alarmCountElem = document.getElementById('alarm-count');
    if (alarmCountElem) alarmCountElem.innerText = '0';

    const alarmLog = document.getElementById('alarm-log');
    if (alarmLog) {
        alarmLog.innerHTML = '<li class="empty-log">Sin registros de activación.</li>';
    }
}

function saveBikeInfo(event) {
    event.preventDefault();
    alert('Información de la bicicleta actualizada correctamente.');
}

function reportStolen() {
    const brand = document.getElementById('bike-brand')?.value || '';
    const model = document.getElementById('bike-model')?.value || '';
    const serial = document.getElementById('bike-serial')?.value || '';

    const confirmAction = confirm(`¿Confirmas el envío de reporte de robo para la bicicleta ${brand} ${model} (Serie: ${serial})?`);
    if (confirmAction) {
        alert('REPORTE GENERADO: Notificación emitida a las autoridades y la red LockByces en Cali.');
    }
}

function updateLocation() {
    if (!mapaCali || !marcadorCali) return;

    // Seleccionar una ubicación aleatoria dentro de Cali
    const puntoAleatorio = puntosCali[Math.floor(Math.random() * puntosCali.length)];
    
    // Variación pequeña de coordenadas para hacer movimiento realista
    const nuevaLat = puntoAleatorio.lat + (Math.random() - 0.5) * 0.005;
    const nuevaLng = puntoAleatorio.lng + (Math.random() - 0.5) * 0.005;

    // Mover el punto amarillo y la cámara del mapa
    marcadorCali.setLatLng([nuevaLat, nuevaLng]);
    mapaCali.panTo([nuevaLat, nuevaLng]);

    marcadorCali.bindPopup(`<b>Bicicleta LOCKBYCES</b><br>Sector: ${puntoAleatorio.sector}`).openPopup();

    const coordsElem = document.getElementById('coords-display');
    const timeElem = document.getElementById('time-display');

    if (coordsElem) coordsElem.innerText = `${nuevaLat.toFixed(4)}° N, ${nuevaLng.toFixed(4)}° W (${puntoAleatorio.sector})`;
    if (timeElem) timeElem.innerText = 'Justo ahora';
}

// ==========================================
// 6. CRUD DE USUARIOS CONECTADO A MYSQL
// ==========================================
let usuariosCacheBD = [];

function crearModalHTML(titulo, idModal, contenidoHTML) {
    const modalExistente = document.getElementById(idModal);
    if (modalExistente) modalExistente.remove();

    const overlay = document.createElement('div');
    overlay.id = idModal;
    overlay.className = 'crud-modal-overlay';

    overlay.innerHTML = `
        <div class="crud-modal-box">
            <h3 class="crud-modal-title">${titulo}</h3>
            ${contenidoHTML}
        </div>
    `;

    document.body.appendChild(overlay);
}

function cerrarModal(idModal) {
    const modal = document.getElementById(idModal);
    if (modal) modal.remove();
}

function cargarUsuariosAdmin() {
    const formData = new FormData();
    formData.append('accion', 'listar');

    fetch('acciones_usuarios.php', { method: 'POST', body: formData })
    .then(async res => {
        if (!res.ok) throw new Error(`HTTP Error ${res.status}`);
        return res.json();
    })
    .then(data => {
        if (data.success) {
            usuariosCacheBD = data.data;
            const tabla = document.getElementById('tabla-admin-usuarios');
            if (tabla) {
                tabla.innerHTML = '';
                if (data.data.length === 0) {
                    tabla.innerHTML = `<tr><td colspan="8" style="text-align:center;">No hay usuarios guardados.</td></tr>`;
                    return;
                }

                data.data.forEach(u => {
                    tabla.innerHTML += `
                        <tr>
                            <td>${u.id}</td>
                            <td>${u.nombre}</td>
                            <td>${u.genero || '-'}</td>
                            <td>${u.edad || '-'}</td>
                            <td><span class="crud-badge-rol">${u.rol}</span></td>
                            <td>${u.telefono || '-'}</td>
                            <td>${u.correo}</td>
                            <td style="white-space: nowrap;">
                                <button onclick="editarUsuarioAdmin(${u.id})" class="btn-accion btn-editar">✏️ Editar</button>
                                <button onclick="eliminarUsuarioAdmin(${u.id})" class="btn-accion btn-eliminar">🗑️ Eliminar</button>
                            </td>
                        </tr>
                    `;
                });
            }
        }
    })
    .catch(err => console.error("Error al cargar usuarios:", err));
}

function crearUsuarioAdmin() {
    const formularioHTML = `
        <div class="crud-form-container">
            <label class="crud-label">Nombre completo *</label>
            <input type="text" id="modal-nombre" class="crud-input" placeholder="Ej: Maria Perez">
            
            <div class="crud-form-row">
                <div class="crud-form-group">
                    <label class="crud-label">Género</label>
                    <select id="modal-genero" class="crud-select">
                        <option value="Masculino">Masculino</option>
                        <option value="Femenino">Femenino</option>
                        <option value="Otro">Otro</option>
                    </select>
                </div>
                <div class="crud-form-group">
                    <label class="crud-label">Edad</label>
                    <input type="number" id="modal-edad" class="crud-input" placeholder="25">
                </div>
            </div>

            <label class="crud-label">Rol del usuario</label>
            <select id="modal-rol" class="crud-select">
                <option value="cliente">Cliente</option>
                <option value="empleado">Empleado</option>
                <option value="admin">Administrador (admin)</option>
            </select>

            <label class="crud-label">Teléfono</label>
            <input type="tel" id="modal-telefono" class="crud-input" placeholder="3001234567">

            <label class="crud-label">Correo electrónico *</label>
            <input type="email" id="modal-correo" class="crud-input" placeholder="correo@ejemplo.com">

            <label class="crud-label">Contraseña *</label>
            <input type="password" id="modal-contrasena" class="crud-input" placeholder="Contraseña">

            <div class="crud-modal-actions">
                <button onclick="cerrarModal('modal-usuario')" class="btn-cancelar">Cancelar</button>
                <button onclick="guardarNuevoUsuario()" class="btn-guardar">Guardar</button>
            </div>
        </div>
    `;
    crearModalHTML('Registrar Nuevo Usuario', 'modal-usuario', formularioHTML);
}

function guardarNuevoUsuario() {
    const nombre = document.getElementById('modal-nombre').value.trim();
    const genero = document.getElementById('modal-genero').value;
    const edad = document.getElementById('modal-edad').value.trim();
    const rol = document.getElementById('modal-rol').value;
    const telefono = document.getElementById('modal-telefono').value.trim();
    const correo = document.getElementById('modal-correo').value.trim();
    const contrasena = document.getElementById('modal-contrasena').value.trim();

    if (!nombre || !correo || !contrasena) {
        alert("Campos obligatorios incompletos.");
        return;
    }

    const formData = new FormData();
    formData.append('accion', 'crear');
    formData.append('nombre', nombre);
    formData.append('genero', genero);
    formData.append('edad', edad);
    formData.append('rol', rol);
    formData.append('telefono', telefono);
    formData.append('correo', correo);
    formData.append('contrasena', contrasena);

    fetch('acciones_usuarios.php', { method: 'POST', body: formData })
    .then(res => res.json())
    .then(data => {
        alert(data.message);
        if (data.success) {
            cerrarModal('modal-usuario');
            cargarUsuariosAdmin();
        }
    });
}

function editarUsuarioAdmin(id) {
    const usuario = usuariosCacheBD.find(u => u.id == id);
    if (!usuario) return alert("Usuario no encontrado.");

    const formularioHTML = `
        <div class="crud-form-container">
            <label class="crud-label">Nombre completo *</label>
            <input type="text" id="modal-edit-nombre" class="crud-input" value="${usuario.nombre || ''}">
            
            <label class="crud-label">Rol del usuario</label>
            <select id="modal-edit-rol" class="crud-select">
                <option value="cliente" ${usuario.rol === 'cliente' ? 'selected' : ''}>Cliente</option>
                <option value="empleado" ${usuario.rol === 'empleado' ? 'selected' : ''}>Empleado</option>
                <option value="admin" ${usuario.rol === 'admin' ? 'selected' : ''}>Administrador (admin)</option>
            </select>

            <label class="crud-label">Teléfono</label>
            <input type="tel" id="modal-edit-telefono" class="crud-input" value="${usuario.telefono || ''}">

            <label class="crud-label">Correo electrónico *</label>
            <input type="email" id="modal-edit-correo" class="crud-input" value="${usuario.correo || ''}">

            <label class="crud-label">Nueva Contraseña (Opcional)</label>
            <input type="password" id="modal-edit-contrasena" class="crud-input">

            <div class="crud-modal-actions">
                <button onclick="cerrarModal('modal-edit-usuario')" class="btn-cancelar">Cancelar</button>
                <button onclick="guardarEdicionUsuario(${id})" class="btn-guardar">Actualizar</button>
            </div>
        </div>
    `;
    crearModalHTML('Editar Usuario', 'modal-edit-usuario', formularioHTML);
}

function guardarEdicionUsuario(id) {
    const nombre = document.getElementById('modal-edit-nombre').value.trim();
    const rol = document.getElementById('modal-edit-rol').value;
    const telefono = document.getElementById('modal-edit-telefono').value.trim();
    const correo = document.getElementById('modal-edit-correo').value.trim();
    const contrasena = document.getElementById('modal-edit-contrasena').value.trim();

    const formData = new FormData();
    formData.append('accion', 'editar');
    formData.append('id', id);
    formData.append('nombre', nombre);
    formData.append('rol', rol);
    formData.append('telefono', telefono);
    formData.append('correo', correo);
    formData.append('contrasena', contrasena);

    fetch('acciones_usuarios.php', { method: 'POST', body: formData })
    .then(res => res.json())
    .then(data => {
        alert(data.message);
        if (data.success) {
            cerrarModal('modal-edit-usuario');
            cargarUsuariosAdmin();
        }
    });
}

function eliminarUsuarioAdmin(id) {
    if (!confirm("¿Deseas eliminar este usuario?")) return;

    const formData = new FormData();
    formData.append('accion', 'eliminar');
    formData.append('id', id);

    fetch('acciones_usuarios.php', { method: 'POST', body: formData })
    .then(res => res.json())
    .then(data => {
        alert(data.message);
        if (data.success) cargarUsuariosAdmin();
    });
}

// ==========================================
// 7. INICIALIZACIÓN GENERAL Y GRÁFICAS (CHART.JS)
// ==========================================
let chartModalidadesInstancia = null;
let chartCiudadesInstancia = null;

document.addEventListener("DOMContentLoaded", function() {
    cargarIntegrantes();
    iniciarCarrusel();
    inicializarMapaCali();

    if (document.getElementById('tabla-admin-usuarios')) {
        cargarUsuariosAdmin();
    }

    // Inicialización de Gráficas Chart.js
    const ctx1 = document.getElementById('chartModalidades');
    if (ctx1) {
        chartModalidadesInstancia = new Chart(ctx1, {
            type: 'doughnut',
            data: {
                labels: ['Halado', 'Atraco con Arma', 'Factor Sorpresa', 'Rompimiento de Candado'],
                datasets: [{
                    data: [58, 24, 11, 7],
                    backgroundColor: ['#f9b816', '#e74c3c', '#3498db', '#9b59b6'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { labels: { color: '#fff' } } }
            }
        });
    }

    const ctx2 = document.getElementById('chartCiudades');
    if (ctx2) {
        chartCiudadesInstancia = new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: ['Bogotá', 'Cali', 'Medellín', 'Bucaramanga', 'Otras'],
                datasets: [{
                    label: 'Porcentaje de casos',
                    data: [42, 21, 18, 9, 10],
                    backgroundColor: '#f9b816',
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { ticks: { color: '#ccc' }, grid: { display: false } },
                    y: { ticks: { color: '#ccc' }, grid: { color: 'rgba(255,255,255,0.1)' } }
                }
            }
        });
    }
});