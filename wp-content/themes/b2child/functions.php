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
//替换为wp经典编辑器
add_filter('use_block_editor_for_post', '__return_false');
remove_action( 'wp_enqueue_scripts', 'wp_common_block_scripts_and_styles' );
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
 //purge代码版
 //初始化配置
$logSwitch  = 0;                  //配置日志开关，1为开启，0为关闭
$logFile    = '/tmp/purge.log';   //配置日志路径
$cache_path = '/dev/shm/sdw_dastou';     //配置缓存路径

//清理所有缓存(仅管理员) 范例：http://www.domain.com/?purge=all
if ($_GET['purge'] == 'all' && is_user_logged_in()) {
    if( current_user_can( 'manage_options' )) 
    {
        delDirAndFile($cache_path, 0);
    }
}

//缓存清理选项
add_action('publish_post', 'Clean_By_Publish', 99);                   //文章发布、更新清理缓存
add_action('comment_post', 'Clean_By_Comments',99);                   //评论提交清理缓存(不需要可注释)
add_action('comment_unapproved_to_approved', 'Clean_By_Approved',99); //评论审核清理缓存(不需要可注释)

//文章发布清理缓存函数
function Clean_By_Publish($post_ID){
    $url = get_permalink($post_ID);

    cleanFastCGIcache($url);        //清理当前文章缓存
    cleanFastCGIcache(home_url().'/');  //清理首页缓存(不需要可注释此行)
        
    //清理文章所在分类缓存(不需要可注释以下5行)
    if ( $categories = wp_get_post_categories( $post_ID ) ) {
        foreach ( $categories as $category_id ) {
            cleanFastCGIcache(get_category_link( $category_id ));
        }
    }

    //清理文章相关标签页面缓存(不需要可注释以下5行)
    if ( $tags = get_the_tags( $post_ID ) ) {
        foreach ( $tags as $tag ) {
	    cleanFastCGIcache( get_tag_link( $tag->term_id ));
        }
    }
}

// 评论发布清理文章缓存
function Clean_By_Comments($comment_id){
    $comment  = get_comment($comment_id);
    $url      = get_permalink($comment->comment_post_ID);
    cleanFastCGIcache($url);
}

// 评论审核通过清理文章缓存
function Clean_By_Approved($comment)
{
    $url      = get_permalink($comment->comment_post_ID); 
    cleanFastCGIcache($url);
}

//日志记录
function purgeLog($msg)
{
    global $logFile, $logSwitch;
    if ($logSwitch == 0 ) return;
    date_default_timezone_set('Asia/Shanghai');
    file_put_contents($logFile, date('[Y-m-d H:i:s]: ') . $msg . PHP_EOL, FILE_APPEND);
    return $msg;
}

// 缓存文件删除函数
function cleanFastCGIcache($url) {
    $url_data  = parse_url($url);
    global $cache_path;
    if(!$url_data) {
        return purgeLog($url.' is a bad url!' );
    }

    $hash        = md5($url_data['scheme'].'GET'.$url_data['host'].$url_data['path']);
    $cache_path  = (substr($cache_path, -1) == '/') ? $cache_path : $cache_path.'/';
    $cached_file = $cache_path . substr($hash, -1) . '/' . substr($hash,-3,2) . '/' . $hash;
    
    if (!file_exists($cached_file)) {
        return purgeLog($url . " is currently not cached (checked for file: $cached_file)" );
    } else if (unlink($cached_file)) {
        return purgeLog( $url." *** CLeanUP *** (cache file: $cached_file)");
    } else {
        return purgeLog("- - An error occurred deleting the cache file. Check the server logs for a PHP warning." );
    }
}

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