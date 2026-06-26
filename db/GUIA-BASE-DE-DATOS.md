# Guía: cómo entrar y ver la base de datos (Docker + MariaDB)

Guía paso a paso, explicada para quien recién arranca con Docker.
Todo se hace **conectado por SSH a la VM**, parado en la carpeta del proyecto.

---

## 0. Conceptos básicos (leer una vez)

El proyecto corre con **3 contenedores** (mini-máquinas aisladas) levantados por Docker:

| Contenedor          | Qué es              | Para qué sirve                          |
|---------------------|---------------------|-----------------------------------------|
| `nginx_container`   | Servidor web (nginx)| Sirve las páginas al navegador          |
| `php_container`     | PHP                 | Ejecuta el código `.php`                 |
| `mariadb_container` | Base de datos       | Guarda las tablas (usuarios, reservas…) |

- La base **vive adentro** del contenedor `mariadb_container`.
- Para "entrar" a la base, abrimos un cliente de MariaDB **dentro** de ese contenedor.
- Datos de conexión:
  - **Usuario:** `root`
  - **Contraseña:** `biblio-ifts`
  - **Base:** `turnos-biblioteca`

> ⚠️ Antes de cualquier comando, posicionate en la carpeta del proyecto:
> ```bash
> cd ~/biblio-ifts
> ```

---

## 1. Ver si los contenedores están prendidos

```bash
docker-compose ps
```

Los 3 deben aparecer como **Up**. Si la base (`mariadb_container`) no está, levantá todo:

```bash
docker-compose up -d
```

(El `-d` = "detached", corre en segundo plano y te devuelve la terminal.)

---

## 2. Entrar a la base de datos (modo interactivo)

Este es el comando principal. Te mete **dentro** de la base, en una consola SQL:

```bash
docker-compose exec db mariadb -u root -pbiblio-ifts turnos-biblioteca
```

Desglose de lo que significa cada parte:

| Parte                     | Qué hace                                            |
|---------------------------|-----------------------------------------------------|
| `docker-compose exec db`  | "ejecutá algo dentro del contenedor `db`"           |
| `mariadb`                 | abrí el cliente de base de datos MariaDB            |
| `-u root`                 | con el usuario `root`                               |
| `-pbiblio-ifts`           | con la contraseña `biblio-ifts` (¡pegada a la `-p`!)|
| `turnos-biblioteca`       | y entrá directo a esta base                         |

Si funcionó, el prompt cambia a:

```
MariaDB [turnos-biblioteca]>
```

Eso significa que **ya estás adentro** y podés escribir SQL.

> 📌 Ojo: en SQL, **cada comando termina con punto y coma `;`**. Si te olvidás,
> la consola queda esperando con `->`; simplemente escribí `;` y Enter.

---

## 3. Comandos SQL para mirar la base

Una vez adentro (`MariaDB [turnos-biblioteca]>`):

### Ver todas las tablas
```sql
SHOW TABLES;
```

### Ver la estructura de una tabla (columnas, tipos)
```sql
DESCRIBE usuarios;
DESCRIBE reservas;
DESCRIBE proyectos;
```
(podés cambiar el nombre por cualquier tabla)

### Ver el contenido de una tabla
```sql
SELECT * FROM roles;
SELECT * FROM tipos_uso;
SELECT * FROM horarios;
SELECT * FROM palabras_clave;
```

### Contar cuántos registros tiene una tabla
```sql
SELECT COUNT(*) FROM usuarios;
```

### Ver solo algunas columnas
```sql
SELECT nombre_completo, email_institucional FROM usuarios;
```

### Filtrar con condiciones
```sql
SELECT * FROM reservas WHERE estado = 'ACTIVA';
SELECT * FROM usuarios WHERE id_rol = 1;
```

### Ver todas las bases que existen en el motor
```sql
SHOW DATABASES;
```

### Cambiar de base (si hubiera otra)
```sql
USE turnos-biblioteca;
```

---

## 4. Salir de la base

```sql
EXIT;
```
o `QUIT;` o `Ctrl + D`. Volvés a la terminal normal de la VM.

---

## 5. Atajo: consultar SIN entrar (una sola línea)

Si solo querés un dato rápido sin entrar al modo interactivo, usá `-e "..."`:

```bash
# Listar tablas
docker-compose exec db mariadb -u root -pbiblio-ifts turnos-biblioteca -e "SHOW TABLES;"

# Ver una tabla
docker-compose exec db mariadb -u root -pbiblio-ifts turnos-biblioteca -e "SELECT * FROM roles;"

# Ver estructura de una tabla
docker-compose exec db mariadb -u root -pbiblio-ifts turnos-biblioteca -e "DESCRIBE reservas;"
```

Ejecuta la consulta, muestra el resultado y te devuelve a la terminal. Ideal para chequeos rápidos.

---

## 6. Ver TODA la base de un vistazo (estructura completa)

Para listar cada tabla con todas sus columnas, de corrido:

```bash
docker-compose exec db sh -c 'mariadb -u root -pbiblio-ifts -e "
  SELECT table_name AS tabla, column_name AS columna, column_type AS tipo
  FROM information_schema.columns
  WHERE table_schema = \"turnos-biblioteca\"
  ORDER BY table_name, ordinal_position;"'
```

Esto te muestra **todas las tablas y todas sus columnas** en una sola lista.

---

## 7. Hacer un backup (exportar la base a un archivo)

Guarda toda la base (estructura + datos) en un archivo `.sql`:

```bash
docker-compose exec db sh -c 'mariadb-dump -u root -pbiblio-ifts turnos-biblioteca' > ~/biblio-ifts/backup.sql
```

Para restaurar ese backup más adelante:

```bash
docker-compose exec -T db mariadb -u root -pbiblio-ifts turnos-biblioteca < ~/biblio-ifts/backup.sql
```

---

## 8. Comandos útiles de Docker (gestión de contenedores)

Siempre desde `cd ~/biblio-ifts`:

| Comando                              | Qué hace                                            |
|--------------------------------------|-----------------------------------------------------|
| `docker-compose ps`                  | Ver qué contenedores están corriendo                |
| `docker-compose up -d`               | Levantar todos los contenedores                     |
| `docker-compose down`                | Apagar los contenedores (los datos se conservan)    |
| `docker-compose restart`             | Reiniciar todo                                      |
| `docker-compose restart db`          | Reiniciar solo la base                              |
| `docker-compose logs db`             | Ver los logs de la base                             |
| `docker-compose logs db | tail -20`  | Ver las últimas 20 líneas de log de la base         |

> ⚠️ **Nunca** uses `docker-compose down -v`: el `-v` **borra el volumen de
> datos** y perdés todo lo cargado en la base. `down` solo (sin `-v`) es seguro.

---

## 9. Si algo no anda

- **"No such service: db"** → no estás en la carpeta del proyecto. Hacé `cd ~/biblio-ifts`.
- **"Can't connect" / "Connection refused"** → la base recién arranca. Esperá
  ~20 segundos y reintentá. Verificá con `docker-compose logs db | tail -20`
  buscando el mensaje *"ready for connections"*.
- **La consola SQL no responde y muestra `->`** → te faltó el `;`. Escribí `;` y Enter.
- **La VM se pone lenta** → revisá memoria con `free -h` y disco con `df -h /`.

---

## Resumen mínimo (lo que vas a usar el 90% del tiempo)

```bash
cd ~/biblio-ifts                                                        # 1. ir a la carpeta
docker-compose ps                                                      # 2. ver que esté Up
docker-compose exec db mariadb -u root -pbiblio-ifts turnos-biblioteca # 3. entrar a la base
```
Ya adentro:
```sql
SHOW TABLES;            -- ver tablas
SELECT * FROM roles;    -- ver una tabla
EXIT;                   -- salir
```
