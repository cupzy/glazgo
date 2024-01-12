Запуск `make setup`

Путь для запросов `/api/posts`

В гет параметре `format` можно передать формат результирующего файла (xlsx, ods, csv) по умолчанию xlsx

Передать файл (xlsx, ods, csv) можно в `form-data` под ключом `file` 

Для тестов: `curl -o result.xlsx --header 'Accept: application/json' --form 'file=@"./example.xlsx"' --request POST http://localhost/api/posts?format=xlsx`
