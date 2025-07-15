ESTRUCTURA MVC MINDEF V. 1.0 🚀
¡Bienvenido al proyecto ESTRUCTURA MVC MINDEF V. 1.0! Este documento te guiará a través de los requisitos y los pasos necesarios para configurar y poner en marcha el proyecto en tu entorno de desarrollo.

Requisitos del Sistema 🛠️
Para asegurar el correcto funcionamiento de esta aplicación, asegúrate de que tu entorno cumpla con los siguientes requisitos:

PHP: Versión 7.2.4 o superior 🐘

Extensión PDO_INFORMIX: Necesaria para la conexión con la base de datos Informix 💾

Node.js: Versión 17.9.0 🟢

NPM: Versión 8.5 📦

Composer: Versión 2.3 o superior 🎶

Git: Versión 2.35 o superior 🌳

Apache mod_rewrite: Debe estar habilitado en tu servidor web para la gestión de URLs amigables 🌐

Guía de Inicio Rápido 🏁
Sigue estos pasos detallados para configurar tu entorno y empezar a trabajar con el proyecto.

1. Verificar y Habilitar mod_rewrite en Apache ✅
Es fundamental que la directiva AllowOverride All esté configurada en el archivo de configuración de tu servidor Apache para el directorio donde alojarás el proyecto. Esto permite que el archivo .htaccess sobrescriba las configuraciones del servidor.

Ejemplo de configuración en Apache:

Fragmento de código

<Directory /var/www/html>
    AllowOverride All
</Directory>
Ubicación común en Ubuntu:

En sistemas Ubuntu, esta configuración generalmente se encuentra en los archivos de configuración de sitios disponibles de Apache, ubicados en: /etc/apache2/sites-available/. 📁

2. Clonar el Repositorio ⬇️
Clona este repositorio en el directorio raíz de tu servidor web o en la ubicación que estés utilizando para tus proyectos (ej. C:\docker en Windows, o /var/www/html en Linux).

Bash

git clone <URL_DEL_REPOSITORIO>
3. Crear el Archivo .gitignore 🚫
Crea un archivo llamado .gitignore en la raíz de tu proyecto con el siguiente contenido. Este archivo es crucial para evitar que archivos temporales, dependencias y configuraciones sensibles sean incluidos en el control de versiones de Git.

Fragmento de código

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
4. Configurar Archivos .htaccess para Redirecciones ➡️
Estos archivos .htaccess son esenciales para que el enrutamiento de la aplicación (MVC) funcione correctamente, redirigiendo todas las solicitudes a index.php dentro de la carpeta public/.

.htaccess en la Raíz del Proyecto 🏠
Crea este archivo en el directorio raíz de tu proyecto:

RewriteEngine on
RewriteRule ^$ public/ [L]
RewriteRule (.*) public/$1 [L]
.htaccess en la Carpeta public/ 🌐
Crea este archivo dentro de la carpeta public/ de tu proyecto:

RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^ index.php [QSA,L]
5. Crear el Archivo de Variables de Entorno (.env) ⚙️
Crea un archivo llamado .env en el directorio includes/ de tu proyecto. Este archivo contendrá la configuración específica de tu entorno, como las credenciales de la base de datos y el modo de depuración.

DEBUG_MODE = 0
DB_HOST=host_de_tu_bd
DB_SERVICE=puerto_de_tu_bd
DB_SERVER=nombre_del_servidor_bd
DB_NAME=nombre_de_tu_bd

APP_NAME = "Nombre de tu Aplicación"
Asegúrate de reemplazar los valores genéricos (host_de_tu_bd, puerto_de_tu_bd, etc.) con la información real de tu base de datos y el nombre de tu aplicación. 🔑

DEBUG_MODE = 1 puede ser útil durante el desarrollo para ver mensajes de error detallados. 🐛

6. Instalar Dependencias de Node.js ➕
Abre tu terminal, navega hasta la raíz del proyecto y ejecuta el siguiente comando para instalar todas las dependencias de Node.js definidas en package.json:

Bash

npm install
Espera a que el proceso termine. ⏳

7. Instalar Dependencias de Composer ➕
Mientras sigues en la raíz del proyecto en tu terminal, ejecuta el siguiente comando para instalar las dependencias de PHP definidas en composer.json:

Bash

composer install
Espera a que el proceso termine. ⏳

8. Construir Archivos Estáticos para la Carpeta public/ 🏗️
Ejecuta este comando en tu terminal desde la raíz del proyecto. Este proceso compilará y moverá los archivos estáticos (CSS, JS, imágenes, etc.) a la carpeta public/build/, haciéndolos accesibles para el navegador.

Bash

npm run build
Nota: Este comando generalmente se mantiene en ejecución durante el desarrollo (watch mode) para recompilar automáticamente los archivos cada vez que detecta un cambio. Si necesitas que el proceso se ejecute solo una vez, verifica la configuración en package.json para ver si hay un comando npm run build:prod o similar. 🔄

9. Configurar Información del Proyecto 📝
Finalmente, asegúrate de actualizar los archivos package.json y composer.json con la información relevante de tu proyecto, como el nombre, la descripción, la versión y los datos del autor.

package.json (para información relacionada con Node.js y front-end) 🌐

composer.json (para información relacionada con PHP y back-end) 🐘
