<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 3/20/2019
 * Time: 11:15 PM
 */

get_header();

global $temp;

/** Masthead */
echo $temp->render( 'landing-masthead' );

/** About */
echo $temp->render( 'landing-about' );

/** How To */
echo $temp->render( 'landing-how-to' );

/** FAQs */
echo $temp->render( 'landing-faq' );

/** Register */
echo $temp->render( 'landing-reg' );

get_footer();