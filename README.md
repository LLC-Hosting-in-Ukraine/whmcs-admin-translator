# Інструмент перекладу для адміністратора WHMCS

Цей інструмент перекладає **лише файли мови адміністратора** WHMCS за допомогою API DeepL.

## Вимоги
- WHMCS
- Обліковий запис DeepL та ключ API

## Налаштування

1. Зареєструйтесь на https://www.deepl.com/pro
2. Отримайте свій ключ API
3. Скопіюйте config.example.php у config.php
4. Вставте свій ключ API DeepL у config.php

## Як це працює

- Інструмент обробляє **вихідні файли мови адміністратора**
- Перекладені рядки записуються в:

./admin/lang/overrides/your_language

- після ручної перевірки перейменувати файл your_language в your_language.php
- файл your_language.php має бути розміщений в ./admin/lang/overrides/, оскільки після оновлення базовий файл в ./admin/lang/ може бути перезаписаний.

⚠️ Для WHMCS потрібен базовий мовний файл.
Ви ПОВИННІ створити:

./admin/lang/your_language.php

Навіть якщо він містить лише:

"<?php return [];"

Інакше перевизначення не завантажуватимуться, WHMCS не підтягне мову.g

## Обмеження

- Тільки область адміністратора
- Без перекладів на стороні клієнта
- Не змінює основні файли WHMCS


##################################################################################

# WHMCS Admin Translation Tool

This tool translates **only WHMCS admin language files** using the DeepL API.

## Requirements
- WHMCS
- DeepL account and API key

## Settings

1. Sign up at https://www.deepl.com/pro
2. Get your API key
3. Copy config.example.php to config.php
4. Paste your DeepL API key to config.php

## How it works

- The tool processes **admin language source files**
- Translated lines are written to:

./admin/lang/overrides/your_language

- after manual verification rename your_language file to your_language.php
- your_language.php file must be placed in ./admin/lang/overrides/, as the base file in ./admin/lang/ may be overwritten after an update.

⚠️ WHMCS requires a base language file.
You MUST create:

./admin/lang/your_language.php

Even if it only contains:

"<?php return [];"

Otherwise the overrides will not load, WHMCS will not pick up the language.

## Limitations

- Admin area only
- No client-side translations
- Does not modify WHMCS core files

Thanks_)