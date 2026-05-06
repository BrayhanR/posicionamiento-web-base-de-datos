Sistema de Gestión de Transporte y Estrategia de Posicionamiento Web (SEO)

Este proyecto evoluciona de un sistema de gestión robusto hacia una plataforma optimizada bajo estándares de SEO Técnico, Semántico y de Contenido. El sistema permite la gestión de usuarios y rutas para una Terminal de Transporte, garantizando no solo la funcionalidad operativa mediante PHP y MySQL, sino también una visibilidad orgánica superior en motores de búsqueda.

⚠️ Nota de Implementación (Entorno Estático)

El repositorio actual está configurado para su despliegue en GitHub Pages. Debido a que este es un entorno de hosting estático, las funciones de backend (PHP) y la conexión a base de datos están disponibles como código fuente para auditoría académica, pero no se ejecutan en el enlace en vivo. Para funcionalidad completa, se requiere un entorno LAMP/WAMP local.

# Características y Optimización SEO

1. Optimización On-Page y Semántica

Estructura Jerárquica: Ajuste de títulos, meta-descripciones y encabezados h1 para mejorar la indexación de palabras clave relacionadas con transporte.<br>
Accesibilidad y Rastreo: Inclusión de atributos alt en imágenes para mejorar la experiencia de usuario y facilitar el rastreo de los bots de búsqueda.<br>
Arquitectura de Información: Uso de etiquetas semánticas de HTML5 (article, time, blockquote) para organizar noticias, FAQs y promociones.<br>

2. SEO Técnico y Rendimiento
   
WPO (Web Performance Optimization): Optimización de imágenes mediante formato .webp y compresión con TinyPNG y EZGIF.

Carga Eficiente: Implementación de loading="lazy" para imágenes y rel="preconnect" para recursos externos, reduciendo tiempos de carga.

Diseño Responsive: Adaptabilidad total mediante reglas CSS para mejorar el posicionamiento en dispositivos mó  ezgifviles.

3. Autoridad y Conversión

SEO Local y Datos Estructurados: Implementación de JSON-LD para mejorar la visibilidad en búsquedas geográficas y directorios locales.

Social SEO: Integración de etiquetas Open Graph para generar tarjetas de previsualización profesionales en redes sociales, aumentando el CTR.

Estrategia de Contenido: Secciones dinámicas de ofertas y descuentos para potenciar la intención de compra y reducir la tasa de rebote.

# Tecnologías Utilizadas

SEO & Analytics: Google Search Console, Google Analytics, JSON-LD, Open Graph.

Frontend: HTML5 Semántico, CSS3 (BEM/Modular), JavaScript (Vanilla).

Backend: PHP 8.x (Código fuente disponible).

Base de Datos: MySQL / MariaDB.

# Estrategia de Medición (KPIs)

Para asegurar el éxito del proyecto, se ha establecido un sistema de seguimiento mediante un Dashboard de Control que monitorea:

Visitas y Sesiones: Flujo de usuarios hacia la terminal.
Posicionamiento Orgánico: Ranking de palabras clave estratégicas.
Tasa de Rebote: Permanencia de los usuarios en secciones de noticias y servicios.
Conversiones: Clics en contactos interactivos y agendamiento.

# Acceso al Sistema y Backend

Aunque el enfoque principal es SEO, el sistema conserva su estructura de roles:

Administrador: Gestión total de rutas, transportadoras y reportes.
Pasajero / Cliente: Consulta de horarios, agendamiento de viajes y perfil personal.
Para ejecución local, importar los archivos .sql y configurar main/PHP/conexion.php con las credenciales de su servidor XAMPP/WAMP.

# Estructura del Proyecto

admin/: Módulos de administración para la gestión de reservas, rutas y logística de transporte.
assets/: Recursos multimedia y archivos gráficos del sitio.
auth/: Procesadores de autenticación y formularios de acceso optimizados.
cliente/: Panel de usuario con funcionalidades exclusivas para la gestión de servicios del pasajero.
database/: Repositorio de scripts .sql (usuario, reserva, servicio, etc.) para la construcción de la base de datos relacional.
documentacion/: Archivos de soporte y manuales del proyecto académico.
main/: Núcleo técnico del sistema que centraliza los estilos CSS modulares, scripts de JavaScript y la lógica de conexión PHP.
public/: Directorio raíz con páginas HTML altamente optimizadas para SEO e indexación.

# Desarrollado por (Grupo: 202047916_21):

Emmanuel Santiago Fernández López
Yuliana Moreno Pérez
Albert Daniel Ramírez García
Brayhan Sty Rodríguez Rueda
María Alejandra Urrea Peña

Curso: Desarrollo de Aplicaciones para la web 
Universidad Nacional Abierta y a Distancia UNAD
