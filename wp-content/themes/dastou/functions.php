<?php
//B2子主题目录url
define( 'B2_CHILD_URI', get_stylesheet_directory_uri() );

//加载父级样式(一般不用修改)
add_action( 'wp_enqueue_scripts', 'parent_theme_enqueue_styles',9 );
function parent_theme_enqueue_styles() {

    //加载父主题样式文件
    wp_enqueue_style( 'parent-style-main', get_template_directory_uri() . '/style.css',array() , B2_VERSION, 'all' );
    wp_enqueue_style( 'parent-style', get_template_directory_uri().'/Assets/fontend/style.css',array() , B2_VERSION, 'all');
}

//加载子主题样式
add_action( 'wp_enqueue_scripts', 'child_theme_enqueue_styles',99 );
function child_theme_enqueue_styles() {

    //禁用当前子主题默认加载项
    wp_dequeue_style( 'b2-style-main' );
    wp_dequeue_style( 'b2-style' );

    //加载子主题样式文件，使它在所有样式之后
    wp_enqueue_style( 'child-style', B2_CHILD_URI.'/style.css' , array() , B2_VERSION, 'all');

    //加载子主题JS文件
    wp_enqueue_script( 'b2-child', B2_CHILD_URI.'/child.js', array(), B2_VERSION , true );
}

/*恢复经典编辑器*/
add_filter('use_block_editor_for_post', '__return_false');
remove_action( 'wp_enqueue_scripts', 'wp_common_block_scripts_and_styles' );

/**
 * 删除目录及目录下所有文件或删除指定文件
 * 代码出自ThinkPHP：http://www.thinkphp.cn/code/1470.html
 * @param str $path   待删除目录路径
 * @param int $delDir 是否删除目录，1或true删除目录，0或false则只删除文件保留目录（包含子目录）
 * @return bool 返回删除状态
 */
function delDirAndFile($path, $delDir = FALSE) {
    $handle = opendir($path);
    if ($handle) {
        while (false !== ( $item = readdir($handle) )) {
            if ($item != "." && $item != "..")
                is_dir("$path/$item") ? delDirAndFile("$path/$item", $delDir) : unlink("$path/$item");
        }
        closedir($handle);
        if ($delDir)
            return rmdir($path);
    }else {
        if (file_exists($path)) {
            return unlink($path);
        } else {
            return FALSE;
        }
    }
}
/*
*Wordpress文章关键词自动添加内链链接代码
*https://www.npc.ink/15286.html
*/
//连接数量
 $match_num_from = 1; //一篇文章中同一个关键字少于多少不锚文本（这个直接填1就好了）
 $match_num_to = 1; //一篇文章中同一个关键字最多出现多少次锚文本（建议不超过1次）
 //连接到WordPress的模块
 add_filter('the_content','tag_link',1);
 //按长度排序
 function tag_sort($a, $b){
 if ( $a->name == $b->name ) return 0;
 return ( strlen($a->name) > strlen($b->name) ) ? -1 : 1;
 }
 //改变标签关键字
 function tag_link($content){
 global $match_num_from,$match_num_to;
 $posttags = get_the_tags();
 if ($posttags) {
 usort($posttags, "tag_sort");
 foreach($posttags as $tag) {
 $link = get_tag_link($tag->term_id);
 $keyword = $tag->name;
 //连接代码
 $cleankeyword = stripslashes($keyword);
 $url = "<a href=\"$link\" title=\"".str_replace('%s',addcslashes($cleankeyword, '$'),__('查看所有文章关于 %s'))."\"";
 $url .= 'target="_blank"';
 $url .= ">".addcslashes($cleankeyword, '$')."</a>";
 $limit = rand($match_num_from,$match_num_to);
 //不连接的代码
 $content = preg_replace( '|(<a[^>]+>)(.*)('.$ex_word.')(.*)(</a[^>]*>)|U'.$case, '$1$2%&&&&&%$4$5', $content);
 $content = preg_replace( '|(<img)(.*?)('.$ex_word.')(.*?)(>)|U'.$case, '$1$2%&&&&&%$4$5', $content);
 $cleankeyword = preg_quote($cleankeyword,'\'');
 $regEx = '\'(?!((<.*?)|(<a.*?)))('. $cleankeyword . ')(?!(([^<>]*?)>)|([^>]*?</a>))\'s' . $case;
 $content = preg_replace($regEx,$url,$content,$limit);
 $content = str_replace( '%&&&&&%', stripslashes($ex_word), $content);
 }
 }
 return $content; 
 }
 /*自动给文章的外部链接添加nofollow属性*/ 
add_filter('the_content','web589_the_content_nofollow',999);
function web589_the_content_nofollow($content){
preg_match_all('/href="(http.*?)"/',$content,$matches);
if($matches){
foreach($matches[1] as $val){
if( strpos($val,home_url())===false )
$content=str_replace("href=\"$val\"", "rel=\"nofollow\" href=\"" . get_bloginfo('wpurl'). "/goto?url=" .base64_encode($val). "\"",$content);
}
}
return $content;
}
// 自动给文章的外部链接添加nofollow属性