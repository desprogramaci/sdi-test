# 🛒 SDI Marketplace API - Backend Challenge

Esta es una solución robusta para una API REST desarrollada con **Laravel 11**, diseñada para la gestión integral de un marketplace. El proyecto implementa seguridad JWT, lógica de negocio para carritos, integración con IA y documentación automatizada.

---

## 🚀 Características Principales

*   **Autenticación JWT:** Gestión de sesiones segura y stateless mediante `tymon/jwt-auth`.
*   **Control de Acceso (RBAC):** Middlewares personalizados para distinguir entre roles de `Administrador` y `Usuario`.
*   **Integración con IA (OpenAI):** Servicio para autocompletar descripciones de productos de forma comercial a partir de su nombre.
*   **Sistema de Carrito & Checkout:** 
    *   Validación de stock en tiempo real.
    *   Cálculo de subtotales, IVA (21%) y aplicación de descuentos.
    *   Persistencia de pedidos y limpieza de carrito tras compra.
*   **Descuentos Dinámicos:** Soporte para cupones fijos y porcentuales con validación de fechas y montos mínimos.
*   **Exportación CSV:** Endpoint para exportar el catálogo de productos filtrado.
*   **Documentación Interactiva:** Implementación de **Swagger (OpenAPI 3.0)** con Atributos de PHP 8.
*   **Entorno Docker:** Configuración completa con Docker Compose para una implementación inmediata.

---

## 🛠️ Requisitos previos

*   Docker y Docker Compose instalados.
*   (Opcional) API Key de OpenAI en el archivo `.env` para la generación de descripciones.

---

## 📦 Instalación y Puesta en Marcha

Sigue estos pasos para levantar el entorno:

1. **Clonar el repositorio:**
   git clone <tu-repositorio-url>
   cd sdi-test

2. **Configurar el entorno:**
    cp .env.example .env

3. **Levantar contenedores con Docker:**
    docker-compose up -d

4. **Instalar dependencias y preparar base de datos:**
    docker exec -it sdi-app composer install
    docker exec -it sdi-app php artisan key:generate
    docker exec -it sdi-app php artisan jwt:secret
    docker exec -it sdi-app php artisan migrate --seed

5. **Generar Documentación API (Swagger):**
    docker exec -it sdi-app php artisan l5-swagger:generate

## 🔑 Credenciales de Prueba (Seeds)
    El sistema incluye usuarios pre-cargados para facilitar la evaluación:
    Rol              Email              Password
    Administrador    admin@sdi.com      password
    Usuario Normal   user@sdi.com       password

## 📖 Documentación de la API
    Una vez levantado el proyecto, accede a la interfaz interactiva para probar los endpoints:

👉 Swagger UI: http://localhost:8000/api/documentation

## ¿Cómo probar?
    Realiza una petición POST a /api/login con las credenciales de arriba.
    Copia el access_token recibido.
    Pulsa el botón "Authorize" (icono de candado) en Swagger.
    Escribe Bearer <tu_token> y pulsa Authorize
    

## 🏗️ Decisiones Técnicas y Arquitectura
    -Service Layer Pattern:
    La lógica compleja (IA, cálculos de carrito, exportaciones) se ha extraído a app/Services para mantener los controladores limpios y facilitar la mantenibilidad.

    -Form Requests:
    Validaciones centralizadas para asegurar que los datos (EAN-13, stocks, precios) cumplan con las reglas de negocio antes de llegar al controlador.

    -Atributos de PHP 8: 
    Uso de sintaxis moderna para la documentación de Swagger, evitando comentarios extensos y mejorando la legibilidad.

    -Manejo de Sesiones en Carrito: 
    Enfoque híbrido persistente para asegurar que el usuario no pierda su selección durante la navegación.

## 🧪 Testing
    Suite de pruebas para validar la seguridad y la lógica del carrito:
    -docker exec -it sdi-app php artisan test

## ✉️ Entrega
    -Desarrollado como parte de la prueba técnica para el equipo de SDi.
