<?php

namespace Flarum\Checkin\Api\Controller;

use Flarum\Http\RequestUtil;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;
use Illuminate\Support\Str;

class UploadPhotoController implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $actor = RequestUtil::getActor($request);
        $uploadedFiles = $request->getUploadedFiles();
        
        if (empty($uploadedFiles['photo'])) {
            return new JsonResponse(['errors' => [['detail' => '未上传文件']]], 400);
        }

        $file = $uploadedFiles['photo'];
        
        if ($file->getError() !== UPLOAD_ERR_OK) {
            return new JsonResponse(['errors' => [['detail' => '文件上传失败']]], 400);
        }

        // 验证文件类型
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        $mimeType = $file->getClientMediaType();
        
        if (!in_array($mimeType, $allowedTypes)) {
            return new JsonResponse(['errors' => [['detail' => '不支持的文件类型']]], 400);
        }

        // 生成文件名
        $extension = pathinfo($file->getClientFilename(), PATHINFO_EXTENSION);
        $filename = Str::random(40) . '.' . $extension;
        
        // 创建上传目录（使用 Flarum 的 public 目录）
        $publicPath = app()->basePath() . '/public';
        $uploadDir = $publicPath . '/assets/checkin';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // 保存文件
        $filePath = $uploadDir . '/' . $filename;
        $file->moveTo($filePath);

        // 返回文件 URL（使用 Flarum 的 URL 生成器）
        $url = app()->url() . '/assets/checkin/' . $filename;

        return new JsonResponse([
            'data' => [
                'type' => 'files',
                'attributes' => [
                    'url' => $url,
                ],
            ],
        ]);
    }
}
