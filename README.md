# Price Scraper and Quotation System

Este proyecto es un servicio web diseñado para automatizar el proceso de recopilación de precios públicos de productos mediante web scraping. Los datos obtenidos se almacenan en una base de datos relacional (MySQL/PostgreSQL), lo que permite a los usuarios generar presupuestos que pueden enviarse por correo electrónico a los clientes. El sistema también incluye autenticación de usuarios y un panel de control para gestionar productos y ofertas.

## Technologies Used

Este proyecto está construido con una variedad de tecnologías web modernas:

*   **Backend:**
    *   PHP 8.2+
    *   Laravel Framework 12.0
*   **Frontend:**
    *   JavaScript
    *   Bootstrap 5.2.3
    *   Sass
    *   Vite
*   **Database:**
    *   MySQL (Recommended)
    *   PostgreSQL (Supported)
    *   SQLite (Default for local development, configurable)
*   **Web Scraping:**
    *   Guzzle HTTP Client
    *   Symfony DOMCrawler
*   **PHP Libraries:**
    *   `laravel/ui` for basic auth scaffolding
    *   `spatie/laravel-permission` for role-based access control (if extensively configured)
    *   `spatie/crawler` for web scraping (as per composer.json)

## Features

El sistema incluye las siguientes características clave:

*   **Automated Web Scraping:** Extrae periódicamente los precios de los productos de una fuente pública determinada.
*   **Centralized Database:** Almacena toda la información sobre productos y precios en una base de datos estructurada (MySQL o PostgreSQL).
*   **User Authentication:** Sistema seguro de inicio de sesión y registro de usuarios.
*   **Quotation Management:**
    *   Cree nuevos presupuestos seleccionando productos y especificando cantidades.
    *   Ver, editar y eliminar (supresión suave) los presupuestos existentes.
    *   Restaurar las cotizaciones/presupuestos eliminados.
*   **Product Search:** Búsqueda rápida de productos por descripción o código al crear presupuestos.
*   **Dashboard:** Un panel intuitivo para ver la actividad reciente y gestionar los componentes del sistema.
*   **Email Notifications:** Envío automático de los presupuestos generados a los clientes por correo electrónico.
*   **Data Integrity:** Validaciones para garantizar la exactitud de los datos de productos y ofertas.
*   **Price Tracking:** Almacena tanto los precios FONASA para pacientes como los precios privados de los productos.

## Project Setup

Siga estos pasos para configurar el proyecto localmente para desarrollo o pruebas:

### Prerequisites

*   PHP 8.2 or higher
*   Composer (PHP package manager)
*   Node.js and npm (or yarn)
*   A database server (MySQL 5.7+ or PostgreSQL 10+)

### Installation Steps

1.  **Clonar el repositorio:**
    ```bash
    git clone <your-repository-url>
    cd <project-directory-name>
    ```

2.  **Instalar Dependencias PHP:**
    ```bash
    composer install
    ```

3.  **Instalar Dependencias de JavaScript :**
    ```bash
    npm install
    # or if you use yarn
    # yarn install
    ```

4.  **Crear Archivo ENV de configuración:**
    Copie el archivo de entorno de ejemplo y personalícelo según sea necesario.
    ```bash
    cp .env.example .env
    ```

5.  **Generar clave de aplicación:**
    ```bash
    php artisan key:generate
    ```

6.  **Configurar valores de las variables (.env file):**
    Abrir el archivo `.env` fy agregue los valores necesarios:

    *   **URL de Aplicación:**
        ```ini
        APP_URL=http://localhost:8000 
        ```
        (Ajústelo si utiliza un dominio o puerto local diferente)

    *   **Conexión de Base de Datos:**
        Establezca el tipo de base de datos y las credenciales. Ejemplo para MySQL:
        ```ini
        DB_CONNECTION=mysql
        DB_HOST=127.0.0.1
        DB_PORT=3306
        DB_DATABASE=your_database_name
        DB_USERNAME=your_database_user
        DB_PASSWORD=your_database_password
        ```
        Asegúrese de que la base de datos especificada (`your_database_name`) existe en su servidor..

    *   **Configuración Driver de EMAIL:**
        Configure su servicio de envío de correo electrónico (e.g., SMTP, Mailgun, Postmark). Ejemplo para SMTP:
        ```ini
        MAIL_MAILER=smtp
        MAIL_HOST=sandbox.smtp.mailtrap.io
        MAIL_PORT=2525
        MAIL_USERNAME=your_smtp_username
        MAIL_PASSWORD=your_smtp_password
        MAIL_ENCRYPTION=tls
        MAIL_FROM_ADDRESS="hello@example.com"
        MAIL_FROM_NAME="${APP_NAME}"
        ```

    *   **URL Servicio de Scraping:**
        Establezca la URL base para el sitio web que desea raspar.
        ```ini
        BASE_URL_SCRAPING_SERVICE="https://www.example-target-scrape-site.com/prices?page="
        ```
        *(Asegúrese de que esta URL es correcta y apunta a la lista de precios paginada)*

7.  **Ejecutar migraciones de bases de datos:**
    Esto creará las tablas necesarias en su base de datos.
    ```bash
    php artisan migrate
    ```

8.  **(Opcional) Ejecutar Database Seeders:**
    Si el proyecto incluye seeders de datos iniciales (Ej:., admin user, default settings):
    ```bash
    php artisan db:seed
    ```

9.  **Build Frontend Assets:**
    Compilar archivos JS y CSS.
    ```bash
    npm run build
    # or if you use yarn
    # yarn build
    ```
    Para el desarrollo, puede utilizar `npm run dev` (o `yarn dev`) para activar la recarga en caliente.

## Running the Application

### Development Server

Para ejecutar el servidor de desarrollo de Laravel (normalmente en `http://localhost:8000`):
```bash
php artisan serve
```

Si también utiliza Vite para el desarrollo frontend con sustitución de módulos en caliente:
```bash
npm run dev
# or
# yarn dev
```
Este comando (usualmente configurado en el script `dev` de `composer.json` o ejecutado en una terminal separada) típicamente iniciará el servidor de desarrollo Vite junto con el servidor PHP.

### Product Scraping

Para activar manualmente el proceso de raspado web de los precios de los productos:
```bash
php artisan scrape:products
```
Este comando obtendrá los últimos precios del `BASE_URL_SCRAPING_SERVICE` configurado y actualizará la base de datos.

**Nota:** Para el scraping regular y automatizado en un entorno de producción, este comando debe programarse como una tarea cron. Por ejemplo, para que se ejecute diariamente a medianoche:
```cron
0 0 * * * cd /path/to/your/project && php artisan scrape:products >> /dev/null 2>&1
```
Ajuste la ruta y el horario según sea necesario.

## Support and Development

Este proyecto fue desarrollado y es soportado por: **Integral Tech Consulting Spa.**

*   **CTO:** Mauricio Durán
*   **Contacto:** [mauricio@integraltech.cl](mailto:mauricio@integraltech.cl)
