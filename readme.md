# TPE Web 2 API: Gestion de gastos

Este proyecto es la segunda parte del trabajo especial de la materia Web2 de la carrera TUDAI cursada 2022.

## Pre-requisitos

El proyecto esta hecho en un ambiente dockerizado, es por esto necesario contar con [docker compose](https://docs.docker.com/compose/install/) en la computadora.

## Instalacion

Primero hay que copiar el archivo .env-example a uno .env
Si se desea en este archivo se puede modificar los puertos utilizados.

```bash
cp .env-example .env
```
Luego hay que levantar los contedores

```bash
docker-compose up --build -d
```

Y por ultimo para cargar la base de datos con el esquema y con los datos de prueba

```bash
docker exec -i tpe-web2-db mysql < ./db/create-schema.sql
docker exec -i tpe-web2-db mysql < ./db/hydrate-tables.sql
```

## Endpoints
Ver la documentacion de postman [aca](https://documenter.getpostman.com/view/15667153/2s8YmNRi8C).

Los endpoints van estar todos con el prefijo -> http://localhost/api/v1

### POST /authorization

Endopoint para obtner el Bearer token. Se requiere basic auth.El user seria el mail. Algunos de ejemplo son: user1@mail.com, user2@mail.com o user3@mail.com, las contraseñas de todos estos es admin.

Una respuesta de ejemplo seria:
```
{
    "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6MSwibmFtZSI6Ik5pY28iLCJleHAiOjE2NjgyODk2OTJ9.9WbWW5FB-IxiyIp_ZI_pw2EBnyWXDaYkgaMb4lpTr1Y"
}
```

### GET /gastos

Endpoint para obtener todos los gastos, el cual puede recibir los siguientes queryParams.

|          Name | Required |  Type   | Description                                                                                                                                                           |
| -------------:|:--------:|:-------:| --------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `sortBy` | opcional | string | El campo por el cual va a ser ordenado los datos, pueden ser id, date, productName, cost o categoryId  |
| `order` | opcional | string | El orden por el cual van a ser ordenado los datos. Este parametro puede faltar (si falta se asume orden ASC) |
| `page` | opcional | integer | Numero de pagina mayor a cero, la primer pagina es la numero uno. (si se manda este parametro es obligatorio el limit tambien) |
| `limit` | opcional | integer | Numero de elementos a traer, mayor a 0. (si se manda este parametro es obligatorio el page tambien) |
| `filter` | opcional | string  | Para filtrar los gastos cuyo nombre contengan el string filter. |

Ejemplo de respuesta al GET /gastos?page=1&limit=3:
```
[
    {
        "id": 1,
        "date": {
            "date": "2022-10-11 00:00:00.000000",
            "timezone_type": 3,
            "timezone": "UTC"
        },
        "productName": "Yerba Mate Mañanita 1kg",
        "cost": 1000,
        "categoryId": 1
    },
    {
        "id": 2,
        "date": {
            "date": "2022-10-02 00:00:00.000000",
            "timezone_type": 3,
            "timezone": "UTC"
        },
        "productName": "Cerveza Guinnes Extra Stout 473cc X6",
        "cost": 1581,
        "categoryId": 1
    },
    {
        "id": 3,
        "date": {
            "date": "2022-10-03 00:00:00.000000",
            "timezone_type": 3,
            "timezone": "UTC"
        },
        "productName": "Jabon en barra Dove Original 90g",
        "cost": 183.25,
        "categoryId": 1
    }
]
```


### GET /gastos/:id

Endpoint para obtener un gasto espicifico, `:id` es un pathParam.

Ejemplo de respuesta al GET /gastos/1:

```
{
    "id": 1,
    "date": {
        "date": "2022-10-11 00:00:00.000000",
        "timezone_type": 3,
        "timezone": "UTC"
    },
    "productName": "Yerba Mate Mañanita 1kg",
    "cost": 1000,
    "categoryId": 1
}
```


### POST /gastos

Endpoint para la creacion de un nuevo gasto. Requiere autenticacion con Bearer token. Se debe pasar en el body el siguiente JSON:
```
{
    "date": "13/03/2022"
    "productName": "Yerba Mate Mañanita 1kg",
    "cost": 1000,
    "categoryId": 1
}
```
La response seria el mismo objeto con su id
```
{
	"id": 1,
    "date": {
        "date": "2022-03-13 00:00:00.000000",
        "timezone_type": 3,
        "timezone": "UTC"
    }
    "productName": "Yerba Mate Mañanita 1kg",
    "cost": 1000,
    "categoryId": 1
}
```

### PUT /gastos/:id

Endpoint para la modificacion de un gasto existente, `:id` es un pathParam. En el body se debe pasar todos los campos exceptuando el id (ya que va especificado en el pathParam). Requiere autenticacion con Bearer token.

Por ejemplo:
PUT /gastos/1
```
{
    "date": "13/03/2022"
    "productName": "Yerba Mate Mañanita 1kg",
    "cost": 3000,
    "categoryId": 1
}
```
obteniendo de respuesta
```
{
    "id": 1,
    "date": {
        "date": "2022-03-13 00:00:00.000000",
        "timezone_type": 3,
        "timezone": "UTC"
    },
    "productName": "Yerba Mate Mañanita 1kg",
    "cost": 3000,
    "categoryId": 1
}
```


### DELETE /gastos/:id

Endoint para la eliminacion de un gasto existente, `:id` es un pathParam. Requiere autenticacion con Bearer token

Ejemplo de respuesta de una eliminacion exitosa:
```
"El gasto con el id=15 se ha borrado con exito"
```
