<?php

/**
 *
 * This file is used to markup the public-facing pattern template.
 *
 * @since      0.0.1
 *
 * @package    WP_Craft_Blogger
 * @subpackage WP_Craft_Blogger/public/partials
 */
?>

<?php
$all_meta = get_post_custom();
$pattern_meta = array();

//Create simpler array of relevant post meta values
foreach( $all_meta as $key => $value ) {
    $pos = strpos( $key, 'wpcb-pattern-' );
    if ($pos !== false) {
        $bits = explode( '-', $key );
        $pattern_meta[$bits[2]] = maybe_unserialize( $value[0] );
    }
}

//Title and tool name depend on pattern settings.
$pattern_title = ($pattern_meta['title'] == '' ? get_the_title() : $pattern_meta['title'] );
$tools = ( $pattern_meta['craft'] == 'knitting' ? __( 'Needle', 'wp-craft-blogger' ) : __( 'Hook', 'wp-craft-blogger' ) );
?>

<div class="wpcb-pattern">
    <h2 class="wpcb-pattern-title"><?php echo $pattern_title; ?></h2>

    <div class="wpcb-pattern-link-buttons">

    <?php if( !empty( $pattern_meta['rav_url'] ) ) { ?>
        <a href="<?php echo $pattern_meta['rav_url']; ?>" target="_blank">
            <img src="<?php echo plugin_dir_url( dirname( __FILE__  ) ) . 'img/ravelry.png'; ?>" alt="__( 'Find this pattern on Ravelry!', 'wp-craft-blogger' ),">
        </a>
    <?php } ?>

    </div>

    <?php if( has_post_thumbnail() ) { ?>
        <div class="wpcb-thumb">
            <?php the_post_thumbnail( 'wpcb-square' ); ?>
        </div>
    <?php } ?>

    <div class="wpcb-supplies">
        <h4><?php echo __( 'Supplies:', 'wp-craft-blogger' ); ?></h4>

        <ul>
        <?php if( !empty( $pattern_meta['needles'] ) ) {

            foreach( $pattern_meta['needles'] as $tool ) {
                $notes = ( $tool['notes'] == '' ? '' : ' <span class="wpcb-item-note">- ' . $tool['notes'] . '</span>' );
                echo '<li>' . $tool['title'] . ' ' . strtolower( $tools ) . $notes . '</li>';
            }

         }

        if( !empty( $pattern_meta['yarns'] ) ) {

            foreach( $pattern_meta['yarns'] as $yarn ) {
                $colorway = ( $yarn['colorway'] == '' ? '' : ' <span class="wpcb-item-note">- ' . $yarn['colorway'] . '</span>' );
                echo '<li>' . $yarn['yardage'] . 'yds ' . $yarn['title'] . $colorway . '</li>';
            }

        }

        if( !empty( $pattern_meta['notions'] ) ) {

            foreach( $pattern_meta['notions'] as $notion ) {
                $notes = ( $notion['notes'] == '' ? '' : ' <span class="wpcb-item-note">- ' . $notion['notes'] . '</span>' );
                echo '<li>' . $notion['amount'] . ' ' . $notion['title'] . $notes . '</li>';
            }

        } ?>
        </ul>
    </div>

    <div class="wpcb-gauge">
      <?php if( !empty( $pattern_meta['gauge'] ) ) { ?>
          <h4><?php echo __( 'Gauge:', 'wp-craft-blogger' ); ?></h4>
          <p><?php echo $pattern_meta['gauge']; ?></p>
      <?php } ?>
    </div>

    <div class="wpcb-notes">
    <?php if( !empty( $pattern_meta['notes'] ) ) { ?>
        <h4><?php echo __( 'Notes:', 'wp-craft-blogger' ); ?></h4>
        <p><?php echo wpautop( $pattern_meta['notes'] ); ?></p>
    <?php } ?>
    </div>

    <div class="wpcb-instructions">

    <?php if( !empty( $pattern_meta['key'] ) ) { ?>
        <h4><?php echo __( 'Key:', 'wp-craft-blogger' ); ?></h4>
        <?php echo wpautop( $pattern_meta['key'] ); ?>
    <?php } ?>

    <?php if( !empty( $pattern_meta['instructions'] ) ) { ?>
        <h4><?php echo __( 'Instructions:', 'wp-craft-blogger' ); ?></h4>
        <?php foreach( $pattern_meta['instructions'] as $instruction ) {
            echo $title = ( $instruction['title'] == '' ? '' : '<h4>' . $instruction['title'] . '</h4>' );
            echo $image = ( $instruction['instruction_image'] == '' ? '' : '<img class="aligncenter" src="' . $instruction['instruction_image'] . '">' );
            echo wpautop( $instruction['instruction_text'] );
        }
        ?>
    <?php } ?>
    </div>
</div>
