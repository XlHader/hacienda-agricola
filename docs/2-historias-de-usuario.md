# Historias de Usuario - Sistema de Hacienda Agrícola (API REST)

## Índice de Historias de Usuario

1. [HU-001: Autenticación y Gestión de Usuarios (API)](#hu-001-autenticación-y-gestión-de-usuarios-api)
2. [HU-002: API de Gestión de Parcelas](#hu-002-api-de-gestión-de-parcelas)
3. [HU-003: API de Gestión de Temporadas de Siembra](#hu-003-api-de-gestión-de-temporadas-de-siembra)
4. [HU-004: API de Registro de Cosechas](#hu-004-api-de-registro-de-cosechas)
5. [HU-005: API de Gestión de Clientes](#hu-005-api-de-gestión-de-clientes)
6. [HU-006: API de Gestión de Pedidos](#hu-006-api-de-gestión-de-pedidos)
7. [HU-007: API de Gestión de Empleados y Habilidades](#hu-007-api-de-gestión-de-empleados-y-habilidades)

---

## HU-001: Autenticación y Gestión de Usuarios (API)

**Como** desarrollador del sistema  
**Quiero** implementar endpoints de autenticación mediante API  
**Para** permitir el acceso seguro al sistema mediante tokens JWT/Sanctum

### Contexto Delimitado

Gestión de Recursos (Transversal a todos los contextos)

### Criterios de Aceptación

-   La API debe permitir registro de nuevos usuarios vía POST
-   Los usuarios deben poder iniciar sesión con email y contraseña
-   El sistema debe generar tokens de autenticación (Sanctum)
-   La API debe permitir cerrar sesión (invalidar token)
-   Se debe poder obtener la información del usuario autenticado
-   Se debe poder actualizar el perfil del usuario autenticado
-   El sistema debe validar que los emails sean únicos
-   Todos los endpoints (excepto registro y login) deben estar protegidos

### Tareas de Desarrollo

#### Tarea 1.1: Configurar Laravel Sanctum

**Complejidad:** Media  
**Tiempo estimado:** 2 horas  
**Descripción:**

-   Instalar y configurar Laravel Sanctum
-   Publicar configuraciones necesarias
-   Configurar modelo User para usar Sanctum
-   Ejecutar migraciones necesarias
-   Configurar guards en `config/auth.php`

**Archivos a crear/modificar:**

-   `config/sanctum.php`
-   `config/auth.php`
-   `app/Models/User.php`

#### Tarea 1.2: Implementar endpoint de registro (POST /api/register)

**Complejidad:** Media  
**Tiempo estimado:** 3 horas  
**Descripción:**

-   Crear endpoint POST `/api/register`
-   Validar: name, email (unique), password (min:8), password_confirmation
-   Hashear contraseña antes de guardar
-   Crear usuario en base de datos
-   Generar y retornar token de autenticación
-   Retornar datos del usuario creado
-   Manejar errores de validación (422)

**Archivos a crear/modificar:**

-   `app/Http/Controllers/Api/AuthController.php`
-   `app/Http/Requests/Api/RegisterRequest.php`
-   `routes/api.php`

**Respuesta esperada:**

```json
{
    "user": {
        "id": 1,
        "name": "Juan Pérez",
        "email": "juan@example.com"
    },
    "token": "1|abc123..."
}
```

#### Tarea 1.3: Implementar endpoint de login (POST /api/login)

**Complejidad:** Media  
**Tiempo estimado:** 3 horas  
**Descripción:**

-   Crear endpoint POST `/api/login`
-   Validar credenciales (email, password)
-   Verificar que el usuario exista
-   Verificar que la contraseña sea correcta
-   Generar y retornar token de autenticación
-   Retornar datos del usuario
-   Manejar errores 401 (credenciales incorrectas)

**Archivos a crear/modificar:**

-   `app/Http/Controllers/Api/AuthController.php`
-   `app/Http/Requests/Api/LoginRequest.php`
-   `routes/api.php`

**Respuesta esperada:**

```json
{
    "user": {
        "id": 1,
        "name": "Juan Pérez",
        "email": "juan@example.com"
    },
    "token": "2|xyz789..."
}
```

#### Tarea 1.4: Implementar endpoint de logout (POST /api/logout)

**Complejidad:** Baja  
**Tiempo estimado:** 1 hora  
**Descripción:**

-   Crear endpoint POST `/api/logout`
-   Proteger con middleware `auth:sanctum`
-   Invalidar token actual del usuario
-   Retornar mensaje de confirmación
-   Manejar errores 401 (no autenticado)

**Archivos a modificar:**

-   `app/Http/Controllers/Api/AuthController.php`
-   `routes/api.php`

**Respuesta esperada:**

```json
{
    "message": "Sesión cerrada exitosamente"
}
```

#### Tarea 1.5: Implementar endpoint de perfil (GET /api/user)

**Complejidad:** Baja  
**Tiempo estimado:** 1 hora  
**Descripción:**

-   Crear endpoint GET `/api/user`
-   Proteger con middleware `auth:sanctum`
-   Retornar datos del usuario autenticado
-   Incluir fecha de creación y última actualización

**Archivos a modificar:**

-   `app/Http/Controllers/Api/AuthController.php`
-   `routes/api.php`

**Respuesta esperada:**

```json
{
    "id": 1,
    "name": "Juan Pérez",
    "email": "juan@example.com",
    "created_at": "2025-01-15T10:00:00.000000Z",
    "updated_at": "2025-01-15T10:00:00.000000Z"
}
```

#### Tarea 1.6: Implementar endpoint de actualización de perfil (PUT /api/user)

**Complejidad:** Media  
**Tiempo estimado:** 2 horas  
**Descripción:**

-   Crear endpoint PUT `/api/user`
-   Proteger con middleware `auth:sanctum`
-   Permitir actualizar: name, email
-   Validar email único (excepto el del usuario actual)
-   Retornar datos actualizados del usuario
-   Manejar errores de validación (422)

**Archivos a modificar:**

-   `app/Http/Controllers/Api/AuthController.php`
-   `app/Http/Requests/Api/UpdateProfileRequest.php`
-   `routes/api.php`

#### Tarea 1.7: Implementar endpoint de cambio de contraseña (PUT /api/user/password)

**Complejidad:** Media  
**Tiempo estimado:** 2 horas  
**Descripción:**

-   Crear endpoint PUT `/api/user/password`
-   Proteger con middleware `auth:sanctum`
-   Validar: current_password, new_password (min:8), new_password_confirmation
-   Verificar que current_password sea correcta
-   Actualizar contraseña (hasheada)
-   Retornar mensaje de confirmación
-   Manejar errores de validación

**Archivos a modificar:**

-   `app/Http/Controllers/Api/AuthController.php`
-   `app/Http/Requests/Api/ChangePasswordRequest.php`
-   `routes/api.php`

#### Tarea 1.8: Crear Resource para formatear respuestas de usuario

**Complejidad:** Baja  
**Tiempo estimado:** 1 hora  
**Descripción:**

-   Crear UserResource para estandarizar respuestas JSON
-   Definir campos a retornar y su formato
-   Aplicar resource en todos los endpoints de autenticación

**Archivos a crear:**

-   `app/Http/Resources/UserResource.php`

---

## HU-002: API de Gestión de Parcelas

**Como** desarrollador del sistema  
**Quiero** crear endpoints para gestionar parcelas  
**Para** permitir operaciones CRUD sobre las áreas de cultivo

### Contexto Delimitado

Cultivo

### Criterios de Aceptación

-   La API debe permitir listar todas las parcelas (GET)
-   La API debe permitir crear nuevas parcelas (POST)
-   La API debe permitir ver una parcela específica (GET)
-   La API debe permitir actualizar una parcela (PUT)
-   La API debe permitir eliminar una parcela (DELETE)
-   Validar que no se elimine si tiene temporadas asociadas
-   Todos los endpoints deben estar protegidos con autenticación

### Tareas de Desarrollo

#### Tarea 2.1: Crear modelo y Request de validación

**Complejidad:** Baja  
**Tiempo estimado:** 2 horas  
**Descripción:**

-   El modelo Plot ya existe, verificar relaciones
-   Crear StorePlotRequest con validaciones:
    -   name: required, string, max:255
    -   area: required, numeric, min:0.01
    -   location: required, string
    -   soil_type: nullable, string
    -   soil_ph: nullable, numeric, between:0,14
    -   soil_texture: nullable, string
-   Crear UpdatePlotRequest (mismas validaciones)

**Archivos a crear/modificar:**

-   `app/Http/Requests/Api/StorePlotRequest.php`
-   `app/Http/Requests/Api/UpdatePlotRequest.php`

#### Tarea 2.2: Implementar endpoint de listado (GET /api/plots)

**Complejidad:** Baja  
**Tiempo estimado:** 2 horas  
**Descripción:**

-   Crear método index() en PlotController
-   Retornar todas las parcelas paginadas (15 por página)
-   Incluir búsqueda opcional por nombre (?search=)
-   Usar PlotResource para formatear respuesta
-   Proteger con middleware auth:sanctum

**Archivos a crear/modificar:**

-   `app/Http/Controllers/Api/PlotController.php`
-   `routes/api.php`

**Respuesta esperada:**

```json
{
    "data": [
        {
            "id": 1,
            "name": "Parcela Norte",
            "area": 15.5,
            "location": "Sector A",
            "soil_type": "Arcilloso",
            "created_at": "2025-01-15T10:00:00.000000Z"
        }
    ],
    "meta": { "current_page": 1, "total": 10 }
}
```

#### Tarea 2.3: Implementar endpoint de creación (POST /api/plots)

**Complejidad:** Baja  
**Tiempo estimado:** 2 horas  
**Descripción:**

-   Crear método store() en PlotController
-   Validar datos con StorePlotRequest
-   Crear parcela en base de datos
-   Retornar parcela creada con código 201
-   Manejar errores de validación (422)

**Archivos a modificar:**

-   `app/Http/Controllers/Api/PlotController.php`

**Body esperado:**

```json
{
    "name": "Parcela Sur",
    "area": 20.5,
    "location": "Sector B",
    "soil_type": "Franco",
    "soil_ph": 6.5
}
```

#### Tarea 2.4: Implementar endpoint de detalle (GET /api/plots/{id})

**Complejidad:** Baja  
**Tiempo estimado:** 1 hora  
**Descripción:**

-   Crear método show() en PlotController
-   Retornar parcela específica por ID
-   Incluir relación con temporadas de siembra (opcional)
-   Manejar error 404 si no existe

**Archivos a modificar:**

-   `app/Http/Controllers/Api/PlotController.php`

#### Tarea 2.5: Implementar endpoint de actualización (PUT /api/plots/{id})

**Complejidad:** Baja  
**Tiempo estimado:** 2 horas  
**Descripción:**

-   Crear método update() en PlotController
-   Validar datos con UpdatePlotRequest
-   Actualizar parcela en base de datos
-   Retornar parcela actualizada
-   Manejar errores 404 y 422

**Archivos a modificar:**

-   `app/Http/Controllers/Api/PlotController.php`

#### Tarea 2.6: Implementar endpoint de eliminación (DELETE /api/plots/{id})

**Complejidad:** Baja  
**Tiempo estimado:** 2 horas  
**Descripción:**

-   Crear método destroy() en PlotController
-   Verificar que no tenga temporadas asociadas
-   Eliminar parcela de base de datos
-   Retornar código 204 (sin contenido)
-   Manejar error 409 si tiene temporadas asociadas

**Archivos a modificar:**

-   `app/Http/Controllers/Api/PlotController.php`

#### Tarea 2.7: Crear Resource para formatear respuestas

**Complejidad:** Baja  
**Tiempo estimado:** 1 hora  
**Descripción:**

-   Crear PlotResource para estandarizar respuestas JSON
-   Definir campos a retornar y su formato
-   Aplicar resource en todos los endpoints

**Archivos a crear:**

-   `app/Http/Resources/PlotResource.php`

---

## HU-003: API de Gestión de Temporadas de Siembra

**Como** desarrollador del sistema  
**Quiero** crear endpoints para gestionar temporadas de siembra  
**Para** registrar qué se cultiva en cada parcela y cuándo

### Contexto Delimitado

Cultivo

### Criterios de Aceptación

-   La API debe permitir listar todas las temporadas (GET)
-   La API debe permitir crear nuevas temporadas (POST)
-   La API debe permitir ver una temporada específica (GET)
-   La API debe permitir actualizar una temporada (PUT)
-   Validar que el área sembrada no exceda el área de la parcela
-   Todos los endpoints deben estar protegidos con autenticación

### Tareas de Desarrollo

#### Tarea 3.1: Crear Requests de validación

**Complejidad:** Baja  
**Tiempo estimado:** 2 horas  
**Descripción:**

-   Crear StorePlantingSeasonRequest con validaciones:
    -   plot_id: required, exists:plots,id
    -   crop_id: required, exists:crops,id
    -   variety_id: required, exists:varieties,id
    -   planting_date: required, date
    -   planted_area: required, numeric, min:0.01
    -   planting_density: nullable, numeric
    -   status: nullable, in:active,harvested,abandoned
-   Crear UpdatePlantingSeasonRequest (mismas validaciones)

**Archivos a crear:**

-   `app/Http/Requests/Api/StorePlantingSeasonRequest.php`
-   `app/Http/Requests/Api/UpdatePlantingSeasonRequest.php`

#### Tarea 3.2: Implementar endpoint de listado (GET /api/planting-seasons)

**Complejidad:** Baja  
**Tiempo estimado:** 2 horas  
**Descripción:**

-   Crear método index() en PlantingSeasonController
-   Retornar temporadas paginadas con relaciones (plot, crop, variety)
-   Incluir filtros opcionales: ?plot_id=1, ?status=active
-   Usar PlantingSeasonResource para formatear respuesta

**Archivos a crear/modificar:**

-   `app/Http/Controllers/Api/PlantingSeasonController.php`
-   `routes/api.php`

**Respuesta esperada:**

```json
{
    "data": [
        {
            "id": 1,
            "plot": { "id": 1, "name": "Parcela Norte" },
            "crop": { "id": 1, "name": "Tomate" },
            "variety": { "id": 1, "name": "Chonto" },
            "planting_date": "2025-01-15",
            "planted_area": 10.5,
            "status": "active"
        }
    ]
}
```

#### Tarea 3.3: Implementar endpoint de creación (POST /api/planting-seasons)

**Complejidad:** Media  
**Tiempo estimado:** 3 horas  
**Descripción:**

-   Crear método store() en PlantingSeasonController
-   Validar datos con StorePlantingSeasonRequest
-   Validar que planted_area no exceda el área de la parcela
-   Crear temporada con status='active' por defecto
-   Retornar temporada creada con código 201

**Archivos a modificar:**

-   `app/Http/Controllers/Api/PlantingSeasonController.php`

#### Tarea 3.4: Implementar endpoint de detalle (GET /api/planting-seasons/{id})

**Complejidad:** Baja  
**Tiempo estimado:** 1 hora  
**Descripción:**

-   Crear método show() en PlantingSeasonController
-   Retornar temporada con relaciones: plot, crop, variety, harvests
-   Calcular días transcurridos desde siembra
-   Manejar error 404 si no existe

**Archivos a modificar:**

-   `app/Http/Controllers/Api/PlantingSeasonController.php`

#### Tarea 3.5: Implementar endpoint de actualización (PUT /api/planting-seasons/{id})

**Complejidad:** Baja  
**Tiempo estimado:** 2 horas  
**Descripción:**

-   Crear método update() en PlantingSeasonController
-   Validar datos con UpdatePlantingSeasonRequest
-   Permitir cambiar status (active, harvested, abandoned)
-   Retornar temporada actualizada

**Archivos a modificar:**

-   `app/Http/Controllers/Api/PlantingSeasonController.php`

#### Tarea 3.6: Crear Resource para formatear respuestas

**Complejidad:** Baja  
**Tiempo estimado:** 1 hora  
**Descripción:**

-   Crear PlantingSeasonResource
-   Incluir relaciones anidadas (plot, crop, variety)
-   Calcular campos adicionales (días desde siembra)

**Archivos a crear:**

-   `app/Http/Resources/PlantingSeasonResource.php`

---

## HU-004: API de Registro de Cosechas

**Como** desarrollador del sistema  
**Quiero** crear endpoints para registrar cosechas  
**Para** llevar control de la producción obtenida

### Contexto Delimitado

Cultivo (con interacción hacia Venta y Distribución)

### Criterios de Aceptación

-   La API debe permitir listar todas las cosechas (GET)
-   La API debe permitir crear nuevas cosechas (POST)
-   La API debe permitir ver una cosecha específica (GET)
-   La API debe permitir actualizar una cosecha (PUT)
-   Validar que la fecha de cosecha sea posterior a la siembra
-   Calcular rendimiento por hectárea automáticamente

### Tareas de Desarrollo

#### Tarea 4.1: Crear Requests de validación

**Complejidad:** Baja  
**Tiempo estimado:** 2 horas  
**Descripción:**

-   Crear StoreHarvestRequest con validaciones:
    -   planting_season_id: required, exists:planting_seasons,id
    -   harvest_date: required, date
    -   quantity: required, numeric, min:0.01
    -   unit: required, string (kg, ton, units)
    -   quality: required, in:extra,first,second,discard
    -   observations: nullable, string
-   Crear UpdateHarvestRequest (mismas validaciones)

**Archivos a crear:**

-   `app/Http/Requests/Api/StoreHarvestRequest.php`
-   `app/Http/Requests/Api/UpdateHarvestRequest.php`

#### Tarea 4.2: Implementar endpoint de listado (GET /api/harvests)

**Complejidad:** Baja  
**Tiempo estimado:** 2 horas  
**Descripción:**

-   Crear método index() en HarvestController
-   Retornar cosechas paginadas con relación planting_season
-   Incluir filtros: ?planting_season_id=1, ?quality=extra
-   Calcular totales por unidad de medida

**Archivos a crear/modificar:**

-   `app/Http/Controllers/Api/HarvestController.php`
-   `routes/api.php`

**Respuesta esperada:**

```json
{
    "data": [
        {
            "id": 1,
            "planting_season": { "id": 1, "crop_name": "Tomate" },
            "harvest_date": "2025-03-15",
            "quantity": 1500,
            "unit": "kg",
            "quality": "first",
            "yield_per_hectare": 142.86
        }
    ],
    "totals": { "kg": 1500 }
}
```

#### Tarea 4.3: Implementar endpoint de creación (POST /api/harvests)

**Complejidad:** Media  
**Tiempo estimado:** 3 horas  
**Descripción:**

-   Crear método store() en HarvestController
-   Validar que harvest_date >= planting_date
-   Calcular yield_per_hectare automáticamente (quantity / planted_area)
-   Crear cosecha en base de datos
-   Retornar cosecha creada con código 201

**Archivos a modificar:**

-   `app/Http/Controllers/Api/HarvestController.php`

#### Tarea 4.4: Implementar endpoint de detalle (GET /api/harvests/{id})

**Complejidad:** Baja  
**Tiempo estimado:** 1 hora  
**Descripción:**

-   Crear método show() en HarvestController
-   Retornar cosecha con relación completa de planting_season
-   Incluir información de parcela y cultivo
-   Calcular días de cultivo (harvest_date - planting_date)

**Archivos a modificar:**

-   `app/Http/Controllers/Api/HarvestController.php`

#### Tarea 4.5: Implementar endpoint de actualización (PUT /api/harvests/{id})

**Complejidad:** Baja  
**Tiempo estimado:** 2 horas  
**Descripción:**

-   Crear método update() en HarvestController
-   Validar datos con UpdateHarvestRequest
-   Recalcular yield_per_hectare si cambia quantity
-   Retornar cosecha actualizada

**Archivos a modificar:**

-   `app/Http/Controllers/Api/HarvestController.php`

#### Tarea 4.6: Crear Resource para formatear respuestas

**Complejidad:** Baja  
**Tiempo estimado:** 1 hora  
**Descripción:**

-   Crear HarvestResource
-   Incluir campos calculados (yield_per_hectare, cultivation_days)
-   Incluir relaciones necesarias

**Archivos a crear:**

-   `app/Http/Resources/HarvestResource.php`

---

## HU-005: API de Gestión de Clientes

**Como** desarrollador del sistema  
**Quiero** crear endpoints para gestionar clientes  
**Para** mantener registro de los compradores

### Contexto Delimitado

Venta y Distribución

### Criterios de Aceptación

-   La API debe permitir listar todos los clientes (GET)
-   La API debe permitir crear nuevos clientes (POST)
-   La API debe permitir ver un cliente específico (GET)
-   La API debe permitir actualizar un cliente (PUT)
-   La API debe permitir desactivar/activar un cliente (PATCH)
-   Validar unicidad de documento y email

### Tareas de Desarrollo

#### Tarea 5.1: Crear Requests de validación

**Complejidad:** Baja  
**Tiempo estimado:** 2 horas  
**Descripción:**

-   Crear StoreCustomerRequest con validaciones:
    -   name: required, string, max:255
    -   customer_type: required, in:person,company
    -   document: required, string, unique:customers
    -   phone: nullable, string
    -   email: nullable, email, unique:customers
    -   address: nullable, string
    -   is_active: boolean (default true)
-   Crear UpdateCustomerRequest (unique excepto el registro actual)

**Archivos a crear:**

-   `app/Http/Requests/Api/StoreCustomerRequest.php`
-   `app/Http/Requests/Api/UpdateCustomerRequest.php`

#### Tarea 5.2: Implementar endpoint de listado (GET /api/customers)

**Complejidad:** Baja  
**Tiempo estimado:** 2 horas  
**Descripción:**

-   Crear método index() en CustomerController
-   Retornar clientes paginados
-   Incluir filtros: ?search=, ?customer_type=person, ?is_active=1
-   Ordenar por nombre

**Archivos a crear/modificar:**

-   `app/Http/Controllers/Api/CustomerController.php`
-   `routes/api.php`

**Respuesta esperada:**

```json
{
    "data": [
        {
            "id": 1,
            "name": "Juan Pérez",
            "customer_type": "person",
            "document": "123456789",
            "email": "juan@example.com",
            "phone": "3001234567",
            "is_active": true
        }
    ]
}
```

#### Tarea 5.3: Implementar endpoint de creación (POST /api/customers)

**Complejidad:** Baja  
**Tiempo estimado:** 2 horas  
**Descripción:**

-   Crear método store() en CustomerController
-   Validar datos con StoreCustomerRequest
-   Crear cliente con is_active=true por defecto
-   Retornar cliente creado con código 201

**Archivos a modificar:**

-   `app/Http/Controllers/Api/CustomerController.php`

#### Tarea 5.4: Implementar endpoint de detalle (GET /api/customers/{id})

**Complejidad:** Baja  
**Tiempo estimado:** 1 hora  
**Descripción:**

-   Crear método show() en CustomerController
-   Retornar cliente con estadísticas básicas
-   Incluir total de pedidos realizados (opcional)
-   Manejar error 404 si no existe

**Archivos a modificar:**

-   `app/Http/Controllers/Api/CustomerController.php`

#### Tarea 5.5: Implementar endpoint de actualización (PUT /api/customers/{id})

**Complejidad:** Baja  
**Tiempo estimado:** 2 horas  
**Descripción:**

-   Crear método update() en CustomerController
-   Validar datos con UpdateCustomerRequest
-   No permitir cambiar customer_type
-   Retornar cliente actualizado

**Archivos a modificar:**

-   `app/Http/Controllers/Api/CustomerController.php`

#### Tarea 5.6: Implementar endpoint de activar/desactivar (PATCH /api/customers/{id}/toggle-status)

**Complejidad:** Baja  
**Tiempo estimado:** 1 hora  
**Descripción:**

-   Crear método toggleStatus() en CustomerController
-   Cambiar is_active entre true/false
-   Retornar cliente actualizado con nuevo estado

**Archivos a modificar:**

-   `app/Http/Controllers/Api/CustomerController.php`
-   `routes/api.php`

#### Tarea 5.7: Crear Resource para formatear respuestas

**Complejidad:** Baja  
**Tiempo estimado:** 1 hora  
**Descripción:**

-   Crear CustomerResource
-   Incluir todos los campos relevantes
-   Aplicar en todos los endpoints

**Archivos a crear:**

-   `app/Http/Resources/CustomerResource.php`

---

## HU-006: API de Gestión de Pedidos

**Como** desarrollador del sistema  
**Quiero** crear endpoints para gestionar pedidos  
**Para** registrar las ventas a clientes

### Contexto Delimitado

Venta y Distribución

### Criterios de Aceptación

-   La API debe permitir listar todos los pedidos (GET)
-   La API debe permitir crear nuevos pedidos con detalles (POST)
-   La API debe permitir ver un pedido específico (GET)
-   La API debe permitir actualizar un pedido pendiente (PUT)
-   La API debe permitir cambiar el estado del pedido (PATCH)
-   Usar transacciones para crear pedido + detalles

### Tareas de Desarrollo

#### Tarea 6.1: Crear Requests de validación

**Complejidad:** Media  
**Tiempo estimado:** 2 horas  
**Descripción:**

-   Crear StoreOrderRequest con validaciones:
    -   customer_id: required, exists:customers,id
    -   order_date: required, date
    -   delivery_date: nullable, date, after:order_date
    -   status: nullable, in:pending,processing,completed,cancelled
    -   details: required, array, min:1
    -   details.\*.product_id: required, exists:products,id
    -   details.\*.quantity: required, numeric, min:0.01
    -   details.\*.unit_price: required, numeric, min:0
-   Crear UpdateOrderRequest (similar)

**Archivos a crear:**

-   `app/Http/Requests/Api/StoreOrderRequest.php`
-   `app/Http/Requests/Api/UpdateOrderRequest.php`

#### Tarea 6.2: Implementar endpoint de listado (GET /api/orders)

**Complejidad:** Baja  
**Tiempo estimado:** 2 horas  
**Descripción:**

-   Crear método index() en OrderController
-   Retornar pedidos paginados con relación customer
-   Incluir filtros: ?customer_id=1, ?status=pending
-   Ordenar por fecha descendente

**Archivos a crear/modificar:**

-   `app/Http/Controllers/Api/OrderController.php`
-   `routes/api.php`

**Respuesta esperada:**

```json
{
    "data": [
        {
            "id": 1,
            "customer": { "id": 1, "name": "Juan Pérez" },
            "order_date": "2025-01-15",
            "total_amount": 250000,
            "status": "pending",
            "details_count": 3
        }
    ]
}
```

#### Tarea 6.3: Implementar endpoint de creación con transacción (POST /api/orders)

**Complejidad:** Media  
**Tiempo estimado:** 4 horas  
**Descripción:**

-   Crear método store() en OrderController
-   Usar DB::transaction para crear orden + detalles
-   Calcular total_amount sumando (quantity × unit_price)
-   Validar que el cliente esté activo
-   Crear pedido con status='pending' por defecto
-   Retornar pedido completo con detalles, código 201

**Archivos a modificar:**

-   `app/Http/Controllers/Api/OrderController.php`

**Body esperado:**

```json
{
    "customer_id": 1,
    "order_date": "2025-01-15",
    "delivery_date": "2025-01-20",
    "details": [
        { "product_id": 1, "quantity": 100, "unit_price": 1500 },
        { "product_id": 2, "quantity": 50, "unit_price": 2000 }
    ]
}
```

#### Tarea 6.4: Implementar endpoint de detalle (GET /api/orders/{id})

**Complejidad:** Baja  
**Tiempo estimado:** 1 hora  
**Descripción:**

-   Crear método show() en OrderController
-   Retornar pedido con relaciones: customer, details (con products)
-   Incluir total_amount calculado
-   Manejar error 404 si no existe

**Archivos a modificar:**

-   `app/Http/Controllers/Api/OrderController.php`

#### Tarea 6.5: Implementar endpoint de actualización (PUT /api/orders/{id})

**Complejidad:** Media  
**Tiempo estimado:** 3 horas  
**Descripción:**

-   Crear método update() en OrderController
-   Solo permitir si status='pending'
-   Usar transacción para actualizar orden + detalles
-   Eliminar detalles anteriores y crear nuevos
-   Recalcular total_amount
-   Retornar error 409 si no está pendiente

**Archivos a modificar:**

-   `app/Http/Controllers/Api/OrderController.php`

#### Tarea 6.6: Implementar endpoint de cambio de estado (PATCH /api/orders/{id}/status)

**Complejidad:** Baja  
**Tiempo estimado:** 2 horas  
**Descripción:**

-   Crear método updateStatus() en OrderController
-   Validar nuevo status (pending, processing, completed, cancelled)
-   No permitir editar si ya está completed o cancelled
-   Retornar pedido con nuevo estado

**Archivos a modificar:**

-   `app/Http/Controllers/Api/OrderController.php`
-   `routes/api.php`

#### Tarea 6.7: Crear Resources para formatear respuestas

**Complejidad:** Baja  
**Tiempo estimado:** 1 hora  
**Descripción:**

-   Crear OrderResource con relaciones anidadas
-   Crear OrderDetailResource
-   Incluir campos calculados (subtotals)

**Archivos a crear:**

-   `app/Http/Resources/OrderResource.php`
-   `app/Http/Resources/OrderDetailResource.php`

---

## HU-007: API de Gestión de Empleados y Habilidades

**Como** desarrollador del sistema  
**Quiero** crear endpoints para gestionar empleados y sus habilidades  
**Para** asignar personal capacitado a las labores agrícolas

### Contexto Delimitado

Gestión de Recursos

### Criterios de Aceptación

-   La API debe permitir listar todos los empleados (GET)
-   La API debe permitir crear nuevos empleados (POST)
-   La API debe permitir ver un empleado específico (GET)
-   La API debe permitir actualizar un empleado (PUT)
-   La API debe permitir gestionar habilidades de empleados (POST/DELETE)
-   La API debe permitir listar catálogo de habilidades (GET)

### Tareas de Desarrollo

#### Tarea 7.1: Crear Requests de validación para empleados

**Complejidad:** Baja  
**Tiempo estimado:** 2 horas  
**Descripción:**

-   Crear StoreEmployeeRequest con validaciones:
    -   first_name: required, string, max:255
    -   last_name: required, string, max:255
    -   document: required, string, unique:employees
    -   position: required, string
    -   phone: nullable, string
    -   email: nullable, email
    -   hire_date: required, date
    -   contract_type: required, in:permanent,temporary,contract
    -   is_active: boolean (default true)
-   Crear UpdateEmployeeRequest (similar, unique excepto actual)

**Archivos a crear:**

-   `app/Http/Requests/Api/StoreEmployeeRequest.php`
-   `app/Http/Requests/Api/UpdateEmployeeRequest.php`

#### Tarea 7.2: Implementar CRUD básico de empleados

**Complejidad:** Baja  
**Tiempo estimado:** 3 horas  
**Descripción:**

-   Crear EmployeeController con métodos: index, store, show, update
-   index: listar paginado con filtros (?search=, ?position=, ?is_active=)
-   store: crear empleado
-   show: mostrar empleado con habilidades
-   update: actualizar empleado
-   Usar EmployeeResource para formatear

**Archivos a crear:**

-   `app/Http/Controllers/Api/EmployeeController.php`
-   `app/Http/Resources/EmployeeResource.php`
-   `routes/api.php`

**Respuesta de listado:**

```json
{
    "data": [
        {
            "id": 1,
            "full_name": "Juan Pérez García",
            "document": "123456789",
            "position": "Operario Agrícola",
            "hire_date": "2024-01-15",
            "is_active": true,
            "skills_count": 3
        }
    ]
}
```

#### Tarea 7.3: Implementar endpoint de asignar habilidad (POST /api/employees/{id}/skills)

**Complejidad:** Baja  
**Tiempo estimado:** 2 horas  
**Descripción:**

-   Crear método attachSkill() en EmployeeController
-   Validar: skill_id (exists), proficiency_level (basic/intermediate/advanced/expert), certification_date (optional)
-   Evitar duplicados (skill_id único por empleado)
-   Crear registro en tabla pivote employee_skills
-   Retornar empleado con habilidades actualizadas

**Archivos a modificar:**

-   `app/Http/Controllers/Api/EmployeeController.php`
-   `routes/api.php`

**Body esperado:**

```json
{
    "skill_id": 1,
    "proficiency_level": "intermediate",
    "certification_date": "2025-01-10"
}
```

#### Tarea 7.4: Implementar endpoint de eliminar habilidad (DELETE /api/employees/{id}/skills/{skillId})

**Complejidad:** Baja  
**Tiempo estimado:** 1 hora  
**Descripción:**

-   Crear método detachSkill() en EmployeeController
-   Eliminar registro de tabla pivote employee_skills
-   Retornar código 204 (sin contenido)
-   Manejar error 404 si no existe la relación

**Archivos a modificar:**

-   `app/Http/Controllers/Api/EmployeeController.php`
-   `routes/api.php`

#### Tarea 7.5: Implementar CRUD de catálogo de habilidades

**Complejidad:** Baja  
**Tiempo estimado:** 2 horas  
**Descripción:**

-   Crear SkillController con métodos: index, store, show, update
-   index: listar todas las habilidades
-   store: crear nueva habilidad (name, description)
-   show: mostrar habilidad específica
-   update: actualizar habilidad
-   Validar nombre único

**Archivos a crear:**

-   `app/Http/Controllers/Api/SkillController.php`
-   `app/Http/Resources/SkillResource.php`
-   `app/Http/Requests/Api/StoreSkillRequest.php`
-   `routes/api.php`

**Respuesta de listado:**

```json
{
    "data": [
        {
            "id": 1,
            "name": "Operación de Tractor",
            "description": "Habilidad para operar maquinaria agrícola pesada"
        }
    ]
}
```

#### Tarea 7.6: Crear Resource completo para empleado con habilidades

**Complejidad:** Baja  
**Tiempo estimado:** 1 hora  
**Descripción:**

-   Actualizar EmployeeResource para incluir habilidades anidadas
-   Incluir en cada habilidad: id, name, proficiency_level, certification_date
-   Calcular full_name (first_name + last_name)
-   Formatear fechas apropiadamente

**Archivos a modificar:**

-   `app/Http/Resources/EmployeeResource.php`

**Respuesta de detalle:**

```json
{
    "id": 1,
    "full_name": "Juan Pérez García",
    "document": "123456789",
    "position": "Operario Agrícola",
    "email": "juan@example.com",
    "hire_date": "2024-01-15",
    "is_active": true,
    "skills": [
        {
            "id": 1,
            "name": "Operación de Tractor",
            "proficiency_level": "intermediate",
            "certification_date": "2025-01-10"
        }
    ]
}
```

---

## Resumen de Estimaciones

| Historia de Usuario                 | Total de Tareas | Tiempo Total Estimado |
| ----------------------------------- | --------------- | --------------------- |
| HU-001: Autenticación (API)         | 8 tareas        | ~15 horas             |
| HU-002: API Parcelas                | 7 tareas        | ~12 horas             |
| HU-003: API Temporadas de Siembra   | 6 tareas        | ~11 horas             |
| HU-004: API Cosechas                | 6 tareas        | ~11 horas             |
| HU-005: API Clientes                | 7 tareas        | ~11 horas             |
| HU-006: API Pedidos                 | 7 tareas        | ~15 horas             |
| HU-007: API Empleados y Habilidades | 6 tareas        | ~11 horas             |
| **TOTAL**                           | **47 tareas**   | **~86 horas**         |

## Notas para Desarrolladores Junior

### Stack Tecnológico

-   **Framework**: Laravel 11
-   **Autenticación**: Laravel Sanctum (tokens)
-   **Base de datos**: MySQL/PostgreSQL
-   **Formato de respuesta**: JSON (API REST)

### Estructura de Archivos API

-   **Controladores**: `app/Http/Controllers/Api/`
-   **Modelos**: `app/Models/`
-   **Resources**: `app/Http/Resources/`
-   **Requests**: `app/Http/Requests/Api/`
-   **Rutas**: `routes/api.php`

### Convenciones de API REST

#### Códigos HTTP

-   `200 OK`: Operación exitosa (GET, PUT, PATCH)
-   `201 Created`: Recurso creado exitosamente (POST)
-   `204 No Content`: Operación exitosa sin contenido (DELETE)
-   `400 Bad Request`: Solicitud mal formada
-   `401 Unauthorized`: No autenticado
-   `403 Forbidden`: No autorizado
-   `404 Not Found`: Recurso no encontrado
-   `409 Conflict`: Conflicto (ej: no se puede eliminar)
-   `422 Unprocessable Entity`: Errores de validación
-   `500 Internal Server Error`: Error del servidor

#### Estructura de URLs

```
GET    /api/resource          # Listar todos
POST   /api/resource          # Crear nuevo
GET    /api/resource/{id}     # Ver específico
PUT    /api/resource/{id}     # Actualizar completo
PATCH  /api/resource/{id}     # Actualizar parcial
DELETE /api/resource/{id}     # Eliminar
```

#### Formato de Respuestas Exitosas

```json
// Listado paginado
{
  "data": [...],
  "links": {...},
  "meta": {
    "current_page": 1,
    "total": 50,
    "per_page": 15
  }
}

// Recurso individual
{
  "id": 1,
  "name": "...",
  "created_at": "2025-01-15T10:00:00.000000Z"
}
```

#### Formato de Respuestas de Error

```json
// Error de validación (422)
{
  "message": "The given data was invalid.",
  "errors": {
    "email": ["The email has already been taken."],
    "name": ["The name field is required."]
  }
}

// Error general (404, 409, etc.)
{
  "message": "Resource not found"
}
```

### Validaciones Comunes en Laravel

```php
'name' => 'required|string|max:255',
'email' => 'required|email|unique:users,email',
'age' => 'required|integer|min:18|max:100',
'price' => 'required|numeric|min:0',
'date' => 'required|date|after:today',
'status' => 'required|in:active,inactive',
'user_id' => 'required|exists:users,id',
```

### Uso de Resources

```php
// En controlador
return UserResource::collection($users);
return new UserResource($user);

// Definición de Resource
class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
        ];
    }
}
```

### Protección de Rutas

```php
// En routes/api.php
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('plots', PlotController::class);
    Route::apiResource('customers', CustomerController::class);
});
```

### Uso de Transacciones

```php
use Illuminate\Support\Facades\DB;

DB::transaction(function () {
    $order = Order::create($data);

    foreach ($details as $detail) {
        $order->details()->create($detail);
    }
});
```

### Orden de Implementación Sugerido

1. **Semana 1-2**: HU-001 (Autenticación) - **Desarrollador Senior**
2. **Semana 2**: HU-002 (Parcelas) - CRUD básico, ideal para empezar
3. **Semana 3**: HU-005 (Clientes) - Similar a parcelas
4. **Semana 3**: HU-007 (Empleados) - Introducción a relaciones
5. **Semana 4**: HU-003 (Temporadas) - Relaciones más complejas
6. **Semana 4**: HU-004 (Cosechas) - Con cálculos
7. **Semana 5**: HU-006 (Pedidos) - Maestro-detalle con transacciones

### Testing con Postman/Insomnia

#### Registro

```http
POST http://localhost:8000/api/register
Content-Type: application/json

{
  "name": "Juan Pérez",
  "email": "juan@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

#### Login

```http
POST http://localhost:8000/api/login
Content-Type: application/json

{
  "email": "juan@example.com",
  "password": "password123"
}
```

#### Endpoints Protegidos

```http
GET http://localhost:8000/api/plots
Authorization: Bearer {token}
```

### Recursos de Apoyo

-   **Laravel Docs**: https://laravel.com/docs/11.x
-   **Sanctum**: https://laravel.com/docs/11.x/sanctum
-   **API Resources**: https://laravel.com/docs/11.x/eloquent-resources
-   **Validation**: https://laravel.com/docs/11.x/validation
-   **Database Transactions**: https://laravel.com/docs/11.x/database#database-transactions
-   **Postman**: https://www.postman.com/downloads/
