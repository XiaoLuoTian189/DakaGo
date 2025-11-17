# 编译前端代码说明

## ⚠️ 重要提示

**如果看不到"这是打卡类主题"按钮，很可能是因为前端代码还没有编译！**

## 编译步骤

### 1. 进入 js 目录

```bash
cd js
```

### 2. 安装依赖（首次运行）

```bash
npm install
```

### 3. 编译前端代码

```bash
npm run build
```

或者开发模式（自动监听文件变化）：

```bash
npm run dev
```

### 4. 清除 Flarum 缓存

```bash
cd /path/to/flarum
php flarum cache:clear
php flarum assets:publish
```

### 5. 清除浏览器缓存

- 按 `Ctrl + Shift + Delete` 清除浏览器缓存
- 或者按 `Ctrl + F5` 强制刷新页面

## 验证编译是否成功

编译成功后，应该会生成以下文件：

- `js/dist/forum.js` - 论坛前端代码
- `js/dist/admin.js` - 管理后台代码

如果这些文件不存在，说明编译没有成功。

## 常见问题

### Q: npm install 报错

**A:** 确保已安装 Node.js（建议版本 16+）

```bash
node --version
npm --version
```

### Q: npm run build 报错

**A:** 检查 `js/package.json` 文件，确保依赖正确

### Q: 编译成功但还是看不到按钮

**A:** 
1. 检查浏览器控制台（F12）是否有 JavaScript 错误
2. 确认 `extend.php` 中前端资源已正确加载
3. 确认插件已启用
4. 尝试完全清除浏览器缓存

### Q: 如何确认前端代码已加载？

**A:** 打开浏览器开发者工具（F12），在 Network 标签中查找 `forum.js`，确认文件已加载。

## 开发模式

如果正在开发，可以使用开发模式：

```bash
cd js
npm run dev
```

这会自动监听文件变化并重新编译，但需要保持终端窗口打开。

