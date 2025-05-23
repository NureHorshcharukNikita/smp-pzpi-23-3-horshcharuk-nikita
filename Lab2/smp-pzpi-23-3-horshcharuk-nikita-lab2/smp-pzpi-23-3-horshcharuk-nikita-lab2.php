<?php
    $products = [
        1 => ["name" => "Молоко пастеризоване", "price" => 12],
        2 => ["name" => "Хліб чорний", "price" => 9],
        3 => ["name" => "Сир білий", "price" => 21],
        4 => ["name" => "Сметана 20%", "price" => 25],
        5 => ["name" => "Кефір 1%", "price" => 19],
        6 => ["name" => "Вода газована", "price" => 18],
        7 => ["name" => "Печиво \"Весна\"", "price" => 14]
    ];

    $cart = [];
    $userProfile = ["name" => "", "age" => 0];

    if (!function_exists('mb_str_pad')) {
        function mb_str_pad($input, $pad_length, $pad_string = ' ', $pad_type = STR_PAD_RIGHT) {
            $diff = $pad_length - preg_match_all('/./us', $input);
            if ($diff > 0) {
                if ($pad_type === STR_PAD_RIGHT) {
                    return $input . str_repeat($pad_string, $diff);
                } 
                else if ($pad_type === STR_PAD_LEFT) {
                    return str_repeat($pad_string, $diff) . $input;
                } 
                else if ($pad_type === STR_PAD_BOTH) {
                    $left = floor($diff / 2);
                    $right = $diff - $left;
                    return str_repeat($pad_string, $left) . $input . str_repeat($pad_string, $right);
                }
            }
        
            return $input;
        }
    }

    function getMaxNameLength($products) {
        return max(array_map(function($p) { return preg_match_all('/./us', $p['name']); }, $products));
    }

    function showMainMenu() {
        echo "################################\n";
        echo "# ПРОДОВОЛЬЧИЙ МАГАЗИН \"ВЕСНА\" #\n";
        echo "################################\n";
        echo "1 Вибрати товари\n";
        echo "2 Отримати пiдсумковий рахунок\n";
        echo "3 Налаштувати свiй профiль\n";
        echo "0 Вийти з програми\n";
        echo "Введiть команду: ";
    }

    function showProducts($products) {
        $maxNameLength = getMaxNameLength($products) + 2;

        echo "№  " . mb_str_pad("НАЗВА", $maxNameLength) . "ЦІНА\n";

        foreach ($products as $id => $product) {
            echo mb_str_pad($id, 3) . mb_str_pad($product['name'], $maxNameLength) . mb_str_pad($product['price'], 6) . "\n";
        }

        echo "   " . str_repeat("-", 11) . "\n";
        echo "0  ПОВЕРНУТИСЯ\n";
        echo "Виберiть товар: ";
    }

    function showCart($cart, $products) {
        if (empty($cart)) {
            echo "КОШИК ПОРОЖНІЙ\n";
        } else {
            $maxNameLength = getMaxNameLength($products) + 2;

            echo "У КОШИКУ:\n";
            echo mb_str_pad("НАЗВА", $maxNameLength) . "КІЛЬКІСТЬ\n";
            foreach ($cart as $id => $quantity) {
                echo mb_str_pad($products[$id]['name'], $maxNameLength) . $quantity . "\n";
            }
        }

        echo "\n";
    }

    function getFinalBill($products, $cart) {
        if (empty($cart)) {
            echo "КОШИК ПОРОЖНІЙ\n";
            return;
        }

        $cartProducts = array_intersect_key($products, $cart);
        $maxNameLength = getMaxNameLength($cartProducts) + 2;

        echo "№  " . mb_str_pad("НАЗВА", $maxNameLength) . "ЦІНА  КІЛЬКІСТЬ  ВАРТІСТЬ\n";
        $total = 0;
        $i = 1;
        foreach ($cart as $id => $quantity) {
            $name = $products[$id]['name'];
            $price = $products[$id]['price'];
            $cost = $price * $quantity;
            echo mb_str_pad($i++, 3) . mb_str_pad($name, $maxNameLength) . mb_str_pad($price, 6) . mb_str_pad($quantity, 11) . $cost . "\n";
            $total += $cost;
        }
        echo "РАЗОМ ДО СПЛАТИ: $total\n";
    }

    function setupProfile(&$userProfile) {
        do {
            echo "Ваше iм'я: ";
            $name = trim(readline());
        
            if (!preg_match('/^[a-zA-Zа-яА-ЯіІїЇєЄ][a-zA-Zа-яА-ЯіІїЇєЄ\'\- ]*$/u', $name)) {
                echo "ПОМИЛКА! Імʼя повинно починатись з літери і може містити лише літери, апостроф «'», дефіс «-», пробіл!\n\n";
            }
        } while (!preg_match('/^[a-zA-Zа-яА-ЯіІїЇєЄ][a-zA-Zа-яА-ЯіІїЇєЄ\'\- ]*$/u', $name));

        do {
            echo "Ваш вiк: ";
            $age = (int)trim(fgets(STDIN));
            if ($age < 7 || $age > 150) {
                echo "ПОМИЛКА! Користувач повинен мати вік від 7 та до 150 років\n\n";
            }
        } while ($age < 7 || $age > 150);

        $userProfile['name'] = $name;
        $userProfile['age'] = $age;

        echo "\nВаше імʼя: " . $name . "\n";
        echo "Ваш вік: " . $age . "\n";
    }

    function chooseProduct($products, &$cart) {
        while (true) {
            showProducts($products);
            $choice = trim(fgets(STDIN));

            if ($choice == '0') {
                break;
            }

            if (!isset($products[(int)$choice])) {
                echo "ПОМИЛКА! ВКАЗАНО НЕПРАВИЛЬНИЙ НОМЕР ТОВАРУ\n\n";
                continue;
            }

            echo "\n";
            echo "Вибрано: " . $products[(int)$choice]['name'] . "\n";
            echo "Введiть кiлькiсть, штук: ";
            $qty = trim(fgets(STDIN));

            if (!is_numeric($qty) || (int)$qty < 0 || (int)$qty > 99) {
                echo "ПОМИЛКА! Кiлькiсть повинна бути вiд 0 до 99\n\n";
                showCart($cart, $products);
                continue;
            }

            $qty = (int)$qty;

            if ($qty == 0) {
                if (array_key_exists($choice, $cart)) {
                    echo "ВИДАЛЯЮ З КОШИКА\n";
                    unset($cart[(int)$choice]);
                }
            } else {
                $cart[(int)$choice] = $qty;
            }

            echo "\n";
            showCart($cart, $products);
        }
    }

    while (true) {
        showMainMenu();
        $command = trim(fgets(STDIN));

        if (!in_array($command, ['0', '1', '2', '3'])) {
            echo "ПОМИЛКА! Введiть правильну команду\n\n";
            continue;
        }

        echo "\n";

        if ($command == '0') {
            break;
        }

        if ($command == '1') {
            chooseProduct($products, $cart);
        }

        if ($command == '2') {
            getFinalBill($products, $cart);
        }

        if ($command == '3') {
            setupProfile($userProfile);
        }

        echo "\n";
    }
?>
