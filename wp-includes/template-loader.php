<?php
/**
 * Loads the correct template based on the visitor's url
 * @package WordPress
 */
if ( defined('WP_USE_THEMES') && WP_USE_THEMES )
	/**
	 * Fires before determining which template to load.
	 *
	 * @since 1.5.0
	 */
	 /***  �ڲⶨģ��֮ǰ, ����������, ����ֱ������(wp_redirect) */
	do_action( 'template_redirect' );

/**
 * Filter whether to allow 'HEAD' requests to generate content.
 *
 * Provides a significant performance bump by exiting before the page
 * content loads for 'HEAD' requests. See #14348.
 *
 * @since 3.5.0
 *
 * @param bool $exit Whether to exit without generating any content for 'HEAD' requests. Default true.
 */
 /* �����HTTP HEAD����,�Ͳ��������� */
if ( 'HEAD' === $_SERVER['REQUEST_METHOD'] && apply_filters( 'exit_on_http_head', true ) )
	exit();

// Process feeds and trackbacks even if not using themes.
if ( is_robots() ) :
	/**
	 * Fired when the template loader determines a robots.txt request.
	 *
	 * @since 2.1.0
	 */
	do_action( 'do_robots' );
	return;
elseif ( is_feed() ) :
	/* 
	http://127.0.0.1/note-wordpress/index.php?feed=rss2 ��ʾ���ĸ���վ������,
	http://127.0.0.1/note-wordpress/?feed=comments-rss2 ��ʾ���ĸ���վ������
	
	ʹ��rss����, ����Բ��ý���ĳ��վ, �͵õ��������������б�
	������firefox(����rss�Ķ�����)���ʴ�����, �ͻ�õ�һ��xml��Ӧ, firefox���һ��ҳ��, ����������ǩ,
	�Ժ��ڷ��ʴ���ǩʱ, ���Զ��õ�һ����ע��վ�����������б�,����б��ϵ�
	���Ӿͻ���뱻��ע����վ��.
			
	�������ӻ����۵�xml��Ӧ,�������ߺ͸���ʱ�� 
	*/
	do_feed();
	return;
elseif ( is_trackback() ) :
	include( ABSPATH . 'wp-trackback.php' );
	return;
endif;

 /*
 ��������ִ����Ӧ��phpģ���ļ�, ��
 ����-> search.php
 error -> 404.php
 ���¹鵵-> archive.php
index.php���Ҳ�����Ӧ��ģ���ļ�֮��, ����������ѡ��, �����Ǵ�����ҳ, home.php������ҳģ���ļ�
front-page.php��home.php������?
 */
if ( defined('WP_USE_THEMES') && WP_USE_THEMES ) :
	$template = false;
	if     ( is_embed()          && $template = get_embed_template()          ) :
	elseif ( is_404()            && $template = get_404_template()            ) :
	elseif ( is_search()         && $template = get_search_template()         ) :
	elseif ( is_front_page()     && $template = get_front_page_template()     ) :		/*** ȡfront-page.php */
	/*
	ǰ��ִ��parse_query()ʱ��������ʱ$_GET�е���������, �ж����������homeҳ���Ǳ��ҳ��
	�������ҳ, ����ӦthemeĿ¼��ȡhome.php(��index.php)���viewģ���ļ�,�õ��ļ���
	���$_GET����'cat' , is_category()Ϊ��, ȡthemeĿ¼�µ�category.php���view�ļ�
	*/
	elseif ( is_home()           && $template = get_home_template()           ) :   /*** ȡhome.php */
	elseif ( is_post_type_archive() && $template = get_post_type_archive_template() ) :
	elseif ( is_tax()            && $template = get_taxonomy_template()       ) :
	elseif ( is_attachment()     && $template = get_attachment_template()     ) :
		remove_filter('the_content', 'prepend_attachment');
	elseif ( is_single()         && $template = get_single_template()         /* ��ʾĳ������ */ ) :
	elseif ( is_page()           && $template = get_page_template()           /* ��ʾĳ��page */) :
	elseif ( is_singular()       && $template = get_singular_template()       ) :
	elseif ( is_category()       && $template = get_category_template()       ) :
	elseif ( is_tag()            && $template = get_tag_template()            ) :
	elseif ( is_author()         && $template = get_author_template()         ) :
	elseif ( is_date()           && $template = get_date_template()           ) :
	elseif ( is_archive()        && $template = get_archive_template()        ) :
	elseif ( is_paged()          && $template = get_paged_template()          ) :
	else :
		$template = get_index_template();			/* ���������ʾindexҳ */
	endif;
	
	/*
	����, ��������һ��ģ���ļ�, ����:
	$template = D:\htdocs\note-wordpress/wp-content/themes/twentysixteen/index.php
	$template = D:\htdocs\note-wordpress/wp-content/themes/twentysixteen/single.php	
	$template = D:\htdocs\note-wordpress/wp-content/themes/twentysixteen/page.php
	archive.php, ...
	*/	
	// debug ��ʾ�������ĸ��ļ�
	error_log($template);
	
	/**
	 * Filter the path of the current template before including it.
	 *
	 * @since 3.0.0
	 *
	 * @param string $template The path of the template to include.
	 */
	 /*** �ٸ�����һ������ȥ�ı�ģ���ļ��� */
	if ( $template = apply_filters( 'template_include', $template ) ) {
		/*
		ִ�й���'template_include'�����й��Ӻ���,ÿ�����Ӻ�����������¸����Ӻ���������?
		��ʱ��ı�defaultģ��,�÷�����
		add_filter( 'template_include', 'portfolio_page_template', 99 );
		function portfolio_page_template( $template ) {
			if ( is_page( 'portfolio' )  ) {
				$new_template = locate_template( array( 'portfolio-page-template.php' ) );
				if ( '' != $new_template ) {
					return $new_template ;
				}
			}
			if ( is_singular('post') ) {
				return 'my_post_template.php';
			}	
			return $template;
		}				
		*/
		include( $template );
	} elseif ( current_user_can( 'switch_themes' ) ) {		/* ����$templateδ�ҵ�, ������������?  ������includeģ����? */
		/*
		�����'switch_themes' Ȩ��...
		*/
		$theme = wp_get_theme();
		if ( $theme->errors() ) {
			wp_die( $theme->errors() );
		}
	}
	return;
endif;
/* ���WP_USE_THEMES=false����ʾ*/
