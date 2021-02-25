# Notes sur lse Fixtures

## Retourner une valeur aléatoire depuis un tableau

Avec `count()`, on récupère un index entre 0 et l'index max du tableau :

- `$randomGenre = $genresList[mt_rand(0, count($genresList) - 1)];`
- `$randomGenre = $genresList[mt_rand(0, self::NB_GENRES - 1)];` (depuis mon code)

Avec `array_rand()` qui retourne un (ou plusieurs) index au hasard : 

- `$randomGenre = $genresList[array_rand($genresList)];`
- https://www.php.net/manual/en/function.array-rand

Si on veut récupérer un tableau de 3 genres au hasard, UNIQUES :

- `$randomGenresArray = $genresList[array_rand($genresList, 3)];`

Avec `shuffle()`, avec l'unicité :

- https://www.php.net/manual/en/function.shuffle.php
- `shuffle($genresList);`
- `$genresList[0]` => un au hasard
- `$genresList[1]` => un second au hasard

