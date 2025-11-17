<?php
/**
 * 插件错误检查脚本
 * 运行此脚本检查插件配置和代码是否有问题
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "========================================\n";
echo "Flarum 打卡插件错误检查\n";
echo "========================================\n\n";

$errors = [];
$warnings = [];

// 1. 检查关键文件是否存在
$requiredFiles = [
    'composer.json',
    'extend.php',
    'src/CheckinRecord.php',
    'src/Listener/SaveCheckinType.php',
    'src/Listener/AddCheckinData.php',
    'src/Api/Controller/CreateCheckinRecordController.php',
    'src/Api/Controller/ListCheckinRecordsController.php',
    'src/Api/Controller/DeleteCheckinRecordController.php',
    'src/Api/Controller/UploadPhotoController.php',
    'src/Api/Serializer/CheckinRecordSerializer.php',
    'migrations/2024_01_01_000000_create_checkin_records_table.php',
    'migrations/2024_01_01_000001_add_checkin_type_to_discussions.php',
];

echo "1. 检查文件是否存在...\n";
foreach ($requiredFiles as $file) {
    if (!file_exists(__DIR__ . '/' . $file)) {
        $errors[] = "缺少文件: $file";
        echo "  ❌ $file\n";
    } else {
        echo "  ✅ $file\n";
    }
}

// 2. 检查 PHP 语法
echo "\n2. 检查 PHP 语法...\n";
$phpFiles = glob(__DIR__ . '/src/**/*.php');
$phpFiles = array_merge($phpFiles, glob(__DIR__ . '/migrations/*.php'));
$phpFiles[] = __DIR__ . '/extend.php';

foreach ($phpFiles as $file) {
    $output = [];
    $return = 0;
    exec("php -l " . escapeshellarg($file) . " 2>&1", $output, $return);
    if ($return !== 0) {
        $errors[] = "语法错误: $file - " . implode("\n", $output);
        echo "  ❌ $file\n";
        echo "    " . implode("\n    ", $output) . "\n";
    } else {
        echo "  ✅ " . basename($file) . "\n";
    }
}

// 3. 检查 composer.json
echo "\n3. 检查 composer.json...\n";
$composer = json_decode(file_get_contents(__DIR__ . '/composer.json'), true);
if (json_last_error() !== JSON_ERROR_NONE) {
    $errors[] = "composer.json JSON 格式错误: " . json_last_error_msg();
    echo "  ❌ JSON 格式错误\n";
} else {
    echo "  ✅ JSON 格式正确\n";
    
    if (!isset($composer['type']) || $composer['type'] !== 'flarum-extension') {
        $warnings[] = "composer.json type 应该是 'flarum-extension'";
    }
    
    if (!isset($composer['require']['flarum/core'])) {
        $errors[] = "composer.json 缺少 flarum/core 依赖";
    }
}

// 4. 检查命名空间
echo "\n4. 检查命名空间...\n";
$namespace = 'Flarum\\Checkin\\';
$autoload = $composer['autoload']['psr-4'] ?? [];
if (!isset($autoload[$namespace]) || $autoload[$namespace] !== 'src/') {
    $warnings[] = "命名空间配置可能不正确";
    echo "  ⚠️  命名空间配置检查\n";
} else {
    echo "  ✅ 命名空间配置正确\n";
}

// 5. 检查迁移文件格式
echo "\n5. 检查迁移文件格式...\n";
$migration1 = file_get_contents(__DIR__ . '/migrations/2024_01_01_000000_create_checkin_records_table.php');
if (strpos($migration1, 'function (Blueprint $table)') === false) {
    $errors[] = "迁移文件格式错误：应该使用回调函数";
    echo "  ❌ create_checkin_records_table.php 格式错误\n";
} else {
    echo "  ✅ create_checkin_records_table.php 格式正确\n";
}

$migration2 = file_get_contents(__DIR__ . '/migrations/2024_01_01_000001_add_checkin_type_to_discussions.php');
if (strpos($migration2, 'function (Blueprint $table)') === false) {
    $errors[] = "迁移文件格式错误：应该使用回调函数";
    echo "  ❌ add_checkin_type_to_discussions.php 格式错误\n";
} else {
    echo "  ✅ add_checkin_type_to_discussions.php 格式正确\n";
}

// 总结
echo "\n========================================\n";
echo "检查结果\n";
echo "========================================\n";

if (empty($errors) && empty($warnings)) {
    echo "✅ 所有检查通过！\n";
} else {
    if (!empty($errors)) {
        echo "❌ 发现 " . count($errors) . " 个错误：\n";
        foreach ($errors as $error) {
            echo "  - $error\n";
        }
    }
    
    if (!empty($warnings)) {
        echo "\n⚠️  发现 " . count($warnings) . " 个警告：\n";
        foreach ($warnings as $warning) {
            echo "  - $warning\n";
        }
    }
}

echo "\n";

