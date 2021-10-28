(function() {

    /**
     * TinyMCE Button
     */

    tinymce.PluginManager.add('xintheme_shortcodes_options', function(editor, url){
        editor.addButton('xinthemeshortcodes_button', {
            text: '插入短代码',
            icon: 'xintheme-shortcodes-icon',
            type: 'menubutton',
            menu: [
                // Single Shortcodes
				XinThemeSlide,
				Codehighlighting,
                progressBarMenuItem,
                accordionMenuItem,
                buttonMenuItem,
            ]
        });
    });


    /**
     * Helpers
     */

    function escapeHTML(text) {
        return text
             .replace(/&/g, "&amp;")
             .replace(/</g, "&lt;")
             .replace(/>/g, "&gt;")
             .replace(/"/g, "&quot;")
             .replace(/'/g, "&#039;");
     }

    /**
     * 幻灯片
     */

    var XinThemeSlide = {
        text: '添加幻灯片',
        onclick: function(){
            tinyMCE.activeEditor.windowManager.open({
                title: '幻灯片',
                body: [
                    {
                        type: 'textbox',
                        name: 'xintheme_slide_img_1',
                        label: '图像地址',
                        placeholder: '贴入图像连接',
						minWidth: 425,
                    },
                    {
                        type: 'textbox',
                        name: 'xintheme_slide_img_2',
                        label: '图像地址',
                        placeholder: '贴入图像连接',
						minWidth: 425,
                    },
                    {
                        type: 'textbox',
                        name: 'xintheme_slide_img_3',
                        label: '图像地址',
                        placeholder: '贴入图像连接',
						minWidth: 425,
                    },
                    {
                        type: 'textbox',
                        name: 'xintheme_slide_img_4',
                        label: '图像地址',
                        placeholder: '贴入图像连接',
						minWidth: 425,
                    },
                    {
                        type: 'textbox',
                        name: 'xintheme_slide_img_5',
                        label: '图像地址',
                        placeholder: '贴入图像连接',
						minWidth: 425,
                    },
                    {
                        type: 'textbox',
                        name: 'xintheme_slide_img_6',
                        label: '图像地址',
                        placeholder: '贴入图像连接',
						minWidth: 425,
                    },
                    {
                        type: 'textbox',
                        name: 'xintheme_slide_img_7',
                        label: '图像地址',
                        placeholder: '贴入图像连接',
						minWidth: 425,
                    },
                    {
                        type: 'textbox',
                        name: 'xintheme_slide_img_8',
                        label: '图像地址',
                        placeholder: '贴入图像连接',
						minWidth: 425,
                    },
                    {
                        type: 'textbox',
                        name: 'xintheme_slide_img_9',
                        label: '图像地址',
                        placeholder: '贴入图像连接',
						minWidth: 425,
                    },
                    {
                        type: 'textbox',
                        name: 'xintheme_slide_img_10',
                        label: '图像地址',
                        placeholder: '贴入图像连接',
						minWidth: 425,
                    },
                ],
                onsubmit: function(e){
                    tinyMCE.activeEditor.insertContent('[xintheme_slide img_1="' + e.data.xintheme_slide_img_1 + '" img_2="' + e.data.xintheme_slide_img_2 + '" img_3="' + e.data.xintheme_slide_img_3 + '" img_4="' + e.data.xintheme_slide_img_4 + '" img_5="' + e.data.xintheme_slide_img_5 + '" img_6="' + e.data.xintheme_slide_img_6 + '" img_7="' + e.data.xintheme_slide_img_7 + '" img_8="' + e.data.xintheme_slide_img_8 + '" img_9="' + e.data.xintheme_slide_img_9 + '" img_10="' + e.data.xintheme_slide_img_10 + '"]');
                }
            });
        }
    };

    /**
     * 代码高亮
     */

    var Codehighlighting = {
        text: '代码高亮',
        onclick: function(){
            tinyMCE.activeEditor.windowManager.open({
                title: '插入代码',
                body: [
                    {
                        type: 'listbox',
                        name: 'xintheme_syntax_language',
                        label: 'Language',
                        minWidth: 300,
                        values: [
                            { text: '选择语言', value: 'auto' },
							{ text: 'HTML/XML', value: 'html' },
							{ text: 'CSS', value: 'css' },
							{ text: 'JavaScript', value: 'javascript' },
							{ text: 'PHP', value: 'php' },
							{ text: 'JSON', value: 'json' },
                        ]
                    },
                    {
                        type: 'textbox',
                        name: 'xintheme_syntax_code',
                        label: '输入代码',
                        minWidth: 500,
                        minHeight: 150,
                        multiline: true
                    }
                ],
                onsubmit: function(e){
                    var xintheme_syntax_language = '';
                    if(e.data.xintheme_syntax_language !== 'auto'){
                        var xintheme_syntax_language = 'class="language-' + e.data.xintheme_syntax_language + '"';
                    }
                    tinyMCE.activeEditor.insertContent('<pre class="line-numbers language-' + e.data.xintheme_syntax_language + '"><code ' + xintheme_syntax_language + '>' + escapeHTML(e.data.xintheme_syntax_code) + '</code></pre>');
                }
            });
        },
    };

    /**
     * 进度条
     */

    var progressBarMenuItem = {
        text: '进度条',
        onclick: function(){
            tinyMCE.activeEditor.windowManager.open({
                title: '进度条',
                body: [
                    {
                        type: 'textbox',
                        name: 'xintheme_progressbar_label',
                        label: '名称',
                        minWidth: 300,
                    },
                    {
                        type: 'slider',
                        name: 'xintheme_progressbar_percentage',
                        label: '百分比',
                        minWidth: 300,
                    },
                    {
                        type: 'colorpicker',
                        name: 'xintheme_progressbar_color',
                        label: '背景颜色',
                        color: '#2659BA',
                    },
                ],
                onsubmit: function(e){
                    tinyMCE.activeEditor.insertContent('[xintheme_progressbar label="' + e.data.xintheme_progressbar_label + '" percentage="' + e.data.xintheme_progressbar_percentage + '" color="' + e.data.xintheme_progressbar_color + '"]');
                }
            });
        },
    };


    /**
     * 手风琴
     */

    var accordionMenuItem = {
        text: '手风琴',
        onclick: function(){
            tinyMCE.activeEditor.windowManager.open({
                title: '手风琴',
                body: [
                    {
                        type: 'textbox',
                        name: 'xintheme_accordion_title',
                        label: '标题',
                        minWidth: 300,
                    },
                    {
                        type: 'textbox',
                        name: 'xintheme_accordion_content',
                        label: '内容',
                        multiline: true,
                        minHeight: 125,
                        minWidth: 425,
                    },
                    {
                        type: 'listbox',
                        name: 'xintheme_accordion_state',
                        label: '默认状态',
                        values: [
                            { text: '关闭', value: 'closed' },
                            { text: '展开', value: 'open' },
                        ]
                    },
                ],
                onsubmit: function(e){
                    tinyMCE.activeEditor.insertContent('[xintheme_accordion title="' + e.data.xintheme_accordion_title + '" state="' + e.data.xintheme_accordion_state + '"]' + e.data.xintheme_accordion_content + '[/xintheme_accordion]');
                }
            });
        },
    };


    /**
     * 按钮
     */

    var buttonMenuItem = {
        text: '添加按钮',
        onclick: function(){
            tinyMCE.activeEditor.windowManager.open({
                title: '添加按钮',
                body: [
                    {
                        type: 'textbox',
                        name: 'xintheme_button_title',
                        label: '按钮文本',
                        minWidth: 300,
                    },
                    {
                        type: 'textbox',
                        name: 'xintheme_button_icon',
                        label: 'Iconfont 图标',
                        values: '',
                        placeholder: 'icon-QQ'
                    },
                    {
                        type: 'textbox',
                        name: 'xintheme_button_url',
                        label: '跳转链接',
                        values: '',
                        placeholder: 'http://www.xintheme.com/'
                    },
                    {
                        type: 'listbox',
                        name: 'xintheme_button_size',
                        label: '按钮尺寸',
                        values: [
                            { text: '标准', value: 'standard' },
                            { text: '大号', value: 'large' },
                            { text: '特大号', value: 'extra' },
                        ]
                    },
                    {
                        type: 'listbox',
                        name: 'xintheme_button_position',
                        label: '位置',
                        values: [
                            { text: '标准', value: 'inline' },
                            { text: '全宽', value: 'block' },
                            { text: '居中', value: 'center' },
                        ]
                    },
                    {
                        type: 'colorpicker',
                        name: 'xintheme_button_color',
                        label: '背景颜色',
                        color: '#2659BA',
                    },
                    {
                        type: 'checkbox',
                        name: 'xintheme_button_rounded',
                        label: '圆角样式',
                    },
                    {
                        type: 'checkbox',
                        name: 'xintheme_button_blank',
                        label: '在新窗口打开链接',
                    },
                ],
                onsubmit: function(e){
                    tinyMCE.activeEditor.insertContent('[xintheme_button title="' + e.data.xintheme_button_title + '" icon="' + e.data.xintheme_button_icon + '" size="' + e.data.xintheme_button_size + '" position="' + e.data.xintheme_button_position + '" color="' + e.data.xintheme_button_color + '" rounded="' + e.data.xintheme_button_rounded + '" url="' + e.data.xintheme_button_url + '" blank="' + e.data.xintheme_button_blank + '"]');
                }
            });
        }
    };


})();
