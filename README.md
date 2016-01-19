# SPFW

自己写的一个简单的 `PHP` 框架

由于 `Laravel` 理念很好, 全栈框架, 就算是团队合作大型项目, 只要遵守它的规范, 应用还是非常有条理, 十分容易维护. 但有时候觉得抽象层级太高导致很重. 而且 `booting` 就花费了大量的时间. `Lument` 是个很好的发展方向, 但是编写 `Laravel` 和 `Lument` 的业务时, 会发现自己貌似不用学 `php` 也能用, 代码没有了 `php` 的风格. 所以想写一个简单的集成了构建项目所需要的简单的配置的框架, 加速开发. 所以, 这个项目就诞生了.


## 特性

1. 简单的依赖注入容器.

很简单的容器, 放进去, 取出来. 支持单例(即使你不会写单例), Ioc(控制反转). 不支持依赖自动注入(这个要利用到反射, 个人觉得为了自动注入而牺牲性能不值得, 自动注入带来的收益并不是很明显).

2. 简单的配置类

一个单例的配置类, 让你的项目有一个存在配置和获取配置的地方.

3. 简单的路由解析

路由的注册在包里, 解析在外面, 这样方便用户扩展.

4. 简单的错误异常处理

错误异常的处理都编写好, 支持扩展和修改, 效果还是不错的.

5. 简单的环境变量

支持设置环境变量, `Laravel` 使用的是 `.env` 文件, 我使用的是 `env.php` 文件, 这样就能少了解析的步骤(个人认为), 这里的利弊有没有人告诉我呢?!

6. 支持 `composer`

有了 `composer` 和 `autoload`, 开发速度不会低效.