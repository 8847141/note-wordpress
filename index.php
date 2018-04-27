<?php
/**
 * Front to the WordPress application. This file doesn't do anything, but loads
 * wp-blog-header.php which does and tells WordPress to load the theme.
 *
 * @package WordPress
 */

/**
 * Tells WordPress to load the WordPress theme and output it.
 *
 * @var bool
 */

/*** Դ��֮ǰ, ��������*/

/***
wp����չ�Ժ�ǿ
ͨ��meta, post������������ֶΣ�ֻ��Ҫ�ں�̨�༭�����й�ѡ�Զ�����Ŀ(ÿ����¼���������Լ���ͬ���ֶ�!)����ʹ��ʱģ������get_post_custom()��get_post_meta()��ȡ������ʾ

metabox�������ʾ�ʹ����?
��ʾmeta_boxʱ��add_meta_boxes, ����ʱ��save_post ����(�����ļ���post.php)  , ����
add_action( 'add_meta_boxes', 'halloween_store_register_meta_box' );  // halloween_store_register_meta_box()������ʾhtmlҳ��
add_action( 'save_post','halloween_store_save_meta_box' ); // �ύmetaboxʱ�ᴥ��'save_post', ����ִ��halloween_store_save_meta_box()

register_post_type()��register_taxonomy()����Ժ�̨�˵���Ӱ��

taxnomy����ͬpost_type�ļ�¼(post)�ļ���, ���ϱ���Ҳ������, �缯����(����wp_term����)
post_type=nav_menu_item��Ӧ��taxnomy��nav_menu
*/ 

/* ���Ϊfalse, ��ʾ����������, index.php����ʾ�հ�*/ 
define('WP_USE_THEMES', true);

/** Loads the WordPress Environment and Template */
require( dirname( __FILE__ ) . '/wp-blog-header.php' );
