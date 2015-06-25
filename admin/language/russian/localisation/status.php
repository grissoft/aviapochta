<?php
// Heading
$_['heading_title']    = 'Статусы упаковок';

// Text
$_['text_success']     = 'Настройки успешно изменены!';
$_['text_list']        = 'Список статусов';
$_['text_add']          = 'Добавить';
$_['text_edit']         = 'Редактирование';

$_['text_sms_help']    = '<div style=\'width: 230px; text-align: left;\'>Пустое значение - не отправлять<br>{pack_id} - ИД упаковки<br>{pack_product_count} - Товаров в упаковке<br>{pack_total} - Стоимость упаковки<br>{customer_id} - ИД пользователя<br>{customer_name} - ФИО пользователя<br>{customer_firstname} - Имя пользователя<br>{customer_lastname} - Фамилия пользователя</div>';
$_['text_customer_notify_help']    = 'Отображать или нет данный статус пользователю';
$_['text_next_status_text_help']    = '<div style=\'width: 230px; text-align: left;\'>Комментарий к текущему статусу<br>Возможны следующие подстановки:
                        <br>{today} - Сегодня
			<br>{next_monday} - Ближайший понедельник
			<br>{next_tueday} - Ближайший вторник
			<br>{next_wednesday} - Ближайшая среда
			<br>{next_thirthday} - Ближайший четверг
			<br>{next_friday} - Ближайшая пятница
			<br>{next_saturday} - Ближайшая суббота
                        <br>{next_sunday} - Ближайшее воскресенье
                        <br>{today+2} - Сегодня + 2 дня
                        <br>{next_thirthday+2} - (Сегодня + 2 дня) Ближайший четверг (т.е. если сегодня среда, тогда будет четверг следующей недели
                        <br>Количество добавляемых дней от 1 до 60</div>';

// Column
$_['column_name']      = 'Статус упаковки';
$_['column_sms']       = 'Текст СМС';
$_['column_action']    = 'Действие';

// Entry
$_['entry_name']       = 'Название статуса упаковки';
$_['entry_sms']        = 'Текст СМС';
$_['entry_customer_notify']        = 'Отображать покупателю';
$_['entry_next_status_text']        = 'Комментарий статуса';

// Error
$_['error_permission'] = 'У Вас нет прав для изменения статусов упаковок!';
$_['error_name']       = 'Название должно быть от 3 до 32 символов!';
$_['error_delete']     = 'Статус упаковки не может быть удален, так как назначена %s упаковкам!';

