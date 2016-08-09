# Usage

1. 在config的`web.php`中, 添加配置:

'modules' => [
    'jdbrbac' => [
        'class' => 'app\jdbrbac\JdbRbac',
    ],
]

2. 配置`jdbrbac/components/Utils.php`文件下的常量: `ENVIRONMENT`, 可填写的值为`dev` or `prod`.

3. 配置`jdbrbac/components/Utils.php`文件下的`$config`, 配置`source_data`.

* dir: 要初始化`路由(系统的资源节点)`的目录

* namespace: 每一个目录的命名空间

* prefix: 如果是module, 则需要填写自定义的命名空间prefix, 默认为空

4. 将views目录下的`jdbrbac`目录拷贝到项目目录的views文件夹下的某个controller文件夹下, 同时在该controller文件中按照Yii的方式配置请求入口.

5. 使用

* 初始化系统路由
* 添加自定义路由
* 基于路由创建权限
* 基于权限创建角色
* 给用户分配角色
* 使用`JdbRbac::isAllowed($userId)`来检查用户是否可以访问当前路由.

6. 演示

> 线上Demo参考：http://demo.hyii2.com 

# 有关界面

1. 路由管理

* 更新(Add)项目全局路由
* 获得全部路由
* 获得系统路由
* 自定义
  * 获得所有自定义路由
  * 获得单个自定义路由
  * 添加路由
  * 修改路由
  * 删除路由

2. 权限管理

* 列表页
* 删除权限
* 新增权限
* 修改权限
  * 获得所有路由

3. 角色管理

* 列表页
* 删除
* 新增
* 修改
  * 获得所有权限, 路由
  
4. 用户-角色分配

* 分配列表
* 新增分配
* 修改分配
* 删除分配

# 有关定义

1. 用户

* 用户ID

2. 角色

3. 权限

4. 资源节点

* 路由
* 自定义路由

5. 用户资源分配
