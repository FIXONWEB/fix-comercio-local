<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
//taxonomy_comercio_categoria
function fix161174_taxonomy_categoria() {
    $labels = array(
        'name'              => 'Categorias de Comércio',
        'singular_name'     => 'Categoria de Comércio',
        'search_items'      => 'Localizar Categoria de Comércio',
        'all_items'         => 'Todas as Categorias de Comércio',
        'parent_item'       => 'Categoria Pai',
        'parent_item_colon' => 'Categoria superior (acima/pai):',
        'edit_item'         => 'EDITAR Categoria de Comércio',
        'update_item'       => 'ATUALIZAR Categoria de Comércio',
        'add_new_item'      => 'Adicionar NOVA categoria de Comércio',
        'new_item_name'     => 'Nome da NOVA categoria de Comércio',
        'menu_name'         => 'Categorias de Comércio',
    );
 
    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'comercio-categoria' ),
    );
 
    register_taxonomy( 'comercio-categoria', array( 'comercio-local' ), $args );
 
    unset( $args );
    unset( $labels );
 
}
add_action( 'init', 'fix161174_taxonomy_categoria', 0 );
