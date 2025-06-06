**Формат запроса:**
```
POST /api/v1/orders/price-calc
```
```
{
    "datetime" => "YYYY-MM-DD HH:mm",
    "customer" => ["birthday" => "YYYY-MM-DD", "gender" => "{M|F}"],
    "basket" => [
        ["price" => 5, "quantity" => 2],
        ["price" => 15, "quantity" => 1], ...
    ]
}
```

**API для расчета итоговой стоимости заказа.**

Заказ содержит следующие поля:

1. Покупатель (содержит дату рождения и пол)
2. Дата и время доставки
3. Товары (каждый товар содержит базовую стоимость и количество)

API должно возвращать итоговую стоимость заказа после применения скидок:

1. Скидка для пенсионеров 5 % (мужчины старше 63 лет включительно, женщины - старше 58 включительно)
2. Скидка на ранний заказ - если заказ сделан за неделю и более, скидка составит 4 %
3. Скидка на количество товаров - если их больше 10 (не 10 разных видов товаров, а 10 единиц, например если выбрано 10 единиц пиццы, то скидка уже дается), дается скидка 3 %.

Итоговая стоимость составляется из суммарной стоимости всех товаров за 
вычетом скидок. Если можно дать несколько скидок,
то каждая последующая высчитывается на основе суммы с учетом всех 
предыдующих скидок.

То есть если у нас есть скидка А, скидка B и базовая стоимость X, то 
итоговая стоимость Y составит (A и B здесь для простоты выражены не в 
процентах а в долях):

Y = X - (X * A) - (X - (X * A)) * B
или иначе
Y = X * (1 - A) * (1 - B)

Если данные невалидны (отсутствуют нужные поля или заполнены неверными 
значениями), то API должно возвращать ошибку.

**Примеры для теста:**

**(1)**
*Request:*
```
{
    "datetime": "2025-04-12 12:12",
    "customer": {"birthday":"1992-04-09", "gender":"M"},
    "basket": [
        {"price":10, "quantity":9},
        {"price":5, "quantity":2}
    ]
}
```
*Response:*
```
{
    "price": 100
}
```

**(2)**
*Request:*
```
{
    "datetime": "2025-12-12 12:12",
    "customer": {"birthday":"1962-04-08", "gender":"M"},
    "basket": [
        {"price":10, "quantity":10},
        {"price":5, "quantity":20}
    ]
}
```
*Response:*
```
{
    "price": 176.93
}
```

---

**PHPUnit tests**

```
PS > ./vendor/bin/phpunit tests
PHPUnit 9.6.22 by Sebastian Bergmann and contributors.

...........................                                       27 / 27 (100%)

Time: 00:00.543, Memory: 4.00 MB

OK (27 tests, 49 assertions)
```
