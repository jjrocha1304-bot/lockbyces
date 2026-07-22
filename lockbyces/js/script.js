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

const cuerpoTabla = document.getElementById('cuerpo-tabla');

if (cuerpoTabla) {
    for (let ID = 0; ID < integrantes.length; ID++) {
        let filaHTML = `
            <tr>
                <td>${ID + 1}</td>
                <td>${integrantes[ID]}</td>
            </tr>
        `;
        cuerpoTabla.innerHTML += filaHTML;
    }
}

// ==========================================
// 2. CARRUSEL DE MENSAJES INFINITO
// ==========================================
const listaMensajes = [
    "¡Bienvenido a LockByces! Explora nuestras opciones.",
    "seguridad inteligente, para tu Tranquilidad.",
    "Deja tu bicicleta en nuestras manos.",
    "Para mayor seguridad, compra en LOCKBYCES.",
    "Cambia las cadenas por LOCKBYCES.",
];

const textoCambiante = document.getElementById('mensaje-infinito');
let posicion = 0;

if (textoCambiante) {
    textoCambiante.textContent = listaMensajes[posicion];

    setInterval(() => {
        posicion++;
        posicion = posicion % listaMensajes.length;
        textoCambiante.textContent = listaMensajes[posicion];
    }, 5000); 
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
    }
}

// ==========================================
// 5. CRUD DE USUARIOS CONECTADO A MYSQL
// ==========================================

let usuariosCacheBD = [];

// FUNCIONES AUXILIARES PARA POP-UPS / MODALES
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

// A. LISTAR USUARIOS EN LA TABLA
function cargarUsuariosAdmin() {
    const formData = new FormData();
    formData.append('accion', 'listar');

    fetch('acciones_usuarios.php', {
        method: 'POST',
        body: formData
    })
    .then(async res => {
        if (!res.ok) {
            throw new Error(`Error HTTP ${res.status}: No se pudo acceder a acciones_usuarios.php`);
        }
        return res.json();
    })
    .then(data => {
        if (data.success) {
            usuariosCacheBD = data.data;
            const tabla = document.getElementById('tabla-admin-usuarios');
            if (tabla) {
                tabla.innerHTML = '';
                
                if (data.data.length === 0) {
                    tabla.innerHTML = `<tr><td colspan="8" class="crud-empty-row">No hay usuarios en la base de datos.</td></tr>`;
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
        } else {
            console.warn("Respuesta del servidor:", data.message);
        }
    })
    .catch(err => console.error("Error en la petición:", err));
}

// B. CREAR USUARIO
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
                    <input type="number" id="modal-edad" class="crud-input" placeholder="Ej: 25">
                </div>
            </div>

            <label class="crud-label">Rol del usuario</label>
            <select id="modal-rol" class="crud-select">
                <option value="cliente">Cliente</option>
                <option value="empleado">Empleado</option>
                <option value="admin">Administrador (admin)</option>
            </select>

            <label class="crud-label">Teléfono</label>
            <input type="tel" id="modal-telefono" class="crud-input" placeholder="Ej: 3001234567">

            <label class="crud-label">Correo electrónico *</label>
            <input type="email" id="modal-correo" class="crud-input" placeholder="correo@ejemplo.com">

            <label class="crud-label">Contraseña *</label>
            <input type="password" id="modal-contrasena" class="crud-input" placeholder="Contraseña de acceso">

            <div class="crud-modal-actions">
                <button onclick="cerrarModal('modal-usuario')" class="btn-cancelar">Cancelar</button>
                <button onclick="guardarNuevoUsuario()" class="btn-guardar">Guardar</button>
            </div>
        </div>
    `;

    crearModalHTML('➕ Registrar Nuevo Usuario', 'modal-usuario', formularioHTML);
}

function guardarNuevoUsuario() {
    const nombre     = document.getElementById('modal-nombre').value.trim();
    const genero     = document.getElementById('modal-genero').value;
    const edad       = document.getElementById('modal-edad').value.trim();
    const rol        = document.getElementById('modal-rol').value;
    const telefono   = document.getElementById('modal-telefono').value.trim();
    const correo     = document.getElementById('modal-correo').value.trim();
    const contrasena = document.getElementById('modal-contrasena').value.trim();

    if (!nombre || !correo || !contrasena) {
        alert("Por favor completa los campos obligatorios: Nombre, Correo y Contraseña.");
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
    })
    .catch(err => console.error("Error al crear usuario:", err));
}

// C. EDITAR USUARIO
function editarUsuarioAdmin(id) {
    const usuario = usuariosCacheBD.find(u => u.id == id);
    if (!usuario) return alert("Usuario no encontrado.");

    const formularioHTML = `
        <div class="crud-form-container">
            <label class="crud-label">Nombre completo *</label>
            <input type="text" id="modal-edit-nombre" class="crud-input" value="${usuario.nombre || ''}">
            
            <div class="crud-form-row">
                <div class="crud-form-group">
                    <label class="crud-label">Género</label>
                    <select id="modal-edit-genero" class="crud-select">
                        <option value="Masculino" ${usuario.genero === 'Masculino' ? 'selected' : ''}>Masculino</option>
                        <option value="Femenino" ${usuario.genero === 'Femenino' ? 'selected' : ''}>Femenino</option>
                        <option value="Otro" ${usuario.genero === 'Otro' ? 'selected' : ''}>Otro</option>
                    </select>
                </div>
                <div class="crud-form-group">
                    <label class="crud-label">Edad</label>
                    <input type="number" id="modal-edit-edad" class="crud-input" value="${usuario.edad || ''}">
                </div>
            </div>

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

            <label class="crud-label">Nueva Contraseña (dejar en blanco para mantener)</label>
            <input type="password" id="modal-edit-contrasena" class="crud-input" placeholder="Opcional">

            <div class="crud-modal-actions">
                <button onclick="cerrarModal('modal-edit-usuario')" class="btn-cancelar">Cancelar</button>
                <button onclick="guardarEdicionUsuario(${id})" class="btn-actualizar">Actualizar BD</button>
            </div>
        </div>
    `;

    crearModalHTML('✏️ Editar Usuario', 'modal-edit-usuario', formularioHTML);
}

function guardarEdicionUsuario(id) {
    const nombre     = document.getElementById('modal-edit-nombre').value.trim();
    const genero     = document.getElementById('modal-edit-genero').value;
    const edad       = document.getElementById('modal-edit-edad').value.trim();
    const rol        = document.getElementById('modal-edit-rol').value;
    const telefono   = document.getElementById('modal-edit-telefono').value.trim();
    const correo     = document.getElementById('modal-edit-correo').value.trim();
    const contrasena = document.getElementById('modal-edit-contrasena').value.trim();

    if (!nombre || !correo) {
        alert("El Nombre y el Correo son obligatorios.");
        return;
    }

    const formData = new FormData();
    formData.append('accion', 'editar');
    formData.append('id', id);
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
            cerrarModal('modal-edit-usuario');
            cargarUsuariosAdmin();
        }
    })
    .catch(err => console.error("Error al actualizar usuario:", err));
}

// D. ELIMINAR USUARIO
function eliminarUsuarioAdmin(id) {
    if (!confirm("¿Seguro que deseas eliminar este usuario de la base de datos?")) return;

    const formData = new FormData();
    formData.append('accion', 'eliminar');
    formData.append('id', id);

    fetch('acciones_usuarios.php', { method: 'POST', body: formData })
    .then(res => res.json())
    .then(data => {
        alert(data.message);
        if (data.success) {
            cargarUsuariosAdmin();
        }
    })
    .catch(err => console.error("Error al eliminar usuario:", err));
}

// INICIALIZACIÓN
document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('tabla-admin-usuarios')) {
        cargarUsuariosAdmin();
    }
});