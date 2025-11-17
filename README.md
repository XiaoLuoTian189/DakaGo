# Flarum 打卡插件

这是一个 Flarum 论坛插件，允许用户创建打卡类主题，并每日上传打卡照片。

## 功能特性

- ✅ 发布主题时可以选择为"打卡类"主题
- ✅ 打卡类主题支持每日上传打卡照片
- ✅ 显示打卡历史记录
- ✅ 用户可以删除自己的打卡记录
- ✅ 防止同一天重复打卡

## 🚀 快速安装

### 通过 Packagist 安装（推荐）

```bash
cd /path/to/flarum
composer require xiaoluotian189/dk
php flarum migrate
php flarum cache:clear
```

然后在 Flarum 管理后台启用插件即可！

> 📖 提交到 Packagist 的详细步骤请查看 [PACKAGIST.md](PACKAGIST.md)

## 使用方法

1. **创建打卡主题**：
   - 在发布新主题时，勾选"这是打卡类主题"选项
   - 发布后，该主题将显示打卡功能

2. **每日打卡**：
   - 在打卡类主题的侧边栏，点击"上传打卡照片"按钮
   - 选择照片并添加可选备注
   - 提交打卡

3. **查看打卡历史**：
   - 在打卡类主题的侧边栏可以查看所有打卡记录
   - 包括日期、照片和备注

## 技术栈

- **后端**：PHP (Flarum Framework)
- **前端**：TypeScript, Mithril.js
- **数据库**：MySQL/MariaDB

## 文件结构

```
flarum-checkin/
├── composer.json          # Composer 配置
├── extend.php             # 插件扩展入口
├── migrations/            # 数据库迁移文件
├── src/                   # PHP 源代码
│   ├── Api/              # API 控制器和序列化器
│   ├── Listener/         # 事件监听器
│   └── CheckinRecord.php # 打卡记录模型
├── js/                    # 前端代码
│   ├── src/
│   │   ├── forum/        # 论坛前端组件
│   │   └── admin/        # 管理后台组件
│   └── package.json
├── less/                  # 样式文件
├── locale/                # 语言文件
└── README.md
```

## 开发

### 前端开发

```bash
cd js
npm install
npm run dev  # 开发模式（监听文件变化）
npm run build  # 生产构建
```

### 后端开发

确保已安装 Composer 依赖：

```bash
composer install
```

## 许可证

MIT License
