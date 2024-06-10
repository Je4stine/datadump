 
<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package SKT Movers Packers
 */

get_header(); ?>
      <div class="page_content">
             <section class="site-main">               
                   @include('layouts.laravel_page')                   
            </section>   

    <div class="clear"></div>

<?php get_footer(); ?>

