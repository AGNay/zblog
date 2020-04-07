# 网易云音乐插件 for ZBlogPHP
## 介绍
一款用于 ZBlogPHP 的网易云音乐插件，方便用户将喜爱的云音乐歌曲添加到自己的博客中。

## 安装
#### 途径一、从博客后台的应用中心搜索 “网易云音乐”插件安装。
#### 途径二、从[应用中心](https://app.zblogcn.com/?id=1884)或[发行页面](https://gitee.com/chrishyze/zbp_neteasecloudmusic/releases)下载插件 zba 文件后从本地上传安装。
#### 途径三、从[发行页面](https://gitee.com/chrishyze/zbp_neteasecloudmusic/releases)下载 zip 文件解压至博客插件目录，再到后台启用即可。
#### 途径四、通过 Git 安装：
1. 获取项目
```bash
git clone https://gitee.com/chrishyze/zbp_neteasecloudmusic <plugin>NeteaseCloudMusic
```
> 注意将 `<plugin>` 替换为 Z-BlogPHP 实际的插件目录。
2. 安装依赖
```bash
cd <plugin>NeteaseCloudMusic
npm install --save
```
> Composer 的安装与使用参考：[英文官网](https://getcomposer.org/doc/00-intro.md) / [Composer 中文文档](https://docs.phpcomposer.com/00-intro.html)  
> npm 的安装与使用参考：[英文官网](https://docs.npmjs.com/) / [中文网站](https://www.npmjs.cn/)
3. 到博客后台的插件管理页面激活 网易云音乐 插件即可。

## 开发
开发分支：v2  
维护分支：master  
历史分支：v1 (只读，仅用于浏览 v1.1 的代码)
```bash
git clone https://gitee.com/chrishyze/zbp_neteasecloudmusic <plugin>NeteaseCloudMusic
cd <plugin>NeteaseCloudMusic
git checkout <branch_name>
npm install --save
```
> `<plugin>` 为 Z-BlogPHP 实际的插件目录。

## 更新日志
[CHANGES.md](https://gitee.com/chrishyze/zbp_neteasecloudmusic/blob/master/CHANGES.md)

## 开源项目
- [kilingzhang/NeteaseCloudMusicApi](https://github.com/kilingzhang/NeteaseCloudMusicApi) (The MIT License)
- [chrishyze/NeteaseCloudMusicSDK](https://gitee.com/chrishyze/NeteaseCloudMusicSDK) (The MIT License)
- [MoePlayer/APlayer](https://github.com/MoePlayer/APlayer) (The MIT License)
- [sentsin/layui](https://github.com/sentsin/layui) (The MIT License)
- [necolas/normalize.css](https://github.com/necolas/normalize.css) (The MIT License)

## License
The MIT License (MIT)
