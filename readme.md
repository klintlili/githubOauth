这是使用Oauth方式登录github的代码，是从QC的SDK基础上修改而来
https://connect.qq.com/
看到这个sdk时都会有github账号了，去api部分申请一个Oauth应用即可，下面是具体地址：
https://github.com/settings/developers
注册后会生成一个Client ID 和Client Secret。在Oauth参数中会用到的。


change log
2017年4月28日: 目前实现了access_token的获取，并把access_token保存到session中。其他业务接口有待开发。