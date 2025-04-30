# Sistema de Prueba Piloto

## Descripción
Este es un sistema desarrollado con Laravel 12 que implementa una arquitectura moderna y escalable. El sistema utiliza una combinación de tecnologías backend para proporcionar una solución robusta y eficiente.

## Requisitos del Sistema
- PHP 8.2 o superior
- Composer
- Docker (opcional)
- MySQL (elegi esta opcion por que se me hace mas facil de instalar y gestionar desde mi gestor preferencial)

## Instalación

### Método 1: Instalación Local
1. Clonar el repositorio:
```bash
git clone https://github.com/28Kevin/prueba-piloto.git
cd prueba-piloto
```

2. Instalar dependencias PHP:
```bash
composer install
```

3. Configurar el entorno:
```bash
cp .env.example .env
php artisan key:generate
```

3. configurar el passport:
 php artisan pssport:keys && php artisan passport:client --personal

5. Configurar la base de datos en el archivo `.env`

6. Ejecutar migraciones:
```bash
php artisan migrate
```

7. Iniciar el servidor:
```bash
php artisan serve
```

### Método 2: Usando Docker
1. Construir la imagen:
```bash
docker build -t prueba-piloto .
```

2. Ejecutar el contenedor:
```bash
docker run -p 9000:9000 prueba-piloto
```

## Arquitectura del Sistema

### Backend
- **Framework**: Laravel 12
- **Autenticación**: Laravel Passport para API
- **Base de datos**: MySQL
- **Documentación API**: Swagger/OpenAPI


## Decisiones Técnicas

### 1. Laravel 12
- Última versión estable con soporte a largo plazo
- Mejoras significativas en rendimiento
- Compatibilidad con PHP 8.2
- Repositorios para capa de seguiridad y comunicacion con modelo
- Request para validacion de formularios en back
- Resource para formatear las respuestas 
- Archivo de apis versionadas para cada microservicio

### 2. Laravel Passport
- Implementación OAuth2 completa
- Seguridad robusta para APIs
- Fácil integración con aplicaciones de terceros

### 3. Docker
- Entorno de desarrollo consistente
- Fácil despliegue
- Aislamiento de dependencias

### 4. Swagger/OpenAPI
- Documentación automática de APIs
- Facilita la integración con otros sistemas
- Mejora la experiencia del desarrollador

## Diagrama de Interacción

```
┌─────────────┐     ┌─────────────┐     ┌─────────────┐
│  Cliente    │     │  API        │     │  Base de    │
│  Frontend   │◄───►│  Laravel    │◄───►│  Datos      │
└─────────────┘     └─────────────┘     └─────────────┘
        ▲                  ▲
        │                  │
        ▼                  ▼
┌─────────────┐     ┌─────────────┐
│  Autenticación     │  Servicios  │
│  Passport   │     │  Externos   │
└─────────────┘     └─────────────┘
```

## Desarrollo

Para iniciar el entorno de desarrollo completo:
```bash
composer dev
```

Este comando iniciará:
- Servidor de desarrollo Laravel
- Cola de trabajos
- Registro de logs
- Servidor de desarrollo Vite

## Pruebas
Para ejecutar las pruebas:
```bash
composer test
```
