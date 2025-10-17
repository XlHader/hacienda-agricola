# Contextos Delimitados (Bounded Contexts)

## 1. Contexto de Cultivo

### Límites
Gestiona todas las operaciones relacionadas con la producción agrícola desde la planificación hasta la cosecha. Este contexto inicia cuando se selecciona una parcela para cultivo y finaliza cuando se registra la cosecha, transfiriendo los productos al contexto de Venta y Distribución.

### Responsabilidades
- **Gestión de parcelas y análisis de suelo**: Administra las unidades de tierra, registrando características físicas, químicas y su historial de uso para optimizar la rotación de cultivos
- **Planificación de cultivos y variedades**: Define qué se cultivará en cada temporada, seleccionando variedades según condiciones climáticas, demanda del mercado y capacidad productiva
- **Control de sistemas de riego y fuentes de agua**: Supervisa infraestructura de irrigación (goteo, aspersión, gravedad), programa riegos y monitorea consumo de agua
- **Planes de fertilización**: Diseña estrategias nutricionales específicas por cultivo, calculando dosis, momento de aplicación y tipo de fertilizantes
- **Control fitosanitario (plagas y enfermedades)**: Detecta, registra y trata problemas sanitarios que afectan los cultivos mediante tratamientos preventivos y correctivos
- **Monitoreo fenológico**: Rastrea etapas de desarrollo del cultivo (germinación, crecimiento vegetativo, floración, fructificación) para anticipar necesidades y optimizar cosecha
- **Registro de labores agrícolas**: Documenta todas las actividades realizadas (preparación de suelo, siembra, aporque, deshierbe, cosecha) con fecha, recursos y personal asignado
- **Gestión de inventario de insumos agrícolas**: Controla existencias de semillas, fertilizantes y agroquímicos, incluyendo lotes, fechas de vencimiento y costos

### Interacciones con Otros Contextos
- **→ Gestión de Recursos**: Solicita personal capacitado para labores agrícolas específicas
- **→ Venta y Distribución**: Entrega información de cosechas disponibles para su comercialización

---

## 2. Contexto de Venta y Distribución

### Límites
Maneja el flujo comercial desde el producto terminado hasta su entrega al cliente. Comienza cuando la cosecha se transforma en producto comercializable y termina cuando el cliente recibe el producto y se registra el pago correspondiente.

### Responsabilidades
- **Gestión de clientes y preferencias**: Mantiene base de datos de compradores con historial de transacciones, preferencias de productos, canales de distribución y condiciones comerciales
- **Procesamiento de pedidos**: Recibe, valida y gestiona solicitudes de compra, verificando disponibilidad de inventario y estableciendo fechas de entrega
- **Control de inventario de productos terminados**: Administra stock de productos cosechados, organizados por lotes con información de origen, calidad y cantidad disponible
- **Gestión de calidad y trazabilidad**: Clasifica productos según estándares (Extra, Primera, Segunda) y mantiene el rastro desde la parcela de origen hasta el cliente final
- **Planificación de rutas de distribución**: Optimiza secuencias de entrega considerando ubicación geográfica, capacidad de vehículos y ventanas de tiempo de clientes
- **Administración de flota de transporte**: Controla vehículos propios o contratados, incluyendo disponibilidad, capacidad de carga y estado operativo
- **Emisión de facturas y guías de envío**: Genera documentación fiscal y de transporte requerida para cumplir con regulaciones y formalizar transacciones
- **Control de pagos, devoluciones y reclamos**: Registra ingresos por ventas, gestiona productos retornados por calidad deficiente y atiende inconformidades de clientes

### Interacciones con Otros Contextos
- **← Cultivo**: Recibe información de cosechas disponibles y sus características de calidad
- **→ Gestión de Recursos**: No tiene interacción directa significativa en el modelo actual

---

## 3. Contexto de Gestión de Recursos

### Límites
Administra los recursos humanos, físicos y financieros de la hacienda. Este contexto opera de manera transversal a los demás, proporcionando el personal y equipamiento necesario para las operaciones agrícolas y comerciales.

### Responsabilidades
- **Gestión de personal**: Registra datos de empleados (identificación, contratación, rol), documenta habilidades certificadas y asigna personal capacitado a labores específicas según competencias
- **Programas de capacitación**: Planifica y ejecuta entrenamientos para desarrollar habilidades del personal (operación de maquinaria, técnicas de poda, manejo fitosanitario), registrando participación y certificaciones
- **Administración de maquinaria y equipos**: Controla tractores, cosechadoras, fumigadoras y equipos de riego, con información de especificaciones técnicas, fecha de adquisición y disponibilidad
- **Mantenimiento preventivo y correctivo**: Programa revisiones periódicas para prevenir fallas y registra reparaciones de averías, controlando costos y asegurando operatividad continua
- **Control de costos operativos**: Consolida gastos de mano de obra, combustibles, mantenimiento e insumos para determinar costos reales de producción
- **Seguimiento de ingresos y presupuestos**: Monitorea flujo de efectivo generado por ventas y compara con proyecciones para evaluar salud financiera
- **Análisis de rentabilidad**: Calcula márgenes de ganancia por cultivo, temporada o cliente para identificar operaciones más lucrativas
- **Gestión de proveedores**: Aunque mencionado en el enunciado, no se implementó en el modelo actual (simplificación)

### Interacciones con Otros Contextos
- **→ Cultivo**: Provee empleados con habilidades específicas para labores agrícolas
- **← Cultivo**: Recibe información de costos de insumos y labores para análisis financiero
- **← Venta y Distribución**: Obtiene datos de ingresos por ventas para cálculos de rentabilidad

---

## Resumen de Contextos

| Contexto | Propósito Principal | Entidades Core |
|----------|---------------------|----------------|
| **Cultivo** | Producir alimentos de calidad | Plot, PlantingSeason, Harvest |
| **Venta y Distribución** | Comercializar y entregar productos | Customer, Order, Invoice |
| **Gestión de Recursos** | Optimizar uso de recursos | Employee, Machinery, MaintenanceRecord |
