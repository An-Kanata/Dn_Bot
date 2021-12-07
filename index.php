<?php

require "db.php";
$mysqltime = date('Y-m-d H:i:s', time());

//Строка для подтверждения адреса сервера из настроек Callback API
$confirmation_token = '280d64cb';

//b96aad2886f79067a7511df42fa30d4cfd6b8f4a1746c45a602705b6c25e1fc1c673fe4711380af187758
//Ключ доступа сообщества ДЛЯ ТЕСТА
require "token.php";

//Получаем и декодируем уведомление
$data1 = file_get_contents('php://input');
$data = json_decode($data1);

//$log = date('Y-m-d H:i:s') . $data1;
//file_put_contents(__DIR__ . '/log.txt', $log . PHP_EOL, FILE_APPEND);
//if ($data->object->message->attachments){
//    $log = 'Тут картинка';
//    if ($data->object->message->text) {
//        $log = $log.' и есть текст';
//    }else{
//        $log = $log.' и нет текста';
//    }
//    file_put_contents(__DIR__ . '/log.txt', $log . PHP_EOL, FILE_APPEND);
//} else{
//    $log = 'Тут нет картинки';
//    if ($data->object->message->text) {
//        $log = $log.' и есть текст';
//    }else{
//        $log = $log.' и нет текста';
//    }
//    file_put_contents(__DIR__ . '/log.txt', $log . PHP_EOL, FILE_APPEND);
//}

$to_start = array(
    array(
        'action' => array(
            'type' => 'text',
            'payload' => array(
                'buttons' => 0
            ),
            'label' => 'У меня другой вопрос'
        ),
        'color' => 'negative'
    )
);
//Проверяем, что находится в поле "type"
switch ($data->type) {
//Если это уведомление для подтверждения адреса...
    case 'confirmation':
//...отправляем строку для подтверждения
        echo $confirmation_token;
        break;
    case 'message_new':
        $flag = 0;
        echo('ok');
        $request_params = '';
        switch ($data->object->message->text) {
            case 'У меня другой вопрос':
            case 'Начать':
                $user_id = $data->object->message->from_id;
                //С помощью messages.send отправляем ответное сообщение
                $request_params = array(
                    'peer_id' => $user_id,
                    'message' => "Привет, я чат-бот службы поддержки Дневник.ру. Чем я могу быть полезен?",
                    'keyboard' => json_encode(array(
                            'one_time' => true,
                            'inline' => false,
                            'buttons' => array(
                                array(
                                    array(
                                        'action' => array(
                                            'type' => 'text',
                                            'label' => 'Я забыл данные для входа'
                                        ),
                                        'color' => 'primary'
                                    ),
                                    array(
                                        'action' => array(
                                            'type' => 'text',
                                            'payload' => array(
                                                'buttons' => 0
                                            ),
                                            'label' => 'Ошибка в личных данных'
                                        ),
                                        'color' => 'primary'
                                    )
                                ),
                                array(
                                    array(
                                        'action' => array(
                                            'type' => 'text',
                                            'payload' => array(
                                                'buttons' => 0
                                            ),
                                            'label' => 'Мне нужны логин и пароль для регистрации'
                                        ),
                                        'color' => 'primary'
                                    )
                                ),
                                array(
                                    array(
                                        'action' => array(
                                            'type' => 'text',
                                            'label' => 'Зарегистрировать школу'
                                        ),
                                        'color' => 'primary'
                                    ),
                                    array(
                                        'action' => array(
                                            'type' => 'text',
                                            'payload' => array(
                                                'buttons' => 0
                                            ),
                                            'label' => 'В дневнике нет оценок, ДЗ'
                                        ),
                                        'color' => 'primary'
                                    )
                                ),
                                array(
                                    array(
                                        'action' => array(
                                            'type' => 'text',
                                            'payload' => array(
                                                'buttons' => 0
                                            ),
                                            'label' => 'В профиле ребёнка указан старый класс'
                                        ),
                                        'color' => 'primary'
                                    )
                                ),
                                array(
                                    array(
                                        'action' => array(
                                            'type' => 'text',
                                            'payload' => array(
                                                'buttons' => 0
                                            ),
                                            'label' => 'Как прикрепить ребёнка'
                                        ),
                                        'color' => 'primary'
                                    ),
                                    array(
                                        'action' => array(
                                            'type' => 'text',
                                            'payload' => array(
                                                'buttons' => 0
                                            ),
                                            'label' => 'Как изменить школу'
                                        ),
                                        'color' => 'primary'
                                    )
                                ),
                                array(
                                    array(
                                        'action' => array(
                                            'type' => 'text',
                                            'payload' => array(
                                                'buttons' => 0
                                            ),
                                            'label' => 'Мне нужны инструкции или руководства'
                                        ),
                                        'color' => 'positive'
                                    )
                                )
                            )
                        )
                    ),
                    'access_token' => $token,
                    'v' => '5.131',
                    'random_id' => 0
                );
                break;
            case 'Я забыл данные для входа':
                $user_id = $data->object->message->from_id;
                $command = "insert into commonLog (type, date_) values ('2', '{$mysqltime}')";
                $equips = mysqli_query($connection, $command);
                //С помощью messages.send отправляем ответное сообщение
                $request_params = array(
                    'peer_id' => $user_id,
                    'message' => "Выберите, пожалуйста, Вашу роль в образовательной организации:",
                    'keyboard' => json_encode(array(
                        'one_time' => true,
                        'inline' => false,
                        'buttons' => array(
                            array(
                                array(
                                    'action' => array(
                                        'type' => 'text',
                                        'payload' => array(
                                            'buttons' => 0,
                                            'flag' => 0
                                        ),
                                        'label' => 'Я ученик'
                                    ),
                                    'color' => 'primary'
                                )
                            ),
                            array(
                                array(
                                    'action' => array(
                                        'type' => 'text',
                                        'payload' => array(
                                            'buttons' => 0,
                                            'flag' => 0
                                        ),
                                        'label' => 'Я родитель'
                                    ),
                                    'color' => 'primary'
                                )
                            ),
                            array(
                                array(
                                    'action' => array(
                                        'type' => 'text',
                                        'payload' => array(
                                            'buttons' => 0,
                                            'flag' => 0
                                        ),
                                        'label' => 'Я сотрудник ОО'
                                    ),
                                    'color' => 'primary'
                                )
                            )
                        )
                    )),
                    'access_token' => $token,
                    'v' => '5.131',
                    'random_id' => 0
                );
                break;
            case 'Мне нужны инструкции или руководства':
                $user_id = $data->object->message->from_id;
                $command = "insert into commonLog (type, date_) values ('1', '{$mysqltime}')";
                $equips = mysqli_query($connection, $command);
                //С помощью messages.send отправляем ответное сообщение
                $request_params = array(
                    'peer_id' => $user_id,
                    'message' => "Все полезные статьи, инструкции и НПА собраны на портале поддержки.",
                    'keyboard' => json_encode(array(
                        'one_time' => true,
                        'inline' => false,
                        'buttons' => array(
                            array(
                                array(
                                    'action' => array(
                                        'type' => 'open_link',
                                        'payload' => array(
                                            'buttons' => 0
                                        ),
                                        'label' => 'Открыть портал поддержки',
                                        'link' => 'https://help.dnevnik.ru/hc/ru'
                                    )
                                )
                            ),
                            $to_start
                        ),
                    )),
                    'access_token' => $token,
                    'v' => '5.131',
                    'random_id' => 0
                );
                break;
            case 'Ошибка в личных данных':
                $user_id = $data->object->message->from_id;
                $command = "insert into commonLog (type, date_) values ('3', '{$mysqltime}')";
                $equips = mysqli_query($connection, $command);
                //С помощью messages.send отправляем ответное сообщение
                $request_params = array(
                    'peer_id' => $user_id,
                    'message' => "Обратитесь к администратору или к классному руководителю в школе, так как согласно ФЗ №152 «О персональных данных», вносить изменения в списки и профили пользователей могут только сотрудники школы.",
                    'keyboard' => json_encode(array(
                        'one_time' => true,
                        'inline' => false,
                        'buttons' => array($to_start),
                    )),
                    'access_token' => $token,
                    'v' => '5.131',
                    'random_id' => 0
                );
                break;
            case 'Мне нужны логин и пароль для регистрации':
                $user_id = $data->object->message->from_id;
                $command = "insert into commonLog (type, date_) values ('4', '{$mysqltime}')";
                $equips = mysqli_query($connection, $command);
                //С помощью messages.send отправляем ответное сообщение
                $request_params = array(
                    'peer_id' => $user_id,
                    'message' => "Подробная информация по регистрации пользователей всех ролей (ученик, родитель, сотрудник) описана в инструкции на портале поддержки.",
                    'keyboard' => json_encode(array(
                        'one_time' => true,
                        'inline' => false,
                        'buttons' => array(
                            array(
                                array(
                                    'action' => array(
                                        'type' => 'open_link',
                                        'payload' => array(
                                            'buttons' => 0
                                        ),
                                        'label' => 'Открыть инструкцию',
                                        'link' => 'https://help.dnevnik.ru/hc/ru/articles/203476468'
                                    )
                                )
                            ),
                            $to_start
                        ),
                    )),
                    'access_token' => $token,
                    'v' => '5.131',
                    'random_id' => 0
                );
                break;
            case 'Зарегистрировать школу':
                $user_id = $data->object->message->from_id;
                $command = "insert into commonLog (type, date_) values ('5', '{$mysqltime}')";
                $equips = mysqli_query($connection, $command);
                //С помощью messages.send отправляем ответное сообщение
                $request_params = array(
                    'peer_id' => $user_id,
                    'message' => "Обращаем внимание, что при обработке заявки на регистрацию школы мы проводим проверку и связываемся с сотрудниками по официальному стационарному телефону организации. В заявке на регистрацию должны быть указаны корректные данные, это поможет быстрее обработать заявку.",
                    'keyboard' => json_encode(array(
                        'one_time' => true,
                        'inline' => false,
                        'buttons' => array(
                            array(
                                array(
                                    'action' => array(
                                        'type' => 'open_link',
                                        'payload' => array(
                                            'buttons' => 0
                                        ),
                                        'label' => 'Открыть инструкцию по регистраци школы',
                                        'link' => 'https://help.dnevnik.ru/hc/ru/articles/203476458'
                                    )
                                )
                            ),
                            $to_start
                        ),
                    )),
                    'access_token' => $token,
                    'v' => '5.131',
                    'random_id' => 0
                );
                break;
            case 'В дневнике нет оценок, ДЗ':
                $user_id = $data->object->message->from_id;
                $command = "insert into commonLog (type, date_) values ('6', '{$mysqltime}')";
                $equips = mysqli_query($connection, $command);
                //С помощью messages.send отправляем ответное сообщение
                $request_params = array(
                    'peer_id' => $user_id,
                    'message' => "Ответственность за качество, своевременность и полноту вносимых данных несут сотрудники образовательной организации.
В случае, если в дневнике ребёнка отсутствует расписание и темы уроков, а также нет информации о домашних заданиях или оценках,  мы рекомендуем обратиться с этим вопросом к классному руководителю или администратору Дневник.ру в школе.  

Информация об администраторах находится на профиле организации в блоке «Администраторы», информация о классных руководителях находится на профиле класса в блоке «Сотрудники класса».",
                    'keyboard' => json_encode(array(
                        'one_time' => true,
                        'inline' => false,
                        'buttons' => array($to_start),
                    )),
                    'access_token' => $token,
                    'v' => '5.131',
                    'random_id' => 0
                );
                break;
            case 'В профиле ребёнка указан старый класс':
                $user_id = $data->object->message->from_id;
                $command = "insert into commonLog (type, date_) values ('7', '{$mysqltime}')";
                $equips = mysqli_query($connection, $command);
                //С помощью messages.send отправляем ответное сообщение
                $request_params = array(
                    'peer_id' => $user_id,
                    'message' => "Если в профиле ребенка отображается класс в прошлом учебном году, это значит, что сотрудники школы ещё не перевели классы в новый учебный год. С вопросом о переводе класса в актуальный учебный год мы рекомендуем обратиться к классному руководителю или администратору Дневник.ру в школе.  Информация об администраторах находится на профиле организации в блоке «Администраторы», информация о классных руководителях находится на профиле класса в блоке «Сотрудники класса».",
                    'keyboard' => json_encode(array(
                        'one_time' => true,
                        'inline' => false,
                        'buttons' => array($to_start),
                    )),
                    'access_token' => $token,
                    'v' => '5.131',
                    'random_id' => 0
                );
                break;
            case 'Как изменить школу':
                $user_id = $data->object->message->from_id;
                $command = "insert into commonLog (type, date_) values ('9', '{$mysqltime}')";
                $equips = mysqli_query($connection, $command);
                //С помощью messages.send отправляем ответное сообщение
                $request_params = array(
                    'peer_id' => $user_id,
                    'message' => "Вам необходимо обратиться к администратору Дневник.ру в Вашей новой школе. Согласно ФЗ №152 «О персональных данных» вносить изменения в списки пользователей организации (создание/исключение/перевод) могут только администраторы. Информация о них находится на профиле организации в блоке «Администраторы». Также можно обратиться к классному руководителю.
Для перевода старого аккаунта ребенка в новую школу Вам нужно сообщить администратору логин профиля ребенка, который используется для входа в Систему.
После зачисления ребенка в новую школу, Вы получите приглашение на присоединение к организации. Вам необходимо будет принять это приглашение в настройках личного профиля на вкладке «Безопасность».",
                    'keyboard' => json_encode(array(
                        'one_time' => true,
                        'inline' => false,
                        'buttons' => array($to_start),
                    )),
                    'access_token' => $token,
                    'v' => '5.131',
                    'random_id' => 0
                );
                break;
            case 'Как прикрепить ребёнка':
                $user_id = $data->object->message->from_id;
                $command = "insert into commonLog (type, date_) values ('8', '{$mysqltime}')";
                $equips = mysqli_query($connection, $command);
                //С помощью messages.send отправляем ответное сообщение
                $request_params = array(
                    'peer_id' => $user_id,
                    'message' => "Выберите, пожалуйста, Вашу роль в системе:",
                    'keyboard' => json_encode(array(
                        'one_time' => true,
                        'inline' => false,
                        'buttons' => array(
                            array(
                                array(
                                    'action' => array(
                                        'type' => 'text',
                                        'payload' => array(
                                            'buttons' => 1,
                                            'flag' => 1
                                        ),
                                        'label' => 'Я родитель'
                                    ),
                                    'color' => 'primary'
                                )
                            ),
                            array(
                                array(
                                    'action' => array(
                                        'type' => 'text',
                                        'payload' => array(
                                            'buttons' => 1,
                                            'flag' => 1
                                        ),
                                        'label' => 'Я сотрудник ОО'
                                    ),
                                    'color' => 'primary'
                                )
                            )
                        ),
                    )),
                    'access_token' => $token,
                    'v' => '5.131',
                    'random_id' => 0
                );
                break;
            case 'Я родитель':
            case 'Я ученик':
                $user_id = $data->object->message->from_id;
//            $log = date('Y-m-d H:i:s') . $flag;
//            file_put_contents(__DIR__ . '/log.txt', $data1 . PHP_EOL, FILE_APPEND);
                $flag = json_decode($data->object->message->payload)->flag;
                //С помощью messages.send отправляем ответное сообщение
                if ($flag == 0) {
                    $request_params = array(
                        'peer_id' => $user_id,
                        'message' => "Обратитесь в образовательную организацию к администратору или классному руководителю - они могут оперативно помочь Вам восстановить доступ в Систему. А ещё данные для входа можно восстанавливать самостоятельно.",
                        'keyboard' => json_encode(array(
                            'one_time' => true,
                            'inline' => false,
                            'buttons' => array(
                                array(
                                    array(
                                        'action' => array(
                                            'type' => 'open_link',
                                            'payload' => array(
                                                'buttons' => 0
                                            ),
                                            'label' => 'Попробовать самостоятельно',
                                            'link' => 'https://login.dnevnik.ru/recovery'
                                        )
                                    )
                                ),
                                $to_start
                            )
                        )),
                        'access_token' => $token,
                        'v' => '5.131',
                        'random_id' => 0
                    );
                } else {
                    $request_params = array(
                        'peer_id' => $user_id,
                        'message' => "Обратитесь к администратору или к классному руководителю в школе, так как согласно ФЗ №152 «О персональных данных», вносить изменения в списки пользователей могут только сотрудники школы. 
Добавление родственных связей возможно даже в случае, если дети обучаются в разных школах. Вам необходимо лишь сообщить сотрудникам логин от Вашего родительского профиля.
После создания родственной связи с ребёнком, Вы получите приглашение на присоединение к школе. Вам необходимо будет перейти на сайт и принять это приглашение в настройках личного профиля на вкладке «Безопасность».",
                        'keyboard' => json_encode(array(
                            'one_time' => true,
                            'inline' => false,
                            'buttons' => array($to_start),
                        )),
                        'access_token' => $token,
                        'v' => '5.131',
                        'random_id' => 0
                    );
                }
                break;
            case 'Я сотрудник ОО':
                $user_id = $data->object->message->from_id;
                $flag = json_decode($data->object->message->payload)->flag;
                //С помощью messages.send отправляем ответное сообщение
                if ($flag == 0) {
                    $request_params = array(
                        'peer_id' => $user_id,
                        'message' => "В соответствии с регламентом восстановление доступа сотрудникам производится только через администраторов образовательной организации.
Информация об администраторах находится в блоке «Администраторы» на профиле организации. 
Пожалуйста, обратитесь к администратору ЭЖД в образовательной организации.",
                        'keyboard' => json_encode(array(
                            'one_time' => true,
                            'inline' => false,
                            'buttons' => array(
                                array(
                                    array(
                                        'action' => array(
                                            'type' => 'open_link',
                                            'payload' => array(
                                                'buttons' => 0
                                            ),
                                            'label' => 'Посмотреть регламент',
                                            'link' => 'http://help.dnevnik.ru/hc/ru/articles/360001728707'
                                        )
                                    )
                                ),
                                $to_start
                            )
                        )),
                        'access_token' => $token,
                        'v' => '5.131',
                        'random_id' => 0
                    );
                } else {
                    $request_params = array(
                        'peer_id' => $user_id,
                        'message' => "Подробная инструкция по установлению родственной связи размещена на портале службы поддержки.",
                        'keyboard' => json_encode(array(
                            'one_time' => true,
                            'inline' => false,
                            'buttons' => array(
                                array(
                                    array(
                                        'action' => array(
                                            'type' => 'open_link',
                                            'payload' => array(
                                                'buttons' => 0
                                            ),
                                            'label' => 'Открыть инструкцию',
                                            'link' => 'https://help.dnevnik.ru/hc/ru/articles/203475898'
                                        )
                                    )
                                ),
                                $to_start
                            ),
                        )),
                        'access_token' => $token,
                        'v' => '5.131',
                        'random_id' => 0
                    );
                }
                break;
            default:
                $user_id = $data->object->message->from_id;
                if ($data->object->message->attachments) {
                    $mass = "К сожалению, я не умею распознавать файлы. Пожалуйста нажмите \"Начать\", чтобы выбрать категорию и получить помощь в вопросах, связанных с Дневник.ру.";
                } else {
                    $command = "insert into defaultCase (word) values ('{$data->object->message->text}')";
                    $equips = mysqli_query($connection, $command);
                    $mass = "К сожалению, я вас не понял. Пожалуйста нажмите \"Начать\", чтобы выбрать категорию и получить помощь в вопросах, связанных с Дневник.ру.";
                }
                $request_params = array(
                    'peer_id' => $user_id,
                    'message' => $mass,
                    'keyboard' => json_encode(array(
                        'one_time' => true,
                        'inline' => false,
                        'buttons' => array(
                            array(
                                array(
                                    'action' => array(
                                        'type' => 'text',
                                        'payload' => array(
                                            'buttons' => 0
                                        ),
                                        'label' => 'Начать'
                                    ),
                                    'color' => 'positive'
                                )
                            ),
                        ),
                    )),
                    'access_token' => $token,
                    'v' => '5.131',
                    'random_id' => 0
                );
        }
        $get_params = http_build_query($request_params);
        $key = file_get_contents('https://api.vk.com/method/messages.send?' . $get_params);
//        $log = date('Y-m-d H:i:s') . $key;
//        file_put_contents(__DIR__ . '/log.txt', $log . PHP_EOL, FILE_APPEND);
}

//$log = date('Y-m-d H:i:s') . ' Конец';
//file_put_contents(__DIR__ . '/log.txt', $log . PHP_EOL, FILE_APPEND);
