@extends('layouts.app')

@section('title', 'Listado de Locales')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><i class="bi bi-list-ul"></i> Locales Comerciales</h4>
    <span class="text-muted small" id="total-label"></span>
</div>

{{-- Filtros --}}
<div class="card mb-4 shadow-sm">
    <div class="card-body">
        <div class="row g-2 align-items-end">
            <div class="col-md-5">
                <label class="form-label fw-semibold mb-1">Buscar por nombre</label>
                <input type="text" id="filtroNombre" class="form-control" placeholder="Ej: Tienda Central...">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold mb-1">Estado</label>
                <select id="filtroEstado" class="form-select">
                    <option value="">Todos</option>
                    <option value="1">Activo</option>
                    <option value="0">Inactivo</option>
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary w-100" onclick="cargarLocales(1)">
                    <i class="bi bi-search"></i> Filtrar
                </button>
            </div>
            <div class="col-md-2">
                <button class="btn btn-outline-secondary w-100" onclick="limpiarFiltros()">
                    <i class="bi bi-x-circle"></i> Limpiar
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Tabla --}}
<div class="card shadow-sm">
    <div class="card-body p-0">
        <div id="tabla-container">
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status"></div>
                <p class="mt-2 text-muted">Cargando locales...</p>
            </div>
        </div>
    </div>
</div>

{{-- Paginación --}}
<div id="paginacion" class="d-flex justify-content-center mt-3"></div>

{{-- Modal Editar --}}
<div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="modalEditarLabel">
                    <i class="bi bi-pencil-square"></i> Editar Local
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="modal-error" class="alert alert-danger d-none"></div>
                <form id="formEditar" novalidate>
                    <input type="hidden" id="edit-id">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nombre <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit-nombre" required maxlength="255">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Dirección <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit-direccion" required maxlength="255">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Estado <span class="text-danger">*</span></label>
                            <select class="form-select" id="edit-estado" required>
                                <option value="1">Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Tipo Documento</label>
                            <select class="form-select" id="edit-tipo_documento">
                                <option value="">Sin tipo</option>
                                <option value="RUC">RUC</option>
                                <option value="CEDULA">CÉDULA</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Nro. Documento</label>
                            <input type="text" class="form-control" id="edit-nro_documento" maxlength="20" placeholder="Opcional">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x"></i> Cancelar
                </button>
                <button type="button" class="btn btn-success" id="btnGuardar" onclick="guardarLocal()">
                    <i class="bi bi-check-lg"></i> Guardar cambios
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let paginaActual = 1;
    const modal = new bootstrap.Modal(document.getElementById('modalEditar'));

    function cargarLocales(pagina = 1) {
        paginaActual = pagina;
        const nombre = document.getElementById('filtroNombre').value.trim();
        const estado = document.getElementById('filtroEstado').value;

        let url = `/api/locales?page=${pagina}`;
        if (nombre) url += `&nombre=${encodeURIComponent(nombre)}`;
        if (estado !== '') url += `&estado=${estado}`;

        document.getElementById('tabla-container').innerHTML = `
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status"></div>
                <p class="mt-2 text-muted">Cargando...</p>
            </div>`;
        document.getElementById('paginacion').innerHTML = '';

        fetch(url)
            .then(r => r.json())
            .then(data => renderTabla(data))
            .catch(() => {
                document.getElementById('tabla-container').innerHTML =
                    `<div class="alert alert-danger m-3">Error al conectar con la API.</div>`;
            });
    }

    function renderTabla(data) {
        document.getElementById('total-label').textContent =
            `Total: ${data.total} local(es)`;

        if (data.data.length === 0) {
            document.getElementById('tabla-container').innerHTML =
                `<div class="text-center py-5 text-muted"><i class="bi bi-inbox fs-1"></i><p class="mt-2">No se encontraron locales.</p></div>`;
            return;
        }

        let filas = data.data.map(l => `
            <tr>
                <td>${l.id}</td>
                <td>${escHtml(l.nombre)}</td>
                <td>${escHtml(l.direccion)}</td>
                <td>
                    <span class="badge ${l.estado == 1 ? 'badge-activo' : 'badge-inactivo'} rounded-pill px-3 py-2">
                        ${l.estado == 1 ? 'Activo' : 'Inactivo'}
                    </span>
                </td>
                <td>${l.tipo_documento ?? '<span class="text-muted">-</span>'}</td>
                <td>${l.nro_documento ?? '<span class="text-muted">-</span>'}</td>
                <td>
                    <button class="btn btn-sm btn-primary" onclick='abrirModal(${JSON.stringify(l)})'>
                        <i class="bi bi-pencil"></i> Editar
                    </button>
                </td>
            </tr>`).join('');

        document.getElementById('tabla-container').innerHTML = `
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Dirección</th>
                            <th>Estado</th>
                            <th>Tipo Doc.</th>
                            <th>Nro. Documento</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>${filas}</tbody>
                </table>
            </div>`;

        renderPaginacion(data);
    }

    function renderPaginacion(data) {
        if (data.last_page <= 1) { document.getElementById('paginacion').innerHTML = ''; return; }

        let btns = '';
        btns += `<li class="page-item ${data.current_page == 1 ? 'disabled' : ''}">
            <a class="page-link" href="#" onclick="cargarLocales(${data.current_page - 1})">
                <i class="bi bi-chevron-left"></i>
            </a></li>`;
        for (let i = 1; i <= data.last_page; i++) {
            btns += `<li class="page-item ${i == data.current_page ? 'active' : ''}">
                <a class="page-link" href="#" onclick="cargarLocales(${i})">${i}</a></li>`;
        }
        btns += `<li class="page-item ${data.current_page == data.last_page ? 'disabled' : ''}">
            <a class="page-link" href="#" onclick="cargarLocales(${data.current_page + 1})">
                <i class="bi bi-chevron-right"></i>
            </a></li>`;

        document.getElementById('paginacion').innerHTML =
            `<nav><ul class="pagination">${btns}</ul></nav>`;
    }

    function abrirModal(local) {
        document.getElementById('edit-id').value           = local.id;
        document.getElementById('edit-nombre').value       = local.nombre;
        document.getElementById('edit-direccion').value    = local.direccion;
        document.getElementById('edit-estado').value       = local.estado;
        document.getElementById('edit-tipo_documento').value = local.tipo_documento ?? '';
        document.getElementById('edit-nro_documento').value  = local.nro_documento ?? '';
        document.getElementById('modal-error').classList.add('d-none');
        document.getElementById('modal-error').textContent = '';
        modal.show();
    }

    function guardarLocal() {
        const id = document.getElementById('edit-id').value;
        const btnGuardar = document.getElementById('btnGuardar');

        const payload = {
            nombre:         document.getElementById('edit-nombre').value.trim(),
            direccion:      document.getElementById('edit-direccion').value.trim(),
            estado:         parseInt(document.getElementById('edit-estado').value),
            tipo_documento: document.getElementById('edit-tipo_documento').value || null,
            nro_documento:  document.getElementById('edit-nro_documento').value.trim() || null,
        };

        if (!payload.nombre || !payload.direccion) {
            mostrarError('Los campos Nombre y Dirección son obligatorios.');
            return;
        }

        btnGuardar.disabled = true;
        btnGuardar.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Guardando...';

        fetch(`/api/locales/${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            body: JSON.stringify(payload),
        })
        .then(async r => {
            const json = await r.json();
            if (!r.ok) throw json;
            return json;
        })
        .then(() => {
            modal.hide();
            cargarLocales(paginaActual);
        })
        .catch(err => {
            let msg = 'Error al guardar. Intente nuevamente.';
            if (err.errors) {
                msg = Object.values(err.errors).flat().join(' ');
            } else if (err.message) {
                msg = err.message;
            }
            mostrarError(msg);
        })
        .finally(() => {
            btnGuardar.disabled = false;
            btnGuardar.innerHTML = '<i class="bi bi-check-lg"></i> Guardar cambios';
        });
    }

    function mostrarError(msg) {
        const el = document.getElementById('modal-error');
        el.textContent = msg;
        el.classList.remove('d-none');
    }

    function limpiarFiltros() {
        document.getElementById('filtroNombre').value = '';
        document.getElementById('filtroEstado').value = '';
        cargarLocales(1);
    }

    function escHtml(str) {
        if (!str) return '';
        return str.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    // Permitir buscar al presionar Enter
    document.getElementById('filtroNombre').addEventListener('keydown', e => {
        if (e.key === 'Enter') cargarLocales(1);
    });

    // Carga inicial
    cargarLocales(1);
</script>
@endsection
