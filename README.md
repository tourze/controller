# tourze框架-控制器模块

## 安装

首先需要下载和安装[composer](https://getcomposer.org/)，具体请查看官网的[Download页面](https://getcomposer.org/download/)

在你的`composer.json`中增加：

    "require": {
        "tourze/controller": "^1.0"
    },

或直接执行

    composer require tourze/controller:"^1.0"

## 使用

控制器模块为使用者提供了数个常用的控制器基类。

使用者只要继承这些基类，就能使用其特定功能。

### 基础部分

继承了控制器模块中的任意基类，就可以有下面变量：

#### $this->request

`$this->request`保存了进入当前控制器实例的请求实例。

通过这个成员，你可以读取到请求相关信息，最常用的有：

1. `$this->request->query()`，读取用户提交的GET数据
2. `$this->request->post()` 读取用户提交的POST数据
3. `$this->request->body` 读取用户提交过来的原始数据
4. `$this->request->method` 用户访问的HTTP方法
5. `$this->request->controller` 最终调用的控制器名称
6. `$this->request->action` 最终执行方法

#### $this->response

一般来说，如果你要输出内容，不要使用`echo`、`print`等函数，而应该使用`$this->response`成员来托管所有输出。

这个成员我们最常用就一个属性和一个方法：

##### $this->response->body

这个很简单，只要将原来的类似`echo 'I am here'`的代码替换成`$this->response->body = 'I am here'`即可。

##### $this->response->headers()

如果我们需要在返回内容中增加自定义header信息，那么不要使用`header()`方法，使用`$this->response->headers()`来替代！

### 通用控制器基类（\tourze\Controller\WebController）

这是最基础的控制器，只提供简单的控制器映射和部分快捷功能。

例子：

    <?php
    
    namespace app\Controller;
    
    use tourze\Controller\WebController;
    
    class NormalSampleController extends WebController
    {
    
        /**
         * ...
         */
        public function actionTest()
        {
            if ($this->request->param('id') == 1)
            {
                $this->redirect('/other-url');
            }
            $this->response->body = 'I am here';
        }
    }

### 带模板功能的控制器基类

一般来说，一个完整的页面会包括头部、内容、底部或其他部分。如果每个视图都要实现一次这些部分，或者include一次这些部分，会造成维护困难。

所以我们为使用者提供了一个模板控制器：

    <?php
    
    namespace app\Controller;
    
    use tourze\Controller\TemplateController;
    
    class TemplateSampleController extends TemplateController
    {
    
        /**
         * ...
         */
        public function actionTest()
        {
            $this->template->set('content', 'I am content');
        }
    }

看代码可以知道，其实用法跟`WebController`差不多。

具体要加载哪个模板，可以通过设置`$this->template`来实现。默认模板名是`template`。

### JSON/JSONP控制器基类

待完善

### REST控制器基类

待完善
