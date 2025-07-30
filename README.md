# ESTRUCTURA MVC MINDEF V. 1.0 🚀

¡Bienvenido al proyecto **ESTRUCTURA MVC MINDEF V. 1.0**!  
Este documento te guiará a través de los requisitos y los pasos necesarios para configurar y poner en marcha el proyecto en tu entorno de desarrollo.

---

## 🛠️ Requisitos del Sistema

Para asegurar el correcto funcionamiento de esta aplicación, asegúrate de que tu entorno cumpla con los siguientes requisitos:

- **PHP**: Versión 7.2.4 o superior 🐘  
- **Extensión PDO_INFORMIX**: Necesaria para la conexión con la base de datos Informix 💾  
- **Node.js**: Versión 17.9.0 🟢  
- **NPM**: Versión 8.5 📦  
- **Composer**: Versión 2.3 o superior 🎶  
- **Git**: Versión 2.35 o superior 🌳  
- **Apache mod_rewrite**: Debe estar habilitado en tu servidor web para la gestión de URLs amigables 🌐

---

## 🏁 Guía de Inicio Rápido

Sigue estos pasos detallados para configurar tu entorno y empezar a trabajar con el proyecto.

### 1. Verificar y habilitar `mod_rewrite` en Apache ✅

Es fundamental que la directiva `AllowOverride All` esté configurada en el archivo de configuración de tu servidor Apache:

```conf
<Directory /var/www/html>
    AllowOverride All
</Directory>
```

📁 En sistemas **Ubuntu**, esta configuración generalmente se encuentra en:  
`/etc/apache2/sites-available/`

---

### 2. Clonar el repositorio ⬇️

Clona este repositorio en el directorio raíz de tu servidor web:

```bash
git clone <URL_DEL_REPOSITORIO>
```

---

### 3. Crear el archivo `.gitignore` 🚫

Crea un archivo llamado `.gitignore` en la raíz del proyecto con el siguiente contenido:

```gitignore
>>>>>>> bitacora
node_modules/
vendor/
composer.lock
packagelock.json
public/build/
.gitignore
.htaccess
public/.htaccess
temp/
storage/
includes/.env

```

---

### 4. Configurar archivos `.htaccess` para redirecciones ➡️

#### 📁 `.htaccess` en la raíz del proyecto

```apache
RewriteEngine on
RewriteRule ^$ public/ [L]
RewriteRule (.*) public/$1 [L]
```

#### 🌐 `.htaccess` en la carpeta `public/`

```apache
>>>>>>> bitacora
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^ index.php [QSA,L]
5. Crear el Archivo de Variables de Entorno (.env) ⚙️
Crea un archivo llamado .env en el directorio includes/ de tu proyecto. Este archivo contendrá la configuración específica de tu entorno, como las credenciales de la base de datos y el modo de depuración.

---

### 5. Crear el archivo de variables de entorno `.env` ⚙️

Crea el archivo `.env` dentro de la carpeta `includes/` con la siguiente información:

```env
>>>>>>> bitacora
DEBUG_MODE = 0
DB_HOST=host_de_tu_bd
DB_SERVICE=puerto_de_tu_bd
DB_SERVER=nombre_del_servidor_bd
DB_NAME=nombre_de_tu_bd

APP_NAME = "Nombre de tu Aplicación"

```

🔑 Reemplaza los valores genéricos con la información real de tu entorno.

> Puedes usar `DEBUG_MODE = 1` durante el desarrollo para mostrar errores detallados 🐛

---

### 6. Instalar dependencias de Node.js ➕

Desde la raíz del proyecto, ejecuta:

```bash
npm install
```

---

### 7. Instalar dependencias de Composer ➕

También desde la raíz del proyecto:

```bash
composer install
```

---

### 8. Construir archivos estáticos para la carpeta `public/` 🏗️

Ejecuta:

```bash
npm run build
```

> Este comando generalmente permanece en ejecución durante el desarrollo (modo watch).  
> Si necesitas una compilación única, puedes usar un comando como `npm run build:prod` si está definido.

---

### 9. Configurar información del proyecto 📝

Actualiza los archivos con los datos reales de tu aplicación:

- `package.json` (para configuración front-end 🌐)
- `composer.json` (para configuración back-end 🐘)

---

