## PetFood: Gestión de Dietas y Preparaciones para Mascotas

PetFood es una aplicación web de gestión diseñada para brindar un control completo sobre la preparación de dietas para mascotas, la administración de inventarios y el seguimiento detallado de las dietas para mascotas, así como el seguimiento detallado de los costos involucrados y la evolución de los pedidos. Esta plataforma está desarrollada en Laravel y Jetstream.

### Características Clave

- **Registro y Gestión de Mascotas:** Mantén un registro detallado de las mascotas, incluyendo su tipo, raza y características específicas. Realiza un seguimiento de la evolución de la salud y el bienestar de cada mascota.

- **Registro de Productos:** Cataloga los productos utilizados en las dietas, distinguiendo entre gastos y aquellos que se transforman durante la preparación (ingredientes).

- **Generación y Gestión de Recetas:** Crea y administra recetas de preparación de dietas, especificando los productos necesarios y sus cantidades.

- **Control de Inventario de Ingredientes:** Administra un registro detallado de unidades y factores de conversión, asegurando la precisión en la preparación de dietas.

- **Seguimiento de Compras e Insumos:** Registra compras de insumos, manteniendo un registro de gastos y actualizando el inventario en consecuencia.

- **Control de Gastos y Costos:** Lleva un control exhaustivo de los gastos asociados con la preparación de dietas y calcula los costos reales de producción.

- **Administración de Pedidos:** Gestiona los pedidos de dietas de mascotas, manteniendo un historial de detalles, fechas y evolución a lo largo del tiempo.

- **Generación de Listas de Compras:** Basado en los pedidos y sus fórmulas de preparación, genera listas de compras para asegurar un suministro eficiente.

- **Generación de Preparaciones:** Crea preparaciones de dietas para mascotas siguiendo las recetas y mantén un registro de las cantidades producidas.

## Requerimientos

- PHP >= 8.1

- Laravel >= 10.10

- Jetstream >= 3.2

- Composer

- Base de datos MySQL

## Instalación

- Clona este repositorio

- Ejecuta `composer install` para instalar las dependencias de Laravel.

- Copia el archivo ***.env.example*** y renómbralo a ***.env***. Luego, configura las variables de entorno, incluida la conexión a la base de datos.

- Ejecuta `php artisan key:generate` para generar la clave de la aplicación.

- Ejecuta `php artisan migrate --seed` para crear las tablas de la base de datos y llenarlas con datos base.

## Licencia

Este proyecto está protegido bajo la Licencia Creative Commons Attribution-NonCommercial-NoDerivatives 4.0 International (CC BY-NC-ND 4.0).

### Resumen de la Licencia:

- **Atribución (BY)**: Debes dar crédito adecuado al autor original (nombre del autor) y proporcionar un enlace a la licencia.

- **No Comercial (NC)**: No puedes utilizar este trabajo con fines comerciales sin el permiso expreso por escrito del autor.

- **No Derivadas (ND)**: No puedes modificar, transformar o crear obras derivadas basadas en este trabajo.

Para más detalles, consulta el archivo [LICENSE](https://creativecommons.org/licenses/by-nc-nd/4.0/legalcode).
