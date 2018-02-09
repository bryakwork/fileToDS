# Установка и настройка
Для использования модуля выполните следующие шаги:
1. Скачайте модуль с github или подключите его как зависимость `Composer`'а.
2. Стяните зависимости модуля c помощью `Composer`
3. Установите сам модуль, сгенерировав для него файлы конфигурации. Для
этого выполните команду `composer lib install` и выберите `File2DSInstaller`
в появившемся списке

> Если Вы хотите использовать БД для хранения данных, запустите
`DbTableInstaller` таким же образом. При установке Вы сможете ввести
данные для доступа к БД.

4. Добавьте соответствующий путь в приложение. Для этого внесите в файл
`config/routes.php` следующие строки:

```
if ($container->has("file2DS")) {
    $app->route('/file2ds[/{resourceName}]', "file2DS", ['POST', 'OPTIONS'], 'fileToDS');
}
```

На этом этапе всё готово к работе.

# Использование
Для того, чтобы сохранить содержимое файла, отправьте POST-запрос,
содержащий файл и разделитель, на адрес типа
```
http://my-resource.xyz/file2ds/my-datastore-name
```
где `my-resource.xyz` - URL, по которому доступно ваше приложение, а
`my-datastore-name` - имя DataStore, в который вы хотите записать данные
Пример отправки запроса средствами JS:
```javascript
function handleUpload (file, delimeter) {
                var form = new FormData();
                var xhr = new XMLHttpRequest();
                form.append('Content-Type', file.type || 'application/octet-stream');
                // файл в запросе будет иметь ключ 'file'
                form.append('file', file);
                //а разделитель - 'delimeter'
                form.append('delimeter', delimeter);
                xhr.open('POST', 'http://my-resource.xyz/file2ds/my-datastore-name', true);
                xhr.send(form);
```