<?php

error_reporting(0);
ini_set('display_errors', 'Off');

$title = "Whoops! Couldn't find a voice.";
$voice = "Maybe try searching for something you like. I recommend 'Abaddon'";

$voice = (isset($_GET['voice']) && is_string($_GET['voice'])) ? substr($_GET['voice'], 0, 15) : 'Abaddon';

$voice = normalize($voice);

if (!is_null($data = getVoice($voice))) {
    $title = $data['voice'];
    $voice = $data['voice'];
}

function normalize(string $name): string
{
    return strtolower($name);
}

function str_contains_any(string $haystack, array $needles): bool
{
    return array_reduce($needles, fn($a, $n) => $a || str_contains($haystack, $n), false);
}

function getVoice(string $voice): ?array
{
    try {
        set_error_handler(function ($errno, $errstr) {
            throw new ErrorException($errstr, 0, $errno);
        });

        if (preg_match('/\b(?:ping|curl|wget|telnet|dig)\b/i', $voice)) {
            return null;
        }

        $command = "cd ./voices; ls | grep -i $voice";

        $output = shell_exec($command);

        if (is_null($output) || !$output)
            return null;

        $raw = preg_split('/\s+/', trim($output));

        $voice = array_values(array_filter($raw, function (string $voice) {
            return !in_array($voice, ['.', '..']);
        }));

        sort($voice);

        if (!file_exists("./voices/$voice[0]"))
            return null;

        $voice_content = file_get_contents("./voices/$voice[0]");

        if (preg_match('/{{(.*?)}}/', $voice_content, $matches)) {
            $title = trim($matches[1]);
            $voice = str_replace("{{" . $title . "}}", '', $voice_content);
        } else {
            return null;
        }

        return [
            'title' => $title,
            'voice' => nl2br(trim($voice)),
        ];
    } catch (ErrorException $e) {
        return null;
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voices Of Dota2</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        html {
            background-image: url('https://moewalls.com/wp-content/uploads/2022/11/the-spirit-brothers-dota-2-pixel-thumb.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            font-family: Minecraft;
        }

        @font-face {
            font-family: Minecraft;
            src: url('./Minecraft.ttf');
        }
    </style>
</head>

<body class="mt-5 dark">
    <section class="">
        <div class="py-8 px-4 mx-auto max-w-screen-xl lg:py-0 mt-16 lg:px-6">
            <div class="mx-auto max-w-screen-md sm:text-center">
                <h2 class="mb-5 text-3xl tracking-tight text-gray-900 sm:text-4xl dark:text-white"
                    style="font-family: Minecraft">Find
                    Dota2 Voices</h2>
                <form method="get">
                    <div class="items-center mx-auto mb-3 space-y-4 max-w-screen-sm sm:flex sm:space-y-0 mt-10">
                        <div class="relative w-full">
                            <label for="voice"
                                class="hidden mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">voice</label>
                            <div class="flex absolute inset-y-0 left-0 items-center pl-3 pointer-events-none">
                                <svg class="w-5 h-5 text-white" fill="currentColor" xmlns="http://www.w3.org/2000/svg"
                                    x="0px" y="0px" width="100" height="100" viewBox="0 0 50 50">
                                    <path
                                        d="M 21 3 C 11.621094 3 4 10.621094 4 20 C 4 29.378906 11.621094 37 21 37 C 24.710938 37 28.140625 35.804688 30.9375 33.78125 L 44.09375 46.90625 L 46.90625 44.09375 L 33.90625 31.0625 C 36.460938 28.085938 38 24.222656 38 20 C 38 10.621094 30.378906 3 21 3 Z M 21 5 C 29.296875 5 36 11.703125 36 20 C 36 28.296875 29.296875 35 21 35 C 12.703125 35 6 28.296875 6 20 C 6 11.703125 12.703125 5 21 5 Z">
                                    </path>
                                </svg>
                            </div>
                            <input style="font-family: Minecraft"
                                class="block p-3 pl-10 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 sm:rounded-none sm:rounded-l-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                placeholder="Nothing else matter..." type="text" id="voice" name="voice" maxlength="15">
                        </div>
                        <div>
                            <button type="submit"
                                class="py-3 px-5 w-full text-sm font-medium text-center text-white rounded-lg border cursor-pointer bg-primary-700 border-primary-600 sm:rounded-none sm:rounded-r-lg hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800"
                                style="font-family: Minecraft">Search</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="py-8 px-4 mx-auto max-w-screen-xl lg:px-6">
            <div class="mx-auto max-w-screen-md text-center mb-8 lg:mb-12">
                <h3 class="mb-4 text-xl tracking-tight font-extrabold text-white">
                </h3>
                <p class="mb-5 font-light text-gray-100 sm:text-xl px-8 py-4 opacity-70 rounded-2xl"
                    style="background-color: black; font-family: Minecraft">
                    <?= htmlspecialchars(str_replace('<br />', '', $voice)) ?>
                </p>
            </div>
        </div>
    </section>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.js"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: {
                            "50": "#eff6ff",
                            "100": "#dbeafe",
                            "200": "#bfdbfe",
                            "300": "#93c5fd",
                            "400": "#60a5fa",
                            "500": "#3b82f6",
                            "600": "#2563eb",
                            "700": "#1d4ed8",
                            "800": "#1e40af",
                            "900": "#1e3a8a",
                            "950": "#172554"
                        }
                    }
                },
                fontFamily: {
                    'body': [
                        'Inter',
                        'ui-sans-serif',
                        'system-ui',
                        '-apple-system',
                        'system-ui',
                        'Segoe UI',
                        'Roboto',
                        'Helvetica Neue',
                        'Arial',
                        'Noto Sans',
                        'sans-serif',
                        'Apple Color Emoji',
                        'Segoe UI Emoji',
                        'Segoe UI Symbol',
                        'Noto Color Emoji'
                    ],
                    'sans': [
                        'Inter',
                        'ui-sans-serif',
                        'system-ui',
                        '-apple-system',
                        'system-ui',
                        'Segoe UI',
                        'Roboto',
                        'Helvetica Neue',
                        'Arial',
                        'Noto Sans',
                        'sans-serif',
                        'Apple Color Emoji',
                        'Segoe UI Emoji',
                        'Segoe UI Symbol',
                        'Noto Color Emoji'
                    ]
                }
            }
        }
    </script>
</body>

</html>