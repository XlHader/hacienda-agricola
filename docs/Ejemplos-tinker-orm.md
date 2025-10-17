
## 1. Ejemplo: Cultivo con Variedades (Relación 1:N)

### Crear Datos
```php
$cultivo = App\Models\Crop::create([
    'name' => 'Tomate',
    'type' => 'vegetable',
    'description' => 'Tomate de mesa'
]);

$variedad1 = $cultivo->varieties()->create([
    'name' => 'Cherry',
    'characteristics' => 'Fruto pequeño, alto rendimiento'
]);

$variedad2 = $cultivo->varieties()->create([
    'name' => 'Beef',
    'characteristics' => 'Fruto grande, ideal para ensaladas'
]);
```

### Consultar con ORM
```php
$cultivos = App\Models\Crop::with('varieties')->get();
```

### Eliminar
```php
$cultivo = App\Models\Crop::find(1);
$cultivo->delete();
```