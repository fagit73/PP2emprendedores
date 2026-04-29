Sistema espacios en Biblioteca- Contexto inicial
1.Contexto
Nos comunicamos con la biblioteca Sarmiento donde nos plantean las siguientes situaciones:
•	Actualmente realizan las reservas manualmente en un cuaderno, lo cual resulta en tiempos largos para comprobar disponibilidad, e incluso superposición de actividades por error u omisión de registro.
•	Trabaja una bibliotecaria diferente en cada turno, con formas diferentes de organización.
•	La recopilación de proyectos se realiza en archivos físicos y clasificación manual.
•	Al momento de capacitaciones y evaluaciones del rol de las bibliotecas en las instituciones educativas resulta complejo presentar los informes de usos y proyectos ya que no hay respaldos digitales que permitan optimizar informes.
2.Objetivo
Crear un sistema que no solo administre reservas, sino que recolecte y analice información sobre el uso del espacio, permitiendo optimizar su asignación y apoyar la toma de decisiones institucionales.
Digitalizar y estandarizar el proceso de reserva de espacios de la biblioteca, eliminando la dependencia del empleado de turno y asegurando que cada reserva quede asociada a su justificación.
 3.Definición del sistema
Sistema de gestión y análisis de uso de espacios en biblioteca
Recurso crítico:
•	Sala única multipropósito 
o	Lectura 
o	Audiovisual 
o	Extensión cultural (torneo de ajedrez, visita de autores, reuniones, etc.)
Problema central:
Asignación eficiente de un recurso compartido con múltiples tipos de uso
4.Alcance del sistema
Módulo de reservas: calendario visual con disponibilidad en tiempo real de la sala. Si alguna bibliotecaria falta a su turno el sistema permite reservar al usuario. Elimina los criterios distintos por turno.
Formulario único: al reservar se pide fecha/hora, responsable, tipo de actividad, cantidad de personas, equipamiento. Posee un campo para adjuntar Proyecto o reseña de la actividad en PDF o texto limitando la posibilidad de que se pierda documentación de los proyectos. 
Gestión: reservado, libre, reasignada, realizada. La bibliotecaria tiene facultad de reasignar reservas o cancelarlas. Estandariza procesos físicos.
Módulo de Informes: permite exportar a Excel distintos informes como Uso por espacio, tipo de actividad, listado de proyectos presentados. Resuelve las dificultades para armar informes de usos.
Usuarios: 3 roles usuario, directivo(tiene funciones de usuario y de observador) y Bibliotecaria/Admin. Ordena las funciones de cada rol.
5. Reglas de negocio 
Restricciones
•	No puede haber superposición de reservas en la misma sala. 
•	Toda reserva debe tener: 
o	docente 
o	curso 
o	reseña completa 
•	El tipo de uso es obligatorio. 

Reglas lógicas
•	Si TipoUso = Audiovisual → contenido_audiovisual debe estar informado. 
•	Si TipoUso = Extensión cultural → la descripción debe detallar la actividad. 
•	Las reservas deben tener duración válida (hora_fin > hora_inicio). 

6. Parte de apoyo a la toma de decisiones 
Informes posibles:
•	% de uso por tipo: 
o	Lectura vs Audiovisual vs Extensión 
•	Horarios más demandados 
•	Días con mayor ocupación 
•	Docentes que más utilizan la sala 
•	Cursos con mayor uso 

Ejemplos de decisiones que el sistema puede apoyar
•	Detectar saturación: El turno mañana está ocupado en un 30% mientras el turno tarde lo está en un 90%
•	Recomendar uso: Los martes a la tarde tienen baja ocupación. Sugerir este espacio para reuniones extraprogramáticas como convocatorias del centro de estudiantes.
•	Identificar patrones: Las actividades audiovisuales se concentran los viernes, proponer otros días como “jueves de película”.

7. Ejemplos de consultas 
•	Reservas por fecha 
•	Horarios libres 
•	Cantidad de reservas por tipo de uso 
•	Actividades de extensión cultural realizadas 
•	Uso por docente 

