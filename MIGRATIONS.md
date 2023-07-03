
### **Migraciones**

*Comandos básicos para migraciones, todo desde la terminal en la raíz de tu proyecto*

Para crear una migración para una nueva tabla en base de datos:
```bash
php artisan make:migration create_nombre_tabla_table --create=nombre_tabla
```

Luego abres el archivo que te creo que esta en app/database/migrations
que te la debio crear como algo parecido a continuación
*2023_06_25_040115_create_nombre_tabla_table.php*

Y tienes que crear dentro de tu método up algo parecido pero con los nuevos campos
```bash
Schema::create('nombre_tabla', function (Blueprint $table) {
    $table->id();
    $table->string('nombre');
    $table->integer('clave');
    $table->string('telefono')->nullable();
    $table->integer('status');
    $table->string('registro_autor_id')->nullable();
    $table->timestamp('registro_fecha');
    $table->string('actualizacion_autor_id')->nullable();
    $table->timestamp('actualizacion_fecha')->nullable();
});
```

Crear el modelo/objeto de la tabla que crearas
```bash
php artisan make:model NombreModelo (en singular)
```

Abres tu modelo que creaste anteriormente y pones esta linea en su clase
```bash
protected $table = 'nombre_tabla';
```
Esto es porque laravel interpreta que las nombres de las tablas tienen que estar en inglés

Para ejecutar las migraciones
```bash
php artisan migrate
```