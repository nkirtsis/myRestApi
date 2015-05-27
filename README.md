# The API consists of the following methods:


| Method | URL                                    | Action                                 |
| ------ | -------------------------------------- | -------------------------------------- |
| GET    | /api/v1/creatures/                     | Retrieves all creatures                |
| GET    | /api/v1/creatures/?creature_type=ninja | Retrieves all creatures based on type  |
| GET    | /api/v1/creatures/2                    | Retrieves creature based on primary key|
| POST   | /api/v1/creatures/                     | Adds a new creature                    |
| DELETE | /api/v1/creatures/2                    | Deletes creature based on primary key  |
| PUT    | /api/v1/creatures/2                    | Updates creature based on primary key  |
| PUT    | /api/v1/creatures/2/hitWithAxe         | Performs action based on primary key   |
| PUT    | /api/v1/creatures/2/performMagicSpell  | Performs action based on primary key   |
| PUT    | /api/v1/creatures/2/drinkHealingPotion | Performs action based on primary key   |


## EXAMPLES OF USE

1. Fill the database with some creatures:

    ```
    curl -X POST -d {"name" : "Blade" , "health" : 10000, "creature_type" : "vampire"} http://localhost/api/v1/creatures/
    curl -X POST -d {"name" : "random zombie 1" , "health" : 2500, "creature_type" : "zombie"} http://localhost/api/v1/creatures/
    curl -X POST -d {"name" : "random zombie 2" , "health" : 2500, "creature_type" : "zombie"} http://localhost/api/v1/creatures/
    curl -X POST -d {"name" : "Kung Fu Panda" , "health" : 5000, "creature_type" : "ninja"} http://localhost/api/v1/creatures/
    curl -X POST -d {"name" : "Barbarossa" , "health" : 7500, "creature_type" : "pirate"} http://localhost/api/v1/creatures/
    ```

2. Retrieve all creatures:

    ```
    curl -X GET http://localhost/api/v1/creatures/
    ```

3. Retrieve all ninja:

    ```
    curl -X GET http://localhost/api/v1/creatures/?creature_type=ninja
    ```

4. Retrieve specific creature ("Kung Fu Panda"):

    ```
    curl -X GET http://localhost/api/v1/creatures/4
    ```

5. Update creature (send "Kung Fu Panda" to the navy..):

    ```
    curl -X PUT -d {"creature_type" : "pirate"} http://localhost/api/v1/creatures/3
    ```

6. Delete creature (never liked zombies, so kill "random zombie 2"):

    ```
    curl -X DELETE http://localhost/api/v1/creatures/3
    ```

7. Perform action (hit "random zombie 1"):

    ```
    curl -X PUT http://localhost/api/v1/creatures/3/hitWithAxe
    ```
8. Perform action (hit "random zombie 1" again!):

    ```
    curl -X PUT http://localhost/api/v1/creatures/3/performMagicSpell
    ```
