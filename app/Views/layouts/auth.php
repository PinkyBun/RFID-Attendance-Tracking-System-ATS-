<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'RFID Attendance System' ?></title>
    <!-- Tailwind and DaisyUI CDN -->
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.10/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4 relative overflow-hidden bg-slate-50">
    <!-- Geometric Background Pattern -->
    <div class="absolute inset-0 z-0 opacity-40" style="background-image: radial-gradient(#cbd5e1 1.5px, transparent 1.5px); background-size: 32px 32px;"></div>
    
    <!-- Soft Gradient Orbs -->
    <div class="absolute top-[10%] left-[20%] w-[30rem] h-[30rem] bg-info/20 rounded-full mix-blend-multiply filter blur-3xl opacity-70"></div>
    <div class="absolute bottom-[10%] right-[20%] w-[30rem] h-[30rem] bg-primary/20 rounded-full mix-blend-multiply filter blur-3xl opacity-70"></div>

    <div class="w-full max-w-md z-10 relative">
        <?= $this->renderSection('content') ?>
    </div>
</body>
</html>
