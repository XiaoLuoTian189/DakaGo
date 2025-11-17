# 调试 500 错误

如果启用插件时出现 500 错误，请按以下步骤排查：

## 1. 检查 PHP 错误日志

查看 Flarum 的错误日志文件：
- `storage/logs/flarum.log`
- 或服务器错误日志

## 2. 检查常见问题

### 问题 1：迁移未运行

```bash
cd /path/to/flarum
php flarum migrate
```

### 问题 2：类未找到

确保已运行：
```bash
composer dump-autoload
```

### 问题 3：前端资源未编译

```bash
cd /path/to/plugin/js
npm install
npm run build
```

### 问题 4：权限问题

确保 Flarum 可以写入：
```bash
chmod -R 755 storage
chmod -R 755 public/assets
```

## 3. 临时禁用扩展检查

在 `extend.php` 中临时注释掉可能有问题的扩展，逐个启用以定位问题。

## 4. 检查数据库

确保数据库表已创建：
```sql
SHOW TABLES LIKE 'checkin%';
DESCRIBE checkin_records;
DESCRIBE discussions;
```

## 5. 清除缓存

```bash
php flarum cache:clear
composer dump-autoload
```

