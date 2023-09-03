Разработать менеджер футбольных турниров.
Легкий генератор встреч команд для разных турниров которые мы добавляем.

Описание

Список роутов:

/ - список всех доступных турниров с возможностью перейти на каждый из этих турниров

/teams/ - список команд с возможностью добавления/удаления, при добавлении сохраняем поле "название".

/tournaments/ - список доступных турниров с возможностью добавить/удалить, при добавлении сохраняем поле "название", по умолчанию ВСЕ команды автоматом попадают в турнир.*

/tournaments/{slug}/ - конкретный турнир, {slug} - генерируется из названия турнира. На этой странице будут отображены играющие пары команды для конкретного турнира с указанием даты события(встречи двух команды) дату можно считать от текущего дня. События происходят каждый день. В турнире каждая команда должна один раз встретиться со всеми остальными. Команда может играть одну игру в день. В один день возможно проведение всего 4х разных встреч. Другими словами мы генерируем сетку проведения мероприятий.**

Пример для 6 команды:

1й день:
- k1 : k2
- k3 : k4
- k5 : k6

2й день:
- k1	:	k3
- k2	:	k6
- k5	:	k4

3й день:
- k1	:	k4
- k2	:	k5
- k6	:	k3

4й день:
- k1 : k5
- k4	:	k6
- k3	:	k2

5й день:

- k1	:	k6
- k3	:	k5
- k4	:	k2


*(не обязательно, но будет большим плюсом)
  Реализовать возможность добавления определенных команды из списка (/teams/) которые будут участвовать в турнире, при его создании.

** (не обязательно, но будет большим плюсом)
  Реализовать сетку, которая будет закреплена и не меняться после обновления страницы, а привязываться к id турнира

Используемые технологии

PHP 7.4+, symfony, DB - по желанию, все завернуть в докер и написать readme как запускать.

Для визуализации данных по роутам вполне достаточно минимального интерфейса, например Twig Components