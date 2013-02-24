kkfanyi
=======

当一个使用量最大的翻译工具遇到一个时下最火移动IM，会发生什么？想用微信做客服机器人，想用微信查单词？想用微信做LBS应用？看完本文，一切都变得那么简单！

什么是微信公众平台？微信公众平台是腾讯公司在微信基础平台上新增的功能模块，通过这一平台，每一个人都可以用一个QQ 号码，打造自己的一个微信的公众号，并在微信平台上实现和特定群体的文字、图片、语音的全方位沟通、互动 。公众号就好比是企业微博，是企业进行品牌传播，舆论监控，客户关系管理的工具，我所在工作室——三峡大学微观星工坊，在本月初也开通了公众号：ctgustar，主要用于向内部成员发布项目进展状况。

之前微信上的公众帐号还是以推送信息和做客服为主，虽然也有自定义回复，但功能太弱。前不久微信公众平台开放了自定义回复接口，这让公众号能够实现的功能就有了更多的想像空间。

我们知道目前有几家公司已经通过这个接口实现了比较高级的功能，比如“订酒店”这个帐号。当用户在微信中把自己当前的地理位置（微信可以直接发送地图信息）发送给订酒店之后，订酒店会回复一条信息，告诉用户附近有哪些酒店可以预订，并提供订房的费用和电话号码。

第二个例子是白鸦做的“逛”，用户向逛的帐号发送“鞋子”等商品信息，逛会自动回复三条图文并茂的鞋子信息给用户，点击可直接进入逛的移动版页面。


感兴趣的童鞋可以看一下这两篇文章：

微信公众平台「订酒店」的订房功能是如何实现的，运用什么原理？by kentzhu，快捷酒店管家首席客服

微信自定义机器人的最初需求样本 by 白鸦，Guang.com创始人

受上面两个Case的启发，我结合官方提供的PHP示例做了一个微信版的有道词典”可可翻译“，微信号：kkfanyi，大家可以体验下。


可可翻译目前具有中英文的互译的功能，可以获得有道翻译的结果，暂不支持中文翻译英语以外的其他语种，但可以将其他语种（日语、韩语、法语、俄语、西班牙）翻译成中文，直接输入其他语种即可，比如输入韩语：사랑해요 会返回：我爱你。

要发送地理位置信息，还可以获取2公里以内的英语培训机构的名称，地址和联系电话（显示前3条，数据来自百度地图）。

在开发这个微信翻译机器人的过程中，我遇到了三大技术难点：

1.如何获取有道翻译结果？

2.如何获取用户的地理位置信息（纬度，经度）并从百度地图拿数据？

3.如何判断用户发送的是文本信息还是地理位置信息？

坦率的讲，在做可可翻译的过程中我走了不少的弯路，内外因都有。内因是自己毕竟还只是PHP初学者，在写代码的过程中经常因符号错误等问题耽误了不少时间。外因是腾讯给的PHP示例太TM弱爆了，很难想象出自企鹅这种大公司之手，如果直接用的话是不能返回消息结果的，必须加上$wechatObj->responseMsg();才行，位置信息获取也没有给出demo，官方的开发文档只给了XML数据的格式。还有就是现在的程序员都尼玛太自私了，网上搜到的跟微信公众号自定义回复接口开发的技术文章不超过10篇（还有很多是互相抄袭的），在官方开发群也不敢轻易发言，人家动不动就给你提钱的事，哎，桑心啊，不说了。
