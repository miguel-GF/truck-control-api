
### **Migraciones**

*Api para control de camiones administrativo*

Comandos básicos para migraciones, todo desde la terminal en la raíz de tu proyecto

Para crear una migración para una nueva tabla en base de datos:
```bash
php artisan make:migration create_nombretabla_table --create=nombretabla
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