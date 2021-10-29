=== zhanzhangb-baidu-submit ===
Contributors: ywtywt
Donate link: https://www.zhanzhangb.com/
Tags: Seo,Baidu
Requires at least: 5.0
Tested up to: 5.6
Stable tag: 5.6
Requires PHP: 5.5
License: GNU General Public License (GPL) version 3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

== Description ==
插件功能：发布新文章时推送文章URL至百度搜索资源平台，支持主动推送、自动推送（百度已暂停）、快速收录，兼容Gutenberg。插件特点：同类插件会在数据库中留下0或1来判断文章是否已提交，本插件采用其它判断逻辑，提交过程中不会写入数据库，更绿色、效率更高，且所有功能永久免费。
插件特色：1、仅发布新文章时才主动提交，修改/更新文章不会重复提交。2、与其它同类插件相比较，本插件不会新增自定义栏目，故而不会在数据库中留下垃圾数据。3、实时显示提交成功的数量与天级收录当天剩余的提交量。
更多详情：<a href="https://www.zhanzhangb.com/2020-921.html" rel="friend">WordPress百度搜索推送插件官网</a>

== Installation ==

This section describes how to install the plugin and get it working.

e.g.

1. Upload the plugin files to the `/wp-content/plugins/plugin-name` directory, or install the plugin through the WordPress plugins screen directly.
1. Activate the plugin through the 'Plugins' screen in WordPress
1. Use the Settings->Plugin Name screen to configure the plugin
1. (Make your instructions match the desired user flow for activating and installing your plugin. Include any steps that might be needed for explanatory purposes)

== Frequently Asked Questions ==

= 如何获取百度主动推送token？ =

https://ziyuan.baidu.com/linksubmit/index

= 如何获取快速收录token？ =

https://ziyuan.baidu.com/dailysubmit/index

= 如何增加快速收录的提交配额 =

百度快速收录配额调整规则：根据上周总体配额使用情况，智能评估出新的配额。

= 启用插件之前已发布的文章支持主动提交吗？ =

如果在启用插件之前的文章为已发布状态，即使更新文章也不会提交了，可以通过手动提交或sitemap方式提交。

== Screenshots ==

1. `/assets/screenshot-1.png` 
2. `/assets/screenshot-2.png` 

== Changelog ==

= 1.4.2 =
*兼容WordPress 5.6。

= 1.4.1 =
*修复1处BUG。

= 1.4.0 =
*同步百度官方站长工具调整，恢复自动提交功能。

= 1.3 =
*适配Wordpress5.5版，优化代码。
*因百度官方调整，取消自动提交功能。

= 1.2.2 =
*因百度官方自动提交工具近期维护升级中，暂停自动提交功能，主动提交和快速收录不受影响。

= 1.2.1 =
*修复：升级插件后自动提交功能被重置为关闭状态的BUG。

= 1.2.0 =
* 因百度业务调整，将百度天级收录更换为快速收录。

= 1.1.0 =
* 新增百度自动推送功能

= 1.0.0 =
* 首次正式发布
* 经过两周的测试

== Upgrade Notice ==
= 1.4.2 =
请升级

= 1.4.1 =
建议升级

= 1.4.0 =
建议升级

= 1.3 =
优化代码，适配Wordpress5.5版。
建议升级

= 1.2.2 =
因百度官方自动提交工具近期维护升级中，暂停自动提交功能，主动提交和快速收录不受影响。
建议升级

= 1.2.1 =
修复已知BUG。 立即升级。

= 1.2.0 =
百度天级收录更换为快速收录。 立即升级。

= 1.1.0 =
新增百度自动推送功能。 立即升级。

= 1.0.0 =
正式发布的版本。 立即升级。


== Arbitrary section ==

插件将调用百度资源平台的API接口：http://data.zz.baidu.com/

插件支持：<a href="https://www.zhanzhangb.com/" rel="friend">站长帮</a>

本插件由站长帮制作并发行，官网：https://www.zhanzhangb.com

== A brief Markdown Example ==