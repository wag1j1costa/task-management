<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Sistema de Gerenciamento de Tarefas' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="date"],
        input[type="file"],
        select,
        textarea {
            padding-left: 8px !important;
            padding-right: 8px !important;
            border: 1px solid #d1d5db !important;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus,
        input[type="date"]:focus,
        select:focus,
        textarea:focus {
            border-color: #3b82f6 !important;
            outline: 2px solid transparent;
            outline-offset: 2px;
            box-shadow: 0 0 0 1px rgb(59 130 246 / 0.5);
        }
    </style>
</head>
<body class="bg-gray-50">
    {{ $slot }}
</body>
</html>
