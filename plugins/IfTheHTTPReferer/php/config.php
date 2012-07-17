<?php
class IfTheHTTPReferer extends MTPlugin {
    var $app;
    var $registry = array(
        'name' => 'IfTheHTTPReferer',
        'id'   => 'IfTheHTTPReferer',
        'key'  => 'ifthehttpReferer',
        'author_name' => 'Alfasado Inc.',
        'author_link' => 'http://alfasado.net/',
        'version'     => '0.2',
        'description' => 'Conditional Tags MTIfTheHTTPReferer or MTIfHTTPReferer.',
        'tags' => array(
            'block'    => array( 'ifthehttpReferer' => 'hdlr_ifthehttpreferer',
                                 'ifhttpReferer' => 'hdlr_ifthehttpreferer',
                                 'ifthehttpReferrer' => 'hdlr_ifthehttpreferer',
                                 'ifhttpReferrer' => 'hdlr_ifthehttpreferer',
                                 ),
        ),
    );

    function hdlr_ifthehttpreferer ( $args, $content, &$ctx, &$repeat ) {
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
                    $referer_domain = $match[ 1 ];
                    $domain = preg_quote( $domain );
                    if ( $scope == 'backward' ) {
                        if ( preg_match ( "/$domain$/", $referer_domain ) ) {
                            return $ctx->_hdlr_if( $args, $content, $ctx, $repeat, TRUE );
                        }
                    } elseif ( $scope == 'forward' ) {
                        if ( preg_match ( "/^$domain/", $referer_domain ) ) {
                            return $ctx->_hdlr_if( $args, $content, $ctx, $repeat, TRUE );
                        }
                    } elseif ( $scope == 'contains' ) {
                        if ( preg_match ( "/$domain/", $referer_domain ) ) {
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