<?php
class IfTheHTTPReferrer extends MTPlugin {
    var $app;
    var $registry = array(
        'name' => 'IfTheHTTPReferrer',
        'id'   => 'IfTheHTTPReferrer',
        'key'  => 'ifthehttpreferrer',
        'author_name' => 'Junnama Noda',
        'author_link' => 'http://junnama.alfasado.net/online/',
        'version'     => '0.1',
        'description' => 'Conditional Tags MTIfTheHTTPReferrer or MTIfHTTPReferrer.',
        'tags' => array(
            'block'    => array( 'ifthehttpreferrer' => 'hdlr_ifthehttpreferrer',
                                 'ifhttpreferrer' => 'hdlr_ifthehttpreferrer',
                                 ),
        ),
    );

    function hdlr_ifthehttpreferrer ( $args, $content, &$ctx, &$repeat ) {
        $referer = $_SERVER[ 'HTTP_REFERER' ];
        $scope = $args[ 'scope' ];
        if ( $scope == 'empty' ) {
            if (! $referer ) $ctx->_hdlr_if( $args, $content, $ctx, $repeat, TRUE );
        }
        if (! $referer ) $ctx->_hdlr_if( $args, $content, $ctx, $repeat, FALSE );
        if ( preg_match ( "!^https{0,1}://(.*?)/!", $referer, $match ) ) {
            if ( isset( $match[ 1 ] ) ) {
                $domain = $args[ 'domain' ];
                if ( $scope == '' || $scope == 'equal' ) {
                    if ( $match[ 1 ] == $domain ) {
                        return $ctx->_hdlr_if( $args, $content, $ctx, $repeat, TRUE );
                    }
                } else {
                    $referer_domain = preg_quote( $match[ 1 ] );
                    if ( $scope == 'backward' ) {
                        if ( preg_match ( "/$referer_domain$/", $domain ) ) {
                            return $ctx->_hdlr_if( $args, $content, $ctx, $repeat, TRUE );
                        }
                    } elseif ( $scope == 'forward' ) {
                        if ( preg_match ( "/^$referer_domain/", $domain ) ) {
                            return $ctx->_hdlr_if( $args, $content, $ctx, $repeat, TRUE );
                        }
                    } elseif ( $scope == 'contains' ) {
                        if ( preg_match ( "/$referer_domain/", $domain ) ) {
                            return $ctx->_hdlr_if( $args, $content, $ctx, $repeat, TRUE );
                        }
                    }
                }
            }
        }
        return $ctx->_hdlr_if( $args, $content, $ctx, $repeat, FALSE );
    }
}

?>